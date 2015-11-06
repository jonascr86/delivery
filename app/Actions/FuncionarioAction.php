<?php

namespace Delivery\Actions;

use \Delivery\Helpers\SessionHandler as SessionHandler; 
use Delivery\Model\Funcionario;
use Delivery\Model\Login as Login;
use Delivery\Dao\LoginDao as LoginDao;
use Delivery\Dao\FuncionarioDao;

class FuncionarioAction extends Action {

    public $session;
    public $usuarioLogado;
    public $loginDao;
    
    public function run() {
        $this->loginDao = new LoginDao();
        if (SessionHandler::checkSession('usuario')) {
            $this->session = SessionHandler::selectSession('usuario');
            $this->usuarioLogado = $this->session['usuario'];
            
            if (isset($this->params['adicionar']) && $this->params['adicionar']) {
                $this->loadTemplate('funcionario/edit_save_funcionario');
            } else if (isset($this->params['salvar']) && $this->params['salvar']) {
                $this->salvarFuncionario();
            } else if (isset($this->params['editar']) && $this->params['editar']) {
                $this->editarFuncionario();
            } else if (isset($this->params['remover']) && $this->params['remover']) {
                $this->removerFuncionario();
            } else {
                $this->load();
            }
        } else {
            $this->redirect($this->UrlBuilder()->doAction('login'));
        }
    }

    public function load() {
        $this->loadTemplate('funcionario/index');
    }

    function removerFuncionario() {
        $where = array('id' => $this->params['id']);
        $funcionarioObj = new Funcionario();
        $funcionarioDao = new FuncionarioDao($funcionarioObj);
        if ($funcionarioDao->apagar($where)) {
            $successMsg = "Funcionario apagada com sucesso!";
            $this->redirect($this->UrlBuilder()->doAction('funcionario', array('successMsg' => $successMsg)));
        } else {
            $errorMsg = "O problema ao apagar funcionario!";
            $this->redirect($this->UrlBuilder()->doAction('funcionario', array('errorMsg' => $errorMsg)));
        }
    }

    function editarFuncionario() {
        $id = $this->params['id'];
        $funcionarioObj = new Funcionario();
        $funcionarioDao = new FuncionarioDao($funcionarioObj);
        $funcionario = $funcionarioDao->listar($colunas = null, array('id' => "$id"));

        $funcionarioObj->setId($id);
        $funcionarioObj->setPessoa_id($funcionario[0]['pessoa_id']);
        $funcionarioObj->setData_admissao($funcionario[0]['data_admissao']);
        $funcionarioObj->setData_desligamento($funcionario[0]['data_desligamento']);
        $funcionarioObj->setSalario($funcionario[0]['salario']);
        $funcionarioObjS = serialize($funcionarioObj);


        $this->redirect($this->UrlBuilder()->doAction('funcionario', array('adicionar' => TRUE,
                    'funcionarioS' => $funcionarioObjS)));
    }

    function salvarFuncionario() {
        $funcionario = new Funcionario();
        $login = new Login();
        $erro = '';

        if ($this->getPost('id')) {
            $oldSenha = '';
            $funcionario->setId($this->getPost('id'));
            $loginSenha = $this->loginDao->obterLogin(array('funcionario_id' => $this->getPost('id')), FALSE);
            $oldSenha = $loginSenha->getSenha();
        }
        
        $funcionario->setPessoa_id($this->getPost('pessoa_id'));
        $funcionario->setData_admissao($this->getPost('data_admissao'));
        $funcionario->setSalario($this->getPost('salario'));
        $funcionario->setData_desligamento($this->getPost('data_desligamento'));
        
        if($this->getPost('usuario')){
            $login->setUsuario($this->getPost('usuario'));
        }
        
        if($this->getPost('senha')){
            $login->setSenha($this->getPost('senha'));
        }

        $funcionario->setLogin($login);
        
        $funcionarioS = serialize($funcionario);
        
        if ($funcionario->getData_admissao() == '') {
            $erro = "Data de admissão deve ser preenchida";
        }
        
         if ($this->getPost('pessoa_id') == '' || $this->getPost('pessoa_id') == 0) {
            $erro = "Pessoa deve ser informada.";
        }
        
        if (strlen($erro) > 0) {
            $this->redirect($this->UrlBuilder()->doAction('funcionario', array('errorMsg' => $erro, 'adicionar' => true, 'funcionarioS' => $funcionarioS)));
        }

        $funcionarioDao = new FuncionarioDao($funcionario, $login);
        if ($this->getPost('id')) {
            
            $successMsg = "Funcionario atualizado com sucesso!";
            $url = $this->UrlBuilder()->doAction('funcionario', array('successMsg' => $successMsg));
            
            if($this->comparaSimples($this->usuarioLogado, $login->getUsuario())){
                
                if(!$this->comparaSimples($oldSenha, $login->getSenha())){
                    SessionHandler::deleteSession('usuario');
                    $url = $this->UrlBuilder()->doAction('login', array('admin' => TRUE));
                }
            }
            if ($funcionarioDao->editar(array('id' => $this->getPost('id')))) {
                
                $this->redirect($url);
            } else {
                $errorMsg = "Funcionario não pode ser salvo!";
                $this->redirect($this->UrlBuilder()->doAction('funcionario', array('adicionar' => true, 'errorMsg' => $funcionarioDao->getErro(), 'funcionarioS' => $funcionarioS)));
            }
        } else if ($funcionarioDao->salvar()) {
            $successMsg = "Funcionario salva com sucesso!";
            $this->redirect($this->UrlBuilder()->doAction('funcionario', array('successMsg' => $successMsg)));
        } else {
            $errorMsg = "Funcionario não pode ser salvo!";
            $this->redirect($this->UrlBuilder()->doAction('funcionario', array('errorMsg' => $errorMsg)));
        }
    }
    
    public function comparaSimples($string1, $string2){
        return ($string1 == $string2);
    }

}
