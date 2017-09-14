<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */


namespace Aimeos\Admin\JQAdm\Service\Price;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp()
	{
		$this->view = \TestHelperJqadm::getView();
		$this->context = \TestHelperJqadm::getContext();
		$templatePaths = \TestHelperJqadm::getTemplatePaths();

		$this->object = new \Aimeos\Admin\JQAdm\Service\Price\Standard( $this->context, $templatePaths );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testCreate()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'service' );

		$this->view->item = $manager->createItem();
		$result = $this->object->create();

		$this->assertContains( 'item-price', $result );
		$this->assertNull( $this->view->get( 'errors' ) );
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'service' );

		$this->view->item = $manager->findItem( 'unitcode', ['price'] );
		$result = $this->object->copy();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertContains( 'value="EUR" selected="selected"', $result );
		$this->assertContains( 'value="1.99"', $result );
	}


	public function testDelete()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'service' );

		$this->view->item = $manager->createItem();
		$result = $this->object->delete();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertNull( $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'service' );

		$this->view->item = $manager->findItem( 'unitcode', ['price'] );
		$result = $this->object->get();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertContains( 'value="EUR" selected="selected"', $result );
		$this->assertContains( 'value="1.99"', $result );
	}


	public function testSave()
	{
		$typeManager = \Aimeos\MShop\Factory::createManager( $this->context, 'price/type' );
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'service' );

		$item = $manager->findItem( 'unitcode', ['price'] );
		$item->setCode( 'jqadm-test-price' );
		$item->setId( null );

		$item = $manager->saveItem( $item );


		$param = array(
			'site' => 'unittest',
			'price' => array(
				'service.lists.id' => array( '' ),
				'price.typeid' => array( $typeManager->findItem( 'default', [], 'service' )->getId() ),
				'price.currencyid' => array( 'EUR' ),
				'price.costs' => array( '1.00' ),
				'price.taxrate' => array( '20.00' ),
			),
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );
		$this->view->item = $item;

		$result = $this->object->save();

		$item = $manager->getItem( $item->getId(), array( 'price' ) );
		$manager->deleteItem( $item->getId() );

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertNull( $result );
		$this->assertEquals( 1, count( $item->getListItems() ) );

		foreach( $item->getListItems( 'price' ) as $listItem )
		{
			$this->assertEquals( 'price', $listItem->getDomain() );
			$this->assertEquals( 'default', $listItem->getType() );

			$refItem = $listItem->getRefItem();
			$this->assertEquals( 'default', $refItem->getType() );
			$this->assertEquals( 'EUR', $refItem->getCurrencyId() );
			$this->assertEquals( '1', $refItem->getQuantity() );
			$this->assertEquals( '1.00', $refItem->getCosts() );
			$this->assertEquals( '20.00', $refItem->getTaxRate() );
			$this->assertNotEmpty( $refItem->getLabel() );
		}
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Service\Price\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$view = \TestHelperJqadm::getView();
		$view->item = \Aimeos\MShop\Factory::createManager( $this->context, 'service' )->createItem();

		$object->setView( $view );

		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$object->save();
	}


	public function testSaveMShopException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Service\Price\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$this->view = \TestHelperJqadm::getView();
		$this->view->item = \Aimeos\MShop\Factory::createManager( $this->context, 'service' )->createItem();

		$object->setView( $this->view );

		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
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
