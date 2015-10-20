<?php
namespace Delivery\Actions;
use \Delivery\Helpers\SessionHandler;

class AdminAction extends Action {

    public function run()
    {
        if ( SessionHandler::checkSession('usuario') ) {
            $this->load();
        } else {
            $this->redirect( $this->UrlBuilder()->doAction('login', ['admin' => 1]) );
        }

    }

    public function load() {
        $this->loadTemplate('admin');
    }
}