<?php

namespace Drupal\Tests\entity_field_helper\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\Plugin\Field\FieldType\IntegerItem;
use Drupal\Core\TypedData\Plugin\DataType\IntegerData;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\entity_field_helper\Plugin\EntityFieldHelper\NumberHelper;

/**
 * Class IntegerNumberHelperTest.
 *
 * @group entity_field_helper
 */
final class IntegerNumberHelperTest extends UnitTestCase {

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
   * @var \Drupal\Core\Field\Plugin\Field\FieldType\IntegerItem|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $item;

  /**
   * The boolean data.
   *
   * @var \Drupal\Core\TypedData\Plugin\DataType\IntegerData|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $data;

  /**
   * Setup.
   */
  protected function setUp() {
    $this->fieldName = 'field_integer';

    $this->data = $this->getMockBuilder(IntegerData::class)
      ->disableOriginalConstructor()
      ->setMethods(['getCastedValue'])
      ->getMock();

    $this->data
      ->expects($this->any())
      ->method('getCastedValue')
      ->willReturn(3);

    $this->item = $this->getMockBuilder(IntegerItem::class)
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
    $numberHelper = new NumberHelper([], 'number', []);
    $this->assertEquals($numberHelper->getValue($this->entity, $this->fieldName), 3);
  }

  /**
   * Test the getValues method.
   */
  public function testGetValues() {
    $numberHelper = new NumberHelper([], 'number', []);
    $this->assertEquals(
      $numberHelper->getValues($this->entity, $this->fieldName), [
        3,
        3,
        3,
      ]
    );
  }

}
