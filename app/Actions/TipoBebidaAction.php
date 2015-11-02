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
use Delivery\Helpers\SessionHandler;

class TipoBebidaAction extends Action{

    public function run() {
        if (SessionHandler::checkSession('usuario')) {
            if (isset($this->params['salvar']) && $this->params['salvar']) {
                $this->salvar();
            }
        }
    }

    public function salvar() {
        $descricao = $this->getPost('tipo_descricao');
        $tipoBebida = new \Delivery\Model\TipoBebida();
        
        if($descricao != ''){
            $tipoBebida->setDescricao($descricao);
            $tipoBebidaDao = new \Delivery\Dao\TipoBebidaDao($tipoBebida);
            if($tipoBebidaDao->salvar()){
                echo 'Dados salvos com sucesso';
                die();
            }else{
                echo 'Problemas ao salvar dados.';
            }
        }
    }

}
