<?php
namespace Delivery\Actions;
use \Delivery\Helpers\SessionHandler;

class IndexAction extends Action {

    public function run()
    {
        $this->loadTemplate('index');
    }
}