<?php

require dirname(dirname(__DIR__)) . '/src.php';

$file = __DIR__ . '/FooClass.php';

echo (new \BEAR\Ace\Editor)->setPath($file);
