<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TemperosDao
 *
 * @author Jonas
 */

namespace Delivery\Dao;

use Delivery\Model\Temperos as Temperos;

class TemperosDao extends DAO {

    protected $id;
    protected $descricao;
    protected $temperos;
    protected $tabela;

    public function __construct(Temperos $temperos = NULL) {
        
        $this->tabela = 'tempero';
        
        if (!$temperos == null) {
            $this->temperos = $temperos;
        }
        parent::__construct();
    }

    function getId() {
        return $this->id;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function editar($conds = null) {

        $data = array(
            'descricao' => $this->temperos->getDescricao(),
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
            $return = $crudManager->create($this->temperos);
            return ($return instanceof Temperos);
        } catch (\Simplon\Mysql\MysqlException $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    function apagar($conds) {
        return $this->getCrudManager()->delete(Temperos::crudGetSource(), $conds);
    }
    
    public function obterTemperos($where, $retorno = true) {

        $wSql = array();

        try {

            $sql = "SELECT tempero.id, tempero.descricao "
                    . "FROM tempero ";

            if (array_key_exists('descricao', $where)) {
                array_push($wSql, "descricao = %:descricao%");
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

                $tempero = new Temperos();
                $tempero->setId($result[0]['id']);
                $tempero->setDescricao($result[0]['descricao']);

                return $tempero;
            } else {
                return false;
            }
        } catch (\Simplon\Mysql\MysqlException $ex) {
            return $ex->getMessage();
        }
    }

}
