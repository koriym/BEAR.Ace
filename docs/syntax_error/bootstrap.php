<?php

// loader
require dirname(dirname(__DIR__)) . '/src.php';

use BEAR\Ace\ErrorEditor;

// register error editor

$editor = new ErrorEditor;
$editor->registerSyntaxErrorEdit();
