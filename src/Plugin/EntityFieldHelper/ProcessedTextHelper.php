<?php

namespace Drupal\entity_field_helper\Plugin\EntityFieldHelper;

use Drupal\entity_field_helper\Plugin\EntityFieldHelperBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\text\TextProcessed;

/**
 * Provides a Entity Field Helper for Processed Text.
 *
 * @EntityFieldHelper(
 *   id = "processed_text",
 *   name = "Processed Text",
 * )
 */
final class ProcessedTextHelper extends EntityFieldHelperBase {

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

    // Look for the computed processed processed.
    try {
      $computedText = $item->get('processed');
      if ($computedText instanceof TextProcessed) {
        return $computedText->getValue();
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
      return FALSE;
    }

    $values = [];
    /** @var \Drupal\Core\Field\FieldItemInterface $item */
    foreach ($itemList->getIterator() as $item) {

      if (!$item) {
        continue;
      }

      // Look for the computed processed processed.
      try {
        $computedText = $item->get('processed');
        if ($computedText instanceof TextProcessed) {
          $values[] = $computedText->getValue();
          continue;
        }
      }
      catch (\Exception $e) {
        return NULL;
      }
    }

    return !empty($values) ? $values : NULL;
  }

}
