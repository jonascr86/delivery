<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Delivery\Actions;

use Delivery\Helpers\SessionHandler as SessionHandler;
use Delivery\Dao\PratoDao as PratoDao;
use Delivery\Dao\PedidoDao as PedidoDao;
use Delivery\Dao\ItensDoPedidoDao as ItensDoPedidoDao;
use Delivery\Model\ItensDoPedido as ItensDoPedido;
use Delivery\Model\Pedido as Pedido;
use Delivery\Dao\ClienteDao as ClienteDao;

/**
 * Description of PedidoAction
 *
 * @author jonascr86
 */
class PedidoAction extends Action {

    public $dadosLogin;

    public function run() {
        if (SessionHandler::checkSession('cliente')) {
            $this->dadosLogin = $this->obterDadosDoLogin();
            if (isset($this->params['adicionar']) && $this->params['adicionar']) {
                $this->adicionarAoCarrinho();
            } elseif (isset($this->params['remover']) && $this->params['remover']) {
                $this->removerDoCarrinho();
            } elseif (isset($this->params['fazerPedido']) && $this->params['fazerPedido']) {
                $this->fazerPedido();
            } elseif (isset($this->params['confirmarPedido']) && $this->params['confirmarPedido']) {
                $this->confirmarPedido();
            } elseif (isset($this->params['Ok']) && $this->params['Ok']) {
                $this->loadTemplate(ROOT_URL);
            }
            $this->loadTemplate('pedido/index');
        }  else {
            $this->loadTemplate('cliente/login');
        }
        
    }
    
    public function confirmarPedido(){
        $clienteDao = new ClienteDao();
        $pedidosSessao = $this->obterPedidoDaSessao();
        $valorTotal = $this->obterValorTotal($pedidosSessao);
        
        $pedido = new Pedido();
        $dadosDoLogin = $this->obterDadosDoLogin();
        $where = array('email' => $dadosDoLogin->email);
        $cliente = $clienteDao->obterCliente($where);
        $pedido->setCliente_id($cliente->getId());
        $pedido->setData(date('Y-m-d H:m:s'));
        $pedido->setStatus_pedido_id(1);
        $pedido->setValor($valorTotal);
        $pedidoDao = new PedidoDao($pedido);
        $novoPedido = $pedidoDao->salvar();
        
        if(!is_null($novoPedido)){
            $this->salvarItensDoPedido($novoPedido, $pedidosSessao);
        }
        
    }
    
    public function salvarItensDoPedido($pedido, $pedidoSessao) {
        
        $itensDoPedido = new ItensDoPedido();
        $itensDoPedidoDao = new ItensDoPedidoDao();
        $pedidoId = $pedido->getId();
        
        if($pedidoId > 0 && !is_null($pedidoId)){
            $retorno = true;
            
            foreach ($pedidoSessao as $chave => $prato){
                $itensDoPedido->setPedido_id($pedidoId);
                $itensDoPedido->setPrato_id($prato[0]);
                $itensDoPedidoDao->setItensDoPedido($itensDoPedido);
                $retorno = $itensDoPedidoDao->salvar();
                
                if(!$retorno){
                    break;
                }
            }
            
            if($retorno){
                $this->pedidoEfetuadoComSucesso();
            }else{
                $this->problemasAoEfetuarPedido();
            }
            die();
        }
        
       
        
    }

    public function adicionarAoCarrinho() {

        $dadosDoPedido = $this->obterDadosDoPedido();

        $this->gardarPedidonaSessao($dadosDoPedido);

        $this->montarCamposPedido();
    }

    public function removerDoCarrinho() {

        $this->removerDadosDoPedido();

        $dadosDoPedido = $this->obterPedidoDaSessao();

        $this->montarCamposPedido();
    }

    public function obterDadosDoPedido() {
        $pratoId = $this->params['prato_id'];
        $dadosDoPedido = array();

        if (strlen($pratoId) > 0) {
            $pratoDao = new PratoDao();
            $prato = $pratoDao->obterPratos(array('id' => $pratoId), false);

            array_push($dadosDoPedido, $prato->getId());
            array_push($dadosDoPedido, $prato->getNome());
            array_push($dadosDoPedido, $prato->getPreco());
        }

        return $dadosDoPedido;
    }

    public function removerDadosDoPedido() {
        $pratoId = $this->params['prato_id'];
        $dadosDoPedido = array();

        if (strlen($pratoId) > 0) {
            $dadosDoPedido = SessionHandler::removerPedidoSession($pratoId);
        }

        return $dadosDoPedido;
    }

    public function obterDadosDoLogin() {

        $dados = new \stdClass();
        $login = SessionHandler::selectSession('cliente');
        $dados->email = $login['email'];
        $dados->senha = $login['senha'];
        return $dados;
    }

    public function gardarPedidonaSessao($dadosDoPedido) {
        SessionHandler::addPedidoSession($dadosDoPedido);
    }

