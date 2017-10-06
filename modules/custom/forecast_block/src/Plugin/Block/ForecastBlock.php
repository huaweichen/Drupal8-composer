<?php

namespace Drupal\forecast_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Forecast\Forecast;

/**
 * @Block(
 *   id = "forecast",
 *   admin_label = @Translation("GeoBlock"),
 * )
 */
class ForecastBlock extends BlockBase {

  /**
   * @return string
   */
  public function label() {
    return t('GeoBlock');
  }

  /**
   * @return mixed
   */
  public function build() {
    $response = [
      'noresponse' => t('<h4>GeoBlock content is missing. The prophet is on holiday.</h4>')
    ];
    $long = $this->configuration['long'];
    $lat = $this->configuration['lat'];

    if ($long && $lat) {
      $forecast = \Drupal::service('forecast_block.forecast');
      $jsonResponse = $forecast->get($this->configuration['lat'], $this->configuration['long'], null,
        array(
          'units' => 'si',
          'exclude' => 'flags'
        ));
      $response += [
        'response' => $jsonResponse
      ];
    }
    return [
      '#theme' => 'forecast_block',
      '#context' => $response,
    ];
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = \Drupal::service('config.factory')->get('forecast_block.settings');

    $form = parent::blockForm($form, $form_state);
    $form['lat'] = [
      '#title' => t('Latitude'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['lat'] ? $this->configuration['lat'] : $config->get('lat'),
    ];

    $form['long'] = [
      '#title' => t('Longitude'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['long'] ? $this->configuration['long'] : $config->get('long'),
    ];

    return $form;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['lat'] = $form_state->getValue('lat');
    $this->configuration['long'] = $form_state->getValue('long');
  }

}
