<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClienteDao
 *
 * @author jonascr86
 */

namespace Delivery\Dao;

use \Delivery\Model\Cliente as Cliente;
use Simplon\Mysql\Manager\SqlQueryBuilder as SqlQueryBuilder;

class ClienteDao extends PessoaDao {

    function __construct() {
        parent::__construct();
    }

    public function editar($cond) {
        
    }

    public function salvar() {
        
    }

    public function obterCliente($where) {
        
        $wSql = [];

        try {

            $sql = "SELECT pessoa.id as pessoa_id, pessoa.nome, pessoa.idade, pessoa.data_nascimento,"
                    . "pessoa.cpf, pessoa.rg, pessoa.nome_mae, pessoa.sexo, pessoa.telefone,"
                    . "pessoa.celular, pessoa.email, pessoa.endereco, pessoa.cidade_id,"
                    . "pessoa.bairro, cliente.id, cliente.senha FROM cliente "
                    . "INNER JOIN pessoa ON (cliente.pessoa_id = pessoa_id) ";

            if (array_key_exists('email', $where)) {
                array_push($wSql, "email = :email");
            }

            if (array_key_exists('senha', $where)) {
                array_push($wSql, "senha = :senha");
            }

            if (array_key_exists('id', $where)) {
                array_push($wSql, "id = :id");
            }

            if (array_key_exists('nome', $where)) {
                array_push($wSql, "nome = :nome");
            }

            if (array_key_exists('cpf', $where)) {
                array_push($wSql, "cpf = :cpf");
            }
            
            
            if(count($wSql) >= 1){
                $wWher = " WHERE " . implode(" AND ", $wSql);
                $sql .= $wWher;
            }
            $result = $this->database()->fetchRow($sql, $where);
            if ($result) {
                $cliente = new Cliente();
                $cliente->setId($result['id']);
                $cliente->setNome($result['nome']);
                $cliente->setIdade($result['idade']);
                $cliente->setData_nascimento($result['data_nascimento']);
                $cliente->setCpf($result['cpf']);
                $cliente->setRg($result['rg']);
                $cliente->setNome_mae($result['nome_mae']);
                $cliente->setSexo($result['sexo']);
                $cliente->setTelefone($result['telefone']);
                $cliente->setCelular($result['celular']);
                $cliente->setEmail($result['email']);
                $cliente->setEndereco($result['endereco']);
                $cliente->setCidade_id($result['cidade_id']);
                $cliente->setSenha($result['senha']);

                return $cliente;
            } else {
                return false;
            }
        } catch (\Simplon\Mysql\MysqlException $ex) {
            return $ex->getMessage();
        }
    }

}
