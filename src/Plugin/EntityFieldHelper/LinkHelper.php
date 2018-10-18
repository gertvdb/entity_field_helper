<?php

namespace Drupal\entity_field_helper\Plugin\EntityFieldHelper;

use Drupal\entity_field_helper\Plugin\EntityFieldHelperBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\link\Plugin\Field\FieldType\LinkItem;
use Drupal\Core\Link;

/**
 * Provides a General Entity Field Helper for Link fields.
 *
 * @EntityFieldHelper(
 *   id = "link",
 *   name = "Link",
 * )
 */
final class LinkHelper extends EntityFieldHelperBase {

  /**
   * {@inheritdoc}
   */
  public function getValue(ContentEntityInterface $entity, $field) {

    /** @var \Drupal\Core\Field\FieldItemListInterface $itemList */
    $itemList = $this->getFieldItemList($entity, $field);
    if (!$itemList) {
      return NULL;
    }

    /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem $item */
    $item = $itemList->first();
    if (!$item) {
      return NULL;
    }

    return $this->buildLink($item);
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

    /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem $item */
    foreach ($itemList->getIterator() as $item) {

      if (!$item) {
        continue;
      }

      // Look for the value property.
      try {
        $values[] = $this->buildLink($item);
      }
      catch (\Exception $e) {
        continue;
      }
    }

    return !empty(array_filter($values)) ? array_filter($values) : NULL;
  }

  /**
   * Build the link object.
   *
   * @param \Drupal\link\Plugin\Field\FieldType\LinkItem $item
   *   A link item.
   *
   * @return \Drupal\Core\Link|null
   *   A Link Object.
   */
  private function buildLink(LinkItem $item) {
    try {
      $title = $item->get('title')->getValue();
      $url = $item->getUrl();

      $attributes = $url->getOption('attributes');
      if (!isset($attributes['target'])) {
        $attributes['target'] = $item->isExternal() ? '_blank' : '_self';
      }
      return Link::fromTextAndUrl($title, $url);
    }
    catch (\Exception $e) {
      return NULL;
    }
  }

}
