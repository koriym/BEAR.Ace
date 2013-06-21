<?php

namespace BEAR\Ace;

class EditorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Editor
     */
    protected $editor;

    protected function setUp()
    {
        $this->editor = new Editor;
    }

    public function testNew()
    {
        $actual = $this->editor;
        $this->assertInstanceOf('\BEAR\Ace\Editor', $actual);
    }

    public function testValidPath()
    {
        $html = (string)$this->editor->setRootPath(__DIR__)->setPath('Mock/bar.txt');
        $this->assertContains('<pre id="editor">{this is bar}</pre>', $html);
    }

    /**
     * @expectedException \BEAR\Ace\Exception
     */
    public function testInvalidPath()
    {
        (string)$this->editor->setRootPath('/')->setPath('not_exists.php');
    }

    public function testAbsolutePath()
    {
        $html = (string)$this->editor->setRootPath(__DIR__ . '/Mock')->setPath(__DIR__ . '/Mock/bar.txt');
        $this->assertContains('<pre id="editor">{this is bar}</pre>', $html);
    }

    /**
     * @expectedException \BEAR\Ace\Exception
     *
     * path use absolute path, which has no problem at all,
     * but it is not under root path.
     */
    public function testInvalidAbsolutePath()
    {
        (string)$this->editor->setRootPath('/Invalid')->setPath(__DIR__ . '/Mock/bar.txt');
    }

    public function testSave()
    {
        $file = __DIR__ . '/Mock/tmp.txt';
        copy(__DIR__ . '/Mock/bar.txt', $file);
        $result = (string)$this->editor->setRootPath(__DIR__)->setPath('Mock/tmp.txt')->save('new_data');
        $expected = "[BEAR.Ace] saved:{$file} result:8";
        $this->assertSame($expected, $result);

        return file_get_contents($file);
    }

    /**
     * @depends testSave
     */
    public function testSavedContents($contents)
    {
        $this->assertSame('new_data', $contents);
    }

    public function testHandleLoad()
    {
        $get = array(
            'file' => 'Mock/bar.txt',
            'line' => 1
        );
        $post = array();
        $server = array('REQUEST_METHOD' => 'GET');
        $html = (string)$this->editor->setRootPath(__DIR__)->handle($get, $post, $server);
        $this->assertContains('<pre id="editor">{this is bar}</pre>', $html);
    }

    public function testHandleLoadWithAbsolutePath()
    {
        $get = array(
            'file' => __DIR__ . '/Mock/bar.txt',
            'line' => 1
        );
        $post = array();
        $server = array('REQUEST_METHOD' => 'GET');
        $html = (string)$this->editor->setRootPath(__DIR__)->handle($get, $post, $server);
        $this->assertContains('<pre id="editor">{this is bar}</pre>', $html);
    }

    /**
     * @expectedException \BEAR\Ace\Exception
     */
    public function testHandleLoadWithAbsolutePathNotExists()
    {
        $get = array(
            'file' => __DIR__ . '/Mock/not_exists.txt'
        );
        $post = array();
        $server = array('REQUEST_METHOD' => 'GET');
        (string)$this->editor->setRootPath(__DIR__)->handle($get, $post, $server);
    }

    public function testHandleSave()
    {
        $get = array();
        $post = array(
            'file' => 'Mock/tmp.txt',
            'contents' => 'new save contents'
        );
        $server = array('REQUEST_METHOD' => 'POST');
        $result = (string)$this->editor->setRootPath(__DIR__)->handle($get, $post, $server);
        $saved = file_get_contents(__DIR__ . '/Mock/tmp.txt');
        $this->assertSame('new save contents', $saved);
        return $result;
    }

    /**
     * @param $result
     *
     * @depends testHandleSave
     */
    public function testHandleSaveResult($result)
    {
        $file = __DIR__ . '/Mock/tmp.txt';
        $expected = "[BEAR.Ace] saved:{$file} result:17";
        $this->assertSame($expected, $result);
    }

    /**
     * @expectedException \BEAR\Ace\Exception
     */
    public function testHandleLoadWithoutFile()
    {
        $get = array(
            'line' => 1
        );
        $post = array();
        $server = array('REQUEST_METHOD' => 'GET');
        (string)$this->editor->setRootPath(__DIR__)->handle($get, $post, $server);
    }

    public function testSetObject()
    {
        require __DIR__ . '/FooClass.php';
        $foo = new \FooClass;
        $html = (string)$this->editor->setObject($foo);
        $this->assertContains('class FooClass', $html);
    }

    public function testEditFunction()
    {
        $foo = new \FooClass;
        $html = edit($foo, true);
        $this->assertContains('class FooClass', $html);
    }

}
