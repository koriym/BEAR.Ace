BEAR.Ace
========

Ace Online editor utility for PHP
----------------------------------

[https://github.com/ajaxorg/ace Ace] is a standalone code editor written in JavaScript.
BEAR.Ace is the PHP utility for Ace.


Getting started
===============

download
```
$ git clone https://github.com/koriym/BEAR.Ace.git
$ cd BEAR.Ace/web
```

edit root dir
ファイルをエディットできるルートのディレクトリを指定します。

web/index.php
```
$rootPath = __DIR__;
```
or
```
$rootPath = '/'; // you can access all files which web server can access
```

Start built-in web server
```
$ php -S localhost:8070 index.php
```

Access the editor with 'file' query
```
http://localhost:8070/?file=hello.php
```

you wil this screen.
    

Requirements
---------
 * PHP 5.3+
