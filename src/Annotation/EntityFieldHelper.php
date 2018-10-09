<?php

namespace Drupal\entity_field_helper\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Entity Field Helper item annotation object.
 *
 * @see \Drupal\entity_field_helper\Plugin\EntityFieldHelperManager
 * @see plugin_api
 *
 * @Annotation
 */
class EntityFieldHelper extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
