BEAR.Ace
========

Ace online editor utility for PHP
----------------------------------

BEAR.AceはオンラインエディターAceのユーティリティです。
エディターwebサービスやシンタックスエラーのオンライン修正が可能です。

BEAR.Ace is the Ace utility for PHP. ([Ace](https://github.com/ajaxorg/ace) is a standalone code editor written in JavaScript. )
It enable to start online editor web service, fix syntax error on the spot.

Getting started
===============

Start online editor web service.  

```
$ cd BEAR.Ace/web
$ php -S localhost:8090 index.php
```

You can browse file content with 'file' and 'line' (optional) query.    

![Editor](https://raw.github.com/koriym/BEAR.Ace/gh-pages/assets/editor.png)

You can aslo save when you have write access to the web server. It supports save shortcut keys for Windows(Ctl+S) / OSX(Cmd+S).

Sample Code
-----------

Get the HTML to display the editor.

```php
$html = (string)(new Editor)->setRootPath($rootPath)->setPath($file)->setLine($line);
```

You can specify $file in absolute path or relative path from $rootPath.
You will not be able to access the files at higher than $rootPath for security reason.


You can start online service in this code.
```php
try {
    $editor = (new Editor)->setRootPath($rootPath)->handle($_GET, $_POST, $_SERVER);
    echo $editor;
} catch (Exception $e) {
    http_response_code($e->getCode());
    echo $e->getCode();
}
```
You can find more sample code in /docs/ folder.

Syntax Error Editor
-------------------
When you register an error handler, you can fix on the place to not only display an error when the Syntax Error.Reload is automatically when you save, it will minimize the time and frustration by careless mistake.

```php
(new \BEAR\Ace\ErrorEditor)->registerSyntaxErrorEdit();
```
![Editor](https://raw.github.com/koriym/BEAR.Ace/gh-pages/assets/syntax_error.png)

edit();
-------------------
You can see file content in an editor in the edit function. Specify the object or file path in the argument.

```php
$file = __DIR__ . 'file.php';
edit($file);
```

```php
$a = new A;
edit($a);
```

xdebug.file_link_format
-----------------------
You can link to online editor the file name of the stack trace in this ini configuration of xdebug.

```php
xdebug.file_link_format=localhost:8090/?file=%f&line=$l
```

Symfony integration for sytanx error editor
-------------------------------------------

1) add "bear / ace" to composer.json, then install it with composer command.
```php
    "require": {
        ...
        "bear/ace": "*"
    },
```
```bash
$ composer update bear/ace
```

2) register a syntax error editor in web/app_dev.php.
```php

require_once __DIR__.'/../app/AppKernel.php';
// after this line

(new \BEAR\Ace\ErrorEditor)->registerSyntaxErrorEdit();

// before this line
$kernel = new AppKernel('dev', true);
```

3) That's it ! You can fix the syntax error on the spot.

Requirements
------------
 * PHP 5.4+
