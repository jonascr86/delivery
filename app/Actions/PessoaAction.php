<?php

namespace Delivery\Actions;

use \Delivery\Helpers\SessionHandler;
use Delivery\Model\Pessoa;
use Delivery\Dao\PessoaDao;

class PessoaAction extends Action {

    public function run() {
        if (SessionHandler::checkSession('usuario')) {
            if (isset($this->params['adicionar']) && $this->params['adicionar']) {
                $this->loadTemplate('pessoa/edit_save_pessoa');
            } else if (isset($this->params['salvar']) && $this->params['salvar']) {
                $this->salvarPessoa();
            } else if (isset($this->params['editar']) && $this->params['editar']) {
                $this->editarPessoa();
            } else if (isset($this->params['remover']) && $this->params['remover']) {
                $this->removerPessoa();
            } else {
                $this->load();
            }
        } else {
            $this->redirect($this->UrlBuilder()->doAction('login'));
        }
    }

    public function load() {
        $this->loadTemplate('pessoa/index');
    }

    function removerPessoa() {
        $where = ['id' => $this->params['id']];
        $pessoaObj = new Pessoa();
        $pessoaDao = new PessoaDao('pessoa', $pessoaObj);
        if ($pessoaDao->apagar($where)) {
            $successMsg = "Pessoa apagada com sucesso!";
            $this->redirect($this->UrlBuilder()->doAction('pessoa', ['successMsg' => $successMsg]));
        }  else {
            $errorMsg = "O problema ao apagar pessoa!";
            $this->redirect($this->UrlBuilder()->doAction('pessoa', ['errorMsg' => $errorMsg]));
        }
    }

    function editarPessoa() {
        $id = $this->params['id'];
        $pessoaObj = new Pessoa();
        $pessoaDao = new PessoaDao('pessoa', $pessoaObj);
        $pessoa = $pessoaDao->listar($colunas = null, ['id' => "$id"])[0];

        $pessoaObj->setId($id);
        $pessoaObj->setNome($pessoa['nome']);
        $pessoaObj->setIdade($pessoa['idade']);
        $sexo = $pessoa['sexo'];
        $pessoaObj->setSexo($sexo);
        $pessoaObj->setData_nascimento($pessoa['data_nascimento']);
        $pessoaObj->setNome_mae($pessoa['nome_mae']);
        $pessoaObj->setCpf($pessoa['cpf']);
        $pessoaObj->setRg($pessoa['rg']);
        $email = $pessoa['email'];
        $pessoaObj->setEmail($email);
        $pessoaObj->setCelular($pessoa['celular']);
        $pessoaObj->setTelefone($pessoa['telefone']);
        $pessoaObj->setCidade_id($pessoa['cidade_id']);
        $pessoaObj->setBairro($pessoa['bairro']);
        $pessoaObj->setEndereco($pessoa['endereco']);

        $pessoaObjS = serialize($pessoaObj);

        $url = $this->redirect($this->UrlBuilder()->doAction('pessoa', ['adicionar' => TRUE,
                    'pessoaS' => $pessoaObjS]));
    }

    function salvarPessoa() {
        $pessoa = new Pessoa();

        $pessoa->setNome($this->getPost('nome'));
        $pessoa->setIdade($this->getPost('idade'));
        $sexo = $this->getPost('sexo') == 'Masculino' ? 'M' : 'F';
        $pessoa->setSexo($sexo);
        $pessoa->setData_nascimento($this->getPost('data_nascimento'));
        $pessoa->setNome_mae($this->getPost('nome_mae'));
        $pessoa->setCpf($this->getPost('cpf'));
        $pessoa->setRg($this->getPost('rg'));
        $email = $this->getPost('email');
        if (!$this->getPost('id')) {
            $this->emailExist($email);
        }
        $pessoa->setEmail($email);
        $pessoa->setCelular($this->getPost('celular'));
        $pessoa->setTelefone($this->getPost('telefone'));
        $pessoa->setCidade_id($this->getPost('cidade_id'));
        $pessoa->setBairro($this->getPost('bairro'));
        $pessoa->setEndereco($this->getPost('endereco'));

        try {
            $pessoaDao = new PessoaDao('pessoa', $pessoa);
            if ($this->getPost('id')) {

                if ($pessoaDao->edit(['id' => $this->getPost('id')])) {
                    $successMsg = "Pessoa atualizada com sucesso!";
                    $this->redirect($this->UrlBuilder()->doAction('pessoa', ['successMsg' => $successMsg]));
                } else {
                    $errorMsg = "Pessoa não pode ser salva!";
                    $this->redirect($this->UrlBuilder()->doAction('pessoa', ['errorMsg' => $errorMsg]));
                }
            } else if ($pessoaDao->salvar()) {
                $successMsg = "Pessoa salva com sucesso!";
                $this->redirect($this->UrlBuilder()->doAction('pessoa', ['successMsg' => $successMsg]));
            } else {
                $errorMsg = "Pessoa não pode ser salva!";
                $this->redirect($this->UrlBuilder()->doAction('pessoa', ['errorMsg' => $errorMsg]));
            }
        } catch (Exception $exc) {
            $errorMsg = "O sistema não pode tratar os dados! {$exc->getMessage()}";
            $this->redirect($this->UrlBuilder()->doAction('pessoa', ['errorMsg' => $errorMsg]));
        }
    }

    function emailExist($where) {
        $colunas = ['email'];
        $bWhere = ['email' => $where];
        $pessoaDao = new PessoaDao('pessoa', new Pessoa());
        $resultado = $pessoaDao->listar($colunas, $bWhere);
        if ($resultado[0]['email']) {
            $errorMsg = "E-mail {$resultado[0]['email']} já esta sendo utilizado!";
            $this->redirect($this->UrlBuilder()->doAction('pessoa', ['errorMsg' => $errorMsg]));
        }
    }

}
