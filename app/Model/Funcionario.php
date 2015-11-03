<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Funcionario
 *
 * @author Jonas
 */
namespace Delivery\Model;

use Delivery\Model\Pessoa;
use \Simplon\Mysql\Crud\SqlCrudInterface as SqlCrudInterface;

class Funcionario extends Pessoa implements SqlCrudInterface{
    
    protected $id;
    protected $salario;
    protected $data_admissao;
    protected $data_desligamento;
    protected $pessoa_id;
    
    function getId() {
        return $this->id;
    }

    function getSalario() {
        return $this->salario;
    }

    function getData_admissao() {
        return $this->data_admissao;
    }

    function getData_desligamento() {
        return $this->data_desligamento;
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setSalario($salario) {
        $this->salario = $salario;
    }

    function setData_admissao($data_admissao) {
        $this->data_admissao = $data_admissao;
    }

    function setData_desligamento($data_desligamento) {
        $this->data_desligamento = $data_desligamento;
    }

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

        
    public static function crudGetSource() {
        return 'funcionario';
    }
    
     public function crudColumns()
    {
        return array(
            'id'        => 'id',
            'pessoa_id'      => 'pessoa_id',
            'data_admissao'     => 'data_admissao',
            'data_desligamento'     => 'data_desligamento',
            'salario'     => 'salario'
        );
    }


}
