<?php

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
/** @var $loader \Composer\Autoload\ClassLoader */
$loader->add('BEAR\Ace', array(__DIR__));
ini_set('error_log', sys_get_temp_dir() . 'bear.ace.log');
