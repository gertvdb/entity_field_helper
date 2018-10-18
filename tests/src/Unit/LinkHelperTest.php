<?php

namespace Drupal\Tests\entity_field_helper\Unit;

use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Tests\UnitTestCase;
use Drupal\Core\Field\FieldItemList;
use Drupal\link\Plugin\Field\FieldType\LinkItem;
use Drupal\Core\TypedData\Plugin\DataType\StringData;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\entity_field_helper\Plugin\EntityFieldHelper\LinkHelper;

/**
 * Class LinkHelperTest.
 *
 * @group entity_field_helper
 */
final class LinkHelperTest extends UnitTestCase {

  /**
   * The link title.
   *
   * @var string
   */
  protected $title;

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
   * @var \Drupal\Core\Field\Plugin\Field\FieldType\FloatItem|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $item;

  /**
   * The boolean data.
   *
   * @var \Drupal\Core\TypedData\Plugin\DataType\FloatData|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $data;

  /**
   * The url.
   *
   * @var \Drupal\Core\Url|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $url;

  /**
   * Setup.
   */
  protected function setUp() {
    $this->fieldName = 'field_link';
    $this->title = 'My Link';

    $this->url = $this->getMockBuilder(Url::class)
      ->disableOriginalConstructor()
      ->setMethods(['getOption'])
      ->getMock();

    $this->url
      ->expects($this->any())
      ->method('getOption')
      ->with('attributes')
      ->willReturn([]);

    $this->data = $this->getMockBuilder(StringData::class)
      ->disableOriginalConstructor()
      ->setMethods(['getValue'])
      ->getMock();

    $this->data
      ->expects($this->any())
      ->method('getValue')
      ->willReturn($this->title);

    $this->item = $this->getMockBuilder(LinkItem::class)
      ->disableOriginalConstructor()
      ->setMethods(['get', 'getUrl', 'isExternal'])
      ->getMock();

    $this->item
      ->expects($this->any())
      ->method('get')
      ->with('title')
      ->willReturn($this->data);

    $this->item
      ->expects($this->any())
      ->method('getUrl')
      ->willReturn($this->url);

    $this->item
      ->expects($this->any())
      ->method('isExternal')
      ->willReturn(TRUE);

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
    $linkHelper = new LinkHelper([], 'link', []);
    $this->assertEquals($linkHelper->getValue($this->entity, $this->fieldName), Link::fromTextAndUrl($this->title, $this->url));
  }

  /**
   * Test the getValues method.
   */
  public function testGetValues() {
    $linkHelper = new LinkHelper([], 'link', []);
    $this->assertEquals(
      $linkHelper->getValues($this->entity, $this->fieldName), [
        Link::fromTextAndUrl($this->title, $this->url),
        Link::fromTextAndUrl($this->title, $this->url),
        Link::fromTextAndUrl($this->title, $this->url),
      ]
    );
  }

}
