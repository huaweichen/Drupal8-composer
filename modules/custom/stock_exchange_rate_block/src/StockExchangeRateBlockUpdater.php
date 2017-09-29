<?php

namespace Drupal\stock_exchange_rate_block;

use Drupal\Component\Serialization\Exception\InvalidDataTypeException;
use Drupal\Core\Config\ConfigManagerInterface;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Component\Serialization\Json;
use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
   * Http client service.
   *
   * @var \Drupal\Component\Serialization\Json
   */
  protected $jsonSerializer;

  /**
   * StockExchangeRateBlockUpdater constructor.
   *
   * @param \Drupal\Core\Config\ConfigManagerInterface $configManager
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \GuzzleHttp\Client $httpClient
   * @param \Drupal\Component\Serialization\Json
   */
  public function __construct(
    ConfigManagerInterface $configManager,
    EntityTypeManagerInterface $entityTypeManager,
    Client $httpClient,
    Json $jsonSerializer
  ) {
    $this->configManager = $configManager;
    $this->entityTypeManager = $entityTypeManager;
    $this->httpClient = $httpClient;
    $this->jsonSerializer = $jsonSerializer;
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

    // Loop blocks and retrieve data from API.
    foreach ($blocks as $block) {
      $symbol = $block->field_symbol->value;
      $endpoint = $api . $symbol;

      try {
        $response = (string) $this->httpClient
          ->get($endpoint, [
            'headers' => [
              'Accept' => 'text',
            ],
          ])
          ->getBody();

        // Remove callback function name, and function brackets.
        $jsonResponse = substr($response, strlen($callbackFnName) + 1, -1);

        $responseArray = Json::decode($jsonResponse);

        // Entity storage.
        $change = $responseArray['Change'];
        $lastPrice = $responseArray['LastPrice'];

        $block->set('field_change', $change)
          ->set('field_lastPrice', $lastPrice)
          ->save();
      }
      catch (HttpException $e) {
        // Log HTTP exception.
        continue;
      }
      catch (InvalidDataTypeException $e) {
        // Invalid data.
        continue;
      }
      catch (EntityStorageException $e) {
        // Entity save exception.
        continue;
      }
    }
  }

}
