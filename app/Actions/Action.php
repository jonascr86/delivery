<?php
namespace Delivery\Actions;
use \Delivery\Helpers\SessionHandler;

abstract class Action {
    protected $params;
    private $db;
    private $urlbuilder;
    private $gravatar;
    protected $template;

    public function __construct() {
        $this->db = \Delivery\Registry::get('appdb');
        $this->urlbuilder = \Delivery\Registry::get('approuter')->getUrlBuilder();
        $this->template = new \stdClass();
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

    public function loadHeader() {
        $this->loadTemplate('layout/header', null);
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

    function getBairros($where) {
        if (!isset($where)) {
            return false;
        }
        $sql = "SELECT id, nome FROM bairro "
                . "WHERE cidade_id = :cidade_id "
                . "ORDER BY nome;";
        $bairros = $this->database()->fetchRowMany($sql, array('cidade_id' => $where));
        return $bairros;
    }

    function getBairro($where) {
        if (!isset($where)) {
            return false;
        }
        $sql = "SELECT id, nome FROM bairro "
                . "WHERE id = :id "
                . "ORDER BY nome;";
        $bairro = $this->database()->fetchRowMany($sql, array('id' => $where));
        return $bairro;
    }
    public abstract function run();
}
