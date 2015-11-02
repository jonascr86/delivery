<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ingredientes
 *
 * @author Jonas
 */

namespace Delivery\Model;

use Simplon\Mysql\Crud\SqlCrudVo;

class Ingredientes extends SqlCrudVo {

    protected $id;
    protected $descricao;

    public static function crudGetSource() {
        return 'ingrediente';
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
