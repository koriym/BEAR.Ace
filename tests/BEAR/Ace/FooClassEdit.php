<?php

require dirname(dirname(__DIR__)) . '/bootstrap.php';
require __DIR__ . '/FooClass.php';

$foo = new FooClass;
echo (new \BEAR\Ace\Editor)->setObject($foo);
