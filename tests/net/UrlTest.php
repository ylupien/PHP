<?php

namespace yl\net;

class UrlTest extends \PHPUnit\Framework\TestCase
{
  /**
   * @cover Url::create
   */
  public function testCreate()
  {
    $url = Url::create('http://www.yanik-lupien.com/');
    $this->assertInstanceOf(Url::CNAME, $url);
  }

  /**
   * @cover Url::create
   */
  public function testGetters()
  {
    $url = Url::create('http://www.yanik-lupien.com:8080/path/file.txt?id=434&ref=abc#article-1');

    $this->assertSame('http', $url->getProtocol());
    $this->assertSame('www.yanik-lupien.com', $url->getHost());
    $this->assertSame(8080, $url->getPort());
    $this->assertSame('/path/file.txt', $url->getPath());
    $this->assertSame('434', $url->getQuery('id'));
    $this->assertSame('abc', $url->getQuery('ref'));
    $this->assertSame('article-1', $url->getFragment());
  }

  /**
   * @cover Url::create
   */
  public function testCreateWithEmptyArg()
  {
    $url = Url::create();
    $this->assertInstanceOf(Url::CNAME, $url);
  }

  public function testGetProtocol()
  {
    $url = Url::create('http://www.yanik-lupien.com/');
    $this->assertSame('http', $url->getProtocol());

    $url = Url::create('ftp://www.yanik-lupien.com/');
    $this->assertSame('ftp', $url->getProtocol());

    $url = Url::create('/abc');
    $this->assertSame('', $url->getProtocol());
  }

  public function testSetProtocol()
  {
    $url = Url::create('http://www.yanik-lupien.com/')->setProtocol('ftp');
    $this->assertSame('ftp', $url->getProtocol());
    $this->assertSame(21, $url->getPort());
    $this->assertSame('ftp://www.yanik-lupien.com/', (string) $url);

    $url = Url::create('http://www.yanik-lupien.com:4454/')->setProtocol('ftp');
    $this->assertSame(4454, $url->getPort());
  }

  /**
   * @expectedException Exception
   */
  public function testSetUnsupportedProtocol()
  {
    Url::create('http://www.yanik-lupien.com/')->setProtocol('yoda');
  }

  /**
   * @cover Url::setUser
   * @cover Url::getUser
   */
  public function testUser() {
    $url = Url::create('http://bob@www.yanik-lupien.com/');
    $this->assertSame('bob', $url->getUser());
    $this->assertSame('http://bob@www.yanik-lupien.com/', (string) $url);

    $url = Url::create('http://www.yanik-lupien.com/')->setUser('root');
    $this->assertSame('root', $url->getUser());
    $this->assertSame('http://root@www.yanik-lupien.com/', (string) $url);
  }

  /**
   * @cover Url::setPassword
   * @cover Url::getPassword
   */
  public function testPassword()
  {
    $url = Url::create('http://bob:1234@www.yanik-lupien.com/');
    $this->assertSame('1234', $url->getPassword());
    $this->assertSame('http://bob:1234@www.yanik-lupien.com/', (string) $url);

    $url = Url::create('http://root@www.yanik-lupien.com/')->setPassword('god');
    $this->assertSame('god', $url->getPassword());
    $this->assertSame('http://root:god@www.yanik-lupien.com/', (string) $url);
  }

  /**
   * @cover Url::setHost
   * @cover Url::getHost
   */
  public function testHost()
  {
    $url = Url::create('http://www.yanik-lupien.com/');
    $this->assertSame('www.yanik-lupien.com', $url->getHost());

    $url->setHost('www.google.com');
    $this->assertSame('www.google.com', $url->getHost());

    $this->assertSame('http://www.google.com/', (string) $url);

    $url = Url::create('http://www.yanik-lupien.com/')->setHost('www.abc.com');
    $this->assertSame('http://www.abc.com/', (string) $url);
  }

  /**
   * @cover Url::setPort
   * @cover Url::getPort
   */
  public function testPort()
  {
    $url = Url::create('http://www.yanik-lupien.com:8080/');
    $this->assertSame(8080, $url->getPort());
    $this->assertSame('http://www.yanik-lupien.com:8080/', (string) $url);

    $url = Url::create('http://www.yanik-lupien.com/')->setPort(443);
    $this->assertSame(443, $url->getPort());
    $this->assertSame('http://www.yanik-lupien.com:443/', (string) $url);

    $url = Url::create('http://www.yanik-lupien.com/');
    $this->assertSame(80, $url->getPort());
    $this->assertSame('http://www.yanik-lupien.com/', (string) $url);

    $url = Url::create('ftp://www.yanik-lupien.com/');
    $this->assertSame(21, $url->getPort());
    $this->assertSame('ftp://www.yanik-lupien.com/', (string) $url);
  }

  /**
   * @cover Url::setPath
   * @cover Url::getPath
   */
  public function testPath() {
    $url = Url::create('http://www.yanik-lupien.com/path/to/file.txt');
    $this->assertSame('/path/to/file.txt', $url->getPath());
    $this->assertSame('http://www.yanik-lupien.com/path/to/file.txt', (string) $url);

    $url = Url::create('http://www.yanik-lupien.com/')->setPath('/path/to/file.txt');
    $this->assertSame('/path/to/file.txt', $url->getPath());
    $this->assertSame('http://www.yanik-lupien.com/path/to/file.txt', (string) $url);
  }

  /**
   * @cover Url::setFragment
   * @cover Url::getFragment
   */
  public function testFragment() {
    $url = Url::create('http://www.yanik-lupien.com/#article-1');
    $this->assertSame('article-1', $url->getFragment());
    $this->assertSame('http://www.yanik-lupien.com/#article-1', (string) $url);

    $url = Url::create('http://www.yanik-lupien.com/')->setFragment('article-1');
    $this->assertSame('article-1', $url->getFragment());
    $this->assertSame('http://www.yanik-lupien.com/#article-1', (string) $url);
  }

  public function testToString()
  {
    $url = Url::create('http://www.yanik-lupien.com/');
    $this->assertSame('http://www.yanik-lupien.com/', (string) $url);

    $url = Url::create('/');
    $this->assertSame('/', (string) $url);

    $url = Url::create('http://www.yanik-lupien.com/path/to/file.txt');
    $this->assertSame('http://www.yanik-lupien.com/path/to/file.txt', (string) $url);

    $url = Url::create('http://www.yanik-lupien.com/?id=44');
    $this->assertSame('http://www.yanik-lupien.com/?id=44', (string) $url);

    $url = Url::create('http://www.yanik-lupien.com/?id=44#fragment');
    $this->assertSame('http://www.yanik-lupien.com/?id=44#fragment', (string) $url);
  }
}