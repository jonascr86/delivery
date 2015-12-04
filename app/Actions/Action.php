<?php
namespace Delivery\Actions;
use \Delivery\Helpers\SessionHandler;
use Delivery\Dao\PessoaDao as PessoaDao;
use Delivery\Model\Pessoa as Pessoa;

abstract class Action {
    protected $params;
    private $db;
    private $urlbuilder;
    private $gravatar;
    protected $template;
    protected $pessoaDao;

    public function __construct() {
        $this->db = \Delivery\Registry::get('appdb');
        $this->urlbuilder = \Delivery\Registry::get('approuter')->getUrlBuilder();
        $this->template = new \stdClass();
        $this->pessoaDao = new PessoaDao('pessoa', new Pessoa);
    }

    public function database() {
        return $this->db;
    }

    public function UrlBuilder() {
        return $this->urlbuilder;
    }

    public function setParams($params = null) {
        $this->params = $params;
    }

    public function redirect($url) {
        header("Location: $url");
        die();
    }

    public function getPost($key) {
        if (isset($_POST[$key])) {
            return filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
        }

        return null;
    }

    public function loadTemplate($template, $data = null) {
        $templatePath = TEMPLATES_DIR . '/' . $template . '.phtml';

        if ( file_exists($templatePath) ) {
            if ( $data && ! empty($data) ) {
                foreach($data as $key => $value){
                    $this->template->{$key} = $value;
                }
            }

            include($templatePath);

        }
    }

    public function loadHeader($menu = false, $carousel = false) {
        $this->loadTemplate('layout/header', array('menu' => $menu, 'carousel' => $carousel));
        
        if($menu){
            $this->loadMenu();
        }
    }
    
    public function loadMenu() {
        $this->loadTemplate('layout/menu', null);
    }

    public function loadHeaderIndex() {
        $this->loadTemplate('layout/index', null);
    }

    public function loadFooter() {
        $this->loadTemplate('layout/footer', null);
    }

    function getEstados($where = null) {
        if ($where) {
            $sql = "SELECT estado.id, estado.sigla FROM estado "
                    . "INNER JOIN cidade ON (estado.id = cidade.estado_id)"
                    . "WHERE cidade.id = :id ORDER BY sigla;";
            $estados = $this->database()->fetchRowMany($sql, array('id' => $where));
        } else {
            $sql = "SELECT id, sigla FROM estado "
                    . "ORDER BY sigla;";
            $estados = $this->database()->fetchRowMany($sql);
        }
        return $estados;
    }
    
    function getTipoBebida() {
        $sql = "SELECT tipo_bebida.id, tipo_bebida.descricao FROM tipo_bebida "
                . "ORDER BY descricao;";
        $tipos = $this->database()->fetchRowMany($sql);
        return $tipos;
    }
    
    function getTipoPrato() {
        $sql = "SELECT tipo_prato.id, tipo_prato.descricao FROM tipo_prato "
                . "ORDER BY descricao;";
        $tipos = $this->database()->fetchRowMany($sql);
        return $tipos;
    }
   
    function getStatusPrato() {
        $sql = "SELECT status_prato.id, status_prato.descricao FROM status_prato "
                . "ORDER BY descricao;";
        $status = $this->database()->fetchRowMany($sql);
        return $status;
    }
    
    function getTamanhoPrato() {
        $sql = "SELECT tamanho_prato.id, tamanho_prato.descricao FROM tamanho_prato "
                . "ORDER BY descricao;";
        $tamanho = $this->database()->fetchRowMany($sql);
        return $tamanho;
    }

    function getCidades($where) {
        if (!isset($where)) {
            return false;
        }
        $sql = "SELECT id, nome, estado_id FROM cidade "
                . "WHERE estado_id = :estado_id "
                . "ORDER BY nome;";
        $cidades = $this->database()->fetchRowMany($sql, array('estado_id' => $where));
        return $cidades;
    }

    function getCidade($where) {
        if (!isset($where)) {
            return false;
        }
        $sql = "SELECT id, nome, estado_id FROM cidade "
                . "WHERE id = :id "
                . "ORDER BY nome;";
        $cidade = $this->database()->fetchRowMany($sql, array('id' => $where));
        return $cidade;
    }

    function emailExist($where) {
        $colunas = array('email');
        $bWhere = array('email' => $where);
        $pessoaDao = new $this->pessoaDao('pessoa', new Pessoa());
        $resultado = $pessoaDao->listar($colunas, $bWhere);
        if ($resultado[0]['email']) {
            return true;
        }

        return false;
    }
    
    public abstract function run();
}
