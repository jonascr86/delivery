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
        $this->tabela = 'pratos';
        if ($prato instanceof \Delivery\Model\Prato) {
            $this->prato = $prato;
        }

        parent::__construct();
    }

    public function editar($conds = null) {

        $data = array(
            'descricao' => $this->prato->getDescricao(),
            'tipo_prato_id' => $this->prato->getTipo_prato_id(),
        );

        try {
            $this->database->update($this->tabela, $conds, $data);
            return true;
        } catch (\Simplon\Mysql\MysqlException $ex) {
            \Delivery\Utils\Utils::displayErrorMessage($ex->getMessage());
            return false;
        }
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function obterPratos($where, $retorno = true) {

        $wSql = [];

        try {

            $sql = "SELECT pratos.id, pratos.descricao, "
                    . "tipo_prato.descricao as tipo, tipo_prato.id as tipo_prato_id "
                    . "FROM pratos INNER JOIN tipo_prato ON "
                    . "(pratos.tipo_prato_id = tipo_prato.id) ";

            if (array_key_exists('tipo', $where)) {
                array_push($wSql, "tipo_prato.descricao = %tipo%");
            }

            if (array_key_exists('tipo_prato_id', $where)) {
                array_push($wSql, "tipo_prato_id = :tipo_prato_id");
            }

            if (array_key_exists('id', $where)) {
                array_push($wSql, "pratos.id = :id");
            }

            if (array_key_exists('descricao', $where)) {
                array_push($wSql, "pratos.descricao = :descricao");
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

                $prato = new \Delivery\Model\Prato();
                $prato->setId($result[0]['id']);
                $prato->setDescricao($result[0]['descricao']);
                $prato->setTipo($result[0]['tipo']);
                $prato->setTipo_prato_id($result[0]['tipo_prato_id']);

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

    function getId() {
        return $this->id;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getTipo_prato_id() {
        return $this->tipo_prato_id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setTipo_prato_id($tipo_prato_id) {
        $this->tipo_prato_id = $tipo_prato_id;
    }

//put your code here
}
