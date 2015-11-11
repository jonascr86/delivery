<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tempero
 *
 * @author Jonas
 */
namespace Delivery\Model;
use Simplon\Mysql\Crud\SqlCrudVo;

class Imagem extends SqlCrudVo{

    protected $id;
    protected $caminho;
    protected $obs;

    public static function crudGetSource() {
        return 'imagem_prato';
    }
    
    function getId() {
        return $this->id;
    }

    function getCaminho() {
        return $this->caminho;
    }

    function getObs() {
        return $this->obs;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCaminho($caminho) {
        $this->caminho = $caminho;
    }

    function setObs($obs) {
        $this->obs = $obs;
    }


}
