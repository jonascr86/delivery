<?php

namespace Delivery\Actions;

use \Delivery\Helpers\SessionHandler;
use Delivery\Model\Pessoa;

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
    }

}
