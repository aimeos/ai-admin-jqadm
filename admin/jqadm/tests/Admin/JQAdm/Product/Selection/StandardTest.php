<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2021
 */


namespace Aimeos\Admin\JQAdm\Product\Selection;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelperJqadm::view();
		$this->context = \TestHelperJqadm::getContext();

		$this->object = new \Aimeos\Admin\JQAdm\Product\Selection\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown() : void
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
					'stock.stocklevel' => 10,
					'attr' => array(
						array(
							'product.lists.id' => '456',
							'product.lists.siteid' => '2',
							'product.lists.refid' => '789',
							'attribute.label' => 'test attribute',
						)
					),
				)
			),
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$manager = \Aimeos\MShop::create( $this->context, 'product' );

		$this->view->item = $manager->create();
		$result = $this->object->create();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( 'item-selection', $result );
		$this->assertStringContainsString( '&quot;product.code&quot;:&quot;testprod&quot;', $result );
		$this->assertStringContainsString( '&quot;attribute.label&quot;:&quot;test attribute&quot;', $result );
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'product' );

		$this->view->item = $manager->find( 'U:TEST', ['attribute', 'product'] );
		$result = $this->object->copy();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( '&quot;product.code&quot;:&quot;U:TESTSUB01_', $result );
		$this->assertStringContainsString( '&quot;product.code&quot;:&quot;U:TESTSUB02_', $result );
		$this->assertStringContainsString( '&quot;product.code&quot;:&quot;U:TESTSUB03_', $result );
		$this->assertStringContainsString( '&quot;product.code&quot;:&quot;U:TESTSUB04_', $result );
		$this->assertStringContainsString( '&quot;product.code&quot;:&quot;U:TESTSUB05_', $result );
		$this->assertStringContainsString( '&quot;attribute.label&quot;:&quot;product\/length\/30&quot;', $result );
		$this->assertStringContainsString( '&quot;attribute.label&quot;:&quot;product\/length\/32&quot;', $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'product' );
		$this->view->item = $manager->find( 'U:TEST', ['attribute', 'product'] );

		$result = $this->object->get();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( '&quot;product.code&quot;:&quot;U:TESTSUB01&quot;', $result );
		$this->assertStringContainsString( '&quot;product.code&quot;:&quot;U:TESTSUB02&quot;', $result );
		$this->assertStringContainsString( '&quot;product.code&quot;:&quot;U:TESTSUB03&quot;', $result );
		$this->assertStringContainsString( '&quot;product.code&quot;:&quot;U:TESTSUB04&quot;', $result );
		$this->assertStringContainsString( '&quot;product.code&quot;:&quot;U:TESTSUB05&quot;', $result );
		$this->assertStringContainsString( '&quot;attribute.label&quot;:&quot;product\/length\/30&quot;', $result );
		$this->assertStringContainsString( '&quot;attribute.label&quot;:&quot;product\/length\/32&quot;', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'product' );
		$this->view->item = $manager->find( 'U:TEST' );

		$param = array(
			'site' => 'unittest',
			'selection' => array(
				array(
					'product.lists.id' => '',
					'product.id' => '',
					'product.code' => 'testprod',
					'product.label' => 'test product',
					'product.status' => '1',
					'stock.stocklevel' => 20,
					'attr' => array(
						array(
							'product.lists.id' => '',
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


		$manager = \Aimeos\MShop::create( $this->context, 'product' );
		$product = $manager->find( 'testprod', ['stock'] );
		$manager->delete( $product );

		$manager = \Aimeos\MShop::create( $this->context, 'stock' );
		$stocks = $manager->search( $manager->filter()->add( ['stock.productid' => $product->getId()] ) );
		$manager->delete( $stocks->toArray() );


		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );

		$variants = $this->view->item->getListItems( 'product' );
		$this->assertEquals( 1, count( $variants ) );

		$refItem = $variants->first()->getRefItem();
		$this->assertEquals( 'testprod', $refItem->getCode() );
		$this->assertEquals( 'test product', $refItem->getLabel() );
		$this->assertEquals( 1, $refItem->getStatus() );

		$attributes = $refItem->getListItems( 'attribute' );
		$this->assertEquals( 1, count( $attributes ) );
		$this->assertEquals( '789', $attributes->first()->getRefId() );
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Product\Selection\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$this->view = \TestHelperJqadm::view();
		$this->view->item = \Aimeos\MShop::create( $this->context, 'product' )->find( 'U:TEST' );

		$object->setView( $this->view );

		$this->expectException( \RuntimeException::class );
		$object->save();
	}


	public function testSaveMShopException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Product\Selection\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$this->view = \TestHelperJqadm::view();
		$this->view->item = \Aimeos\MShop::create( $this->context, 'product' )->find( 'U:TEST' );

		$object->setView( $this->view );

		$this->expectException( \Aimeos\MShop\Exception::class );
		$object->save();
	}


	public function testGetSubClient()
	{
		$this->expectException( \Aimeos\Admin\JQAdm\Exception::class );
		$this->object->getSubClient( 'unknown' );
	}
}
