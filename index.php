<pre>
<?php
require_once('class/net/Url.php');

$url = Url::create("http://test:bob@www.google.com:8080/path/to/file.php?id=44&ref=444#first");

$url->setHost('www.yahoo.com')->setUser('Lucie');

print $url;
print "<br/>";
print $url->getPort();

$url = Url::create()->setHost('www.yahoo.com')->setQuery('id', 'hello');

$url = Url::create()
  ->setProtocol('ftp')
  ->setHost('www.yahoo.com')
  ->setQuery('id', 'hello world')
  ->setUser('bob')
  ->setPassword('123');

print "<hr/>";

print $url;

print "<br/>";
print $url->getPort();
