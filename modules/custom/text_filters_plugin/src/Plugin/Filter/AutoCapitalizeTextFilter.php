<?php

namespace Drupal\text_filters_plugin\Plugin\Filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\filter\Annotation\Filter;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * @Filter(
 *   id = "auto_capitalize_text_filter",
 *   title = @Translation("Auto Capitalize Text Filter"),
 *   description = @Translation("Auto-capitalize text."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class AutoCapitalizeTextFilter extends FilterBase {

  /**
   * @inheritDoc
   */
  public function process($text, $langcode) {
    $preConfiguredWords = [];

    if (!empty($this->settings['auto_capitalize_text_filter'])) {
      $preConfiguredWords = explode(',', $this->settings['auto_capitalize_text_filter']);
    }
    if (!empty($this->settings['never_upper_case'])) {
      $preConfiguredNeverUppercase = explode(',', $this->settings['never_upper_case']);
    }

    if (empty($preConfiguredWords)) {
      $capitalized = strtoupper($text);
    }
    else {
      $capitalized = $text;
      foreach ($preConfiguredWords as $word) {
        $capitalized = str_replace($word, strtoupper($word), $capitalized);
      }
    }

    return new FilterProcessResult($capitalized);
  }

  /**
   * @inheritDoc
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form['auto_capitalize_text_filter'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Auto-capitalize text pre-configured words: '),
      '#default_value' => $this->settings['auto_capitalize_text_filter'],
      '#description' => $this->t('Create a new format type, then enable \'Auto-Capital\' checkbox.'),
    ];

    $form['never_upper_case'] = [
      '#type' => 'textarea',
      '#title' => $this->t('NEVER-capitalize text pre-configured words: '),
      '#default_value' => $this->settings['never_upper_case'],
      '#description' => $this->t('Create a new format type, then enable \'Auto-Capital\' checkbox.'),
    ];

    return $form;
  }

}
