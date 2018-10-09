<?php

namespace Drupal\entity_field_helper;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EntityFieldHelper.
 *
 * @package Drupal\entity_field_helper
 */
class EntityFieldHelper implements ContainerInterface {

  /**
   * Retrieves the container.
   *
   * @return \Symfony\Component\DependencyInjection\ContainerInterface
   *   The currently active global container.
   */
  public static function getContainer() {
    return \Drupal::getContainer();
  }

  /**
   * Retrieves the general helper.
   *
   * @return \Drupal\entity_field_helper\Plugin\EntityFieldHelper\GeneralHelper
   *   The general helper.
   */
  public static function generalHelper() {
    return static::getContainer()->get('plugin.manager.entity_field_helper')->createInstance('general');
  }

}
