<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pedido
 *
 * @author Jonas
 */

namespace Delivery\Model;

use Simplon\Mysql\Crud\SqlCrudVo;

class Pedido extends SqlCrudVo {

    protected $id;
    protected $hora;
    protected $valor;
    protected $cliente_id;
    protected $status_pedido_id;

    function getValor() {
        return $this->valor;
    }

    function getCliente_id() {
        return $this->cliente_id;
    }

    function getStatus_pedido_id() {
        return $this->status_pedido_id;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }

    function setCliente_id($cliente_id) {
        $this->cliente_id = $cliente_id;
    }

    function setStatus_pedido_id($status_pedido_id) {
        $this->status_pedido_id = $status_pedido_id;
    }

    function getId() {
        return $this->id;
    }

    function getData() {
        return $this->data;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setData($data) {
        $this->data = $data;
    }

    public static function crudGetSource() {
        return 'pedido';
    }

    public function crudColumns() {
        return array(
            'id' => 'id',
            'data' => 'data',
            'valor' => 'valor',
            'cliente_id' => 'cliente_id',
            'status_pedido_id' => 'status_pedido_id',
        );
    }

}
