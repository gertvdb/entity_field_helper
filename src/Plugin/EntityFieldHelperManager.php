<?php

namespace Drupal\entity_field_helper\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Entity Field Helper plugin manager.
 */
class EntityFieldHelperManager extends DefaultPluginManager {

  /**
   * Constructs a new EntityFieldHelperManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/EntityFieldHelper', $namespaces, $module_handler, 'Drupal\entity_field_helper\Plugin\EntityFieldHelperInterface', 'Drupal\entity_field_helper\Annotation\EntityFieldHelper');

    $this->alterInfo('entity_field_helper_entity_field_helper_info');
    $this->setCacheBackend($cache_backend, 'entity_field_helper_entity_field_helper_plugins');
  }

  /**
   * Retrieves the General helper.
   *
   * Provide a shortcut to load the General helper.
   *
   * @return \Drupal\entity_field_helper\Plugin\EntityFieldHelper\GeneralHelper
   *   The General helper.
   */
  public function generalHelper() {
    return $this->createInstance('general');
  }

  /**
   * Retrieves the Processed Text helper.
   *
   * Provide a shortcut to load the Processed Text helper.
   *
   * @return \Drupal\entity_field_helper\Plugin\EntityFieldHelper\ProcessedTextHelper
   *   The Processed Text helper.
   */
  public function processedTextHelper() {
    return $this->createInstance('processed_text');
  }

  /**
   * Retrieves the Number helper.
   *
   * Provide a shortcut to load the Number helper.
   *
   * @return \Drupal\entity_field_helper\Plugin\EntityFieldHelper\NumberHelper
   *   The Number helper.
   */
  public function numberHelper() {
    return $this->createInstance('number');
  }

  /**
   * Retrieves the Reference helper.
   *
   * Provide a shortcut to load the Reference helper.
   *
   * @return \Drupal\entity_field_helper\Plugin\EntityFieldHelper\ReferenceHelper
   *   The Reference helper.
   */
  public function referenceHelper() {
    return $this->createInstance('reference');
  }

  /**
   * Retrieves the File helper.
   *
   * Provide a shortcut to load the File helper.
   *
   * @return \Drupal\entity_field_helper\Plugin\EntityFieldHelper\FileHelper
   *   The File helper.
   */
  public function fileHelper() {
    return $this->createInstance('file');
  }

  /**
   * Retrieves the Image helper.
   *
   * Provide a shortcut to load the Image helper.
   *
   * @return \Drupal\entity_field_helper\Plugin\EntityFieldHelper\ImageHelper
   *   The Image helper.
   */
  public function imageHelper() {
    return $this->createInstance('image');
  }

  /**
   * Retrieves the Link helper.
   *
   * Provide a shortcut to load the Link helper.
   *
   * @return \Drupal\entity_field_helper\Plugin\EntityFieldHelper\LinkHelper
   *   The Link helper.
   */
  public function linkHelper() {
    return $this->createInstance('link');
  }

  /**
   * Retrieves the DateTime helper.
   *
   * Provide a shortcut to load the DateTime helper.
   *
   * @return \Drupal\entity_field_helper\Plugin\EntityFieldHelper\DateTimeHelper
   *   The DateTime helper.
   */
  public function dateTimeHelper() {
    return $this->createInstance('datetime');
  }

  /**
   * Retrieves the Boolean helper.
   *
   * Provide a shortcut to load the Boolean helper.
   *
   * @return \Drupal\entity_field_helper\Plugin\EntityFieldHelper\BooleanHelper
   *   The DateTime helper.
   */
  public function booleanHelper() {
    return $this->createInstance('boolean');
  }

}
