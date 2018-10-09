<?php

namespace Drupal\entity_field_helper\Plugin\EntityFieldHelper;

use Drupal\entity_field_helper\Plugin\EntityFieldHelperBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\link\Plugin\Field\FieldType\LinkItem;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\TypedData\Plugin\DataType\Uri;
use Drupal\Core\TypedData\Plugin\DataType\StringData;
use Drupal\Core\TypedData\Plugin\DataType\Map;

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

    /** @var \Drupal\Core\Field\FieldItemInterface $item */
    $item = $itemList->first();
    if (!$item) {
      return NULL;
    }

    // Look for the value property.
    try {
      $uri = $item->get('uri');
      $title = $item->get('title');
      $options = $item->get('options');

      if ($uri instanceof Uri && $title instanceof StringData && $options instanceof Map) {
        return $this->buildLink($uri, $title, $options);
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
        $uri = $item->get('uri');
        $title = $item->get('title');
        $options = $item->get('options');

        if ($uri instanceof Uri && $title instanceof StringData && $options instanceof Map) {
          $values[] = $this->buildLink($uri, $title, $options);
        }
      }
      catch (\Exception $e) {
        continue;
      }
    }

    return !empty(array_filter($values)) ? array_filter($values) : NULL;
  }

  /**
   * Get the title for the given link.
   *
   * @param \Drupal\Core\TypedData\Plugin\DataType\Uri $uri
   *   An uri.
   * @param \Drupal\Core\TypedData\Plugin\DataType\StringData $title
   *   A title.
   * @param \Drupal\Core\TypedData\Plugin\DataType\Map $options
   *   An option map.
   *
   * @return \Drupal\Core\Link|null
   *   A Link Object.
   */
  private function buildLink(Uri $uri, StringData $title, Map $options) {
    try {
      $url = Url::fromUri($uri->getCastedValue(), $options->getValue());
      return Link::fromTextAndUrl($title->getCastedValue(), $url);
    }
    catch (\Exception $e) {
      return NULL;
    }
  }

  /**
   * Get the title for the given link.
   *
   * @param \Drupal\link\Plugin\Field\FieldType\LinkItem $link
   *   A link object.
   *
   * @return string|null
   *   The passed link's title.
   */
  public function getTitle(LinkItem $link) {
    return $link->get('title') ?: NULL;
  }

  /**
   * Determine the target for the given link.
   *
   * @param \Drupal\link\Plugin\Field\FieldType\LinkItem $link
   *   A link object.
   *
   * @return string
   *   The target attribute value.
   */
  public function getTarget(LinkItem $link) {
    return $link->isExternal() ? '_blank' : '_self';
  }

}
