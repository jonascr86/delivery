<?php

namespace Delivery\Actions;

use \Delivery\Helpers\SessionHandler;
use Delivery\Model\Funcionario;
use Delivery\Dao\FuncionarioDao;
use Delivery\Utils;

class FuncionarioAction extends Action {

    public function run() {
        if (SessionHandler::checkSession('usuario')) {
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
        $funcionarioObj->setNome($funcionario[0]['nome']);
        $funcionarioObj->setIdade($funcionario[0]['idade']);
        $sexo = $funcionario[0]['sexo'];
        $funcionarioObj->setSexo($sexo);
        $funcionarioObj->setData_nascimento($funcionario[0]['data_nascimento']);
        $funcionarioObj->setNome_mae($funcionario[0]['nome_mae']);
        $funcionarioObj->setCpf($funcionario[0]['cpf']);
        $funcionarioObj->setRg($funcionario[0]['rg']);
        $email = $funcionario[0]['email'];
        $funcionarioObj->setEmail($email);
        $funcionarioObj->setCelular($funcionario[0]['celular']);
        $funcionarioObj->setTelefone($funcionario[0]['telefone']);
        $funcionarioObj->setCidade_id($funcionario[0]['cidade_id']);
        $funcionarioObj->setBairro($funcionario[0]['bairro']);
        $funcionarioObj->setEndereco($funcionario[0]['endereco']);

        $funcionarioObjS = serialize($funcionarioObj);


        $this->redirect($this->UrlBuilder()->doAction('funcionario', array('adicionar' => TRUE,
                    'funcionarioS' => $funcionarioObjS)));
    }

    function salvarFuncionario() {
        $funcionario = new Funcionario();
        $erro = '';

        if ($this->getPost('id')) {
            $funcionario->setId($this->getPost('id'));
        }
        $funcionario->setPessoa_id($this->getPost('pessoa_id'));
        $funcionario->setData_admissao($this->getPost('data_admissao'));
        $funcionario->setSalario($this->getPost('salario'));
        $funcionario->setData_desligamento($this->getPost('data_desligamento'));

        $funcionarioS = serialize($funcionario);

        if (strlen($erro) > 0) {
            $this->redirect($this->UrlBuilder()->doAction('funcionario', array('errorMsg' => $erro, 'adicionar' => true, 'funcionarioS' => $funcionarioS)));
        }

        $funcionarioDao = new FuncionarioDao($funcionario);
        if ($this->getPost('id')) {

            if ($funcionarioDao->editar(array('id' => $this->getPost('id')))) {
                $successMsg = "Funcionario atualizado com sucesso!";
                $this->redirect($this->UrlBuilder()->doAction('funcionario', array('successMsg' => $successMsg)));
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

}
