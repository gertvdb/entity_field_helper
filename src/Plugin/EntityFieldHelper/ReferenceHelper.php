<?php

namespace Drupal\entity_field_helper\Plugin\EntityFieldHelper;

use Drupal\entity_field_helper\Plugin\EntityFieldHelperBase;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides a General Entity Field Helper for Reference fields.
 *
 * @EntityFieldHelper(
 *   id = "reference",
 *   name = "Reference",
 * )
 */
class ReferenceHelper extends EntityFieldHelperBase {

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

    /** @var \Drupal\Core\Entity\Plugin\DataType\EntityReference $entityReference */
    $entityReference = $item->get('entity');
    if (!$entityReference) {
      return NULL;
    }

    /** @var \Drupal\Core\Entity\Plugin\DataType\EntityAdapter $entityAdapter */
    $entityAdapter = $entityReference->getTarget();
    if (!$entityAdapter) {
      return NULL;
    }

    return $entityAdapter->getValue() ?: NULL;
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

      // Referenced items can return NULL if the item
      // that is referenced is deleted but the entity containing
      // the reference is not yet updated. So we need to check
      // if the entity exists.
      /** @var \Drupal\Core\Entity\Plugin\DataType\EntityReference $entityReference */
      $entityReference = $item->get('entity');
      if (!$entityReference) {
        continue;
      }

      /** @var \Drupal\Core\Entity\Plugin\DataType\EntityAdapter $entityAdapter */
      $entityAdapter = $entityReference->getTarget();
      if (!$entityAdapter) {
        return NULL;
      }

      $values[] = $entityAdapter->getValue();
    }

    return !empty(array_filter($values)) ? array_filter($values) : NULL;
  }

}
