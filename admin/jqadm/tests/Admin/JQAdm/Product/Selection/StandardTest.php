<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
 */


namespace Aimeos\Admin\JQAdm\Product\Selection;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp()
	{
		$this->view = \TestHelperJqadm::getView();
		$this->context = \TestHelperJqadm::getContext();

		$this->object = new \Aimeos\Admin\JQAdm\Product\Selection\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testCreate()
	{
		$param = array(
			'site' => 'unittest',
			'selection' => array(
				array(
					'product.id' => '123',
					'product.siteid' => '1',
					'product.code' => 'testprod',
					'product.label' => 'test product',
					'attr' => array(
						array(
							'product.lists.id' => '456',
							'product.lists.siteid' => '2',
							'product.lists.refid' => '789',
							'attribute.label' => 'test attribute',
						)
					)
				)
			),
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );

		$this->view->item = $manager->createItem();
		$result = $this->object->create();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertContains( 'item-selection', $result );
		$this->assertContains( '&quot;product.code&quot;:&quot;testprod&quot;', $result );
		$this->assertContains( '&quot;attribute.label&quot;:&quot;test attribute&quot;', $result );
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );

		$this->view->item = $manager->findItem( 'U:TEST', ['attribute', 'product'] );
		$result = $this->object->copy();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertContains( '&quot;product.code&quot;:&quot;U:TESTSUB01&quot;', $result );
		$this->assertContains( '&quot;product.code&quot;:&quot;U:TESTSUB02&quot;', $result );
		$this->assertContains( '&quot;product.code&quot;:&quot;U:TESTSUB03&quot;', $result );
		$this->assertContains( '&quot;product.code&quot;:&quot;U:TESTSUB04&quot;', $result );
		$this->assertContains( '&quot;product.code&quot;:&quot;U:TESTSUB05&quot;', $result );
		$this->assertContains( '&quot;attribute.label&quot;:&quot;product\/length\/30&quot;', $result );
		$this->assertContains( '&quot;attribute.label&quot;:&quot;product\/length\/32&quot;', $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );
		$this->view->item = $manager->findItem( 'U:TEST', ['attribute', 'product'] );

		$result = $this->object->get();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertContains( '&quot;product.code&quot;:&quot;U:TESTSUB01&quot;', $result );
		$this->assertContains( '&quot;product.code&quot;:&quot;U:TESTSUB02&quot;', $result );
		$this->assertContains( '&quot;product.code&quot;:&quot;U:TESTSUB03&quot;', $result );
		$this->assertContains( '&quot;product.code&quot;:&quot;U:TESTSUB04&quot;', $result );
		$this->assertContains( '&quot;product.code&quot;:&quot;U:TESTSUB05&quot;', $result );
		$this->assertContains( '&quot;attribute.label&quot;:&quot;product\/length\/30&quot;', $result );
		$this->assertContains( '&quot;attribute.label&quot;:&quot;product\/length\/32&quot;', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );
		$this->view->item = $manager->findItem( 'U:TEST' );

		$param = array(
			'site' => 'unittest',
			'selection' => array(
				array(
					'product.lists.id' => '',
					'product.id' => '123',
					'product.code' => 'testprod',
					'product.label' => 'test product',
					'product.status' => '1',
					'attr' => array(
						array(
							'product.lists.id' => '456',
							'product.lists.refid' => '789',
							'attribute.label' => 'test attribute',
						)
					)
				),
			),
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->save();


		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertNull( $result );

		$variants = $this->view->item->getListItems( 'product' );
		$this->assertEquals( 1, count( $variants ) );

		$refItem = reset( $variants )->getRefItem();
		$this->assertEquals( '123', reset( $variants )->getRefId() );
		$this->assertEquals( '123', $refItem->getId() );
		$this->assertEquals( 'testprod', $refItem->getCode() );
		$this->assertEquals( 'test product', $refItem->getLabel() );
		$this->assertEquals( 1, $refItem->getStatus() );

		$attributes = $refItem->getListItems( 'attribute' );
		$this->assertEquals( 1, count( $attributes ) );
		$this->assertEquals( '456', reset( $attributes )->getId() );
		$this->assertEquals( '789', reset( $attributes )->getRefId() );
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Product\Selection\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$this->view = \TestHelperJqadm::getView();
		$this->view->item = \Aimeos\MShop\Factory::createManager( $this->context, 'product' )->findItem( 'U:TEST' );

		$object->setView( $this->view );

		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$object->save();
	}


	public function testSaveMShopException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Product\Selection\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$this->view = \TestHelperJqadm::getView();
		$this->view->item = \Aimeos\MShop\Factory::createManager( $this->context, 'product' )->findItem( 'U:TEST' );

		$object->setView( $this->view );

		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$object->save();
	}


	public function testGetSubClient()
	{
		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$this->object->getSubClient( 'unknown' );
	}
}
