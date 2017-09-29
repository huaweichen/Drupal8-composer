<?php

namespace Drupal\stock_exchange_rate_block;

use Drupal\Core\Config\ConfigManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\webprofiler\Config\ConfigFactoryWrapper;
use GuzzleHttp\Client;

/**
 * Class StockExchangeRateBlockUpdater
 *
 * @package Drupal\stock_exchange_rate_block
 */
class StockExchangeRateBlockUpdater {

  /**
   * Config manager service.
   *
   * @var \Drupal\Core\Config\ConfigManagerInterface
   */
  protected $configManager;

  /**
   * Entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Http client service.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * StockExchangeRateBlockUpdater constructor.
   *
   * @param \Drupal\Core\Config\ConfigManagerInterface $configManager
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \GuzzleHttp\Client $httpClient
   */
  public function __construct(
    ConfigManagerInterface $configManager,
    EntityTypeManagerInterface $entityTypeManager,
    Client $httpClient
  ) {
    $this->configManager = $configManager;
    $this->entityTypeManager = $entityTypeManager;
    $this->httpClient = $httpClient;
  }

  /**
   * Api call to get stock price.
   */
  public function update() {
    // Load immutable config.
    $config = $this->configManager
      ->getConfigFactory()
      ->get('stock_exchange_rate_block.settings');
    $companies = $config->get('companies');
    $api = $config->get('api');
    $bundle = $config->get('bundle');
    $callbackFnName = $config->get('callback');

    // Get block.
    $blocks = $this->entityTypeManager
      ->getStorage('block_content')
      ->loadByProperties([
        'type' => $bundle,
      ]);

    foreach ($blocks as $block) {
      $symbol = $block->field_symbol->value;
      dump($symbol);
      $endpoint = $api . $symbol;
      $response = $this->httpClient->get($endpoint, [
        'headers' => [
          'Accept' => 'text',
        ],
      ]);
      dump($response);
    }
  }

}
