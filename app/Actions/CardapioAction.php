<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CardapioAction
 *
 * @author Jonas
 */

namespace Delivery\Actions;

use Delivery\Helpers\SessionHandler;
use Delivery\Dao\CardapioDao;
use Delivery\Model\Cardapio as Cardapio;
use Delivery\Model\CardapioPrato as CardapioPrato;
use Delivery\Dao\CardapioPratoDao as CardapioPratoDao;

class CardapioAction extends Action {

    public function run() {
        if (SessionHandler::checkSession('usuario')) {
            if (isset($this->params['adicionar']) && $this->params['adicionar']) {
                $this->loadTemplate('cardapio/edit_save_cardapio');
            } else if (isset($this->params['salvar']) && $this->params['salvar']) {
                $this->salvarCardapio();
            } else if (isset($this->params['editar']) && $this->params['editar']) {
                $this->editarCardapio();
            } else if (isset($this->params['remover']) && $this->params['remover']) {
                $this->removerCardapio();
            } else {
                $this->loadIndex();
            }
        } else {
            $this->redirect($this->UrlBuilder()->doAction('login'));
        }
    }

    function obterChecksMarcados() {
        $post = filter_input_array(INPUT_POST);
        $dados = array();
        foreach ($post as $chave => $valor) {
            if (substr($chave, 0, 5) == 'check') {
                array_push($dados, $valor);
            }
        }
        return $dados;
    }

    function salvarCardapio() {

        $cardapio = new Cardapio();
        $pratosId = $this->obterChecksMarcados();
        $descricao = $this->getPost('descricao');
        $erro = '';

        if (!isset($descricao) || $descricao == '') {
            $erro = "A descrição deve ser preenchida.";
        } else {
            $cardapio->setDescricao($descricao);
        }

        $sCardapio = serialize($cardapio);

        if (strlen($erro) > 0) {
            $this->redirect($this->UrlBuilder()->doAction('cardapio', array('cardapioS' => $sCardapio,
                        'adicionar' => true,
                        'errorMsg' => $erro)));
        }

        $cardapioDao = new CardapioDao($cardapio);

        if ($this->getPost('id')) {
            
            if ($cardapioDao->editar(array('id' => $this->getPost('id')), $pratosId)) {
                $this->removerCardapioPraro();
                $this->salvarCardapioPrato($this->getPost('id'), $pratosId);
                $this->redirect($this->UrlBuilder()->doAction('cardapio', array('successMsg' => 'Dados salvos com sucesso.')));
            } else {
                $this->redirect($this->UrlBuilder()->doAction('cardapio', array('errorMsg' => 'Problemas ao salvar os dados.')));
            }
        }

        if ($objCardapio = $cardapioDao->salvar(true)) {
            $idCardapio = $objCardapio->getId();
            $this->salvarCardapioPrato($idCardapio, $pratosId);
            
            $this->redirect($this->UrlBuilder()->doAction('cardapio', array('successMsg' => 'Dados salvos com sucesso.')));
        } else {
            $this->redirect($this->UrlBuilder()->doAction('cardapio', array('errorMsg' => 'Problemas ao salvar os dados.')));
        }
    }

    function salvarCardapioPrato($idCardapio, $pratosId) {
        
        $cardapioPrato = new CardapioPrato();
        $cardapioPratoDao = new CardapioPratoDao();

        foreach ($pratosId as $value) {
            
            $cardapioPrato->setCardapio_id($idCardapio);
            $cardapioPrato->setPrato_id($value);
            $cardapioPratoDao->setCardapioPrato($cardapioPrato);
            
            $cardapioPratoDao->salvar();
            
        }
    }
    
    function removerCardapioPraro($id = null) {
        
        if(strlen($id) <= 0){
            $id = $this->getPost('id');
        }
        
        $cardapioPratoDao = new CardapioPratoDao();
        
        $pratosDoCardapio = $cardapioPratoDao->obterCardapioPrato(array('cardapio_id' => $id));

        foreach ($pratosDoCardapio as $values){
            $cardapioPratoDao->apagar($values);
        }
    }
    
    function vinculaImageCardapio($imagem, $cardapio) {
        $erro = '';
        $nomeImagem = $this->uploadImage($imagem);

        if (strlen($nomeImagem) <= 0) {
            $erro = "Problemas ao enviar imagem.";
        } else {

            $imagem_cardapio_id = $this->salvarImagem($nomeImagem);
            $cardapio->setImagem_cardapio_id($imagem_cardapio_id);
        }

        return $erro;
    }

    public function editarCardapio() {
        $conds = array('id' => $this->params['id']);
        $cardapioDao = new CardapioDao();

        $cardapio = $cardapioDao->obterCardapios($conds, false);

        $scardapio = serialize($cardapio);
        $this->redirect($this->UrlBuilder()->doAction('cardapio', array('cardapioS' => $scardapio, 'adicionar' => TRUE)));
    }

    public function removerCardapio() {
        $conds = array('id' => $this->params['id']);
        $cardapioDao = new CardapioDao();
        $this->removerCardapioPraro($this->params['id']);
        if ($cardapioDao->apagar($conds)) {
            $this->redirect($this->UrlBuilder()->doAction('cardapio', array('successMsg' => 'Dados apagados com sucesso.')));
        } else {
            $this->redirect($this->UrlBuilder()->doAction('cardapio', array('errorMsg' => 'Problemas ao apagar cardapio.')));
        }
    }

    public function loadIndex() {
        $this->loadTemplate('cardapio/index');
    }

}
