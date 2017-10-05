<?php

namespace Drupal\logging_in_d8\Logger;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Logger\RfcLoggerTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NodeCreateLogger implements LoggerInterface, ContainerInjectionInterface {
  use RfcLoggerTrait;

  /**
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  private $loggerFactory;

  /**
   * NodeCreateLogger constructor.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerFactory
   */
  public function __construct(LoggerChannelFactoryInterface $loggerFactory) {
    $this->loggerFactory = $loggerFactory;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    /** @var LoggerChannelFactoryInterface $loggerFactory */
    $loggerFactory = $container->get('logger.factory');
    return new static($loggerFactory);
  }

  /**
   * @param mixed $level
   * @param string $message
   * @param array $context
   */
  public function log($level, $message, array $context = []) {
    $this->loggerFactory->get('logging_in_d8')->$level($message);
  }

}
