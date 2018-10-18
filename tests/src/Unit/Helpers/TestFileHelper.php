<?php

namespace Drupal\Tests\entity_field_helper\Unit\Helpers;

use Drupal\entity_field_helper\Plugin\EntityFieldHelper\FileHelper;

/**
 * Class TestFileHelper.
 *
 * @package Drupal\Tests\entity_field_helper\Unit\Helpers
 */
class TestFileHelper extends FileHelper {

  const FILE_URI = 'public://2018-10/file.jpg';
  const FILE_ABSOLUTE_URL = 'http://my-website.be/sites/default/files/2018-10/file.jpg';
  const FILE_URL = '/sites/default/files/2018-10/file.jpg';

  /**
   * Override fileCreateUrl().
   *
   * Override parent::fileCreateUrl()
   * Do NOT call parent::fileCreateUrl() inside this function
   * or you will receive the original error.
   */
  protected function fileCreateUrl($uri) {
    return TestFileHelper::FILE_ABSOLUTE_URL;
  }

  /**
   * Override fileUrlTransformRelative().
   *
   * Override parent::fileUrlTransformRelative()
   * Do NOT call parent::fileUrlTransformRelative() inside this function
   * or you will receive the original error.
   */
  protected function fileUrlTransformRelative($fileUrl) {
    return TestFileHelper::FILE_URL;
  }

}
