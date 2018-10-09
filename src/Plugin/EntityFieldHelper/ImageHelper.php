<?php

namespace Drupal\entity_field_helper\Plugin\EntityFieldHelper;

use Drupal\file\FileInterface;
use Drupal\image\ImageStyleInterface;
use Drupal\Core\Image\ImageFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides a General Entity Field Helper for Image fields.
 *
 * @EntityFieldHelper(
 *   id = "image",
 *   name = "Image",
 * )
 */
final class ImageHelper extends FileHelper implements ContainerFactoryPluginInterface {

  /**
   * The image factory.
   *
   * @var \Drupal\Core\Image\ImageFactory
   */
  protected $imageFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ImageFactory $imageFactory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->imageFactory = $imageFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('image.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getImage(FileInterface $file) {
    return $this->imageFactory->get($file->getFileUri())->isValid() ? $this->imageFactory->get($file->getFileUri()) : NULL;
  }

  /**
   * Get the relative file url for the given file.
   *
   * @param \Drupal\file\FileInterface $file
   *   The file entity.
   * @param \Drupal\image\ImageStyleInterface $imageStyle
   *   Optional image style to use.
   *
   * @return string|null
   *   Relative File Url with the specified settings.
   */
  public function getImageStyleUrl(FileInterface $file, ImageStyleInterface $imageStyle) {
    $absolute_url = $this->getAbsoluteImageStyleUrl($file, $imageStyle);
    return $absolute_url ? file_url_transform_relative($absolute_url) : NULL;
  }

  /**
   * Get the absolute file url for the given file.
   *
   * @param \Drupal\file\FileInterface $file
   *   The file entity.
   * @param \Drupal\image\ImageStyleInterface $imageStyle
   *   Optional image style to use.
   *
   * @return string|null
   *   Absolute File Url with the specified settings.
   */
  public function getAbsoluteImageStyleUrl(FileInterface $file, ImageStyleInterface $imageStyle) {
    $uri = $file->getFileUri();
    if (!$uri) {
      return NULL;
    }

    return $imageStyle->buildUrl($uri);
  }

}
