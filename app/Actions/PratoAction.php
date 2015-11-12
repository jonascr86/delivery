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

use Delivery\Helpers\SessionHandler;
use Delivery\Utils\Upload as Upload;
use Delivery\Dao\PratoDao;
use Delivery\Model\Prato as Prato;
use Delivery\Model\Imagem as Imagem;
use Delivery\Dao\ImagemDao as ImagemDao;

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
//        $tipo_prato_id = $this->getPost('tipo_prato_id');
        $tipo_prato_id = 1;
//        $status_prato_id = $this->getPost('status_prato_id');
        $status_prato_id = 1;
//        $tamanho_prato_id = $this->getPost('tamanho_prato_id');
        $tamanho_prato_id = 1;
        $imagem = $_FILES['imagem'];
        
        $erro = '';

        $prato = new Prato();

        if (!isset($nome) || $nome == '') {
            $erro = "O nome deve ser preenchido.";
        } else {
            $prato->setNome($nome);
        }

        if (!isset($descricao) || $descricao == '') {
            $erro = "A descrição deve ser preenchida.";
        } else {
            $prato->setDescricao($descricao);
        }

        if (!isset($preco) || $preco == '') {
            $erro = "O preço deve ser preenchido.";
        } else {
            
            $preco = explode(" ", $preco);
            $preco = end($preco);
            $prato->setPreco($preco);
        }

        if (!isset($status_prato_id) || $status_prato_id == 0) {
            $erro = "O status deve ser preenchido.";
        } else {
            $prato->setStatus_prato_id($status_prato_id);
        }
        
        if (!isset($tipo_prato_id) || $tipo_prato_id == 0) {
            $erro = "O tipo deve ser preenchido.";
        } else {
            $prato->setTipo_prato_id($tipo_prato_id);
        }
        
        if (!isset($tamanho_prato_id) || $tamanho_prato_id == 0) {
            $erro = "O tamanho deve ser preenchido.";
        } else {
            $prato->setTamanho_prato_id($tamanho_prato_id);
        }

        if ($imagem['error'] != 0) {
            $erro = "Problemas ao enviar imagem.";
        } else {

            $nomeImagem = $this->uploadImage($imagem);

            if (strlen($nomeImagem) <= 0) {
                $erro = "Problemas ao enviar imagem.";
            } else {

                $imagem_prato_id = $this->salvarImagem($nomeImagem);
                $prato->setImagem_prato_id($imagem_prato_id);
            }
        }

        $prato->setTipo_prato_id($tipo_prato_id);
        $sPrato = serialize($prato);

        if (strlen($erro) > 0) {
            $this->redirect($this->UrlBuilder()->doAction('prato', array('pratoS' => $sPrato,
                        'adicionar' => true,
                        'errorMsg' => $erro)));
        }
        
        $pratoDao = new PratoDao($prato);

        if ($this->getPost('id')) {
            if ($pratoDao->editar(array('id' => $this->getPost('id')))) {
                $this->redirect($this->UrlBuilder()->doAction('prato', array('successMsg' => 'Dados salvos com sucesso.')));
            } else {
                $this->redirect($this->UrlBuilder()->doAction('prato', array('errorMsg' => 'Problemas ao salvar os dados.')));
            }
        }

        if ($pratoDao->salvar()) {
            $this->redirect($this->UrlBuilder()->doAction('prato', array('successMsg' => 'Dados salvos com sucesso.')));
        } else {
            $this->redirect($this->UrlBuilder()->doAction('prato', array('errorMsg' => 'Problemas ao salvar os dados.')));
        }
    }

    function uploadImage($imagem) {
        $upload = new Upload($imagem, 200, 200, UPLOAD_IMAGES);
        $nome = $upload->salvar();
        return $nome;
    }

    function salvarImagem($nomeImagem) {
        $objImagem = new Imagem();
        $objImagem->setCaminho(UPLOAD_IMAGES . $nomeImagem);

        $imagemDao = new ImagemDao($objImagem);
        $imagem_prato_id = $imagemDao->salvar();
        return $imagem_prato_id;
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
        }

        if ($this->getPost('id')) {
            if ($prato->editar(array('id' => $this->getPost('id')))) {
                $this->redirect($this->UrlBuilder()->doAction('prato', array('successMsg' => 'Dados salvos com sucesso.')));
            } else {
                $this->redirect($this->UrlBuilder()->doAction('prato', array('errorMsg' => 'Problemas ao salvar os dados.')));
            }
        } else

        if ($prato->salvar()) {
            $this->redirect($this->UrlBuilder()->doAction('prato', array('successMsg' => 'Dados salvos com sucesso.')));
        } else {
            $this->redirect($this->UrlBuilder()->doAction('prato', array('errorMsg' => 'Problemas ao salvar os dados.')));
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
