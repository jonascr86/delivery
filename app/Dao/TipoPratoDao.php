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
use Delivery\Dao\DAO as Dao;
use Delivery\Model\TipoPrato as TipoPrato;

class TipoPratoDao extends Dao {

    protected $id;
    protected $descricao;
    protected $tipoPrato;

    function __construct(TipoPrato $tipoPrato = null) {
        if ($tipoPrato != null) {
            $this->tipoPrato = $tipoPrato;
        }
        
        parent::__construct();
    }

    public function editar($cond) {
        
    }

    public function salvar() {
        try{
            $crudManager = $this->getCrudManager();
            $return = $crudManager->create($this->tipoPrato);
            
            return ($return instanceof TipoPrato);
            
        } catch (\Simplon\Mysql\MysqlException $ex) {
               echo $ex->getTraceAsString();
        }
        
    }

}
