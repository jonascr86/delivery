<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TipoBebida
 *
 * @author Jonas
 */
namespace Delivery\Model;
use \Simplon\Mysql\Crud\SqlCrudVo;
use \Simplon\Mysql\Crud\SqlCrudInterface as SqlCrudInterface;

class StatusPrato extends SqlCrudVo implements SqlCrudInterface{
    
    protected $id;
    protected $descricao;
    
    public static function crudGetSource() {
        return 'status_prato';
    }
    
    public function crudColumns() {
        return array(
            'id' => 'id',
            'descricao' => 'descricao'
        );
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


            
}
