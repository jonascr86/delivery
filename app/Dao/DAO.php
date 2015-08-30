<?php
namespace Delivery\Dao;
use Delivery\Registry;

abstract class DAO {

    protected $database;
    
    function __construct() {
        $this->database = Registry::get('appdb');
    }
    
    function database() {
        return $this->database;
    }
    
    abstract function salvar(); 
    abstract function editar(); 
    abstract function listar(); 
    abstract function apagar(); 
}
