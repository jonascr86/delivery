<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TipoBebidaDao
 *
 * @author Jonas
 */

namespace Delivery\Dao;
use Delivery\Dao\DAO;

class TipoBebidaDao extends DAO {

    protected $id;
    protected $descricao;
    protected $tipoBebida;

    function __construct(\Delivery\Model\TipoBebida $tipoBebida = null) {
        if ($tipoBebida != null) {
            $this->tipoBebida = $tipoBebida;
        }
        
        parent::__construct();
    }

    public function editar($cond) {
        
    }

    public function salvar() {
        try{
            $crudManager = $this->getCrudManager();
            $return = $crudManager->create($this->tipoBebida);
            
            return ($return instanceof \Delivery\Model\TipoBebida);
            
        } catch (\Simplon\Mysql\MysqlException $ex) {
               echo $ex->getTraceAsString();
        }
        
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

//put your code here
}
