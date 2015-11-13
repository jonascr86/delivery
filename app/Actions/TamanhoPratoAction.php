<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TamanhoPratoAction
 *
 * @author Jonas
 */

namespace Delivery\Actions;

use Delivery\Helpers\SessionHandler as SessionHandler;
use Delivery\Dao\TamanhoPratoDao as TamanhoPratoDao;
use Delivery\Model\TamanhoPrato as TamanhoPrato;


class TamanhoPratoAction extends Action {

    public function run() {
        if (SessionHandler::checkSession('usuario')) {
            if (isset($this->params['salvar']) && $this->params['salvar']) {
                $this->salvar();
            }
        }
    }

    public function salvar() {
        $descricao = $this->getPost('tamanho_prato_descricao');
        $tamanhoPrato = new TamanhoPrato();

        if ($descricao != '') {
            $tamanhoPrato->setDescricao(strtoupper($descricao));
            $tamanhoPratoDao = new TamanhoPratoDao($tamanhoPrato);
            if ($tamanhoPratoDao->salvar()) {
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
