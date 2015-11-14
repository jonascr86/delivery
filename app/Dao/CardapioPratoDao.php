<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CardapioPratoDao
 *
 * @author Jonas
 */

namespace Delivery\Dao;

use Delivery\Model\CardapioPrato as CardapioPrato;

class CardapioPratoDao extends DAO {

    protected $cardapioPrato;
    protected $tabela;

    public function __construct(CardapioPrato $cardapioPrato = NULL) {
        
        $this->tabela = 'cardapio_prato';
        
        if (!$cardapioPrato == null) {
            $this->setCardapioPrato($cardapioPrato);
        }
        parent::__construct();
    }
    
    function setCardapioPrato(CardapioPrato $cardapioPrato) {
        $this->cardapioPrato = $cardapioPrato;
    }

    public function editar($conds = null) {

        $data = array(
            'cardapio_id' => $this->cardapioPrato->getCardapio_id(),
            'prato_id' => $this->cardapioPrato->getPrato_id()
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
            $return = $crudManager->create($this->cardapioPrato);
            return ($return instanceof CardapioPrato);
        } catch (\Simplon\Mysql\MysqlException $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    function apagar($conds) {
        return $this->getCrudManager()->delete(CardapioPrato::crudGetSource(), $conds);
    }
    
    public function obterCardapioPrato($where, $retorno = true) {

        $wSql = array();

        try {

            $sql = "SELECT cardapio_prato.cardapio_id, cardapio_prato.prato_id "
                    . "FROM cardapio_prato ";

            if (array_key_exists('cardapio_id', $where)) {
                array_push($wSql, "cardapio_id = :cardapio_id");
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

                $cardapioPrato = new CardapioPrato();
                $cardapioPrato->setId($result[0]['prato_id']);
                $cardapioPrato->setDescricao($result[0]['cardapio_id']);

                return $cardapioPrato;
            } else {
                return false;
            }
        } catch (\Simplon\Mysql\MysqlException $ex) {
            return $ex->getMessage();
        }
    }

}
