<?php
require_once('class/yl/php/Autoloader.php');
yl\php\Autoloader::create()->classPath(__DIR__ . '/class')->register();

use yl\net\Url;

$url = Url::create("http://test:bob@www.google.com:8080/path/to/file.php?id=44&ref=444#first");
?>
<html>
  <head>
		<style>
			body {
			  font-family: Arial;
			}
		</style>
  </head>
  <body>
    <h3><?= htmlentities($url); ?></h3>
    <table border="1" cellspacing="0" cellpadding="3">
    	<tr><td>$url-&gt;getProtocol()</td><td><?= htmlentities($url->getProtocol()); ?></td></tr>
    	<tr><td>$url-&gt;getHost()</td><td><?= htmlentities($url->getHost()); ?></td></tr>
    	<tr><td>$url-&gt;getUser()</td><td><?= htmlentities($url->getUser()); ?></td></tr>
    	<tr><td>$url-&gt;getPassword()</td><td><?= htmlentities($url->getPassword()); ?></td></tr>
    	<tr><td>$url-&gt;getPort()</td><td><?= htmlentities($url->getPort()); ?></td></tr>
    	<tr><td>$url-&gt;getPath()</td><td><?= htmlentities($url->getPath()); ?></td></tr>
    	<tr><td>$url-&gt;getQueries()</td><td><?= htmlentities(print_r($url->getQueries(), true)); ?></td></tr>
    	<tr><td>$url-&gt;getFragment()</td><td><?= htmlentities($url->getFragment()); ?></td></tr>
    </table>
  </body>
</html>