    public function obterPedidoDaSessao() {
        $pedido = SessionHandler::obterPedidos();
        return $pedido;
    }

    public function obterValorTotal($pedidos) {
        $valorTotal = 0;

        foreach ($pedidos as $id => $prato) {
            $valorTotal += $prato[2];
        }
        return $valorTotal;
    }

    public function prepararItens($pedidos) {
        $descricao = array();
        $htmlArray = array();
        foreach ($pedidos as $id => $prato) {
            if (!in_array($prato[1], $descricao)) {
                $input = "<input type=\"text\"  required class=\"form-control\" id=\"{$prato[1]}_id\" value=\"{$prato[1]} - {$prato[2]}\" name=\"{$prato[1]}_id\"  placeholder=\"\" />";
                array_push($htmlArray, $input);
//                array_push($descricao, $prato[1]);
            }
        }
        return $htmlArray;
    }

    public function obtemItens($htmlArray) {
        $htmlString = '';

        if (count($htmlArray) <= 0) {
            $htmlString = "<input type=\"text\"  required class=\"form-control\" id=\"\" value=\"\" name=\"\"  placeholder=\"\" /> </br>";
        } else {
            foreach ($htmlArray as $html) {
                $htmlString .= $html;
            }
        }

        return $htmlString;
    }

    public function montarCamposPedido() {
        $pedidos = $this->obterPedidoDaSessao();
        $urlFazerPedido = $this->UrlBuilder()->doAction('pedido', array('fazerPedido' => TRUE));
        $htmlArray = $this->prepararItens($pedidos);

        $camposPedido = "
        <div class=\"list-group\">
            <div class=\"form-group\">
              <a class=\"list-group-item\">Informações do pedido</a>
              <a class=\"list-group-item active\">Valor</a>
              <input type=\"text\"  required class=\"form-control\" id=\"usuario\" name=\"usuario\"  value=\" {$this->obterValorTotal($pedidos)}\" placeholder=\"R$ \" />
            </div>
            <div class=\"form-group\">
              <a href=\"#\" class=\"list-group-item active\">Itens</a>
              {$this->obtemItens($htmlArray)}
            </div>
            <div class=\"btn-group btn-group-justified\" role=\"group\" aria-label=\"...\">
                <div class=\"btn-group\" role=\"group\">
                    <button type=\"button\" data-toggle=\"modal\" data-target=\"#modalConfirmarPedido\" id=\"confirmarPedido\"
                    onclick=\"fazerPedido('http://localhost/delivery/public_html/?action=pedido&fazerPedido=1')\" class=\"btn btn-success\"><strong> FAZER PEDIDO </strong></button>
                </div>
              </div>
        </div>
      </div><!--/.sidebar-offcanvas-->";
        echo $camposPedido;
        die();
    }

    public function fazerPedido() {
        $pedidos = $this->obterPedidoDaSessao();

        $tabela = "<table id=\"tabelaPratos\" class=\"table\">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>Preço</th>
                        </tr>
                    </thead>
                        <tbody>";

        foreach ($pedidos as $prato) {

            $tabela .= "<tr>
                            <th>{$prato[0]}</th>
                            <th>{$prato[1]}</th>
                            <th>{$prato[2]}</th>
                        </tr>";
        }

        $tabelafooter = "   
            </tbody>
        </table>
        <div class=\"modal-footer\">
            <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\" id=\"fecharModalConfirmacao\" >Fechar</button>
            <button type=\"button\" class=\"btn btn-primary\" onclick=\"confirmarPedido('http://localhost/delivery/public_html/?action=pedido&confirmarPedido=1')\" id=\"confirmarPedido\"><strong>CONFIRMAR PEDIDO</strong></button>
        </div>";

        echo $tabela . $tabelafooter;
        die();
    }
    
    public function pedidoEfetuadoComSucesso() {
        SessionHandler::romoverTodosOsPedidosSession();
        $sucesso = "
        <div class=\"alert alert-success\" role=\"alert\">Pedido Efetuado com Sucesso!</div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\" id=\"fecharModalConfirmacao\" >Fazer novo pedido</button>
                <button type=\"button\" class=\"btn btn-primary\" data-dismiss=\"modal\" onclick=\"confirmarPedido('http://localhost/delivery/public_html/?action=pedido&Ok=1')\" id=\"confirmarPedido\"><strong>OBRIGADO!</strong></button>
                </div>";
        echo $sucesso;
        die();
    }
    
    public function problemasAoEfetuarPedido() {
        $fracasso = "
        <div class=\"alert alert-danger\" role=\"alert\">Problemas ao efetuar pedido!</div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\" id=\"fecharModalConfirmacao\" >Tentar novamente</button>
                <button type=\"button\" class=\"btn btn-primary\" onclick=\"confirmarPedido('<?= $urlConfirmarPedido ?>')\" id=\"confirmarPedido\"><strong>CONFIRMAR PEDIDO</strong></button>
                </div>";
        echo $fracasso;
        die();
    }

//put your code here
}
