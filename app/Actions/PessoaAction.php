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
        $pessoaDao = new PessoaDao('cliente', $pessoaObj);
        if ($pessoaDao->apagar($where)) {
            $redirect = $this->redirect($this->UrlBuilder()->doAction('pessoa'));
        }
    }

    function editarPessoa() {
        $id = $this->params['id'];
        $pessoaObj = new Pessoa();
        $pessoaDao = new PessoaDao('cliente', $pessoaObj);
        $pessoa = $pessoaDao->listar($colunas = null, ['id' => "$id"])[0];

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
        $pessoaObj->setBairro_id($pessoa['bairro_id']);
        $pessoaObj->setEndereco($pessoa['endereco']);
        $pessoaObj->setUsuario($pessoa['usuario']);
        $pessoaObj->setSenha($pessoa['senha']);
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
        $this->emailExist($email);
        $pessoa->setEmail($email);
        $pessoa->setCelular($this->getPost('celular'));
        $pessoa->setTelefone($this->getPost('telefone'));
        $pessoa->setCidade_id($this->getPost('cidade_id'));
        $pessoa->setBairro_id($this->getPost('bairro_id'));
        $pessoa->setEndereco($this->getPost('endereco') . ' ' . $this->getPost('n_casa'));
        $pessoa->setUsuario($this->getPost('usuario'));
        $pessoa->setSenha($this->getPost('senha'));

        $pessoaDao = new PessoaDao('cliente', $pessoa);
        if ($pessoaDao->salvar()) {
            $this->redirect($this->UrlBuilder()->doAction('pessoa'));
        }
    }

    function emailExist($where) {
        $colunas = ['email'];
        $bWhere = ['email' => $where];
        $pessoaDao = new PessoaDao('cliente', new Pessoa());
        $resultado = $pessoaDao->listar($colunas, $bWhere);
        if ($resultado[0]['email']) {
//            throw new \Exception("E-mail já exite.");
            $this->redirect($this->UrlBuilder()->doAction('pessoa', ['erro' => "E-mail " . $resultado[0]['email'] . " já exite."]));
        }
    }

}
