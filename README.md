BEAR.Ace
========

Ace Online editor utility for PHP
----------------------------------

[Ace](https://github.com/ajaxorg/ace) is a standalone code editor written in JavaScript. BEAR.Ace is the PHP utility for Ace.  
[Ace](https://github.com/ajaxorg/ace) はJavaScriptでかかれたスタンドアロンのコードエディターです。 BEAR.Ace はPHPでAceを便利に使うためのユーティリティです。

Getting started
===============

Start online editor web service.  
オンラインエディタを起動します。

```
$ cd BEAR.Ace/web
$ php -S localhost:8090 index.php
```

 Access editor with 'file' (and 'line') query.    
 file,lineクエリーを使ってファイルにアクセスできます。

![Editor](https://raw.github.com/koriym/BEAR.Ace/gh-pages/assets/editor.png)

You can save the document if web server has permission to save.  
webサーバーに書き込み権限があるときは保存も行えます。Windows/OSXそれぞれのショートカットキーにも対応しています。

Configuration
-------------

エディタを表示するためのHTMLを取得します。

```php
$html = (string)(new Editor)->setRootPath($rootPath)->setPath($file)->setLine($line);
```
$fileは$rootPathからの相対パス、または絶対パスを指定できます。また$rootPathより上位のファイルにはアクセスできません。


オンラインエディターサービスを開始します。

```php
try {
    $editor = (new Editor)->setRootPath($rootPath)->handle($_GET, $_POST, $_SERVER);
    echo $editor;
} catch (Exception $e) {
    http_response_code($e->getCode());
    echo $e->getCode();
}
```

Syntax Error Editor
-------------------
エラーハンドラーを登録すると、Syntax Errorの時にエラー表示するだけでなくそのその場で修正ができます。保存をするとリロードが自動でされ、ケアレスミスによるフラストレーションと時間を最小化します。

```php
(new \BEAR\Ace\ErrorEditor)->registerSyntaxErrorEdit();
```
![Editor](https://raw.github.com/koriym/BEAR.Ace/gh-pages/assets/syntax_error.png)

edit();
-------------------

edit関数でファイルをエディターで見る事ができます。引き数にはファイルパスまたはオブジェクトを指定します。

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
xdebugのini設定でstack traceのファイル名をオンラインエディターにリンクすることができます。

```php
xdebug.file_link_format=localhost:8070/?file=%f&line=$l
```

Requirements
---------
 * PHP 5.4+
