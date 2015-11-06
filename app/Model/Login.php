<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author Jonas
 */
namespace Delivery\Model;
use \Simplon\Mysql\Crud\SqlCrudVo as SqlCrudVo;

class Login extends SqlCrudVo{
    
    protected $id;
    protected $usuario;
    protected $senha;
    protected $funcionario_id;

    public static function crudGetSource() {
        return 'login';
    }
    
    function getId() {
        return $this->id;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getSenha() {
        return $this->senha;
    }

    function getFuncionario_id() {
        return $this->funcionario_id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    function setSenha($senha) {
        $this->senha = $senha;
    }

    function setFuncionario_id($funcionario_id) {
        $this->funcionario_id = $funcionario_id;
    }


}
