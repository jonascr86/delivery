<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FuncionarioDao
 *
 * @author Jonas
 */

namespace Delivery\Dao;
use Delivery\Model\Funcionario as Funcionario;
use Delivery\Model\Login as Login;
use \Simplon\Mysql\MysqlException as MysqlException;
use \Delivery\Utils\Utils as Utils;

class FuncionarioDao extends DAO {

    protected $tabela;
    protected $funcionario;
    protected $login;

    function __construct(Funcionario $funcionario = null, Login $login = null) {
        $this->tabela = 'funcionario';
        
        if ($funcionario instanceof Funcionario) {
            $this->funcionario = $funcionario;
        }
        
        if ($login instanceof Login) {
            $this->login = $login;
        }

        parent::__construct();
    }

    public function editar($conds = null) {

        $data = array(
            'salario' => $this->funcionario->getSalario(),
            'data_admissao' => $this->funcionario->getData_admissao(),
            'data_desligamento' => $this->funcionario->getData_desligamento(),
            'pessoa_id' => $this->funcionario->getPessoa_id()
        );

        $dataLogin = array(
            'usuario' => $this->login->getUsuario(),
            'senha' => $this->login->getSenha(),
            'funcionario_id' => $this->funcionario->getId()
        );

        $condsLogin = array('funcionario_id' => $conds['id']);
        
        try {
            $this->database->update($this->tabela, $conds, $data);
            $this->database->update('login', $condsLogin, $dataLogin);
            return true;
        } catch (MysqlException $ex) {
            Utils::displayErrorMessage($ex->getMessage());
            return false;
        }
    }

    public function obterFuncionarios($where, $retorno = true) {

        $wSql = [];

        try {

            $sql = "SELECT funcionario.id, funcionario.pessoa_id, "
                    . "funcionario.data_admissao, funcionario.data_desligamento, "
                    . "funcionario.salario, pessoa.nome, login.usuario "
                    . "FROM funcionario INNER JOIN pessoa ON "
                    . "(funcionario.pessoa_id = pessoa.id) "
                    . "INNER JOIN login ON (login.funcionario_id = funcionario.id) ";

            if (array_key_exists('pessoa_id', $where)) {
                array_push($wSql, "funcinario.pessoa_id = :passoa_id");
            }

            if (array_key_exists('id', $where)) {
                array_push($wSql, "funcionarios.id = :id");
            }

            if (array_key_exists('nome', $where)) {
                array_push($wSql, "pessoa.nome = :nome");
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

                $funcionario = new Funcionario();
                $funcionario->setId($result[0]['id']);
                $funcionario->setData_admissao($result[0]['data_admissao']);
                $funcionario->setData_desligamento($result[0]['data_desligamento']);
                $funcionario->setPessoa_id($result[0]['pessoa_id']);
                $funcionario->setSalario($result[0]['salario']);

                return $funcionario;
            } else {
                return false;
            }
        } catch (MysqlException $ex) {
            return $ex->getMessage();
        }
    }

    function apagar($conds) {
        return $this->getCrudManager()->delete(Login::crudGetSource(), array('funcionario_id' => $conds['id'])) &&
        $this->getCrudManager()->delete(Funcionario::crudGetSource(), $conds);
    }

    public function salvar() {
        try {
            $crudManager = $this->getCrudManager();
            $result = $crudManager->create($this->funcionario);
            if ($result instanceof Funcionario) {
                $this->login->setFuncionario_id($result->getId());
                $login = $crudManager->create($this->login);
                if($login instanceof Login){
                    return TRUE;
                }
            }
            return FALSE;
        } catch (MysqlException $exc) {
            echo $exc->getTraceAsString();
        }
    }
}
