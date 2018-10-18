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
  public function __construct(array $configuration, $pluginId, $pluginDefinition, ImageFactory $imageFactory) {
    parent::__construct($configuration, $pluginId, $pluginDefinition);
    $this->imageFactory = $imageFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition) {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition,
      $container->get('image.factory')
    );
  }

  /**
   * Get the Image Object.
   *
   * @param \Drupal\file\FileInterface $file
   *   A file object.
   *
   * @return \Drupal\Core\Image\ImageInterface|null
   *   An image object or null.
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
    $absoluteUrl = $this->getAbsoluteImageStyleUrl($file, $imageStyle);
    return $absoluteUrl ? $this->fileUrlTransformRelative($absoluteUrl) : NULL;
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
