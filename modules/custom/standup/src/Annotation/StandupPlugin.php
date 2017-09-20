<?php

namespace Drupal\standup\Annotation;

use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;

/**
 * Defines a Standup plugin item annotation object.
 *
 * @see \Drupal\standup\Plugin\StandupPluginManager
 * @see plugin_api
 *
 * @Annotation
 */
class StandupPlugin extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * A human readable description.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

  /**
   * Number of calories per serve.
   *
   * @var float
   */
  public $calories;

}
