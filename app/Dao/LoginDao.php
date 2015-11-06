<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginDao
 *
 * @author Jonas
 */

namespace Delivery\Dao;

use \Simplon\Mysql\MysqlException as MysqlException;
use \Delivery\Utils\Utils as Utils;
use Delivery\Model\Login as Login;

class LoginDao extends DAO {

    protected $tabela;
    protected $login;

    function __construct(Login $login = null) {
        $this->tabela = 'login';
        if ($login instanceof Login) {
            $this->login = $login;
        }

        parent::__construct();
    }

    public function editar($conds = null) {

        $data = array(
            'senha' => $this->login->getSenha(),
            'usuario' => $this->login->getUsuario(),
            'funcionario_id' => $this->login->getFuncionario_id()
        );

        try {
            $this->database->update($this->tabela, $conds, $data);
            return true;
        } catch (MysqlException $ex) {
            Utils::displayErrorMessage($ex->getMessage());
            return false;
        }
    }

    public function obterLogin($where, $retorno = true) {

        $wSql = [];

        try {

            $sql = "SELECT login.id, login.usuario, "
                    . "login.senha, login.funcionario_id "
                    . "FROM login ";

            if (array_key_exists('id', $where)) {
                array_push($wSql, "tipo_login.descricao = %tipo%");
            }

            if (array_key_exists('usuario', $where)) {
                array_push($wSql, "usuario = :usuario");
            }

            if (array_key_exists('funcionario_id', $where)) {
                array_push($wSql, "login.funcionario_id = :funcionario_id");
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

                $login = new Login();
                $login->setId($result[0]['id']);
                $login->setUsuario($result[0]['usuario']);
                $login->setSenha($result[0]['senha']);
                $login->setFuncionario_id($result[0]['funcionario_id']);

                return $login;
            } else {
                return false;
            }
        } catch (MysqlException $ex) {
            return $ex->getMessage();
        }
    }

    function apagar($conds) {
        return $this->getCrudManager()->delete(Login::crudGetSource(), $conds);
    }

    public function salvar() {
        try {
            $crudManager = $this->getCrudManager();
            $result = $crudManager->create($this->login);
            if ($result instanceof Login) {
                return TRUE;
            }
            return FALSE;
        } catch (MysqlException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
