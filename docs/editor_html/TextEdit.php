<?php

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';
$file = __DIR__ . '/FooClass.php';

echo (new \BEAR\Ace\Editor)->setPath($file);
