<?php

namespace Drupal\entity_field_helper\Plugin\EntityFieldHelper;

use Drupal\entity_field_helper\Plugin\EntityFieldHelperBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\datetime\DateTimeComputed;
use Drupal\Core\TypedData\Plugin\DataType\Timestamp;

/**
 * Provides a Entity Field Helper for DateTime fields.
 *
 * @EntityFieldHelper(
 *   id = "datetime",
 *   name = "Datetime",
 * )
 */
class DateTimeHelper extends EntityFieldHelperBase implements ContainerFactoryPluginInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, DateFormatterInterface $dateFormatter) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->dateFormatter = $dateFormatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getValue(ContentEntityInterface $entity, $field) {
    /** @var \Drupal\Core\Field\FieldItemListInterface $item_list */
    $item_list = $this->getFieldItemList($entity, $field);
    if (!$item_list) {
      return NULL;
    }

    /** @var \Drupal\Core\Field\FieldItemInterface $item */
    $item = $item_list->first();
    if (!$item) {
      return NULL;
    }

    // Look for the computed date property.
    try {
      $computedDateTime = $item->get('date');
      if ($computedDateTime instanceof DateTimeComputed) {
        return $this->onlyReturnValidDateTime($computedDateTime->getValue());
      }
    }
    catch (\Exception $e) {
    }

    // When no computed date property is present,
    // we try to compute our own DrupalDateTime object
    // from the value property. Since this will contain
    // a timestamp when the field that is passed holds a date.
    // So we try to up cast this to a DrupalDateTime object to
    // have a consistent return value.
    try {
      $value = $item->get('value');
      if ($value instanceof Timestamp) {
        return $this->onlyReturnValidDateTime($value->getDateTime());
      }
    }
    catch (\Exception $e) {
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getValues(ContentEntityInterface $entity, $field) {

    /** @var \Drupal\Core\Field\FieldItemListInterface $item_list */
    $item_list = $this->getFieldItemList($entity, $field);
    if (!$item_list) {
      return NULL;
    }

    $values = [];

    /** @var \Drupal\Core\Field\FieldItemInterface $item */
    foreach ($item_list->getIterator() as $item) {

      if (!$item) {
        continue;
      }

      // Look for the computed date property.
      try {
        $computedDateTime = $item->get('date');
        if ($computedDateTime instanceof DateTimeComputed) {
          $values[] = $this->onlyReturnValidDateTime($computedDateTime->getValue());
          continue;
        }
      }
      catch (\Exception $e) {
      }

      // When no computed date property is present,
      // we try to compute our own DrupalDateTime object
      // from the value property. Since this will contain
      // a timestamp when the field that is passed holds a date.
      // So we try to up cast this to a DrupalDateTime object to
      // have a consistent return value.
      try {
        $value = $item->get('value');
        if ($value instanceof Timestamp) {
          $values[] = $this->onlyReturnValidDateTime($value->getDateTime());
          continue;
        }
      }
      catch (\Exception $e) {
      }
    }

    return !empty(array_filter($values)) ? array_filter($values) : NULL;
  }

  /**
   * {@inheritdoc}
   */
  private function onlyReturnValidDateTime(DrupalDateTime $dateTime) {
    if ($dateTime->hasErrors()) {
      return NULL;
    }
    return $dateTime;
  }

}
