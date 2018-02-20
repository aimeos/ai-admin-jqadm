<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018
 */


namespace Aimeos\Admin\JQAdm\Attribute\Image\Property;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp()
	{
		$this->view = \TestHelperJqadm::getView();
		$this->context = \TestHelperJqadm::getContext();

		$this->object = new \Aimeos\Admin\JQAdm\Attribute\Image\Property\Standard( $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testCreate()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'attribute' );

		$this->view->item = $manager->createItem();
		$result = $this->object->create();

		$this->assertContains( 'Media properties', $result );
		$this->assertNull( $this->view->get( 'errors' ) );
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'attribute' );

		$this->view->item = $manager->findItem( 'xs', ['media'], 'product', 'size' );
		$result = $this->object->copy();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertContains( 'item-image-property', $result );
	}


	public function testDelete()
	{
		$result = $this->object->delete();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertNull( $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'attribute' );

		$this->view->item = $manager->findItem( 'xs', ['media'], 'product', 'size' );
		$result = $this->object->get();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertContains( 'item-image-property', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'attribute' );
		$mediaManager = \Aimeos\MShop\Factory::createManager( $this->context, 'media' );
		$typeManager = \Aimeos\MShop\Factory::createManager( $this->context, 'media/property/type' );
		$listManager = \Aimeos\MShop\Factory::createManager( $this->context, 'attribute/lists' );

		$item = $manager->findItem( 'xs', ['media'], 'product', 'size' );
		$item->setCode( 'jqadm-test-image-property' );
		$item->setId( null );

		$item = $manager->saveItem( $item );
		$listItems = $item->getListItems( 'media' );

		if( ( $listItem = reset( $listItems ) ) === false ) {
			throw \Exception( 'No media list item found' );
		}

		if( ( $mediaItem = $listItem->getRefItem() ) === null ) {
			throw \Exception( 'No media item found' );
		}

		$mediaItem->setId( null );
		$mediaItem = $mediaManager->saveItem( $mediaItem );

		$listItem->setId( null );
		$listItem->setParentId( $item->getId() );
		$listItem->setRefId( $mediaItem->getId() );
		$listItem = $listManager->saveItem( $listItem, false );

		$item = $manager->findItem( 'jqadm-test-image-property', ['media'], 'product', 'size' );


		$param = array(
			'site' => 'unittest',
			'image' => array(
				'property' => array(
					0 => array(
						'media.property.id' => array( '' ),
						'media.property.typeid' => array( $typeManager->findItem( 'size', [], 'media' )->getId() ),
					)
				),
			),
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );
		$this->view->item = $item;

		$result = $this->object->save();

		$item = $manager->getItem( $item->getId(), ['media'] );
		$manager->deleteItem( $item->getId() );

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertNull( $result );

		$mediaItems = $item->getRefItems( 'media' );
		$this->assertEquals( 1, count( $mediaItems ) );
		$this->assertEquals( 1, count( reset( $mediaItems )->getPropertyItems() ) );
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Attribute\Image\Property\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$this->view = \TestHelperJqadm::getView();
		$this->view->item = \Aimeos\MShop\Factory::createManager( $this->context, 'attribute' )->createItem();

		$object->setView( $this->view );

		$this->setExpectedException( '\RuntimeException' );
		$object->save();
	}


	public function testSearch()
	{
		$this->assertNull( $this->object->search() );
	}


	public function testGetSubClient()
	{
		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$this->object->getSubClient( 'unknown' );
	}
}
