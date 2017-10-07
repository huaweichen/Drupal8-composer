<?php

namespace Drupal\events_and_subscribers\EventSubscriber;

use Drupal\Core\Logger\LoggerChannelFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventExampleSubscriber implements EventSubscriberInterface {

  /**
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $loggerFactory;

  public function __construct(LoggerChannelFactory $loggerFactory) {
    $this->loggerFactory = $loggerFactory;
  }

  /**
   * @return array
   */
  public static function getSubscribedEvents() {
    return [
      'simple_event' => [
        ['eventHandler', 0],
      ],
    ];
  }

  public function eventHandler () {
    drupal_set_message("Simple Page Loaded.");

    \Drupal::service('logger.factory')
      ->get('event_example_subscriber')
      ->notice('EventExampleSubscriber->eventHandler is executed.');
  }

}
