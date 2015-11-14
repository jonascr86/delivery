<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IngredientesDao
 *
 * @author Jonas
 */

namespace Delivery\Dao;

use Delivery\Model\Ingredientes as Ingredientes;

class IngredientesDao extends DAO {

    protected $id;
    protected $descricao;
    protected $ingredientes;
    protected $tabela;

    public function __construct(Ingredientes $ingredientes = NULL) {
        
        $this->tabela = 'ingrediente';
        
        if (!$ingredientes == null) {
            $this->ingredientes = $ingredientes;
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
            'descricao' => $this->ingredientes->getDescricao(),
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
            $return = $crudManager->create($this->ingredientes);
            return ($return instanceof Ingredientes);
        } catch (\Simplon\Mysql\MysqlException $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    function apagar($conds) {
        return $this->getCrudManager()->delete(Ingredientes::crudGetSource(), $conds);
    }
    
    public function obterIngredientes($where, $retorno = true) {

        $wSql = array();

        try {

            $sql = "SELECT ingrediente.id, ingrediente.descricao "
                    . "FROM ingrediente ";

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

                $ingrediente = new Ingredientes();
                $ingrediente->setId($result[0]['id']);
                $ingrediente->setDescricao($result[0]['descricao']);

                return $ingrediente;
            } else {
                return false;
            }
        } catch (\Simplon\Mysql\MysqlException $ex) {
            return $ex->getMessage();
        }
    }

}
