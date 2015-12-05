<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BebidaAction
 *
 * @author Jonas
 */

namespace Delivery\Actions;

use \Delivery\Helpers\SessionHandler;
use Delivery\Dao\BebidaDao;

class AdmPedidoAction extends Action {

    public function run() {
        if (SessionHandler::checkSession('usuario')) {
            if (isset($this->params['verificar']) && $this->params['verificar']) {
                $this->loadTemplate('admpedido/verificar');
            } else {
                $this->loadIndex();
            }
        } else {
            $this->redirect($this->UrlBuilder()->doAction('login'));
        }
    }

    function salvarBebida() {

        if ($this->getPost('descricao')) {
            $descricao = $this->getPost('descricao');
          
        } else {
       
            $descricao = '';
        }
      
        if ($this->getPost('tipo_bebida_id')) {
            $tipo_bebida_id = $this->getPost('tipo_bebida_id');
        } else {
            $tipo_bebida_id = '';
        }

        $mBebida = new \Delivery\Model\Bebida();
        $mBebida->setDescricao($descricao);
        $mBebida->setTipo_bebida_id($tipo_bebida_id);
        $sBebida = serialize($mBebida);
        $bebida = new BebidaDao($mBebida);
        
        if ($descricao == '' || $tipo_bebida_id == '') {
            $this->redirect($this->UrlBuilder()->doAction('bebida', array('bebidaS' => $sBebida, 'adicionar' => true,
                        'errorMsg' => 'Preencha os dados requridos')));
        } else {

            if ($this->getPost('id')) {
                if ($bebida->editar(array('id' => $this->getPost('id')))) {
                    $this->redirect($this->UrlBuilder()->doAction('bebida', array('successMsg' => 'Dados salvos com sucesso.')));
                } else {
                    $this->redirect($this->UrlBuilder()->doAction('bebida', array('errorMsg' => 'Problemas ao salvar os dados.')));
                }
            }

            if ($bebida->salvar()) {
                $this->redirect($this->UrlBuilder()->doAction('bebida', array('successMsg' => 'Dados salvos com sucesso.')));
            } else {
                $this->redirect($this->UrlBuilder()->doAction('bebida', array('errorMsg' => 'Problemas ao salvar os dados.')));
            }
        }
    }

    public function editarBebida() {
        $conds = array('id' => $this->params['id']);
        $bebidaDao = new BebidaDao();

        $bebida = $bebidaDao->obterBebidas($conds, false);

        $sbebida = serialize($bebida);
        $this->redirect($this->UrlBuilder()->doAction('bebida', array('bebidaS' => $sbebida, 'adicionar' => TRUE)));
    }

    public function removerBebida() {
        $conds = array('id' => $this->params['id']);
        $bebidaDao = new BebidaDao();
        if ($bebidaDao->apagar($conds)) {
            $this->redirect($this->UrlBuilder()->doAction('bebida', array('successMsg' => 'Dados apagados com sucesso.')));
        } else {
            $this->redirect($this->UrlBuilder()->doAction('bebida', array('errorMsg' => 'Problemas ao apagar bebida.')));
        }
    }

    public function loadIndex() {
        $this->loadTemplate('admpedido/index');
    }

}
