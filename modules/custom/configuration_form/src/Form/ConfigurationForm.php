<?php

namespace Drupal\configuration_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ConfigurationForm extends ConfigFormBase {

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'd8cards_day03_configuration_form';
  }

  /**
   * @inheritDoc
   */
  protected function getEditableConfigNames() {
    return ['configuration_form.settings'];
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get immutable config.
    $config = $this->configFactory->get('configuration_form.settings');

    $form['title'] = array(
      '#type' => 'select',
      '#title' => t('Title'),
      '#required' => TRUE,
      '#options' => [
        'sir' => 'Sir',
        'mr' => 'Mr',
        'ms' => 'Ms',
      ],
      '#default_value' => $config->get('title'),
    );
    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#required' => TRUE,
      '#default_value' => $config->get('name')
    );
    $form['gender'] = array(
      '#type' => 'radios',
      '#title' => t('Gender'),
      '#required' => TRUE,
      '#options' => [
        'male' => t('Male'),
        'female' => t('Female'),
      ],
      '#default_value' => $config->get('gender'),
    );
    $form['description'] = array(
      '#type' => 'textarea',
      '#title' => t('Description'),
      '#default_value' => $config->get('description'),
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );

    return $form;
  }

  /**
   * @inheritDoc
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get mutable config.
    $this->configFactory->getEditable('configuration_form.settings')
      ->set('title', $form_state->getValue('title'))
      ->set('name', $form_state->getValue('name'))
      ->set('gender', $form_state->getValue('gender'))
      ->set('description', $form_state->getValue('description'))
      ->save();
  }

}
