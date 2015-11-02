<?php

namespace Delivery\Actions;

use \Delivery\Helpers\SessionHandler;
use \Delivery\Dao\ClienteDao as ClienteDao;
use \Delivery\Model\Cliente as Cliente;

class LoginAction extends Action {

    public function run() {
        if (isset($this->params['cliente']) && $this->params['cliente']) {
            $this->efetuarloginCliente();
        }
        if (isset($this->params['logar']) && !$this->params['logar']) {
            $this->efetuarLogout();
        }
        if (SessionHandler::checkSession('usuario')) {
            $this->redirect($this->UrlBuilder()->doAction('admin'));
        } elseif (isset($this->params['admin'])) {
            $this->loadTemplate('login');
        } else if (@$this->params['logar'] && isset($this->params['logar'])) {
            $this->efetuarLogin();
        } else {
            $this->loadTemplate('index');
        }
    }

    public function efetuarLogin() {
        if (isset($_POST['usuario']) && isset($_POST['senha'])) {
            $usuario = $this->getPost('usuario');
            $senha = $this->getPost('senha');

            if (strlen($usuario) <= 3) {
                $this->redirect($this->UrlBuilder()->doAction('login', array('error' => 'Usuário muito curto')));
            }

            if (!isset($senha)) {
                $this->redirect($this->UrlBuilder()->doAction('login', array('error' => 'Senha inválida')));
            }

            try {
                $sql = "SELECT usuario, senha FROM login "
                        . "WHERE usuario = :usuario AND senha = :senha ";

                if ($this->database()->fetchRow($sql, array(':usuario' => $usuario, ':senha' => $senha))) {
                    $login = array('usuario' => $usuario, 'senha' => $senha);
                    SessionHandler::createSession('usuario', $login);
                    $this->loadTemplate('admin');
                } else {
                    $this->loadTemplate('login');
                }
            } catch (\Simplon\Mysql\MysqlException $exc) {
                $this->redirect($this->UrlBuilder()->doAction('login', array('error' => 'Problemas ao efetuar login.')));
            }
        }
    }

    function efetuarLogout() {
        if (SessionHandler::checkSession('usuario')) {
            SessionHandler::deleteSession('usuario');
        }
    }

    function efetuarloginCliente() {
        $email = $this->getPost('email');
        $senha = $this->getPost('senha');
        
        $where = array('email' => $email, 'senha' => $senha);
        
        $clienteDao = new ClienteDao();
        $cliente = $clienteDao->obterCliente($where);

        if ($cliente instanceof Cliente) {
            $login = array('email' => $email, 'senha' => $senha);
            SessionHandler::createSession('cliente', $login);
            $array = array('nome' => $cliente->getNome(), 'id' => $cliente->getId());
            echo $array;
            die();
        } else {
            echo 'Seu dados não foram encontrados!';
        }
        die();
    }

}
