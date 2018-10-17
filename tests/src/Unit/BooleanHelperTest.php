<?php

namespace Drupal\Tests\entity_field_helper\Unit\Preprocess;

use Drupal\Tests\UnitTestCase;
use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\Plugin\Field\FieldType\BooleanItem;
use Drupal\Core\TypedData\Plugin\DataType\BooleanData;
use Drupal\Core\Entity\ContentEntityBase;
/**
 * Class BooleanHelperTest.
 *
 * @group entity_field_helper
 */
final class BooleanHelperTest extends UnitTestCase {

  /**
   * Test a EntityPreprocessEvent.
   */
  public function testGetValue() {
    $field = 'field_boolean';

    $data = $this->getMockBuilder(BooleanData::class)
      ->disableOriginalConstructor()
      ->setMethods(['getCastedValue'])
      ->getMock();

    $data
      ->expects($this->any())
      ->method('getCastedValue')
      ->willReturn(TRUE);

    $item = $this->getMockBuilder(BooleanItem::class)
      ->disableOriginalConstructor()
      ->setMethods(['get'])
      ->getMock();

    $item
      ->expects($this->any())
      ->method('get')
      ->with('value')
      ->willReturn($item);

    $itemList = $this->getMockBuilder(FieldItemList::class)
      ->disableOriginalConstructor()
      ->setMethods(['first'])
      ->getMock();

    $itemList
      ->expects($this->any())
      ->method('first')
      ->willReturn($item);

    $entity = $this->getMockForAbstractClass(ContentEntityBase::class);
    $entity
      ->expects($this->any())
      ->method('hasField')
      ->with($field)
      ->willReturn(TRUE);
    $entity
      ->expects($this->any())
      ->method('get')
      ->with($field)
      ->willReturn(TRUE);

    /** @var \Drupal\entity_field_helper\Plugin\EntityFieldHelperManager $pluginManager */
    $pluginManager = \Drupal::service('plugin.manager.entity_field_helper');
    $booleanHelper = $pluginManager->booleanHelper();

    $this->assertEquals($booleanHelper->getValue($entity, $field), TRUE);
  }
}