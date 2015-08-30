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
            } else if(isset($this->params['salvar']) && $this->params['salvar']){
                $this->salvarPessoa();
            }  else {
                $this->load();
            }
        } else {
            $this->redirect($this->UrlBuilder()->doAction('login'));
        }
    }

    public function load() {
        $this->loadTemplate('pessoa/index');
    }
    
    function salvarPessoa() {
        var_dump($_POST);
        $pessoa = new Pessoa();
        
        $pessoa->setNome($this->getPost('nome'));        
        $pessoa->setIdade($this->getPost('idade'));
        $sexo = $this->getPost('sexo') == 'Masculino' ? 'M' : 'F';
        $pessoa->setSexo($sexo);        
        $pessoa->setData_nascimento($this->getPost('data_nascimento'));        
        $pessoa->setNome_mae($this->getPost('nome_mae'));        
        $pessoa->setCpf($this->getPost('cpf'));        
        $pessoa->setRg($this->getPost('rg'));        
        $pessoa->setEmail($this->getPost('email'));        
        $pessoa->setCelular($this->getPost('celular'));        
        $pessoa->setTelefone($this->getPost('telefone'));        
        $pessoa->setCidade_id($this->getPost('cidade_id'));        
        $pessoa->setBairro_id($this->getPost('bairro_id'));        
        $pessoa->setEndereco($this->getPost('endereco') . ' ' . $this->getPost('n_casa'));        
        $pessoa->setUsuario($this->getPost('usuario'));        
        $pessoa->setSenha($this->getPost('senha'));
        
        $pessoaDao = new PessoaDao('cliente', $pessoa);
        $pessoaDao->salvar();
        
    }

}
