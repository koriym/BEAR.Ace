<?php

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';
require __DIR__ . '/FooClass.php';

$foo = new FooClass;
edit($foo);
