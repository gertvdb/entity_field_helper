<?php

namespace Drupal\entity_field_helper\Plugin\EntityFieldHelper;

use Drupal\file\FileInterface;

/**
 * Provides a General Entity Field Helper for File fields.
 *
 * @EntityFieldHelper(
 *   id = "file",
 *   name = "File",
 * )
 */
class FileHelper extends ReferenceHelper {

  /**
   * Get the relative file url for the given file.
   *
   * @param \Drupal\file\FileInterface $file
   *   The file entity.
   *
   * @return string
   *   Relative File Url with the specified settings.
   */
  public function getUrl(FileInterface $file) {
    $absolute_url = $this->getAbsoluteUrl($file);
    return $absolute_url ? file_url_transform_relative($absolute_url) : $absolute_url;
  }

  /**
   * Get the absolute file url for the given file.
   *
   * @param \Drupal\file\FileInterface $file
   *   The file entity.
   *
   * @return string
   *   Absolute File Url with the specified settings.
   */
  public function getAbsoluteUrl(FileInterface $file) {
    $uri = $file->getFileUri();
    if (!$uri) {
      return FALSE;
    }

    return file_create_url($uri);
  }

}
