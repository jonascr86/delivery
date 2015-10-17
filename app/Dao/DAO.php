<?php

namespace Delivery\Dao;

use Delivery\Registry;

abstract class DAO {

    protected $database;
    protected $builder;
    protected $manager;

    function __construct() {
        $this->database = Registry::get('appdb');
        $this->builder = new \Simplon\Mysql\Manager\SqlQueryBuilder();
        $this->manager = new \Simplon\Mysql\Manager\SqlManager($this->database);
    }

    function database() {
        return $this->database;
    }

    function builder() {
        return $this->builder;
    }

    abstract function salvar();

    abstract function editar($cond);

    function listar($colunas = null, $where = null) {
        $separador = ' AND ';
        $strColunas = '*';
        $placeholder = '';


        if ($colunas) {
            $strColunas = implode(',', $colunas);
        }

        if (isset($where) && is_array($where)) {
            $placeholder = ' WHERE ';
            $whereKeys = array_keys($where);
            $arrayWhere = array_map(function($array) {
                return $array . ' = ' . ":{$array}";
            }, $whereKeys);

            $placeholder .= implode("$separador", $arrayWhere);
        }

        $sqlBuilder = $this->builder()->setQuery("SELECT {$strColunas} FROM $this->tabela "
                . "{$placeholder}");

        if ($placeholder !== '') {
            $sqlBuilder->setConditions($where);
        }
        return $this->manager->fetchRowMany($sqlBuilder);
    }

    function apagar($where) {
        return $this->database->delete($this->tabela, $where);
    }

}
