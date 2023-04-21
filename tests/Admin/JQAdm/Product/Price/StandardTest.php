<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Admin\JQAdm\Product\Price;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelper::view();
		$this->context = \TestHelper::context();

		$this->object = new \Aimeos\Admin\JQAdm\Product\Price\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelper::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testBatch()
	{
		$this->view->items = map( \Aimeos\MShop::create( $this->context, 'product' )->find( 'CNC', ['price'] ) );

		$param = array(
			'site' => 'unittest',
			'price' => array(
				'price.taxrate' => '15',
				'rebatepercent' => 10,
				'valuepercent' => 10,
				'costspercent' => '5'
			),
		);

		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->batch();

		$prices = $this->view->items->first()->getRefItems( 'price' );

		$this->assertEquals( 2, count( $prices ) );
		$this->assertEquals( '15.00', $prices->getTaxrate()->first() );
		$this->assertEquals( '0.00', $prices->getRebate()->first() );
		$this->assertEquals( '660.00', $prices->getValue()->first() );
		$this->assertEquals( '31.50', $prices->getCosts()->first() );
	}


	public function testCreate()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'product' );

		$this->view->item = $manager->create();
		$result = $this->object->create();

		$this->assertStringContainsString( 'item-price', $result );
		$this->assertEmpty( $this->view->get( 'errors' ) );
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'product' );

		$this->view->item = $manager->find( 'CNC', array( 'price' ) );
		$result = $this->object->copy();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( '&quot;price.type&quot;:&quot;default&quot;', $result );
	}


	public function testDelete()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'product' );

		$this->view->item = $manager->create();
		$result = $this->object->delete();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'product' );

		$this->view->item = $manager->find( 'CNC', array( 'price' ) );
		$result = $this->object->get();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( '&quot;price.type&quot;:&quot;default&quot;', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'product' );
		$item = $manager->create();

		$param = array(
			'site' => 'unittest',
			'pricecustom' => 1,
			'price' => [[
				'price.id' => '',
				'price.value' => '10.00',
				'price.costs' => '1.00',
				'price.rebate' => '5.00',
				'price.taxrate' => '20.00',
				'price.quantity' => '2',
				'price.currencyid' => 'EUR',
				'price.type' => 'default',
				'product.lists.type' => 'default',
			]],
		);

		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );
		$this->view->item = $item;

		$result = $this->object->save();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );
		$this->assertEquals( 1, count( $item->getListItems( 'price' ) ) );
		$this->assertEquals( 1, count( $item->getListItems( 'attribute', 'custom', 'price' ) ) );

		foreach( $item->getListItems( 'price' ) as $listItem )
		{
			$this->assertEquals( 'price', $listItem->getDomain() );

			$refItem = $listItem->getRefItem();
			$this->assertEquals( 'EUR', $refItem->getCurrencyId() );
			$this->assertEquals( '2', $refItem->getQuantity() );
			$this->assertEquals( '10.00', $refItem->getValue() );
			$this->assertEquals( '1.00', $refItem->getCosts() );
			$this->assertEquals( '5.00', $refItem->getRebate() );
			$this->assertEquals( '20.00', $refItem->getTaxRate() );
		}
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Product\Price\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelper::getTemplatePaths() ) )
			->onlyMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$view = \TestHelper::view();
		$view->item = \Aimeos\MShop::create( $this->context, 'product' )->create();

		$object->setView( $view );

		$this->expectException( \RuntimeException::class );
		$object->save();
	}


	public function testSaveMShopException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Product\Price\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelper::getTemplatePaths() ) )
			->onlyMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$this->view = \TestHelper::view();
		$this->view->item = \Aimeos\MShop::create( $this->context, 'product' )->create();

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
