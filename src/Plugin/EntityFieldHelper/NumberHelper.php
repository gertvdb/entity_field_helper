<?php

namespace Drupal\entity_field_helper\Plugin\EntityFieldHelper;

use Drupal\entity_field_helper\Plugin\EntityFieldHelperBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\TypedData\PrimitiveBase;

/**
 * Provides a General Entity Field Helper for Number fields.
 *
 * @EntityFieldHelper(
 *   id = "number",
 *   name = "Number",
 * )
 */
final class NumberHelper extends EntityFieldHelperBase {

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
        return $value->getCastedValue();
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
          $values[] = $value->getCastedValue();
        }
      }
      catch (\Exception $e) {
      }
    }

    return !empty($values) ? $values : NULL;
  }

}