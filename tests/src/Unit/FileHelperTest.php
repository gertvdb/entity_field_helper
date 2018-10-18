<?php

namespace Drupal\Tests\entity_field_helper\Unit;

use Drupal\Tests\entity_field_helper\Unit\Helpers\TestFileHelper;
use Drupal\Tests\UnitTestCase;
use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\file\FileInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Entity\Plugin\DataType\EntityAdapter;
use Drupal\Core\Entity\Plugin\DataType\EntityReference;

/**
 * Class FileHelperTest.
 *
 * @group entity_field_helper
 */
final class FileHelperTest extends UnitTestCase {

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
   * The file entity.
   *
   * @var \Drupal\file\FileInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $refEntity;

  /**
   * Setup.
   */
  protected function setUp() {
    $this->fieldName = 'field_entity';

    $this->refEntity = $this->getMockBuilder(FileInterface::class)
      ->disableOriginalConstructor()
      ->setMethods(['getFileUri'])
      ->getMock();

    $this->refEntity
      ->expects($this->any())
      ->method('getFileUri')
      ->willReturn(TestFileHelper::FILE_URI);

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
    $fileHelper = new TestFileHelper([], 'file', []);
    $this->assertEquals($fileHelper->getValue($this->entity, $this->fieldName), $this->refEntity);
  }

  /**
   * Test the getValues method.
   */
  public function testGetValues() {
    $fileHelper = new TestFileHelper([], 'file', []);
    $this->assertEquals(
      $fileHelper->getValues($this->entity, $this->fieldName), [
        $this->refEntity,
        $this->refEntity,
        $this->refEntity,
      ]
    );
  }

  /**
   * Test the getValue method.
   */
  public function testGetAbsoluteUrl() {
    $fileHelper = new TestFileHelper([], 'file', []);
    $this->assertEquals($fileHelper->getAbsoluteUrl($this->refEntity), TestFileHelper::FILE_ABSOLUTE_URL);
  }

  /**
   * Test the getValue method.
   */
  public function testGetUrl() {
    $fileHelper = new TestFileHelper([], 'file', []);
    $this->assertEquals($fileHelper->getUrl($this->refEntity), TestFileHelper::FILE_URL);
  }

}
