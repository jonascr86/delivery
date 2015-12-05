<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PedidoDao
 *
 * @author Jonas
 */

namespace Delivery\Dao;

class PedidoDao extends DAO {

    function __construct(\Delivery\Model\Pedido $pedido = null) {
        $this->tabela = 'pedido';
        if ($pedido instanceof \Delivery\Model\Pedido) {
            $this->pedido = $pedido;
        }

        parent::__construct();
    }

    public function editar($conds = null) {

        $data = array(
            'data' => $this->pedido->getData(),
            'valor' => $this->pedido->getValor(),
            'cliente_id' => $this->pedido->getCliente_id(),
            'status_pedido_id' => $this->pedido->getStatus_pedido_id()
        );

        try {
            $this->database->update($this->tabela, $conds, $data);
            return true;
        } catch (\Simplon\Mysql\MysqlException $ex) {
            \Delivery\Utils\Utils::displayErrorMessage($ex->getMessage());
            return false;
        }
    }

    public function obterPedidos($where = null, $retorno = false) {

        $wSql = array();

        try {

            $sql = "SELECT DISTINCT pedido.id, pedido.data, 
                    pedido.valor, 
                    pedido.status_pedido_id, 
                    pessoa.nome as nome_cliente, pessoa.cpf, pessoa.sexo, 
                    pessoa.celular, pessoa.telefone, pessoa.email, pessoa.endereco,
                    status_pedido.descricao as status
                    FROM pedido
                    INNER JOIN cliente ON (pedido.cliente_id = cliente.id)
                    INNER JOIN pessoa ON (cliente.pessoa_id = pessoa.id)
                    INNER JOIN status_pedido ON (pedido.status_pedido_id = status_pedido.id) ";

            if ($where == null) {
                $sql .= " GROUP BY pedido.id ";
                $result = $this->database()->fetchRowMany($sql);
            } else {
                if (array_key_exists('data', $where)) {
                    array_push($wSql, "pedido.data = :data");
                }

                if (array_key_exists('cliente_id', $where)) {
                    array_push($wSql, "pedido.cliente_id = :cliente_id");
                }

                if (array_key_exists('pessoa_id', $where)) {
                    array_push($wSql, "pessoa.id = :pessoa_id");
                }

                if (array_key_exists('id', $where)) {
                    array_push($wSql, "pedido.id = :id");
                }
                
                 if (array_key_exists('prato_id', $where)) {
                    array_push($wSql, "prato.id = :prato_id");
                }
                
                 if (array_key_exists('status_pedido_id', $where)) {
                    array_push($wSql, "prato.status_pedido_id = :status_pedido_id");
                }

                if (count($wSql) >= 1) {
                    $wWher = " WHERE " . implode(" AND ", $wSql);
                    $sql .= $wWher;
                }
                $sql .= " GROUP BY pedido.id ";
                $result = $this->database()->fetchRowMany($sql, $where);
            }

            if ($result) {

                if ($retorno) {
                    return $result;
                }

                $pedido = new \Delivery\Model\Pedido();
                $pedido->setId($result[0]['id']);
                $pedido->setValor($result[0]['valor']);
                $pedido->setData($result[0]['data']);
                $pedido->setStatus_pedido_id($result[0]['cliente_id']);

                return $pedido;
            } else {
                return false;
            }
        } catch (\Simplon\Mysql\MysqlException $ex) {
            return $ex->getMessage();
        }
    }

    function apagar($conds) {
        return $this->getCrudManager()->delete(\Delivery\Model\Pedido::crudGetSource(), $conds);
    }

    public function salvar() {
        try {
            $crudManager = $this->getCrudManager();
            $result = $crudManager->create($this->pedido);

            if ($result instanceof \Delivery\Model\Pedido) {
                return $result;
            }
            return FALSE;
        } catch (Simplon\Mysql\MysqlException $exc) {
            echo $exc->getTraceAsString();
        }
    }
}
