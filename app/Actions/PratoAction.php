<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PratoAction
 *
 * @author Jonas
 */

namespace Delivery\Actions;

use \Delivery\Helpers\SessionHandler;
use Delivery\Utils\Upload as Upload;
use Delivery\Dao\PratoDao;
use Delivery\Model\Prato as Prato;

class PratoAction extends Action {

    public function run() {
        if (SessionHandler::checkSession('usuario')) {
            if (isset($this->params['adicionar']) && $this->params['adicionar']) {
                $this->loadTemplate('prato/edit_save_prato');
            } else if (isset($this->params['salvar']) && $this->params['salvar']) {
                $this->salvarPrato();
            } else if (isset($this->params['editar']) && $this->params['editar']) {
                $this->editarPrato();
            } else if (isset($this->params['remover']) && $this->params['remover']) {
                $this->removerPrato();
            } else {
                $this->loadIndex();
            }
        } else {
            $this->redirect($this->UrlBuilder()->doAction('login'));
        }
    }

    function salvarPrato() {
        
        $nome = $this->getPost('nome');
        $descricao = $this->getPost('descricao');
        $preco = $this->getPost('preco');
        $tipo_prato_id = $this->getPost('tipo_prato_id');
        $status_prato_id = $this->getPost('status_prato_id');
        $imagem = $_FILES['imagem'];
        
        $prato = new Prato();

        if (!isset($nome) || $nome == '') {
            $erro = "O nome deve ser preenchido.";
        }  else {
            $prato->setNome($nome);
        }
        
        if (!isset($descricao) || $descricao == '') {
            $erro = "A descrição deve ser preenchida.";
        }  else {
            $prato->setDescricao($descricao);
        }

        if (!isset($preco) || $preco == '') {
            $erro = "O preço deve ser preenchido.";
        }  else {
            $prato->setPreco($preco);
        }
        
        if (!isset($status_prato_id) || $status_prato_id == '') {
            $erro = "O status deve ser preenchido.";
        }  else {
            $prato->setStatus_prato_is($status_prato_id);
        }
        
        $prato->setTipo_prato_id($tipo_prato_id);

        if (strlen($erro) > 0) {
            $this->redirect($this->UrlBuilder()->doAction('prato', array('pratoS' => $sPrato,
                        'adicionar' => true,
                        'errorMsg' => 'Preencha os dados requridos')));
        }
    }

    function salvarPrato2() {

        if ($this->getPost('descricao')) {
            $descricao = $this->getPost('descricao');
        } else {

            $descricao = '';
        }

        if ($this->getPost('tipo_prato_id')) {
            $tipo_prato_id = $this->getPost('tipo_prato_id');
        } else {
            $tipo_prato_id = '';
        }

        $mPrato = new \Delivery\Model\Prato();
        $mPrato->setDescricao($descricao);
        $mPrato->setTipo_prato_id($tipo_prato_id);
        $sPrato = serialize($mPrato);
        $prato = new PratoDao($mPrato);

        if ($descricao == '' || $tipo_prato_id == '') {
            $this->redirect($this->UrlBuilder()->doAction('prato', array('pratoS' => $sPrato, 'adicionar' => true,
                        'errorMsg' => 'Preencha os dados requridos')));
        } else {

            if ($this->getPost('id')) {
                if ($prato->editar(array('id' => $this->getPost('id')))) {
                    $this->redirect($this->UrlBuilder()->doAction('prato', array('successMsg' => 'Dados salvos com sucesso.')));
                } else {
                    $this->redirect($this->UrlBuilder()->doAction('prato', array('errorMsg' => 'Problemas ao salvar os dados.')));
                }
            }

            if ($prato->salvar()) {
                $this->redirect($this->UrlBuilder()->doAction('prato', array('successMsg' => 'Dados salvos com sucesso.')));
            } else {
                $this->redirect($this->UrlBuilder()->doAction('prato', array('errorMsg' => 'Problemas ao salvar os dados.')));
            }
        }
    }

    public function editarPrato() {
        $conds = array('id' => $this->params['id']);
        $pratoDao = new PratoDao();

        $prato = $pratoDao->obterPratos($conds, false);

        $sprato = serialize($prato);
        $this->redirect($this->UrlBuilder()->doAction('prato', array('pratoS' => $sprato, 'adicionar' => TRUE)));
    }

    public function removerPrato() {
        $conds = array('id' => $this->params['id']);
        $pratoDao = new PratoDao();
        if ($pratoDao->apagar($conds)) {
            $this->redirect($this->UrlBuilder()->doAction('prato', array('successMsg' => 'Dados apagados com sucesso.')));
        } else {
            $this->redirect($this->UrlBuilder()->doAction('prato', array('errorMsg' => 'Problemas ao apagar prato.')));
        }
    }

    public function loadIndex() {
        $this->loadTemplate('prato/index');
    }

}
