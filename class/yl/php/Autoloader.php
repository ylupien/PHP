<?php

namespace yl\php;

class Autoloader {

  private $classPath;

  static function create() {
    $o = new self();
    return $o;
  }

  /**
   * @param string $classPath
   * @return Autoloader
   */
  public function classPath($classPath) {
    $this->classPath = $classPath;
    return $this;
  }

  public function register() {
    spl_autoload_register(array($this, 'load'));
  }

  private function load($className) {
    include_once($this->classPath . DIRECTORY_SEPARATOR . "{$className}.php");
  }
}

