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
      $uri = $item->get('uri');
      $title = $item->get('title');
      $options = $item->get('options');

      if ($uri instanceof Uri && $title instanceof StringData && $options instanceof Map) {
        try {
          $url = Url::fromUri($uri->getCastedValue(), $options->getValue());
          $link = Link::fromTextAndUrl($title->getCastedValue(), $url);
          return $link;
        }
        catch (\Exception $e) {
        }
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
        $uri = $item->get('uri');
        $title = $item->get('title');
        $options = $item->get('options');

        if ($uri instanceof Uri && $title instanceof StringData && $options instanceof Map) {
          try {
            $url = Url::fromUri($uri->getCastedValue(), $options->getValue());
            $link = Link::fromTextAndUrl($title->getCastedValue(), $url);
            $values[] = $link;
          }
          catch (\Exception $e) {
          }
        }
      }
      catch (\Exception $e) {
      }
    }

    return !empty($values) ? $values : NULL;
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
