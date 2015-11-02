<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BebidaDao
 *
 * @author Jonas
 */

namespace Delivery\Dao;

class BebidaDao extends DAO {

    protected $id;
    protected $descricao;
    protected $tipo_bebida_id;
    protected $tipo;
    protected $tabela;
    protected $bebida;

    function __construct(\Delivery\Model\Bebida $bebida = null) {
        $this->tabela = 'bebidas';
        if ($bebida instanceof \Delivery\Model\Bebida) {
            $this->bebida = $bebida;
        }

        parent::__construct();
    }

    public function editar($conds = null) {

        $data = array(
            'descricao' => $this->bebida->getDescricao(),
            'tipo_bebida_id' => $this->bebida->getTipo_bebida_id(),
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

    public function obterBebidas($where, $retorno = true) {

        $wSql = [];

        try {

            $sql = "SELECT bebidas.id, bebidas.descricao, "
                    . "tipo_bebida.descricao as tipo, tipo_bebida.id as tipo_bebida_id "
                    . "FROM bebidas INNER JOIN tipo_bebida ON "
                    . "(bebidas.tipo_bebida_id = tipo_bebida.id) ";

            if (array_key_exists('tipo', $where)) {
                array_push($wSql, "tipo_bebida.descricao = %tipo%");
            }

            if (array_key_exists('tipo_bebida_id', $where)) {
                array_push($wSql, "tipo_bebida_id = :tipo_bebida_id");
            }

            if (array_key_exists('id', $where)) {
                array_push($wSql, "bebidas.id = :id");
            }

            if (array_key_exists('descricao', $where)) {
                array_push($wSql, "bebidas.descricao = :descricao");
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

                $bebida = new \Delivery\Model\Bebida();
                $bebida->setId($result[0]['id']);
                $bebida->setDescricao($result[0]['descricao']);
                $bebida->setTipo($result[0]['tipo']);
                $bebida->setTipo_bebida_id($result[0]['tipo_bebida_id']);

                return $bebida;
            } else {
                return false;
            }
        } catch (\Simplon\Mysql\MysqlException $ex) {
            return $ex->getMessage();
        }
    }

    function apagar($conds) {
        return $this->getCrudManager()->delete(\Delivery\Model\Bebida::crudGetSource(), $conds);
    }

    public function salvar() {
        try {
            $bebida = new \Delivery\Model\Bebida();
            $bebida->setDescricao($this->bebida->getDescricao());
            $bebida->setTipo_bebida_id($this->bebida->getTipo_bebida_id());
            $crudManager = $this->getCrudManager();
            $result = $crudManager->create($bebida);
            if ($result instanceof \Delivery\Model\Bebida) {
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

    function getTipo_bebida_id() {
        return $this->tipo_bebida_id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setTipo_bebida_id($tipo_bebida_id) {
        $this->tipo_bebida_id = $tipo_bebida_id;
    }

//put your code here
}
