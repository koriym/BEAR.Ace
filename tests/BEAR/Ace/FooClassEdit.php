<?php

require dirname(dirname(__DIR__)) . '/bootstrap.php';
require __DIR__ . '/FooClass.php';

$foo = new FooClass;
$editor = new \BEAR\Ace\Editor;
echo $editor->setObject($foo);
