<?php
namespace Delivery\Helpers\URL;
use Delivery\Router\Router;

abstract class URLBuilder {
    protected  $router;

    public function __construct() {
        $this->router = null;
    }

    public function setRouter(Router $router) {
        $this->router = $router;
    }

    abstract public function doAction($action, Array $params = array());
}