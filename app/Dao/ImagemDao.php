<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ImagemDao
 *
 * @author Jonas
 */

namespace Delivery\Dao;

use Delivery\Model\Imagem as Imagem;

class ImagemDao extends DAO {

    protected $imagem;

    public function __construct(Imagem $imagem = NULL) {

        $this->tabela = 'imagem_prato';

        if (!$imagem == null) {
            $this->imagem = $imagem;
        }
        parent::__construct();
    }

    public function editar($conds = null) {

        $data = array(
            'caminho' => $this->imagem->getDescricao(),
        );

        try {
            $this->database->update($this->tabela, $conds, $data);
            return true;
        } catch (\Simplon\Mysql\MysqlException $ex) {
            \Delivery\Utils\Utils::displayErrorMessage($ex->getMessage());
            return false;
        }
    }

    public function salvar() {
        try {
            $crudManager = $this->getCrudManager();
            $return = $crudManager->create($this->imagem);
            if ($return instanceof Imagem) {
                return $return->getId();
            } else {
                return FALSE;
            }
            
        } catch (\Simplon\Mysql\MysqlException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    function apagar($conds) {
        return $this->getCrudManager()->delete(Imagem::crudGetSource(), $conds);
    }

    public function obterImagem($where, $retorno = true) {

        $wSql = array();

        try {

            $sql = "SELECT imagem_prato.id, imagem_prato.obs, imagem_prato.caminho "
                    . "FROM imagem_prato ";

            if (array_key_exists('caminho', $where)) {
                array_push($wSql, "caminho = %:caminho%");
            }

            if (array_key_exists('id', $where)) {
                array_push($wSql, "id = :id");
            }

            if (count($wSql) >= 1) {
                $wWher = " WHERE " . implode(" AND ", $wSql);
                $sql .= $wWher;
            }

            $result = $this->database()->fetchRowMany($sql, $where);

            if ($result) {

                if ($retorno) {
                    return $result;
                }

                $imagem = new Imagem();
                $imagem->setId($result[0]['id']);
                $imagem->setCaminho($result[0]['caminho']);
                $imagem->setObs($result[0]['obs']);

                return $imagem;
            } else {
                return false;
            }
        } catch (\Simplon\Mysql\MysqlException $ex) {
            return $ex->getMessage();
        }
    }

}
