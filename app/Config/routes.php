<?php
use \Delivery\Router\Route;

$approuter = \Delivery\Registry::get('approuter');

$approuter->addRoute(
    new Route(
        array(
            'match' => array('index', ''),
            'action' => 'index'
        )
    )
);

$approuter->addRoute(
    new Route(
        array(
            'match' => array('admin'),
            'action' => 'admin'
        )
    )
);

$approuter->addRoute(
    new Route(
        array(
            'match' => array('login'),
            'action' =>  'login'
        )
    )
);

$approuter->addRoute(
  new Route(
      array(
          'match' => array('message'),
          'action' => 'message'
      )
  )
);

$approuter->addRoute(
    new Route(
        array(
            'match' => array('pessoa'),
            'action' => 'pessoa'
        )
    )
);

$approuter->addRoute(
    new Route(
        array(
            'match' => array('combo'),
            'action' => 'combo'
        )
    )
);

$approuter->addRoute(
    new Route(
        array(
            'match' => array('bebida'),
            'action' => 'bebida'
        )
    )
);

$approuter->addRoute(
    new Route(
        array(
            'match' => array('tipoBebida'),
            'action' => 'tipoBebida'
        )
    )
);