<?php
use \Delivery\Router\Route;

$approuter = \Delivery\Registry::get('approuter');

$approuter->addRoute(
    new Route(
        [
            'match' => ['index', ''],
            'action' => 'index'
        ]
    )
);

$approuter->addRoute(
    new Route(
        [
            'match' => ['admin'],
            'action' => 'admin'
        ]
    )
);

$approuter->addRoute(
    new Route(
        [
            'match' => ['login'],
            'action' =>  'login'
        ]
    )
);

$approuter->addRoute(
  new Route(
      [
          'match' => ['message'],
          'action' => 'message'
      ]
  )
);

$approuter->addRoute(
    new Route(
        [
            'match' => ['pessoa'],
            'action' => 'pessoa'
        ]
    )
);