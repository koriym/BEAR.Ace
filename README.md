BEAR.Ace
========

Ace online editor utility for PHP
----------------------------------

BEAR.Ace is an Ace utility for PHP. ([Ace](https://github.com/ajaxorg/ace) is a standalone code editor written in JavaScript.)
It enables you to use an editor via a web service or to fix syntax errors on the fly.

BEAR.AceはオンラインエディターAceのユーティリティです。
エディターwebサービスやシンタックスエラーのオンライン修正が可能です。

[![Latest Stable Version](https://poser.pugx.org/bear/ace/v/stable.png)](https://packagist.org/packages/bear/ace)
[![Build Status](https://secure.travis-ci.org/koriym/BEAR.Ace.png?branch=master)](http://travis-ci.org/koriym/BEAR.Ace)

Getting started
===============

### Installing via Composer

The recommended way to install BEAR.Ace is through [Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php

# Install as stand alone editor
php composer.phar create-project bear/ace BEAR.Ace

# Add BEAR.Ace as a dependency
php composer.phar require bear/ace:~1.0
```

### Start the online editor web service

```
$ cd BEAR.Ace/web
$ php -S localhost:8090 index.php
```

You can now browse file content using the 'file' and 'line' (optional) query.    

![Editor](https://raw.github.com/koriym/BEAR.Ace/gh-pages/assets/editor.png)

You can also save the file you are editing when you have write access to the web server. It supports save shortcut keys for Windows(Ctl+S) and OSX(Cmd+S).

Sample Code
-----------

Getting HTML to display in the editor.

```php
$html = (string)(new Editor)->setRootPath($rootPath)->setPath($file)->setLine($line);
```

You can specify a `$file` as an absolute path or as a relative path from `$rootPath`.
Files higher than `$rootPath` are not accessible for security reasons.


Starting the online service is simple using the following code.
```php
try {
    $editor = (new Editor)->setRootPath($rootPath)->handle($_GET, $_POST, $_SERVER);
    echo $editor;
} catch (Exception $e) {
    http_response_code($e->getCode());
    echo $e->getCode();
}
```
More sample code can be found in the /docs/ directory.

Syntax Error Editor
-------------------
Once an error handler has been registered, when a syntax error occurs, you can not only display the error but make a fix in the browser on the fly. The browser is then automatically reloaded for you upon save. This feature can really minimize the time and frustration caused by simple careless mistakes.

```php
(new \BEAR\Ace\ErrorEditor)->registerSyntaxErrorEdit();
```
![Editor](https://raw.github.com/koriym/BEAR.Ace/gh-pages/assets/syntax_error.png)

edit();
-------------------
You can also view file content in the editor by using the edit function. Just specify the object or file path in the argument.

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
The online editor can be linked to in the stack trace file name using the following ini configuration of xdebug.

```php
xdebug.file_link_format=localhost:8090/?file=%f&line=$l
```

Syntax Error Integration in Symfony2
-------------------------------------------

1) Add "bear / ace" to composer.json, then install it with the composer command.
```php
    "require": {
        ...
        "bear/ace": "*"
    },
```
```bash
$ composer update bear/ace
```

2) Register a syntax error editor in web/app_dev.php.
```php

require_once __DIR__.'/../app/AppKernel.php';
// after this line

(new \BEAR\Ace\ErrorEditor)->registerSyntaxErrorEdit();

// before this line
$kernel = new AppKernel('dev', true);
```

3) That's it ! You can now fix syntax errors on the spot.

Requirements
------------
 * PHP 5.3+
