<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TipoBebidaAction
 *
 * @author Jonas
 */

namespace Delivery\Actions;

use Delivery\Helpers\SessionHandler as SessionHandler;
use Delivery\Model\StatusPrato as StatusPrato;
use Delivery\Dao\StatusPratoDao as StatusPratoDao;

class StatusPratoAction extends Action {

    public function run() {
        if (SessionHandler::checkSession('usuario')) {
            if (isset($this->params['salvar']) && $this->params['salvar']) {
                $this->salvar();
            }
        }
    }

    public function salvar() {
        $descricao = $this->getPost('status_prato_descricao');
        $statusPrato = new StatusPrato();

        if ($descricao != '') {
            $statusPrato->setDescricao(strtoupper($descricao));
            $statusPratoDao = new StatusPratoDao($statusPrato);
            if ($statusPratoDao->salvar()) {
                echo 'Dados salvos com sucesso';
                die();
            } else {
                echo 'Problemas ao salvar dados.';
                die();
            }
        } else {
            echo 'Os dados devem ser preenchidos.';
            die();
        }
    }

}
