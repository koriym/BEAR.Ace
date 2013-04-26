<?php
/**
 * This file is part of the BEAR.Ace package
 *
 * @package BEAR.Ace
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Ace;

/**
 * Syntax error instance edit
 *
 * @author Akihito Koriyama <akihito.koriyama@gmail.com>
 */
class ErrorEditor
{
    /**
     * @var callable
     */
    private $handler;

    /**
     * @param callable $handler
     */
    public function __construct(callable $handler = null)
    {
        $this->handler = $handler ? : function () {
            if (PHP_SAPI === 'cli') {
                return;
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['save']) {

            }
            $type = $message = $file = $line = $trace = '';
            $error = error_get_last();
            if (!$error) {
                return;
            }
            extract($error);
            // redirect
            if ($type == E_PARSE) {
                ob_end_clean();
                $rootPath = '/';
                $message .= " on line {$line} in";
                echo (new Editor)
                    ->setRootPath($rootPath)
                    ->setPath($file)
                    ->setLine($line)
                    ->setMessage($message)
                    ->setSaveUrl('save.php')
                    ->enableReloadAfterSave();
                exit(0);
            }
            // Logic error only
            if (!in_array($type, [E_COMPILE_ERROR, E_CORE_ERROR, E_ERROR])) {
                return;
            }
            error_log(ob_get_clean());
            http_response_code(500);
            $html = require __DIR__ . '/ErrorEditor/view.php';
            echo $html;
            exit(1);
        };
    }

    /**
     * @param array $server
     * @param array $post
     *
     * @return string
     */
    public function registerSyntaxErrorEdit(array $server = null, array $post = null)
    {
        $server = $server ?: $_SERVER;
        $isBearAceSave = isset($server['HTTP_X_REQUESTED_WITH'])
            && strtolower($server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            && $server['REQUEST_METHOD'] === 'POST'
            && isset($server['HTTP_X_BEAR_ACE']);

        if ($isBearAceSave) {
            $post = $post ?: $_POST;
            (string)(new Editor)
                ->setRootPath('/')
                ->setPath($post['file'])
                ->save($post['contents']);
            exit(0);
        }

        // register syntax error editor
        register_shutdown_function($this->handler);
        return '';
    }
}
