<?php

namespace Drupal\content_entity_example\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Class ContactForm
 *
 * @package Drupal\content_entity_example\Form
 *
 * @ingroup content_entity_example
 */
class ContactForm extends ContentEntityForm {

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $form['langcode'] = [
      '#title' => $this->t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages' => Language::STATE_ALL,
    ];

    return $form;
  }

  /**
   * @inheritDoc
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    $entity = $this->entity;

    if ($status == SAVED_UPDATED) {
      \Drupal\Core\Form\drupal_set_message($this->t('The contact %feed has been updated.', [
        '%feed' => $entity->toLink()->toString()]));
    }
    else {
      \Drupal\Core\Form\drupal_set_message($this->t('The contact %feed has been added.', ['%feed' => $entity->toLink()->toString()]));
    }

    $form_state->setRedirectUrl($this->entity->toUrl('collection'));

    return $status;
  }




}
