<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TamanhoPratoDao
 *
 * @author Jonas
 */

namespace Delivery\Dao;
use Delivery\Dao\DAO as Dao;
use Delivery\Model\TamanhoPrato as TamanhoPrato;

class TamanhoPratoDao extends Dao {

    protected $id;
    protected $descricao;
    protected $tamanhoPrato;

    function __construct(TamanhoPrato $tamanhoPrato = null) {
        if ($tamanhoPrato != null) {
            $this->tamanhoPrato = $tamanhoPrato;
        }
        
        parent::__construct();
    }

    public function editar($cond) {
        
    }

    public function salvar() {
        try{
            $crudManager = $this->getCrudManager();
            $return = $crudManager->create($this->tamanhoPrato);
            return ($return instanceof TamanhoPrato);
            
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
