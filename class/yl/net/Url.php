<?php

namespace yl\net;

/**
 * @package    PHP
 * @subpackage Net
 * @author     Yanik Lupien
 */
class Url
{
  const CNAME = __CLASS__;

  static private $protocols = array(
    'http' => 80, 'ftp' => 21
  );

  private $parts = array (
    'scheme' => '',
    'user' => '',
    'pass' => '',
    'host' => '',
    'port' => null,
    'path' => '/',
    'query' => array(),
    'fragment' => '',
  );

  /**
   * @param string $v
   * @return Url
   */
  public static function create($v = null)
  {
    $url = null;

    if (is_null($v))
      $url = new self();

    if (is_string($v)) {
      $url = new self();
      $url->parse($v);
    }

    if ($url === null)
      throw new Exception("Failed to create url");

    return $url;
  }

  /**
   * @return string
   */
  public function getProtocol()
  {
    return $this->parts['scheme'];
  }

  /**
   * @param string $s
   * @throws Exception
   * @return Url
   */
  public function setProtocol($s)
  {
    $protocols = self::$protocols;

    if (array_key_exists(strtolower($s), $protocols) === false)
      throw new Exception("Unsupported protocol '{$s}'");

    if (
      isset($protocols[strtolower($this->parts['scheme'])]) &&
      $protocols[strtolower($this->parts['scheme'])] == $this->parts['port']
    )
      $this->parts['port'] = $protocols[strtolower($s)];

    $this->parts['scheme'] = $s;

    return $this;
  }

  /**
   * @return string
   */
  public function getUser()
  {
    return $this->parts['user'];
  }

  /**
   * @param string $s
   * @return Url
   */
  public function setUser($s)
  {
    $this->parts['user'] = $s;

    return $this;
  }

  /**
   * @return string
   */
  public function getPassword()
  {
    return $this->parts['pass'];
  }

  /**
   * @param string $s
   * @return Url
   */
  public function setPassword($s)
  {
    $this->parts['pass'] = $s;
    return $this;
  }

  /**
   * @return string
   */
  public function getHost()
  {
    return $this->parts['host'];
  }

  /**
   * @param string $s
   * @return Url
   */
  public function setHost($s)
  {
    if (preg_match('/[^a-z-\.]/', $s))
      throw new Exception("Host {$s} is invalid");

    $this->parts['host'] = $s;
    return $this;
  }

  /**
   * @return string
   */
  public function getPort()
  {
    if ($this->parts['port'])
      $port = $this->parts['port'];

    else if ($this->parts['scheme'])
      $port = self::$protocol[$this->parts['scheme']];

    return $port;
  }

  /**
   * @param string $s
   * @return Url
   */
  public function setPort($i)
  {
    $this->parts['port'] = (int) $i;
    return $this;
  }

  /**
   * @return string
   */
  public function getPath()
  {
    return $this->parts['path'];
  }

  /**
   * @param string $s
   * @return Url
   */
  public function setPath($s)
  {
    $this->parts['path'] = $s;
    return $this;
  }

  /**
   * @param string $key
   * @return mixed
   */
  public function getQuery($key)
  {
    return $this->parts['query'][$key];
  }

  /**
   * @param string $s
   * @return Url
   */
  public function setQuery($key, $value)
  {
    $this->parts['query'][$key] = $value;
    return $this;
  }

  /**
   * @return array
   */
  public function getQueries()
  {
    return $this->parts['query'];
  }

  /**
   * @return string
   */
  public function getFragment()
  {
    return $this->parts['fragment'];
  }

  /**
   * @param string $s
   * @return Url
   */
  public function setFragment($s)
  {
    $this->parts['fragment'] = $s;
    return $this;
  }

  public function parse($url)
  {
    $parts = parse_url($url);

    if (isset($parts['query'])) {
      parse_str($parts['query'], $parts['query']);
    }

    $query = $this->parts['query'];

    $this->parts = array_merge($this->parts, $parts);

    if (isset($parts['query'])) {
      $this->parts['query'] = array_merge($query, $parts['query']);
    }

    if (!$this->parts['port'] && $this->parts['scheme'])
      $this->parts['port'] = self::$protocols[$this->parts['scheme']];
  }

  /**
   * @return string
   */
  private function buildUserPass()
  {
    $s = '';

    if ($this->parts['pass'])
      $s .= (
        urlencode($this->parts['user']) .
        ':' .
        urlencode($this->parts['pass'])
      );
    else
      $s .= urlencode($this->parts['user']);

    return $s;
  }

  /**
   * @return string
   */
  private function buildQueries()
  {
    $query = '';
    foreach ($this->parts['query'] as $key => $value) {
      $query .= (
        ($query ? '&' : '') .
        urlencode($key) .
        '=' .
        urlencode($value)
      );
    }

    return $query;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    $url = '';

    if ($this->parts['scheme'])
      $url .= $this->parts['scheme'] . "://";
    else if ($this->parts['host'])
      $url = 'http://';

    if ($this->parts['user'] || $this->parts['pass']) {
      $url .= $this->buildUserPass() . '@';
    }

    if ($this->parts['host'])
      $url .= $this->parts['host'];

    if (
      $this->parts['port'] &&
      $this->parts['port'] != self::$protocols[$this->parts['scheme']]
    )
      $url .= ':' . $this->parts['port'];

    if ($this->parts['path'])
      $url .= $this->parts['path'];

    if ($this->parts['query']) {
      $url .= '?' . $this->buildQueries();
    }

    if ($this->parts['fragment'])
      $url .= '#' . $this->parts['fragment'];

    return $url;
  }

}