<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PratoDao
 *
 * @author Jonas
 */

namespace Delivery\Dao;

class PratoDao extends DAO {

    protected $id;
    protected $descricao;
    protected $tipo_prato_id;
    protected $tipo;
    protected $tabela;
    protected $prato;

    function __construct(\Delivery\Model\Prato $prato = null) {
        $this->tabela = 'prato';
        if ($prato instanceof \Delivery\Model\Prato) {
            $this->prato = $prato;
        }

        parent::__construct();
    }

    public function editar($conds = null) {

        $data = array(
            'descricao' => $this->prato->getDescricao(),
            'nome' => $this->prato->getNome(),
            'tipo_prato_id' => $this->prato->getTipo_prato_id(),
            'tamanho_prato_id' => $this->prato->getTamanho_prato_id(),
            'preco' => $this->prato->getPreco(),
            'imagem_prato_id' => $this->prato->getImagem_prato_id(),
            'status_prato_id' => $this->prato->getStatus_prato_id()
        );

        try {
            $this->database->update($this->tabela, $conds, $data);
            return true;
        } catch (\Simplon\Mysql\MysqlException $ex) {
            \Delivery\Utils\Utils::displayErrorMessage($ex->getMessage());
            return false;
        }
    }

    public function obterPratos($where = null, $retorno = true) {

        $wSql = array();

        try {

            $sql = "SELECT prato.id, prato.nome, prato.descricao, "
                    . "status_prato.descricao as status, prato.preco, "
                    . "prato.status_prato_id, prato.tamanho_prato_id, "
                    . "prato.imagem_prato_id, prato.tipo_prato_id, "
                    . "imagem_prato.caminho as imagem, tipo_prato.descricao as tipo, "
                    . "tamanho_prato.descricao as tamanho "
                    . "FROM prato "
                    . "LEFT JOIN tipo_prato ON (prato.tipo_prato_id = tipo_prato.id) "
                    . "LEFT JOIN status_prato ON (prato.status_prato_id = status_prato.id) "
                    . "LEFT JOIN tamanho_prato ON (prato.tamanho_prato_id = tamanho_prato.id) "
                    . "LEFT JOIN imagem_prato ON (prato.imagem_prato_id = imagem_prato.id) ";

            if ($where == null) {
                $result = $this->database()->fetchRowMany($sql);
            } else {
                if (array_key_exists('tipo', $where)) {
                    array_push($wSql, "tipo_prato.descricao = %tipo%");
                }

                if (array_key_exists('tipo_prato_id', $where)) {
                    array_push($wSql, "tipo_prato_id = :tipo_prato_id");
                }

                if (array_key_exists('id', $where)) {
                    array_push($wSql, "prato.id = :id");
                }

                if (array_key_exists('descricao', $where)) {
                    array_push($wSql, "prato.descricao = :descricao");
                }

                if (count($wSql) >= 1) {
                    $wWher = " WHERE " . implode(" AND ", $wSql);
                    $sql .= $wWher;
                }

                $result = $this->database()->fetchRowMany($sql, $where);
            }

            if ($result) {

                if ($retorno) {
                    return $result;
                }

                $prato = new \Delivery\Model\Prato();
                $prato->setId($result[0]['id']);
                $prato->setNome($result[0]['nome']);
                $prato->setPreco($result[0]['preco']);
                $prato->setDescricao($result[0]['descricao']);
                $prato->setTipo_prato_id($result[0]['tipo_prato_id']);
                $prato->setStatus_prato_id($result[0]['status_prato_id']);
                $prato->setTamanho_prato_id($result[0]['tamanho_prato_id']);
                $prato->setImagem_prato_id($result[0]['imagem_prato_id']);

                return $prato;
            } else {
                return false;
            }
        } catch (\Simplon\Mysql\MysqlException $ex) {
            return $ex->getMessage();
        }
    }

    function apagar($conds) {
        return $this->getCrudManager()->delete(\Delivery\Model\Prato::crudGetSource(), $conds);
    }

    public function salvar() {
        try {
            $crudManager = $this->getCrudManager();
            $result = $crudManager->create($this->prato);
            if ($result instanceof \Delivery\Model\Prato) {
                return TRUE;
            }
            return FALSE;
        } catch (Simplon\Mysql\MysqlException $exc) {
            echo $exc->getTraceAsString();
        }
    }
}
