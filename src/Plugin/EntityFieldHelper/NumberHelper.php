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
        return $value->getCastedValue();
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
          $values[] = $value->getCastedValue();
        }
      }
      catch (\Exception $e) {
        continue;
      }
    }

    return !empty($values) ? $values : NULL;
  }

}
