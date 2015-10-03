<?php

return [
     'host' => 'localhost',
     'user' => 'root',
     'password' => 'mysql',
     'database' => 'delivery',

     'fetchMode' => \PDO::FETCH_ASSOC,
     'charset'   => 'utf8',
     'options'   => ['port' => 3306, 'unixSocket' => null]
];
