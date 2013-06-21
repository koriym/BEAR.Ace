<?php
/**
 * This file is part of the BEAR.Ace package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Ace;

/**
 * Ace (Ajax.org Cloud9 Editor)
 *
 * @author Akihito Koriyama <akihito.koriyama@gmail.com>
 * @see http://ace.ajax.org/
 */
class Editor
{
    /**
     * @var string editable root path
     */
    protected $rootPath;

    /**
     * @var string relative path
     */
    protected $path;

    /**
     * @var string full file path
     */
    protected $file;

    /**
     * @var int line number
     */
    protected $line;

    /**
     * @var string message
     */
    protected $message;

    /**
     * @var string save url
     */
    protected $saveUrl = 'index.php';

    protected $enableReloadAfterSave = false;

    /**
     * @param $message
     *
     * @return Editor
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Enable instant reload after save
     *
     * @param bool $enableReloadAfterSave
     *
     * @return $this
     */
    public function enableReloadAfterSave($enableReloadAfterSave = true)
    {
        $this->enableReloadAfterSave = $enableReloadAfterSave;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $fullPath = $this->file;
        $line = $this->line;
        $relativePath = $this->path;
        // set variable for view
        $view = array();
        $view['file'] = $fullPath;
        $view['file_path'] = $relativePath;
        $view['line'] = $line;
        $view['file_contents'] = htmlspecialchars(file_get_contents($fullPath));
        $id = md5($fullPath);
        $view['mod_date'] = date(DATE_RFC822, filemtime($fullPath));
        $view['is_writable'] = is_writable($fullPath);
        $view['is_writable_label'] = $view['is_writable'] ? "" : " Read Only";
        $view['auth'] = md5(session_id() . $id);
        $view['error'] = (isset($_GET['error'])) ? ($_GET['error']) : '';
        $view['enable_reload_after_save'] = $this->enableReloadAfterSave ? 'true' : 'false';
        // get html view
        $view = $this->getView($view);

        return $view;
    }

    /**
     * @param array $view
     *
     * @return string
     */
    private function getView(array $view)
    {
        $view['is_read_only'] = $view['is_writable'] ? 0 : 1;
        $view['is_writable_label'] = $view['is_writable'] ? 'reset' : 'read only';
        $view['line'] = $view['line'] ? "({$view['line']})" : 0;
        $view['error'] = $view['error'] ? '' : '';
        $view['save_url'] = $this->saveUrl;
        $view['message'] = $this->message ? "<span class=\"error\">{$this->message}</span>" : '';

        return require __DIR__ . '/Editor/view.php';
    }

    /**
     * @param array $get
     * @param array $post
     * @param array $server
     *
     * @return string
     * @throws Exception
     */
    public function handle(array $get, array $post, array $server)
    {
        // 400 ?
        if (!isset($post['file']) && !isset($get['file'])) {
            throw new Exception("Bad request: no 'file' requested", 400);
        }

        // relative path
        $file = ($server['REQUEST_METHOD'] === 'POST') ? $post['file'] : $get['file'];

        // 404 ?
        $fileFullPath = $this->rootPath . '/' . $file;
        if (!file_exists($fileFullPath)) {
            // absolute path ?
            if (file_exists($file) && strpos($file, $this->rootPath) === 0) {
                $fileFullPath = $file;
                $rootPath = '/';
            } else {
                error_log("[BEAR.Ace] 404: {$fileFullPath}");
                throw new Exception('Not found ' . $fileFullPath, 404);
            }
        }
        $this->setPath($file);

        // load ?
        if ($server['REQUEST_METHOD'] !== 'POST') {
            $line = isset($get['line']) ? $get['line'] : 0;

            return (string)($this->setLine($line));
        }
        error_log($server['REQUEST_METHOD']);
        // or save
        return (string)($this->setSaveUrl('index.php')->save($post['contents']));
    }

    /**
     * @param $path
     *
     * @return $this
     * @throws Exception
     */
    public function setPath($path)
    {
        $this->path = $path;
        $fileFullPath = "{$this->rootPath}/{$this->path}";
        // readable ?
        if (file_exists($fileFullPath)) {
            $this->file = $fileFullPath;
            return $this;
        }
        // absolute path ?
        if (file_exists($path) && strpos($path, $this->rootPath) === 0) {
            $this->file = $path;
            $this->rootPath = '/';
        } else {
            error_log("[BEAR.Ace] 404: {$fileFullPath}");
            throw new Exception('Not found ' . $fileFullPath, 404);
        }

        return $this;
    }

    /**
     * @param $rootPath
     *
     * @return Editor
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = $rootPath;

        return $this;
    }

    /**
     * @param $line
     *
     * @return Editor
     */
    public function setLine($line)
    {
        $this->line = $line;

        return $this;
    }

    /**
     * Save contents
     *
     * @param $contents
     *
     * @return string
     */
    public function save($contents)
    {
        $result = (string)file_put_contents($this->file, $contents, LOCK_EX | FILE_TEXT);
        $log = "[BEAR.Ace] saved:{$this->file} result:{$result}";

        return $log;
    }

    /**
     * @param $saveUrl
     *
     * @return Editor
     */
    public function setSaveUrl($saveUrl)
    {
        $this->saveUrl = $saveUrl;

        return $this;
    }

    /**
     * @param      $object
     * @param null $method
     *
     * @return $this
     * @throws Exception
     */
    public function setObject($object, $method  = null)
    {
        if (! is_object($object)) {
            throw new Exception('not object');
        }
        $ref = new \ReflectionObject($object);
        $file = $ref->getFileName();
        $this->setPath($file);

        return $this;
    }
}
