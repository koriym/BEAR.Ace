<?php

use BEAR\Ace\Editor;

require dirname(dirname(__DIR__)) . '/src.php';

$file = __DIR__ . '/FooClass.php';

$editor = new Editor;
echo $editor->setPath($file);
