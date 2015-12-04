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
use Delivery\Dao\PessoaDao as PessoaDao;
use \Simplon\Mysql\MysqlException as MysqlException;

class ClienteDao extends PessoaDao {

    public $tabela;
    public $cliente;

    function __construct(Cliente $cliente = null) {
        parent::__construct();

        $this->tabela = 'cliente';

        if ($cliente == NULL) {
            $this->cliente = new Cliente();
        } else {
            $this->cliente = $cliente;
        }
    }

    public function editar($cond) {
       
        $data = array(
            'bairro' => $this->cliente->getBairro(),
            'cidade_id' => $this->cliente->getCidade_id(),
            'nome' => $this->cliente->getNome(),
            'idade' => $this->cliente->getIdade(),
            'data_nascimento' => $this->cliente->getData_nascimento(),
            'cpf' => $this->cliente->getCpf(),
            'rg' => $this->cliente->getRg(),
            'nome_mae' => $this->cliente->getNome_mae(),
            'sexo' => $this->cliente->getSexo(),
            'telefone' => $this->cliente->getTelefone(),
            'celular' => $this->cliente->getCelular(),
            'email' => $this->cliente->getEmail(),
            'endereco' => $this->cliente->getEndereco(),
        );
        try {
            if($id = $this->database->update('pessoa', $cond, $data)){

                $dadoscliente = array(
                        'pessoa_id' => $id,
                        'senha' => $this->cliente->getSenha()
                    );
                
//                if ($idCliente = $this->database->update('cliente', $dadoscliente)) {
//                    
//                    return $idCliente;
//                }
            }
            return true;
        } catch (\Simplon\Mysql\MysqlException $ex) {
            \Delivery\Utils\Utils::displayErrorMessage($ex->getMessage());
            return false;
        }
    }

    public function salvar() {

        $data = array(
            'bairro' => $this->cliente->getBairro(),
            'cidade_id' => $this->cliente->getCidade_id(),
            'nome' => $this->cliente->getNome(),
            'idade' => $this->cliente->getIdade(),
            'data_nascimento' => $this->cliente->getData_nascimento(),
            'cpf' => $this->cliente->getCpf(),
            'rg' => $this->cliente->getRg(),
            'nome_mae' => $this->cliente->getNome_mae(),
            'sexo' => $this->cliente->getSexo(),
            'telefone' => $this->cliente->getTelefone(),
            'celular' => $this->cliente->getCelular(),
            'email' => $this->cliente->getEmail(),
            'endereco' => $this->cliente->getEndereco(),
        );
        try {

            if ($id = $this->database->insert('pessoa', $data)) {
                $dadoscliente = array(
                    'pessoa_id' => $id,
                    'senha' => $this->cliente->getSenha()
                );

                if ($idCliente = $this->database->insert('cliente', $dadoscliente)) {
                    return $idCliente;
                }
            }
            return FALSE;
        } catch (MysqlException $ex) {
     
            return $ex->getTraceAsString();
        }
    }

    public function obterCliente($where = null, $array = false) {

        $wSql = array();

        try {

            $sql = "SELECT pessoa.id as pessoa_id, pessoa.nome, pessoa.idade, pessoa.data_nascimento,"
                    . "pessoa.cpf, pessoa.rg, pessoa.nome_mae, pessoa.sexo, pessoa.telefone,"
                    . "pessoa.celular, pessoa.email, pessoa.endereco, pessoa.cidade_id,"
                    . "pessoa.bairro, cliente.id, cliente.senha FROM cliente "
                    . "INNER JOIN pessoa ON (cliente.pessoa_id = pessoa.id) ";

            if ($where == null) {
                $result = $this->database()->fetchRowMany($sql);
            } else {
                if (array_key_exists('email', $where)) {
                    array_push($wSql, "email = :email");
                }

                if (array_key_exists('senha', $where)) {
                    array_push($wSql, "senha = :senha");
                }

                if (array_key_exists('id', $where)) {
                    array_push($wSql, "cliente.id = :id");
                }

                if (array_key_exists('nome', $where)) {
                    array_push($wSql, "nome = :nome");
                }

                if (array_key_exists('cpf', $where)) {
                    array_push($wSql, "cpf = :cpf");
                }


                if (count($wSql) >= 1) {
                    $wWher = " WHERE " . implode(" AND ", $wSql);
                    $sql .= $wWher;
                }

                $result = $this->database()->fetchRow($sql, $where);
            }

            if ($array) {
                return $result;
            } elseif ($result) {
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
