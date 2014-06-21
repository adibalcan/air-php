<?php
//Database settings
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_DATABASE', '');
define('DB_HOST', 'localhost');

//Base location after domain
define('BASE', '');

error_reporting(E_ALL);
//error_reporting(E_ALL ^ E_NOTICE);

$routes = array();
//URL Must contain a regex with delimiters
//Action it's called function from controller

//$routes[] = array('url' => '', 'controller' => '', 'action' => '');


//DEFAULT CONTROLLER
$default = array('controller' => 'start', 'action' => 'test');

