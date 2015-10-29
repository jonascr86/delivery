<?php
namespace Delivery\Router;


use Delivery\Helpers\URL\URLBuilder;

class GETRouter extends Router{
    private $getvalue;
    private $getvar;

    public function __construct($getvar, URLBuilder $URLBuilder ) {
        parent::__construct($URLBuilder);

        $this->getvar = $getvar;
        $this->getvalue = filter_input(INPUT_GET, $getvar);
    }

    public function getGetVar() {
        return $this->getvar;
    }

    public function checkRoute($action, $route)
    {
        return in_array($action, $route->getActionsKey());
    }

    public function fetchParams()
    {
        if ( ! empty($_GET) ) {
            $params = $_GET;

            if ( isset($params[$this->getvar]) ) {
                unset($params[$this->getvar]);
            }

            foreach($params as $key => &$param) {
//                var_dump(substr($key, -1));
                if( strcmp(substr($key, -1), 'S') != 0 || $key === "errorMsg"){
                    $param = filter_var($param, FILTER_SANITIZE_STRING);
                }
            }
            return $params;
        }

        return array();
    }

    public function getAction()
    {
        return $this->getvalue;
    }
}