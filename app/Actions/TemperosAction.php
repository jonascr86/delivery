<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TemperosAction
 *
 * @author Jonas
 */

namespace Delivery\Actions;

use Delivery\Helpers\SessionHandler;

class TemperosAction extends Action {

    public function run() {
        if (SessionHandler::checkSession('usuario')) {
            if (isset($this->params['adicionar']) && $this->params['adicionar']) {
                $this->loadTemplate('temperos/edit_save_temperos');
            } else if (isset($this->params['salvar']) && $this->params['salvar']) {
                $this->salvarTempero();
            } else if (isset($this->params['editar']) && $this->params['editar']) {
                $this->editarTempero();
            } else if (isset($this->params['remover']) && $this->params['remover']) {
                $this->removerTempero();
            } else {
                $this->loadIndex();
            }
        } else {
            $this->redirect($this->UrlBuilder()->doAction('login'));
        }
    }

    public function loadIndex() {
        $this->loadTemplate('temperos/index');
    }

    public function salvarTempero() {

        $descricao = $this->getPost('descricao');
        $id = $this->getPost('id');
        $temperos = new \Delivery\Model\Temperos();
        $temperos->setDescricao($descricao);
        $temperoS = serialize($temperos);
        $temperoDao = new \Delivery\Dao\TemperosDao($temperos);

        if ($descricao != '') {

            if ($id) {
                if ($temperoDao->editar(array('id' => $id))) {
                    $this->redirect($this->UrlBuilder()->doAction('temperos', array('successMsg' => 'Dados alterados com sucesso.')));
                } else {
                    $this->redirect($this->UrlBuilder()->doAction('temperos', array('errorMsg' => 'Problemas ao alterar os dados.')));
                }
            } else {
                if ($temperoDao->salvar()) {
                    $this->redirect($this->UrlBuilder()->doAction('temperos', array('successMsg' => 'Dados salvos com sucesso.')));
                } else {
                    $this->redirect($this->UrlBuilder()->doAction('temperos', array('errorMsg' => 'Problemas ao salvar os dados.')));
                }
            }
        } else {
            $this->redirect($this->UrlBuilder()->doAction('temperos', array('adicionar' => true,
                        'errorMsg' => 'Preencha os dados requridos')));
        }
    }
    
    public function removerTempero() {
        $conds = array('id' => $this->params['id']);
        $temperoDao = new \Delivery\Dao\TemperosDao();
        if ($temperoDao->apagar($conds)) {
            $this->redirect($this->UrlBuilder()->doAction('temperos', array('successMsg' => 'Dados apagados com sucesso.')));
        } else {
            $this->redirect($this->UrlBuilder()->doAction('temperos', array('errorMsg' => 'Problemas ao apagar ingrediente.')));
        }
    }
    
    public function editarTempero() {
        $conds = array('id' => $this->params['id']);
        $temperoDao = new \Delivery\Dao\TemperosDao();

        $tempero = $temperoDao->obterTemperos($conds, false);

        $sTempero = serialize($tempero);
        $this->redirect($this->UrlBuilder()->doAction('temperos', array('temperoS' => $sTempero, 'adicionar' => TRUE)));
    }

//put your code here
}
