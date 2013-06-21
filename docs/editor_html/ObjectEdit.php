<?php

require dirname(dirname(__DIR__)) . '/src.php';
require __DIR__ . '/FooClass.php';

$foo = new FooClass;
edit($foo);
