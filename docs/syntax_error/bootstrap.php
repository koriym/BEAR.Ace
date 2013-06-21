<?php

// loader
require dirname(dirname(__DIR__)) . '/src.php';

// register error editor
(new \BEAR\Ace\ErrorEditor)->registerSyntaxErrorEdit();
