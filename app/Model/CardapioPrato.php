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

class CardapioPrato extends SqlCrudVo implements \Simplon\Mysql\Crud\SqlCrudInterface{
    
    protected $cardapio_id;
    protected $prato_id;
    
    
    public static function crudGetSource() {
        return 'cardapio_prato';
    }
    
     public function crudColumns()
    {
        return array(
            'cardapio_id'        => 'cardapio_id',
            'prato_id'        => 'prato_id'
        );
    }

    function getCardapio_id() {
        return $this->cardapio_id;
    }

    function getPrato_id() {
        return $this->prato_id;
    }

    function setCardapio_id($cardapio_id) {
        $this->cardapio_id = $cardapio_id;
    }

    function setPrato_id($prato_id) {
        $this->prato_id = $prato_id;
    }



}
