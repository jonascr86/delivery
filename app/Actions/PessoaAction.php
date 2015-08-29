<?php
namespace Delivery\Actions;
use \Delivery\Helpers\SessionHandler;

class PessoaAction extends Action {

    public function run()
    {
        if ( SessionHandler::checkSession('usuario') ) {
            $this->load();
        } else {
            $this->redirect( $this->UrlBuilder()->doAction('login') );
        }

    }

    public function load() {
        $this->loadTemplate('pessoa');
    }
}