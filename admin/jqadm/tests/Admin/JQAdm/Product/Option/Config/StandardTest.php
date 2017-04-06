<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */


namespace Aimeos\Admin\JQAdm\Product\Option\Config;


class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp()
	{
		$this->view = \TestHelperJqadm::getView();
		$this->context = \TestHelperJqadm::getContext();
		$templatePaths = \TestHelperJqadm::getTemplatePaths();

		$this->object = new \Aimeos\Admin\JQAdm\Product\Option\Config\Standard( $this->context, $templatePaths );
		$this->object->setView( $this->view );
	}


	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testCreate()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );

		$this->view->item = $manager->createItem();
		$result = $this->object->create();

		$this->assertContains( 'Config', $result );
		$this->assertNull( $this->view->get( 'errors' ) );
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );

		$this->view->item = $manager->findItem( 'CNE', array( 'attribute' ) );
		$result = $this->object->copy();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertContains( 'white', $result );
	}


	public function testDelete()
	{
		$result = $this->object->delete();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertNull( $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );

		$this->view->item = $manager->findItem( 'CNE', array( 'attribute' ) );
		$result = $this->object->get();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertContains( 'white', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );
		$attrManager = \Aimeos\MShop\Factory::createManager( $this->context, 'attribute' );

		$item = $manager->findItem( 'CNE' );
		$item->setCode( 'jqadm-test-config' );
		$item->setId( null );

		$manager->saveItem( $item );


		$param = array(
			'option' => array(
				'config' => array(
					'product.lists.id' => array( '' ),
					'product.lists.refid' => array( $attrManager->findItem( 'white', [], 'product', 'color' )->getId() ),
				),
			),
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );
		$this->view->item = $item;

		$result = $this->object->save();

		$item = $manager->getItem( $item->getId(), array( 'attribute' ) );
		$manager->deleteItem( $item->getId() );

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertNull( $result );
		$this->assertEquals( 1, count( $item->getListItems( 'attribute' ) ) );
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Product\Option\Config\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'updateItems' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'updateItems' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( \TestHelperJqadm::getView() );

		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$object->save();
	}


	public function testSaveMShopException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Product\Option\Config\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'updateItems' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'updateItems' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$object->setView( \TestHelperJqadm::getView() );

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
