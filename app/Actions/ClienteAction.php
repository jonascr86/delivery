<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClienteAction
 *
 * @author jonascr86
 */

namespace Delivery\Actions;

use Delivery\Helpers\SessionHandler as SessionHandler;
use \Delivery\Model\Cliente as Cliente;
use Delivery\Dao\ClienteDao as ClienteDao;
use \Delivery\Utils\Utils as Utils;

class ClienteAction extends Action {

    public $cliente;

    public function run() {
        $this->cliente = new Cliente();
        if (SessionHandler::checkSession('cliente')) {
            $this->loadTemplate('cliente/login');
        } elseif (isset($this->params['novo'])) {
            if ($this->getPost('new_email')) {
                $newEmail = $this->getPost('new_email');
            }

            if ($this->getPost('new_senha') && $this->getPost('rsenha')) {
                $newSenha = $this->getPost('new_senha');
                $rSenha = $this->getPost('rsenha');
                if ($rSenha != $newSenha) {
                    $this->loadTemplate('cliente/login', array('msg' => 'As senha nÃ£o sÃ£o iguais.'));
                }
                $dados = array(
                    'senha' => $newSenha,
                    'email' => $newEmail
                );
                $this->loadTemplate('cliente/edit_save_cliente', array('dados' => $dados));
            } else {
                $this->loadTemplate('cliente/login', array('msg' => 'Preencha todos os dados requeridos (*).'));
            }
        } elseif (isset($this->params['adicionar'])) {
            $this->loadTemplate('cliente/edit_save_cliente');
        } elseif (isset($this->params['salvar'])) {
            $this->salvarCliente();
        } elseif (isset($this->params['login'])) {
            $this->efetuarloginCliente();
        } elseif (isset($this->params['admin'])) {
            $this->loadTemplate('cliente/index');
        } else {
            $this->loadTemplate('cliente/login');
        }
    }

    function salvarCliente() {
        $cliente = new Cliente();
        $erro = '';

        if ($this->getPost('id')) {
            $cliente->setId($this->getPost('id'));
        }

        $cliente->setNome($this->getPost('nome'));
        $cliente->setIdade($this->getPost('idade'));
        $sexo = $this->getPost('sexo') == 'Masculino' ? 'M' : 'F';
        $cliente->setSexo($sexo);
        $cliente->setData_nascimento($this->getPost('data_nascimento'));
        $cliente->setNome_mae($this->getPost('nome_mae'));
        $cpf = $this->getPost('cpf');
//        if (!Utils::validaCPF($cpf)) {
//            $erro .= "CPF invÃ¡lido.";
//        } else {
        $cliente->setCpf($cpf);
//        }
        $cliente->setRg($this->getPost('rg'));
        $email = $this->getPost('email');
//        if (!$this->getPost('id') && $this->emailExist($email)) {
//            $erro .= "E-mail jÃ¡ esta sendo utilizado.";
//        } else {
        $cliente->setEmail($email);
//        }

        $cliente->setCelular($this->getPost('celular'));
        $cliente->setTelefone($this->getPost('telefone'));
        if ($this->getPost('cidade_id') == "0") {
            $erro .= "Cidade deve ser informada.";
        }


        $cliente->setSenha($this->getPost('senha'));
        $cliente->setCidade_id($this->getPost('cidade_id'));
        $cliente->setBairro($this->getPost('bairro'));
        $cliente->setEndereco($this->getPost('endereco'));

        $clienteS = serialize($cliente);

        if (strlen($erro) > 0) {
            $this->redirect($this->UrlBuilder()->doAction('cliente', array('errorMsg' => $erro, 'adicionar' => true, 'clienteS' => $clienteS)));
        }

        $clienteDao = new ClienteDao($cliente);
        if ($this->getPost('id')) {

            if ($clienteDao->editar(array('id' => $this->getPost('id')))) {
                $successMsg = "Cliente atualizada com sucesso!";
                $this->redirect($this->UrlBuilder()->doAction('cliente', array('successMsg' => $successMsg)));
            } else {
                $errorMsg = "Cliente nÃ£o pode ser salvo!";
                $this->redirect($this->UrlBuilder()->doAction('cliente', array('adicionar' => true, 'errorMsg' => $clienteDao->getErro(), 'clienteS' => $clienteS)));
            }
        } else if ($idcliente = $clienteDao->salvar()) {
            $successMsg = "Cliente salvo com sucesso!";
            $where = array('id' => $idcliente);
            $clienteDao = new ClienteDao();
            $cliente = $clienteDao->obterCliente($where);

            if ($cliente instanceof Cliente) {
                $session = array(
                    'senha' => $cliente->getSenha(),
                    'email' => $cliente->getEmail()
                );
            }

            SessionHandler::createSession('cliente', $session);

            $this->redirect($this->UrlBuilder()->doAction('index'));
        } else {
            $errorMsg = "Cliente nÃ£o pode ser salvo!";
            $this->redirect($this->UrlBuilder()->doAction('index'));
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
            $this->redirect($this->UrlBuilder()->doAction('index'));
        } else {
            $this->loadTemplate('cliente/login', array('msgLogin' => 'Seus dados não foram encontrados.'));
        }
    }

}
