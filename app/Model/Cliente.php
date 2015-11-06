<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cliente
 *
 * @author jonascr86
 */

namespace Delivery\Model;

use Delivery\Model\Pessoa as Pessoa;
use \Simplon\Mysql\Crud\SqlCrudInterface as SqlCrudInterface;

class Cliente extends Pessoa implements SqlCrudInterface {

    private $id;
    private $senha;
    private $pessoa_id;

    public static function crudGetSource() {
        return 'cliente';
    }

    public function crudColumns() {
        return array(
            'id' => 'id',
            'pessoa_id' => 'pessoa_id',
            'senha' => 'senha'
        );
    }

    function getId() {
        return $this->id;
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

    function getPessoa() {
        return $this->pessoa;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getSenha() {
        return $this->senha;
    }

    function setSenha($senha) {
        $this->senha = $senha;
    }

}
