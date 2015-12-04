<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CardapioDao
 *
 * @author Jonas
 */

namespace Delivery\Dao;
use Delivery\Model\Cardapio as Cardapio;

class CardapioDao extends DAO {

    protected $id;
    protected $descricao;
    protected $tabela;
    protected $cardapio;

    function __construct(Cardapio $cardapio = null) {
        $this->tabela = 'cardapio';
        if ($cardapio instanceof Cardapio) {
            $this->cardapio = $cardapio;
        }

        parent::__construct();
    }

    public function editar($conds = null, $pratosId = NULL) {

        $data = array(
            'descricao' => $this->cardapio->getDescricao(),
            'id' => $this->cardapio->getId(),
        );

        try {
            $this->database->update($this->tabela, $conds, $data);
            return true;
        } catch (\Simplon\Mysql\MysqlException $ex) {
            \Delivery\Utils\Utils::displayErrorMessage($ex->getMessage());
            return false;
        }
    }

    public function obterCardapios($where, $retorno = true) {

        $wSql = array();

        try {

            $sql = "SELECT DISTINCT cardapio.id, cardapio.descricao, "
                    . "cardapio_prato.prato_id "
                    . "FROM cardapio LEFT JOIN cardapio_prato ON "
                    . "(cardapio.id = cardapio_prato.cardapio_id) ";

            if (array_key_exists('tipo', $where)) {
                array_push($wSql, "tipo_cardapio.descricao = %tipo%");
            }

            if (array_key_exists('tipo_cardapio_id', $where)) {
                array_push($wSql, "tipo_cardapio_id = :tipo_cardapio_id");
            }

            if (array_key_exists('id', $where)) {
                array_push($wSql, "cardapio.id = :id");
            }

            if (array_key_exists('descricao', $where)) {
                array_push($wSql, "cardapio.descricao = :descricao");
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

                $cardapio = new Cardapio();
                $cardapio->setId($result[0]['id']);
                $cardapio->setDescricao($result[0]['descricao']);
                
                return $cardapio;
            } else {
                return false;
            }
        } catch (\Simplon\Mysql\MysqlException $ex) {
            return $ex->getMessage();
        }
    }

    public function listarCardapios() {

        $wSql = array();

        try {

            $sql = "SELECT cardapio.id, cardapio.descricao "
                    . "FROM cardapio";

            $result = $this->database()->fetchRowMany($sql);
 
            if ($result) {
                
                return $result;
                
            } else {
                return false;
            }
        } catch (\Simplon\Mysql\MysqlException $ex) {
            return $ex->getMessage();
        }
    }
    function apagar($conds) {
        return $this->getCrudManager()->delete(Cardapio::crudGetSource(), $conds);
    }

    public function salvar($return = false) {
        try {
            $crudManager = $this->getCrudManager();
            $result = $crudManager->create($this->cardapio);
            if($return){
                return $result;
            }
            if ($result instanceof Cardapio) {
                return TRUE;
            }
            return FALSE;
        } catch (Simplon\Mysql\MysqlException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
