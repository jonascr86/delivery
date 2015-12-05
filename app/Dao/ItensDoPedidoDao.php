<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ItensDoPedidoDao
 *
 * @author Jonas
 */

namespace Delivery\Dao;

use Delivery\Model\ItensDoPedido as ItensDoPedido;

class ItensDoPedidoDao extends DAO {

    protected $itensDoPedido;
    protected $tabela;

    public function __construct(ItensDoPedido $itensDoPedido = NULL) {
        
        $this->tabela = 'itens_do_pedido';
        
        if (!$itensDoPedido == null) {
            $this->setItensDoPedido($itensDoPedido);
        }
        parent::__construct();
    }
    
    function setItensDoPedido(ItensDoPedido $itensDoPedido) {
        $this->itensDoPedido = $itensDoPedido;
    }

    public function editar($conds = null) {

        $data = array(
            'pedido_id' => $this->itensDoPedido->getPedido_id(),
            'prato_id' => $this->itensDoPedido->getPrato_id()
        );

        try {
            $this->database->update($this->tabela, $conds, $data);
            return true;
        } catch (\Simplon\Mysql\MysqlException $ex) {
            \Delivery\Utils\Utils::displayErrorMessage($ex->getMessage());
            return false;
        }
    }

    public function salvar() {
        try {
            $crudManager = $this->getCrudManager();
            $return = $crudManager->create($this->itensDoPedido);
            return ($return instanceof ItensDoPedido && !is_null($return));
        } catch (\Simplon\Mysql\MysqlException $exc) {
            echo $exc->getMessage();
        }
    }
    
    function apagar($conds) {
        return $this->getCrudManager()->delete(ItensDoPedido::crudGetSource(), $conds);
    }
    
    public function obterItensDoPedido($where, $retorno = true) {

        $wSql = array();

        try {

            $sql = "SELECT itens_do_pedido.pedido_id, itens_do_pedido.prato_id, "
                    . "prato.nome, prato.preco, imagem_prato.caminho "
                    . "FROM itens_do_pedido "
                    . "LEFT JOIN prato ON (prato.id = itens_do_pedido.prato_id) "
                    . "LEFT JOIN imagem_prato ON (imagem_prato.id = prato.imagem_prato_id)";

            if (array_key_exists('pedido_id', $where)) {
                array_push($wSql, "pedido_id = :pedido_id");
            }
            
            if (array_key_exists('prato_id', $where)) {
                array_push($wSql, "prato_id = :prato_id");
            }

            if (count($wSql) >= 1) {
                $wWher = " WHERE " . implode(" AND ", $wSql);
                $sql .= $wWher;
            }

            $result = $this->database()->fetchRowMany($sql, $where);
            
            if ($result) {

                if ($retorno) {
                    return $result;
                }

                $itensDoPedido = new ItensDoPedido();
                $itensDoPedido->setId($result[0]['prato_id']);
                $itensDoPedido->setDescricao($result[0]['pedido_id']);

                return $itensDoPedido;
            } else {
                return false;
            }
        } catch (\Simplon\Mysql\MysqlException $ex) {
            return $ex->getMessage();
        }
    }

}
