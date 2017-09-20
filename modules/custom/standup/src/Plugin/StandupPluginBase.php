<?php

namespace Drupal\standup\Plugin;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for Standup plugin plugins.
 */
abstract class StandupPluginBase extends PluginBase implements StandupPluginInterface {

  public function getDescription() {
    return $this->pluginDefinition['description'];
  }

  public function getCalories() {
    return (float) $this->pluginDefinition['calories'];
  }

  abstract public function order(array $extras);

}
