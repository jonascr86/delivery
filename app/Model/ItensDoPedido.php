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

class ItensDoPedido extends SqlCrudVo implements \Simplon\Mysql\Crud\SqlCrudInterface{
    
    protected $pedido_id;
    protected $prato_id;
    
    
    public static function crudGetSource() {
        return 'itens_do_pedido';
    }
    
     public function crudColumns()
    {
        return array(
            'pedido_id'        => 'pedido_id',
            'prato_id'        => 'prato_id'
        );
    }

    function getPedido_id() {
        return $this->pedido_id;
    }

    function getPrato_id() {
        return $this->prato_id;
    }

    function setPedido_id($pedido_id) {
        $this->pedido_id = $pedido_id;
    }

    function setPrato_id($prato_id) {
        $this->prato_id = $prato_id;
    }



}
