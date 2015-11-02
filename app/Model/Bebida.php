<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Bebida
 *
 * @author Jonas
 */
namespace Delivery\Model;

use Simplon\Mysql\Crud\SqlCrudVo;

class Bebida extends SqlCrudVo implements \Simplon\Mysql\Crud\SqlCrudInterface{
    
    protected $id;
    protected $descricao;
    protected $tipo_bebida_id;
    protected $tipo;
    
    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
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
    
    public static function crudGetSource() {
        return 'bebidas';
    }
    
     public function crudColumns()
    {
        return array(
            'id'        => 'id',
            'descricao'      => 'descricao',
            'tipo_bebida_id'     => 'tipo_bebida_id'
        );
    }


}
