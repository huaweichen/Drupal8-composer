<?php

namespace Drupal\content_entity_example\Entity;

use Drupal\content_entity_example\ContactInterface;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\EntityOwnerInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Contact entity.
 *
 *This is the main definition of the entity type. From it, an entityType is derived. The most important properties in this example are listed below.
 *
 * id: the unique identifier of this entityType. It follows the pattern 'moduleName_xyx' top avoid naming conflicts.
 *
 * label: human readable name of the entity type.
 *
 * handlers: Handler classes are used for different tasks. You can use standard handlers provided by D8 or build your own, most probably derived from the standard class. In detail:
 *
 * - view_builder: we use the standard controller to view an instance. It is called when a route lists an '_entity_view' default for the entityType (see routing.yml for details. The view can be manipulated by using the standard drupal tools in the settings.
 *
 * - list_builder: We derive our own list builder class from the entityListBuilder to control the presentation.
 * If there is a view available for this entity from the view module, it overrides the list builder. @todo: any view? naming convention?
 *
 * - form: We derive our own forms to add functionality like additional fields, redirects etc. These forms are called when the routing list '_entity_form' default for the entityType. Depending on the suffix (.add/.edit/.delete) in the route, the correct form is called.
 *
 * - access: Our own accessController where we determine access rights based on permissions.
 *
 * More properties:
 *
 * - base_table: Define the name of the table used to store the data. Make sure it is unique. The schema is automatically determined from the BaseFieldDefinitions below. The table is automatically created during installation.
 *
 * - fieldable: Can additional fields be added to the entity via the GUI? Analog to content types.
 *
 * - entity_keys: How to access the fields. Analog to 'nid' or 'uid'.
 *
 * - links: Provide links to do standard tasks. The 'edit-form' and 'delete-form' links are added to the list built by the entityListController. They will show up as action buttons in an additional column.
 *
 * There are many more properties to be used in an entity type definition. For a complete overview, please refer to the '\Drupal\Core\Entity\EntityType' class definition.
 *
 * The following construct is the actual definition of the entity type which is read and cached. Don't forget to clear cache after changes.
 *
 * @ContentEntityType(
 *   id = 'content_entity_example_contact',
 *   label = @Translation('Contact entity),
 *   handlers = {
 *      "view_builders" = "Drupal\Core\Entity\EntityViewBuilder",
 *      "list_builder" = "Drupal\content_entity_example\Entity\Controller\ContactListBuilder",
 *      "views_data" = "Drupal\views\EntityViewsData",
 *      "form" = {
 *          "add" = "Drupal\content_entity_example\Form\ContactForm",
 *          "edit" = "Drupal\content_entity_example\Form\ContactForm",
 *          "delete" = "Drupal\content_entity_example\Form\ContactDeleteForm",
 *      },
 *      "access" = "Drupal\content_entity_example\ContactAccessControlHandler",
 *   },
 *   base_table = "contact",
 *   admin_permission = "administer contact entity",
 *   fieldable = TRUE,
 *   entity_keys = {
 *      "id" = "id",
 *      "label" = "name",
 *      "uuid" = "uuid",
 *   },
 *   links = {
 *      "canonical" = "/content_entity_example_contact/{content_entity_example_contact}",
 *      "edit-form" = "/content_entity_example_contact/{content_entity_example_contact}/edit",
 *      "delete-form" = "/content_entity_example_contact/{content_entity_example_contact}/delete",
 *      "collection" = "/content_entity_example_contact/list",
 *   },
 *   field_ui_base_route = "content_entity_example.contact_settings"
 * )
 *
 * The 'links' above are defined by their path. For core to find the corresponding route, the route name must follow the correct pattern:
 *
 * entity.<entity-name>.<link-name> (replace dashes with underscores)
 * Example: 'entity.content_entity_example_contact.canonical'
 *
 * See routing file above for the corresponding implementation
 *
 * The 'Contact' class defines methods and fields for the contact entity.
 *
 * Being derived from the ContentEntityBase class, we can override the methods we want. In our case we want to provide access to the standard fields about creation and changed time stamps.
 *
 * Our interface (see ContactInterface) also exposes the EntityOwnerInterface. This allows us to provide methods for setting and providing ownership information.
 *
 * The most important part is the definitions of the field properties for this entity type. These are of the same type as fields added through the GUI, but they can be changed in code. In the definition we can define if the user with the rights privileges can influence the presentation (view, edit) of each field.
 *
 * @package Drupal\content_entity_example\Entity
 *
 * @ingroup content_entity_example
 */
class Contact extends ContentEntityBase implements ContactInterface {

  /**
   * @inheritDoc
   *
   * When a new entity instance is added, set the user_id entity reference to the current user as the creator of the instance.
   */
  public static function preCreate(EntityStorageInterface $storage, array &$values) {
    parent::preCreate($storage, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * @inheritDoc
   */
  public function getChangedTime() {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setChangedTime($timestamp) {
    $this->set('changed', $timestamp);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getChangedTimeAcrossTranslations() {
    $changed = $this->getUntranslated()->getChangedTime();
    foreach ($this->getTranslationLanguages(FALSE) as $language) {
      $translation_changed = $this->getTranslation($language->getId())->getChangedTime();
      $changed = max($translation_changed, $changed);
    }
    return $changed;
  }

  /**
   * @inheritDoc
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * @inheritDoc
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * @inheritDoc
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * @inheritDoc
   *
   * Define the field properties here.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['name'] = BaseFieldDefinition::create('string')
        ->setLabel(t('Name'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -6,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First Name'))
      ->setDescription(t('The first name of the contact entity.'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['gender'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Gender'))
      ->setDescription(t('The gender of the contact entity.'))
      ->setSettings([
        'allowed_values' => [
          'female' => 'female',
          'male' => 'male',
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'list_default',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'option_select',

      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language Code'))
      ->setDescription(t('The language code of Contact entity.'));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
