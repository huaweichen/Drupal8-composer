<?php

namespace Drupal\standup\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Standup plugin plugins.
 */
interface StandupPluginInterface extends PluginInspectionInterface {

  /**
   * Provide a description of the sandwich.
   *
   * @return string
   *   A string description of the sandwich.
   */
  public function getDescription();

  /**
   * Provide the number of calories per serving for the sandwich.
   *
   * @return float
   *   The number of calories per serving.
   */
  public function getCalories();

  /**
   * @param array $extras
   *   An array of extra ingredients to include with this sandwich.
   *
   * @return mixed
   */
  public function order(array $extras);

}
