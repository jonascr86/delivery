<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IngredientesAction
 *
 * @author Jonas
 */

namespace Delivery\Actions;

use Delivery\Helpers\SessionHandler;

class IngredientesAction extends Action {

    public function run() {
        if (SessionHandler::checkSession('usuario')) {
            if (isset($this->params['adicionar']) && $this->params['adicionar']) {
                $this->loadTemplate('ingredientes/edit_save_ingredientes');
            } else if (isset($this->params['salvar']) && $this->params['salvar']) {
                $this->salvarIngrediente();
            } else if (isset($this->params['editar']) && $this->params['editar']) {
                $this->editarIngrediente();
            } else if (isset($this->params['remover']) && $this->params['remover']) {
                $this->removerIngrediente();
            } else {
                $this->loadIndex();
            }
        } else {
            $this->redirect($this->UrlBuilder()->doAction('login'));
        }
    }

    public function loadIndex() {
        $this->loadTemplate('ingredientes/index');
    }

    public function salvarIngrediente() {

        $descricao = $this->getPost('descricao');
        $id = $this->getPost('id');
        $ingredientes = new \Delivery\Model\Ingredientes();
        $ingredientes->setDescricao($descricao);
        $ingredienteS = serialize($ingredientes);
        $ingredienteDao = new \Delivery\Dao\IngredientesDao($ingredientes);

        if ($descricao != '') {

            if ($id) {
                if ($ingredienteDao->editar(array('id' => $id))) {
                    $this->redirect($this->UrlBuilder()->doAction('ingredientes', array('successMsg' => 'Dados salvos com sucesso.')));
                } else {
                    $this->redirect($this->UrlBuilder()->doAction('ingredientes', array('errorMsg' => 'Problemas ao salvar os dados.')));
                }
            } else {
                if ($ingredienteDao->salvar()) {
                    $this->redirect($this->UrlBuilder()->doAction('ingredientes', array('successMsg' => 'Dados salvos com sucesso.')));
                } else {
                    $this->redirect($this->UrlBuilder()->doAction('ingredientes', array('errorMsg' => 'Problemas ao salvar os dados.')));
                }
            }
        } else {
            $this->redirect($this->UrlBuilder()->doAction('ingredientes', array('adicionar' => true,
                        'errorMsg' => 'Preencha os dados requridos')));
        }
    }
    
    public function removerIngrediente() {
        $conds = array('id' => $this->params['id']);
        $ingredienteDao = new \Delivery\Dao\IngredientesDao();
        if ($ingredienteDao->apagar($conds)) {
            $this->redirect($this->UrlBuilder()->doAction('ingredientes', array('successMsg' => 'Dados apagados com sucesso.')));
        } else {
            $this->redirect($this->UrlBuilder()->doAction('ingredientes', array('errorMsg' => 'Problemas ao apagar ingrediente.')));
        }
    }
    
    public function editarIngrediente() {
        $conds = array('id' => $this->params['id']);
        $ingredienteDao = new \Delivery\Dao\IngredientesDao();

        $ingrediente = $ingredienteDao->obterIngredientes($conds, false);

        $sIngrediente = serialize($ingrediente);
        $this->redirect($this->UrlBuilder()->doAction('ingredientes', array('ingredienteS' => $sIngrediente, 'adicionar' => TRUE)));
    }

//put your code here
}
