<?php
/**
 * This file is part of the BEAR.Ace package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
use BEAR\Ace\Editor;

/**
 * Ace online editor
 * 
 * @param mixed $target file path or object
 * @param bool  $return
 *
 * @return string
 */
function edit($target, $return = false)
{
    if (ob_get_contents()) {
        ob_end_clean();
    }

    $editor = new Editor;
    if (is_object($target)) {
        $html = $editor->setObject($target);
    }
    if (is_string($target) && file_exists($target)) {
        $html = $editor->setPath($target);
    }
    if ($return) {
        return (string)$html;
    }
    echo $html;
    exit(0);
}
