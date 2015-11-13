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
use Delivery\Model\StatusPrato as StatusPrato;

class StatusPratoDao extends Dao {

    protected $id;
    protected $descricao;
    protected $statusPrato;

    function __construct(StatusPrato $statusPrato = null) {
        if ($statusPrato != null) {
            $this->statusPrato = $statusPrato;
        }
        
        parent::__construct();
    }

    public function editar($cond) {
        
    }

    public function salvar() {
        try{
            $crudManager = $this->getCrudManager();
            $return = $crudManager->create($this->statusPrato);
            
            return ($return instanceof StatusPrato);
            
        } catch (\Simplon\Mysql\MysqlException $ex) {
               echo $ex->getTraceAsString();
        }
        
    }

}
