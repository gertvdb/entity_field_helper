<?php

namespace Drupal\entity_field_helper\Plugin\EntityFieldHelper;

use Drupal\entity_field_helper\Plugin\EntityFieldHelperBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\TypedData\Plugin\DataType\StringData;
use Drupal\filter\Plugin\DataType\FilterFormat;

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

    // Look for the value property.
    try {
      $value = $item->get('value');
      $format = $item->get('format');
      if ($value instanceof StringData && $format instanceof FilterFormat) {
        return [
          '#type' => 'processed_text',
          '#text' => $value->getCastedValue(),
          '#format' => $format->getCastedValue(),
        ];
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

      if (!$item || !$item->get('value')) {
        continue;
      }

      $values[] = [
        '#type' => 'processed_text',
        '#text' => $item->get('value'),
        '#format' => $item->get('format'),
      ];
    }

    return !empty($values) ? $values : NULL;
  }

}
