<?php

namespace Delivery\Dao;


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ComboDao
 *
 * @author jonascr86
 */
class ComboDao extends DAO {

    function __construct() {
        parent::__construct();
    }

    public function editar() {
        return;
    }

    public function salvar() {
        return;
    }

    function getEstados() {
        $sql = "SELECT id, sigla FROM estado "
                . "ORDER BY sigla;";
        $estados = $this->database()->fetchRowMany($sql);
        return $estados;
    }

    function getCidades($where) {
        if (!isset($where)) {
            return false;
        }
        $sql = "SELECT id, nome FROM cidade "
                . "WHERE estado_id = :estado_id "
                . "ORDER BY nome;";
        $cidades = $this->database()->fetchRowMany($sql, ['estado_id' => $where]);
        return $cidades;
    }

    function getBairros($where) {
        if (!isset($where)) {
            return false;
        }
        $sql = "SELECT id, nome FROM bairro "
                . "WHERE cidade_id = :cidade_id "
                . "ORDER BY nome;";
        $bairros = $this->database()->fetchRowMany($sql, ['cidade_id' => $where]);
        return $bairros;
    }

}
