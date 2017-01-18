<?php

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
        'dsn'   => 'mysql:host=localhost;dbname=snack_main',
        'hostname' => 'localhost',
        'username' => 'snack01_main',
        'password' => 'hfKwGegkWg',
        'database' => 'snack01_main',
        'dbdriver' => 'mysqli',
        'dbprefix' => '',
        'pconnect' => FALSE,
        'db_debug' => (ENVIRONMENT !== 'production'),
        'cache_on' => FALSE,
        'cachedir' => '',
        'char_set' => 'utf8',
        'dbcollat' => 'utf8_general_ci',
        'swap_pre' => '',
        'encrypt' => FALSE,
        'compress' => FALSE,
        'stricton' => FALSE,
        'failover' => array(),
        'save_queries' => TRUE
);

?>
