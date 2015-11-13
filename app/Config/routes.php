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

$approuter->addRoute(
    new Route(
        array(
            'match' => array('ingredientes'),
            'action' => 'ingredientes'
        )
    )
);

$approuter->addRoute(
    new Route(
        array(
            'match' => array('temperos'),
            'action' => 'temperos'
        )
    )
);

$approuter->addRoute(
    new Route(
        array(
            'match' => array('funcionario'),
            'action' => 'funcionario'
        )
    )
);

$approuter->addRoute(
    new Route(
        array(
            'match' => array('cliente'),
            'action' => 'cliente'
        )
    )
);

$approuter->addRoute(
    new Route(
        array(
            'match' => array('prato'),
            'action' => 'prato'
        )
    )
);

$approuter->addRoute(
    new Route(
        array(
            'match' => array('tipoPrato'),
            'action' => 'tipoPrato'
        )
    )
);

$approuter->addRoute(
    new Route(
        array(
            'match' => array('tamanhoPrato'),
            'action' => 'tamanhoPrato'
        )
    )
);

$approuter->addRoute(
    new Route(
        array(
            'match' => array('statusPrato'),
            'action' => 'statusPrato'
        )
    )
);
