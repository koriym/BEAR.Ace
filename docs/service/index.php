<?php

require dirname(dirname(__DIR__)) . '/src.php';

use BEAR\Ace\Editor;
use BEAR\Ace\Exception;

try {
    $editor = new Editor;
    $html = (string)$editor->setRootPath('/')->handle($_GET, $_POST, $_SERVER);
    echo $html;
} catch (Exception $e) {
    http_response_code($e->getCode());
    echo $e->getCode();
}
