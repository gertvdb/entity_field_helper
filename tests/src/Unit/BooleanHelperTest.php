<?php

namespace Drupal\Tests\entity_field_helper\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\Plugin\Field\FieldType\BooleanItem;
use Drupal\Core\TypedData\Plugin\DataType\BooleanData;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\entity_field_helper\Plugin\EntityFieldHelper\BooleanHelper;

/**
 * Class BooleanHelperTest.
 *
 * @group entity_field_helper
 */
final class BooleanHelperTest extends UnitTestCase {

  /**
   * The field name.
   *
   * @var string
   */
  protected $fieldName;

  /**
   * The entity.
   *
   * @var \Drupal\Core\Entity\ContentEntityBase|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $entity;

  /**
   * The item list.
   *
   * @var \Drupal\Core\Field\FieldItemList|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $itemList;

  /**
   * The item.
   *
   * @var \Drupal\Core\Field\Plugin\Field\FieldType\BooleanItem|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $item;

  /**
   * The boolean data.
   *
   * @var \Drupal\Core\TypedData\Plugin\DataType\BooleanData|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $data;

  /**
   * Setup.
   */
  protected function setUp() {
    $this->fieldName = 'field_boolean';

    $this->data = $this->getMockBuilder(BooleanData::class)
      ->disableOriginalConstructor()
      ->setMethods(['getCastedValue'])
      ->getMock();

    $this->data
      ->expects($this->any())
      ->method('getCastedValue')
      ->willReturn(TRUE);

    $this->item = $this->getMockBuilder(BooleanItem::class)
      ->disableOriginalConstructor()
      ->setMethods(['get'])
      ->getMock();

    $this->item
      ->expects($this->any())
      ->method('get')
      ->with('value')
      ->willReturn($this->data);

    $this->itemList = $this->getMockBuilder(FieldItemList::class)
      ->disableOriginalConstructor()
      ->setMethods(['first', 'isEmpty', 'getIterator'])
      ->getMock();

    $this->itemList
      ->expects($this->any())
      ->method('isEmpty')
      ->willReturn(FALSE);

    $this->itemList
      ->expects($this->any())
      ->method('first')
      ->willReturn($this->item);

    $this->itemList
      ->expects($this->any())
      ->method('getIterator')
      ->willReturn([$this->item, $this->item, $this->item]);

    $this->entity = $this->getMockBuilder(ContentEntityBase::class)
      ->disableOriginalConstructor()
      ->setMethods(['hasField', 'get'])
      ->getMockForAbstractClass();

    $this->entity
      ->expects($this->any())
      ->method('hasField')
      ->with($this->fieldName)
      ->willReturn(TRUE);

    $this->entity
      ->expects($this->any())
      ->method('get')
      ->with($this->fieldName)
      ->willReturn($this->itemList);
  }

  /**
   * Test the getValue method.
   */
  public function testGetValue() {
    $booleanHelper = new BooleanHelper([], 'boolean', []);
    $this->assertEquals($booleanHelper->getValue($this->entity, $this->fieldName), TRUE);
  }

  /**
   * Test the getValues method.
   */
  public function testGetValues() {
    $booleanHelper = new BooleanHelper([], 'boolean', []);
    $this->assertEquals(
      $booleanHelper->getValues($this->entity, $this->fieldName), [
        TRUE,
        TRUE,
        TRUE,
      ]
    );
  }

}
