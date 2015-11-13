<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TesteAction
 *
 * @author Jonas C. Rosa
 */

namespace Delivery\Actions;

class ComboAction extends Action {

//put your code here
    public function run() {
        if (isset($this->params['estado'])) {
            $this->montarComboCidade($this->params['estado']);
        } else if (isset($this->params['cidade'])) {
            $this->montarComboBairro($this->params['cidade']);
        } elseif (isset($this->params['tipoBebida'])) {
            $this->montarComboTipoBebida();
        }elseif (isset($this->params['statusPrato'])) {
            $this->montarComboStatusPrato();
        }elseif (isset($this->params['tipoPrato'])) {
            $this->montarComboTipoPrato();
        }elseif (isset($this->params['tamanhoPrato'])) {
            $this->montarComboTamanhoPrato();
        }
        
    }

    public function montarComboCidade($estadoId) {
        $cidades = $this->getCidades($estadoId);

        echo "<option value = '0'>Selecione</option>";
        foreach ($cidades as $cidade) {
            echo '<option value= ' . "{$cidade['id']}" . '>' . "{$cidade['nome']}" . '</option>';
        }
    }

    public function montarComboBairro($cidadeId) {
        $bairros = $this->getBairros($cidadeId);

        echo "<option value = '0'>Selecione</option>";
        if (isset($bairros) && $bairros) {
            foreach ($bairros as $bairro) {
                echo '<option value= ' . "{$bairro['id']}" . '>' . "{$bairro['nome']}" . '</option>';
            }
        }
    }

    public function montarComboTipoBebida() {
        $tipo_bebida = $this->getTipoBebida();
        echo "<option value = '0'>Selecione</option>";

        foreach ($tipo_bebida as $tipo) {
            echo '<option value= ' . "{$tipo['id']}" . '>' . "{$tipo['descricao']}" . '</option>';
        }
    }
    
    public function montarComboStatusPrato() {
        $status_prato = $this->getStatusPrato();
        echo "<option value = '0'>Selecione</option>";

        foreach ($status_prato as $status) {
            echo '<option value= ' . "{$status['id']}" . '>' . "{$status['descricao']}" . '</option>';
        }
    }
    
    function montarComboTipoPrato() {
         $tipo_prato = $this->getTipoPrato();
        echo "<option value = '0'>Selecione</option>";

        foreach ($tipo_prato as $tipo) {
            echo '<option value= ' . "{$tipo['id']}" . '>' . "{$tipo['descricao']}" . '</option>';
        }
    }
    
    function montarComboTamanhoPrato() {
        $tamanho_prato = $this->getTamanhoPrato();
        echo "<option value = '0'>Selecione</option>";

        foreach ($tamanho_prato as $tamanho) {
            echo '<option value= ' . "{$tamanho['id']}" . '>' . "{$tamanho['descricao']}" . '</option>';
        }
    }

}
