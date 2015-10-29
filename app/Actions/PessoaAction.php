<?php

namespace Delivery\Actions;

use \Delivery\Helpers\SessionHandler;
use Delivery\Model\Pessoa;
use Delivery\Dao\PessoaDao;
 use Delivery\Utils;

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
        $where = array('id' => $this->params['id']);
        $pessoaObj = new Pessoa();
        $pessoaDao = new PessoaDao('pessoa', $pessoaObj);
        if ($pessoaDao->apagar($where)) {
            $successMsg = "Pessoa apagada com sucesso!";
            $this->redirect($this->UrlBuilder()->doAction('pessoa', array('successMsg' => $successMsg)));
        } else {
            $errorMsg = "O problema ao apagar pessoa!";
            $this->redirect($this->UrlBuilder()->doAction('pessoa', array('errorMsg' => $errorMsg)));
        }
    }

    function editarPessoa() {
        $id = $this->params['id'];
        $pessoaObj = new Pessoa();
        $pessoaDao = new PessoaDao('pessoa', $pessoaObj);
        $pessoa = $pessoaDao->listar($colunas = null, array('id' => "$id"));

        $pessoaObj->setId($id);
        $pessoaObj->setNome($pessoa[0]['nome']);
        $pessoaObj->setIdade($pessoa[0]['idade']);
        $sexo = $pessoa[0]['sexo'];
        $pessoaObj->setSexo($sexo);
        $pessoaObj->setData_nascimento($pessoa[0]['data_nascimento']);
        $pessoaObj->setNome_mae($pessoa[0]['nome_mae']);
        $pessoaObj->setCpf($pessoa[0]['cpf']);
        $pessoaObj->setRg($pessoa[0]['rg']);
        $email = $pessoa[0]['email'];
        $pessoaObj->setEmail($email);
        $pessoaObj->setCelular($pessoa[0]['celular']);
        $pessoaObj->setTelefone($pessoa[0]['telefone']);
        $pessoaObj->setCidade_id($pessoa[0]['cidade_id']);
        $pessoaObj->setBairro($pessoa[0]['bairro']);
        $pessoaObj->setEndereco($pessoa[0]['endereco']);

        $pessoaObjS = serialize($pessoaObj);


        $url = $this->redirect($this->UrlBuilder()->doAction('pessoa', array('adicionar' => TRUE,
                    'pessoaS' => $pessoaObjS)));
    }

    function salvarPessoa() {
        $pessoa = new Pessoa();
        $erro = '';
        
        if ($this->getPost('id')) {
            $pessoa->setId($this->getPost('id'));
        }
        $pessoa->setNome($this->getPost('nome'));
        $pessoa->setIdade($this->getPost('idade'));
        $sexo = $this->getPost('sexo') == 'Masculino' ? 'M' : 'F';
        $pessoa->setSexo($sexo);
        $pessoa->setData_nascimento($this->getPost('data_nascimento'));
        $pessoa->setNome_mae($this->getPost('nome_mae'));
        $cpf = $this->getPost('cpf');
        if(!\Delivery\Utils\Utils::validaCPF($cpf)){
            $erro .= "CPF inválido.";
        }else{
            $pessoa->setCpf($cpf);
        }
        $pessoa->setRg($this->getPost('rg'));
        $email = $this->getPost('email');
        if (!$this->getPost('id') && $this->emailExist($email)) {
                       $erro .= "E-mail {$resultado[0]['email']} já esta sendo utilizado.";
        }else{
            $pessoa->setEmail($email);
        }

        $pessoa->setCelular($this->getPost('celular'));
        $pessoa->setTelefone($this->getPost('telefone'));
        if ($this->getPost('cidade_id')  == "0") {
            $erro .= "Cidade deve ser informada.";
        }

        $pessoa->setCidade_id($this->getPost('cidade_id'));
        $pessoa->setBairro($this->getPost('bairro'));
        $pessoa->setEndereco($this->getPost('endereco'));


        $pessoaS = serialize($pessoa);

        if (strlen($erro) > 0) {
            $this->redirect($this->UrlBuilder()->doAction('pessoa', array('errorMsg' => $erro, 'adicionar' => true,  'pessoaS' => $pessoaS)));
        }



        $pessoaDao = new PessoaDao('pessoa', $pessoa);
        if ($this->getPost('id')) {

            if ($pessoaDao->editar(array('id' => $this->getPost('id')))) {
                $successMsg = "Pessoa atualizada com sucesso!";
                $this->redirect($this->UrlBuilder()->doAction('pessoa', array('successMsg' => $successMsg)));
            } else {
                $errorMsg = "Pessoa não pode ser salva!";
                $this->redirect($this->UrlBuilder()->doAction('pessoa', array('adicionar' => true, 'errorMsg' => $pessoaDao->getErro(), 'pessoaS' => $pessoaS)));
            }
        } else if ($pessoaDao->salvar()) {
            $successMsg = "Pessoa salva com sucesso!";
            $this->redirect($this->UrlBuilder()->doAction('pessoa', array('successMsg' => $successMsg)));
        } else {
            $errorMsg = "Pessoa não pode ser salva!";
            $this->redirect($this->UrlBuilder()->doAction('pessoa', array('errorMsg' => $pessoaDao->getErro())));
        }
    }

    function emailExist($where) {
        $colunas = array('email');
        $bWhere = array('email' => $where);
        $pessoaDao = new PessoaDao('pessoa', new Pessoa());
        $resultado = $pessoaDao->listar($colunas, $bWhere);
        if ($resultado[0]['email']) {
            return true;
        }

        return false;
    }

}
