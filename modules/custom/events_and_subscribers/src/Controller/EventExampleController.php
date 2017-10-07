<?php

namespace Drupal\events_and_subscribers\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\events_and_subscribers\Event\SimpleEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventExampleController extends ControllerBase {

  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * @var \Drupal\events_and_subscribers\Event\SimpleEvent
   */
  protected $simpleEvent;

  /**
   * @var
   */
  protected $loggerFactory;

  /**
   * EventExampleController constructor.
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
   * @param \Drupal\events_and_subscribers\Event\SimpleEvent $simpleEvent
   * @param LoggerChannelFactory $loggerFactory
   */
  public function __construct (
    EventDispatcherInterface $eventDispatcher,
    SimpleEvent $simpleEvent,
    LoggerChannelFactory $loggerFactory
  ) {
    $this->eventDispatcher = $eventDispatcher;
    $this->simpleEvent = $simpleEvent;
    $this->loggerFactory = $loggerFactory;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    $eventDispatcher = $container->get('event_dispatcher');
    $simpleEvent = $container->get('events_and_subscribers.simple_event');
    $loggerFactory = $container->get('logger.factory');
    return new static($eventDispatcher, $simpleEvent, $loggerFactory);
  }

  public function simpleDispatcher() {
    $this->loggerFactory->get('events_and_subscribers')->notice('Dispatching an event: simple');
    $this->eventDispatcher
      ->dispatch('simple_event', $this->simpleEvent
          ->setConfig([
            'name' => 'simple event',
            'value' => 'configuration of simple event'
          ]));

    return [
      '#markup' => t('Simple Event Page: check reports and logs.'),
    ];
  }

}
