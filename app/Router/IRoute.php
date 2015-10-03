<?php
namespace Delivery\Router;

interface IRoute {
    public function setRoute( Array $params );
    public function getActionClass();
}