<?php

namespace Drupal\events_and_subscribers\Event;

use Symfony\Component\EventDispatcher\Event;

class SimpleEvent extends Event {

  /**
   * @var array
   */
  protected $config;

  /**
   * SimpleEvent constructor.
   *
   * @param array $config
   */
  public function __construct(array $config = []) {
    $this->config = $config;
  }

  /**
   * @param $name
   *
   * @return mixed
   */
  public function getConfig($name) {
    return $this->config[$name];
  }

  /**
   * @param $name
   * @param $value
   */
  public function setConfig(array $value) {
    $this->config = $value;
  }

}
