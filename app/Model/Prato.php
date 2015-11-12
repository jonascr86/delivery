<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Prato
 *
 * @author Jonas
 */

namespace Delivery\Model;

use Simplon\Mysql\Crud\SqlCrudVo;

class Prato extends SqlCrudVo implements \Simplon\Mysql\Crud\SqlCrudInterface {

    protected $id;
    protected $nome;
    protected $preco;
    protected $descricao;
    protected $tipo_prato_id;
    protected $status_prato_id;
    protected $imagem_prato_id;
    protected $tamanho_prato_id;

    public static function crudGetSource() {
        return 'prato';
    }
    

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

    function getTipo_prato_id() {
        return $this->tipo_prato_id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setTipo_prato_id($tipo_prato_id) {
        $this->tipo_prato_id = $tipo_prato_id;
    }

    function getNome() {
        return $this->nome;
    }

    function getPreco() {
        return $this->preco;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setPreco($preco) {
        $this->preco = $preco;
    }

    function getStatus_prato_id() {
        return $this->status_prato_id;
    }

    function getImagem_prato_id() {
        return $this->imagem_prato_id;
    }

    function getTamanho_prato_id() {
        return $this->tamanho_prato_id;
    }

    function setStatus_prato_id($status_prato_id) {
        $this->status_prato_id = $status_prato_id;
    }

    function setImagem_prato_id($imagem_prato_id) {
        $this->imagem_prato_id = $imagem_prato_id;
    }

    function setTamanho_prato_id($tamanho_prato_id) {
        $this->tamanho_prato_id = $tamanho_prato_id;
    }

}
