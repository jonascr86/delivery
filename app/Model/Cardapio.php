<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cardapio
 *
 * @author Jonas
 */
namespace Delivery\Model;

use Simplon\Mysql\Crud\SqlCrudVo;

class Cardapio extends SqlCrudVo {
    
    protected $id;
    protected $descricao;
    
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

    public static function crudGetSource() {
        return 'cardapio';
    }
    
     public function crudColumns()
    {
        return array(
            'id'        => 'id',
            'descricao'      => 'descricao'
        );
    }


}
