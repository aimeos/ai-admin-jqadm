<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Admin\JQAdm\Product\Bundle;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelper::view();
		$this->context = \TestHelper::context();

		$this->object = new \Aimeos\Admin\JQAdm\Product\Bundle\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelper::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testCreate()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'product' );

		$this->view->item = $manager->create();
		$result = $this->object->create();

		$this->assertStringContainsString( 'item-bundle', $result );
		$this->assertEmpty( $this->view->get( 'errors' ) );
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'product' );

		$this->view->item = $manager->find( 'U:BUNDLE', array( 'product' ) );
		$result = $this->object->copy();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( 'Cafe Noire Cappuccino', $result );
		$this->assertStringContainsString( 'Cafe Noire Expresso', $result );
	}


	public function testDelete()
	{
		$result = $this->object->delete();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'product' );

		$this->view->item = $manager->find( 'U:BUNDLE', array( 'product' ) );
		$result = $this->object->get();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( 'Cafe Noire Cappuccino', $result );
		$this->assertStringContainsString( 'Cafe Noire Expresso', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'product' );
		$this->view->item = $manager->find( 'U:BUNDLE' );

		$param = array(
			'site' => 'unittest',
			'bundle' => [[
					'product.lists.id' => '',
					'product.lists.refid' => $manager->find( 'CNE' )->getId(),
				]
			]
		);

		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->save();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );
		$this->assertEquals( 1, count( $this->view->item->getListItems( 'product' ) ) );
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Product\Bundle\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelper::getTemplatePaths() ) )
			->onlyMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$this->view = \TestHelper::view();
		$this->view->item = \Aimeos\MShop::create( $this->context, 'product' )->find( 'U:BUNDLE' );

		$object->setView( $this->view );

		$this->expectException( \RuntimeException::class );
		$object->save();
	}


	public function testSaveMShopException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Product\Bundle\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelper::getTemplatePaths() ) )
			->onlyMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$this->view = \TestHelper::view();
		$this->view->item = \Aimeos\MShop::create( $this->context, 'product' )->find( 'U:BUNDLE' );

		$object->setView( $this->view );

		$this->expectException( \Aimeos\MShop\Exception::class );
		$object->save();
	}


	public function testSearch()
	{
		$this->assertEmpty( $this->object->search() );
	}


	public function testGetSubClient()
	{
		$this->expectException( \LogicException::class );
		$this->object->getSubClient( 'unknown' );
	}
}
