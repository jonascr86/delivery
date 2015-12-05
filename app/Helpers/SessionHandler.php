<?php
namespace Delivery\Helpers;

class SessionHandler {

    
    public static function createSession( $name, $value ) {

        $_SESSION[$name] = $value;
        $_SESSION['pedido'] = array();

    }

    public static function addSessionVar( $topName, $name, $value ) {
        $_SESSION[$topName][$name] = $value;
    }

    public static function selectSession( $name ) {
        if ( self::checkSession($name) ) {
            return $_SESSION[$name];
        }

        return false;
    }

    public static function checkSession( $name ) {
        return isset( $_SESSION[$name] );
    }

    public static function deleteSession( $name ) 
    {
        unset( $_SESSION[$name] );
        unset( $_SESSION['pedido'] );
    }
    
    public static function addPedidoSession($dadosDoPedido){
        $pedido = &$_SESSION['pedido'];
        array_push($pedido, $dadosDoPedido);
    }

    public static function removerPedidoSession($pratoId){
        foreach ($_SESSION['pedido'] as $key => $dados) {
            if ($dados[0] == $pratoId) {
                unset($_SESSION['pedido'][$key]);
                break;
            }
        }
    }
    
    public static function romoverTodosOsPedidosSession(){
        unset($_SESSION['pedido']);
        $_SESSION['pedido'] = array();
    }

    public static function obterPedidos(){
        $pedido = &$_SESSION['pedido'];
        return $pedido;
    }
}