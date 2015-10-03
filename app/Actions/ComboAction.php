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
        }  else {
            if(isset($this->params['cidade'])){
                $this->montarComboBairro($this->params['cidade']);
            }
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

}
