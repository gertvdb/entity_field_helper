<?php

namespace Drupal\entity_field_helper\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines an interface for Entity Field Helper plugins.
 */
interface EntityFieldHelperInterface extends PluginInspectionInterface {

  /**
   * Get a single field value.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity to get the field value from.
   * @param string $field
   *   The field name.
   *
   * @return mixed|null
   *   A single value.
   */
  public function getValue(ContentEntityInterface $entity, $field);

  /**
   * Get multiple field values.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity to get the field values from.
   * @param string $field
   *   The field name.
   *
   * @return array|null
   *   An array of values
   */
  public function getValues(ContentEntityInterface $entity, $field);

}
