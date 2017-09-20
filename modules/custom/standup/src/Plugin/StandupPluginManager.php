<?php

namespace Drupal\standup\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Standup plugin plugin manager.
 */
class StandupPluginManager extends DefaultPluginManager {


  /**
   * Constructs a new StandupPluginManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/StandupPlugin', $namespaces, $module_handler, 'Drupal\standup\Plugin\StandupPluginInterface', 'Drupal\standup\Annotation\StandupPlugin');

    $this->alterInfo('standup_standup_plugin_info');
    $this->setCacheBackend($cache_backend, 'standup_standup_plugin_plugins');
  }

}
