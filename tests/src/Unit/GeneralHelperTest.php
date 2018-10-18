<?php

namespace Drupal\Tests\entity_field_helper\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\TypedData\Plugin\DataType\BooleanData;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\entity_field_helper\Plugin\EntityFieldHelper\GeneralHelper;
use Drupal\Core\TypedData\PrimitiveBase;

/**
 * Class GeneralHelperTest.
 *
 * @group entity_field_helper
 */
final class GeneralHelperTest extends UnitTestCase {

  /**
   * The value.
   *
   * @var string
   */
  protected $value;

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
    $this->value = 'General Field Value';
    $this->fieldName = 'field_general';

    $this->data = $this->getMockBuilder(PrimitiveBase::class)
      ->disableOriginalConstructor()
      ->setMethods(['getValue'])
      ->getMockForAbstractClass();

    $this->data
      ->expects($this->any())
      ->method('getValue')
      ->willReturn($this->value);

    $this->item = $this->getMockBuilder(FieldItemInterface::class)
      ->disableOriginalConstructor()
      ->setMethods(['get'])
      ->getMockForAbstractClass();

    $this->item
      ->expects($this->any())
      ->method('get')
      ->with('value')
      ->willReturn($this->data);

    $this->itemList = $this->getMockBuilder(FieldItemListInterface::class)
      ->disableOriginalConstructor()
      ->setMethods(['first', 'isEmpty', 'getIterator'])
      ->getMockForAbstractClass();

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
    $generalHelper = new GeneralHelper([], 'general', []);
    $this->assertEquals($generalHelper->getValue($this->entity, $this->fieldName), $this->value);
  }

  /**
   * Test the getValues method.
   */
  public function testGetValues() {
    $generalHelper = new GeneralHelper([], 'general', []);
    $this->assertEquals(
      $generalHelper->getValues($this->entity, $this->fieldName), [
        $this->value,
        $this->value,
        $this->value,
      ]
    );
  }

}
