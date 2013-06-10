<?php

// loader
require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

// register error editor
(new \BEAR\Ace\ErrorEditor)->registerSyntaxErrorEdit();
