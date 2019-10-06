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

    /** @var \Drupal\Core\Field\FieldItemListInterface $itemList */
    $itemList = $this->getFieldItemList($entity, $field);
    if (!$itemList) {
      return NULL;
    }

    /** @var \Drupal\Core\Field\FieldItemInterface $item */
    $item = $itemList->first();
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
      return NULL;
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getValues(ContentEntityInterface $entity, $field) {

    /** @var \Drupal\Core\Field\FieldItemListInterface $itemList */
    $itemList = $this->getFieldItemList($entity, $field);
    if (!$itemList) {
      return NULL;
    }

    $values = [];

    /** @var \Drupal\Core\Field\FieldItemInterface $item */
    foreach ($itemList->getIterator() as $item) {

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
        return NULL;
      }
    }

    return !empty(array_filter($values)) ? array_filter($values) : NULL;
  }

  /**
   * Get the field item list.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity to get the field values from.
   * @param string $field
   *   The field name.
   *
   * @return \Drupal\Core\Field\FieldItemListInterface|null
   *   The field item list for the field.
   */
  protected function getFieldItemList(ContentEntityInterface $entity, $field) {
    if (!$entity->hasField($field)) {
      return NULL;
    }

    try {
      $itemList = $entity->get($field);
      if ($itemList->isEmpty()) {
        return NULL;
      }
    }
    catch (\Exception $e) {
      return NULL;
    }

    return $itemList;
  }

}
