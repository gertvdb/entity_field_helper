<?php

namespace Drupal\entity_field_helper\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\TypedData\PrimitiveBase;

/**
 * Base class for Entity Field Helper plugins.
 */
abstract class EntityFieldHelperBase extends PluginBase implements EntityFieldHelperInterface {

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

    // Look for the value property.
    try {
      $value = $item->get('value');
      if ($value instanceof PrimitiveBase) {
        return $value->getValue();
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

      // Look for the value property.
      try {
        $value = $item->get('value');
        if ($value instanceof PrimitiveBase) {
          $values[] = $value->getValue();
        }
      }
      catch (\Exception $e) {
      }
    }

    return !empty($values) ? $values : NULL;
  }

  /**
   * {@inheritdoc}
   */
  protected function getFieldItemList(ContentEntityInterface $entity, $field) {
    if (!$entity->hasField($field)) {
      return NULL;
    }

    $item_list = $entity->get($field);
    if ($item_list->isEmpty()) {
      return NULL;
    }

    return $item_list;
  }

}
