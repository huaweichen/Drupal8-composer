<?php

namespace Drupal\cron_queuing\Plugin\QueueWorker;

use Drupal\Core\Annotation\QueueWorker;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Mail\MailManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CronUserEmailer
 *
 * @package Drupal\cron_queuing\Plugin\QueueWorker
 * @QueueWorker(
 *   id = "cron_user_emailer",
 *   title = @translation("Cron user emailer"),
 *   cron = {"time" = 10}
 * )
 */
class CronUserEmailer extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $userStorage;

  /**
   * @var \Drupal\Core\Mail\MailManager
   */
  protected $mailer;

  /**
   * CronUserEmailer constructor.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $userStorage
   * @param \Drupal\Core\Mail\MailManager $mailer
   */
  public function __construct(EntityStorageInterface $userStorage, MailManager $mailer) {
    $this->userStorage = $userStorage;
    $this->mailer = $mailer;
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('entity.manager')->getStorage('user'),
      $container->get('plugin.manager.mail')
    );
  }

  /**
   * @inheritDoc
   */
  public function processItem($data) {
    // Retrieve user ID.
    $user = $this->userStorage->load($data->nid);

    // Send email.
    $this->mailer->mail(
      // Module.
      'cron_queuing',
      // A key to identify the email sent.
      'cron_user_emailer',
      // Send email_to address.
      $user->get('email'),
      // Language code.
      'en',
      // Params.
      [
        'subject' => 'Welcome to D8 Cards testutorial.',
        'message' => 'Thanks for your registration. Your personal details gonna be sold in high price.',
      ],
      // Successfully sent or not.
      TRUE
    );
  }

}
