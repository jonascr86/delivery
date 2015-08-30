<?php
namespace Delivery\Actions;
use \Delivery\Helpers\SessionHandler;

class LoginAction extends Action{

    public function run()
    {
        if(isset($this->params['logar']) && !$this->params['logar']){
            $this->efetuarLogout();
        }
        if ( SessionHandler::checkSession('usuario') ) {
            $this->redirect( $this->UrlBuilder()->doAction('admin') );
        } else if(@$this->params['logar'] && isset($this->params['logar'])){
            $this->efetuarLogin();
        }  else {
            $this->loadTemplate('login');
        }
    }

    public function efetuarLogin() {
        if ( isset($_POST['usuario']) && isset($_POST['senha']) ) {
            $usuario  = $this->getPost('usuario');
            $senha     = $this->getPost('senha');

            if ( strlen($usuario) <= 3 ) {
                $this->redirect($this->UrlBuilder()->doAction('login', ['error' => 'Usuário muito curto']));
            }
            
            if(!isset($senha)){
                $this->redirect($this->UrlBuilder()->doAction('login', ['error' => 'Senha inválida']));
            }

            $sql = "SELECT usuario, senha FROM cliente INNER JOIN "
                    . "funcionario ON (cliente.id = funcionario.cliente_id) "
                    . "WHERE usuario = :usuario AND senha = :senha ";
            
            if ( $this->database()->fetchRow($sql, [':usuario' => $usuario, ':senha' => $senha]) ) {
                $login = ['usuario' => $usuario, 'senha' => $senha];
                SessionHandler::createSession('usuario', $login);
                $this->loadTemplate('admin');
            }  else {
                $this->loadTemplate('login');
            }
        }
    }
    
    function efetuarLogout() {
        if(SessionHandler::checkSession('usuario')){
            SessionHandler::deleteSession('usuario');
        }
    }
}