<?php
/**
 * This file is part of the BEAR.Ace package
 *
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
            $type = $message = $file = $line = $trace = '';
            $error = error_get_last();
            if (!$error) {
                return;
            }
            extract($error);
            // redirect
            if ($type !== E_PARSE) {
                return;
            }
            ob_end_clean();
            $rootPath = '/';
            $message .= " on line {$line} in";
            $selfUrl = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]. ':' . $_SERVER['REMOTE_PORT'];
            $editor = new Editor;
            echo $editor
                ->setRootPath($rootPath)
                ->setPath($file)
                ->setLine($line)
                ->setMessage($message)
                ->setSaveUrl($selfUrl)
                ->enableReloadAfterSave();
            exit(0);
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
            try {
                $post = $post ?: $_POST;
                /** @noinspection PhpExpressionResultUnusedInspection */
                $editor = new Editor;
                (string)$editor
                    ->setRootPath('/')
                    ->setPath($post['file'])
                    ->save($post['contents']);
                exit(0);
            } catch (Exception $e) {
            }
        }

        // register syntax error editor
        register_shutdown_function($this->handler);
        return '';
    }
}
