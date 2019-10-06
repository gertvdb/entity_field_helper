<?php

namespace Drupal\entity_field_helper\Plugin\EntityFieldHelper;

use Drupal\entity_field_helper\Plugin\EntityFieldHelperBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\datetime\DateTimeComputed;
use Drupal\Core\TypedData\Plugin\DataType\Timestamp;
use Drupal\Core\Datetime\Entity\DateFormat;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a Entity Field Helper for DateTime fields.
 *
 * @EntityFieldHelper(
 *   id = "datetime",
 *   name = "Datetime",
 * )
 */
class DateTimeHelper extends EntityFieldHelperBase implements ContainerFactoryPluginInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $pluginId, $pluginSefinition, DateFormatterInterface $dateFormatter, ConfigFactoryInterface $configFactory) {
    parent::__construct($configuration, $pluginId, $pluginSefinition);
    $this->dateFormatter = $dateFormatter;
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition) {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition,
      $container->get('date.formatter'),
      $container->get('config.factory')
    );
  }

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

    // Look for the computed date property.
    try {
      $computedDateTime = $item->get('date');
      if ($computedDateTime instanceof DateTimeComputed) {
        return $this->onlyReturnValidDateTime($computedDateTime->getValue());
      }
    }
    catch (\Exception $e) {
      // This is expected behavior since we want to continue
      // and try to use the value property.
    }

    // When no computed date property is present,
    // we try to compute our own DrupalDateTime object
    // from the value property. Since this will contain
    // a timestamp when the field that is passed holds a date.
    // So we try to up cast this to a DrupalDateTime object to
    // have a consistent return value.
    try {
      $value = $item->get('value');
      if ($value instanceof Timestamp) {
        return $this->onlyReturnValidDateTime($value->getDateTime());
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

      // Look for the computed date property.
      try {
        $computedDateTime = $item->get('date');
        if ($computedDateTime instanceof DateTimeComputed) {
          $values[] = $this->onlyReturnValidDateTime($computedDateTime->getValue());
          continue;
        }
      }
      catch (\Exception $e) {
        // This is expected behavior since we want to continue
        // and try to use the value property.
      }

      // When no computed date property is present,
      // we try to compute our own DrupalDateTime object
      // from the value property. Since this will contain
      // a timestamp when the field that is passed holds a date.
      // So we try to up cast this to a DrupalDateTime object to
      // have a consistent return value.
      try {
        $value = $item->get('value');
        if ($value instanceof Timestamp) {
          $values[] = $this->onlyReturnValidDateTime($value->getDateTime());
          continue;
        }
      }
      catch (\Exception $e) {
        continue;
      }
    }

    return !empty(array_filter($values)) ? array_filter($values) : NULL;
  }

  /**
   * {@inheritdoc}
   */
  private function onlyReturnValidDateTime(DrupalDateTime $dateTime) {
    if ($dateTime->hasErrors()) {
      return NULL;
    }
    return $dateTime;
  }

  /**
   * Format the specified date.
   *
   * @param \Drupal\Core\Datetime\DrupalDateTime $date
   *   The date to format.
   * @param string $format
   *   A valid php date format.
   * @param string $timezone
   *   An optional timezone.
   * @param string|null $langCode
   *   (optional) Language code to translate to. NULL (default) means to use
   *   the user interface language for the page.
   *
   * @return string|bool
   *   The formatted date.
   */
  public function formatDate(DrupalDateTime $date, $format, $timezone = NULL, $langCode = NULL) {

    $timestamp = $date->getTimestamp();

    $systemDateConfig = $this->configFactory->get('system.date');
    $timezone = $timezone ?: $systemDateConfig->get('timezone.default');

    return $this->dateFormatter->format($timestamp, 'custom', $format, $timezone, $langCode);
  }

  /**
   * Format the specified date.
   *
   * @param \Drupal\Core\Datetime\DrupalDateTime $date
   *   The date to format.
   * @param \Drupal\Core\Datetime\Entity\DateFormat $format
   *   A valid php date format.
   * @param string|null $timezone
   *   An optional timezone.
   * @param string|null $langCode
   *   (optional) Language code to translate to. NULL (default) means to use
   *   the user interface language for the page.
   *
   * @return string|bool
   *   The formatted date.
   */
  public function formatDateFromDateFormat(DrupalDateTime $date, DateFormat $format, $timezone = NULL, $langCode = NULL) {

    $timestamp = $date->getTimestamp();

    $systemDateConfig = $this->configFactory->get('system.date');
    $timezone = $timezone ?: $systemDateConfig->get('timezone.default');

    return $this->dateFormatter->format($timestamp, $format->id(), '', $timezone, $langCode);
  }

}
