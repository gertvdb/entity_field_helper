<?php

namespace Drupal\Tests\entity_field_helper\Unit;

use Drupal\entity_field_helper\Plugin\EntityFieldHelper\ReferenceHelper;
use Drupal\Tests\UnitTestCase;
use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Plugin\DataType\EntityAdapter;
use Drupal\Core\Entity\Plugin\DataType\EntityReference;

/**
 * Class ReferenceHelperTest.
 *
 * @group entity_field_helper
 */
final class ReferenceHelperTest extends UnitTestCase {

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
   * @var \Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $item;

  /**
   * The entity data.
   *
   * @var \Drupal\Core\Entity\Plugin\DataType\EntityReference|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $data;

  /**
   * The entity adapter.
   *
   * @var \Drupal\Core\Entity\Plugin\DataType\EntityAdapter|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $entityAdapter;

  /**
   * The entity.
   *
   * @var \Drupal\Core\Entity\EntityInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $refEntity;

  /**
   * Setup.
   */
  protected function setUp() {
    $this->fieldName = 'field_entity';

    $this->refEntity = $this->getMockBuilder(EntityInterface::class)
      ->disableOriginalConstructor()
      ->setMethods([])
      ->getMockForAbstractClass();

    $this->entityAdapter = $this->getMockBuilder(EntityAdapter::class)
      ->disableOriginalConstructor()
      ->setMethods(['getValue'])
      ->getMock();

    $this->entityAdapter
      ->expects($this->any())
      ->method('getValue')
      ->willReturn($this->refEntity);

    $this->data = $this->getMockBuilder(EntityReference::class)
      ->disableOriginalConstructor()
      ->setMethods(['getTarget'])
      ->getMock();

    $this->data
      ->expects($this->any())
      ->method('getTarget')
      ->willReturn($this->entityAdapter);

    $this->item = $this->getMockBuilder(EntityReferenceItem::class)
      ->disableOriginalConstructor()
      ->setMethods(['get'])
      ->getMock();

    $this->item
      ->expects($this->any())
      ->method('get')
      ->with('entity')
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
    $referenceHelper = new ReferenceHelper([], 'reference', []);
    $this->assertEquals($referenceHelper->getValue($this->entity, $this->fieldName), $this->refEntity);
  }

  /**
   * Test the getValues method.
   */
  public function testGetValues() {
    $referenceHelper = new ReferenceHelper([], 'reference', []);
    $this->assertEquals(
      $referenceHelper->getValues($this->entity, $this->fieldName), [
        $this->refEntity,
        $this->refEntity,
        $this->refEntity,
      ]
    );
  }

}
