<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


namespace Aimeos\Admin\JQAdm\Catalog\Product;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelper::view();
		$this->context = \TestHelper::context();

		$this->object = new \Aimeos\Admin\JQAdm\Catalog\Product\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelper::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'catalog' );
		$this->view->item = $manager->find( 'cafe' );

		$result = $this->object->copy();

		$this->assertStringContainsString( 'item-product', $result );
	}


	public function testCreate()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'catalog' );
		$this->view->item = $manager->find( 'cafe' );

		$result = $this->object->create();

		$this->assertStringContainsString( 'item-product', $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'catalog' );
		$this->view->item = $manager->find( 'cafe' );

		$result = $this->object->get();

		$this->assertStringContainsString( 'item-product', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'product' );
		$prodId = $manager->save( $manager->create()->setCode( 'jqadm_catalog_test' ) )->getId();
		$item = \Aimeos\MShop::create( $this->context, 'catalog' )->find( 'cafe' );

		$param = array(
			'site' => 'unittest',
			'product' => array(
				'product.lists.id' => [0 => ''],
				'product.lists.parentid' => [0 => $prodId],
				'product.lists.status' => [0 => 1],
				'product.lists.refid' => [0 => 'test'],
				'product.lists.type' => [0 => 'promotion'],
				'product.lists.datestart' => [0 => '2000-01-01 00:00:00'],
				'product.lists.dateend' => [0 => '2100-01-01 00:00:00'],
				'product.lists.config' => [0 => '{"test": "value"}'],
			),
		);

		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );
		$this->view->item = $item;

		$result = $this->object->save();

		$product = $manager->get( $prodId, ['catalog'] );
		$manager->delete( $prodId );

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );
		$this->assertEquals( 1, count( $product->getListItems() ) );

		foreach( $product->getListItems( 'catalog' ) as $listItem )
		{
			$this->assertEquals( $prodId, $listItem->getParentId() );
			$this->assertEquals( 'promotion', $listItem->getType() );
			$this->assertEquals( 'catalog', $listItem->getDomain() );
			$this->assertEquals( '2000-01-01 00:00:00', $listItem->getDateStart() );
			$this->assertEquals( '2100-01-01 00:00:00', $listItem->getDateEnd() );
			$this->assertEquals( ['test' => 'value'], $listItem->getConfig() );
			$this->assertEquals( $item->getId(), $listItem->getRefId() );
			$this->assertEquals( 1, $listItem->getStatus() );
		}
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Catalog\Product\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelper::getTemplatePaths() ) )
			->onlyMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$this->expectException( \RuntimeException::class );
		$object->save();
	}


	public function testGetSubClient()
	{
		$this->expectException( \LogicException::class );
		$this->object->getSubClient( 'unknown' );
	}


	protected function getViewNoRender()
	{
		$view = $this->getMockBuilder( \Aimeos\Base\View\Standard::class )
			->setConstructorArgs( array( [] ) )
			->onlyMethods( array( 'render' ) )
			->getMock();

		$manager = \Aimeos\MShop::create( $this->context, 'catalog' );
		$view->item = $manager->find( 'cafe' );

		return $view;
	}
}
