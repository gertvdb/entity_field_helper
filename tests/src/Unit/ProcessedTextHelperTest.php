<?php

namespace Drupal\Tests\entity_field_helper\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\Core\Field\FieldItemList;
use Drupal\text\Plugin\Field\FieldType\TextLongItem;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\entity_field_helper\Plugin\EntityFieldHelper\ProcessedTextHelper;
use Drupal\text\TextProcessed;

/**
 * Class BooleanHelperTest.
 *
 * @group entity_field_helper
 */
final class ProcessedTextHelperTest extends UnitTestCase {

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
   * @var \Drupal\text\Plugin\Field\FieldType\TextLongItem|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $item;

  /**
   * The string data.
   *
   * @var \Drupal\Core\TypedData\Plugin\DataType\StringData|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $data;

  /**
   * The format data.
   *
   * @var \Drupal\filter\Plugin\DataType\FilterFormat|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $formatData;

  /**
   * Setup.
   */
  protected function setUp() {
    $this->fieldName = 'field_text';

    $this->data = $this->getMockBuilder(TextProcessed::class)
      ->disableOriginalConstructor()
      ->setMethods(['getValue'])
      ->getMock();

    $this->data
      ->expects($this->any())
      ->method('getValue')
      ->willReturn('<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. <strong>Pellentesque consequat</strong> quis ipsum non feugiat. Praesent sed urna in orci duis finibus rutrum pulvinar.</p>');

    $this->item = $this->getMockBuilder(TextLongItem::class)
      ->disableOriginalConstructor()
      ->setMethods(['get'])
      ->getMock();

    $this->item
      ->expects($this->any())
      ->method('get')
      ->with('processed')
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
    $processHelper = new ProcessedTextHelper([], 'processed_text', []);
    $this->assertEquals($processHelper->getValue($this->entity, $this->fieldName), '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. <strong>Pellentesque consequat</strong> quis ipsum non feugiat. Praesent sed urna in orci duis finibus rutrum pulvinar.</p>');
  }

  /**
   * Test the getValues method.
   */
  public function testGetValues() {
    $processHelper = new ProcessedTextHelper([], 'processed_text', []);
    $this->assertEquals(
      $processHelper->getValues($this->entity, $this->fieldName), [
        '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. <strong>Pellentesque consequat</strong> quis ipsum non feugiat. Praesent sed urna in orci duis finibus rutrum pulvinar.</p>',
        '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. <strong>Pellentesque consequat</strong> quis ipsum non feugiat. Praesent sed urna in orci duis finibus rutrum pulvinar.</p>',
        '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. <strong>Pellentesque consequat</strong> quis ipsum non feugiat. Praesent sed urna in orci duis finibus rutrum pulvinar.</p>',
      ]
    );
  }

}
