<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


class CacheTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $mock;
	private $cache;


	protected function setUp() : void
	{
		$this->cache = $this->getMockBuilder( 'Aimeos\MW\Cache\None' )
			->setMethods( array( 'deleteByTags' ) )
			->disableOriginalConstructor()
			->getMock();

		$this->mock = $this->getMockBuilder( 'Aimeos\Admin\JQAdm\Product\Standard' )
			->setMethods( array( 'delete', 'save' ) )
			->disableOriginalConstructor()
			->getMock();

		$this->context = \TestHelperJqadm::getContext();
		$this->context->setCache( $this->cache );

		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Cache( $this->mock, $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( \TestHelperJqadm::view() );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->mock, $this->context, $this->cache );
	}


	public function testDelete()
	{
		$view = \TestHelperJqadm::view();
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, array( 'id' => 1 ) );
		$view->addHelper( 'param', $helper );

		$tags = array( 'product', 'product-1' );
		$this->cache->expects( $this->once() )->method( 'deleteByTags' )->with( $this->equalTo( $tags ) );
		$this->mock->expects( $this->once() )->method( 'delete' )->will( $this->returnValue( 'test' ) );

		$this->object->setView( $view );
		$result = $this->object->delete();

		$this->assertEquals( 'test', $result );
	}


	public function testSave()
	{
		$item = \Aimeos\MShop::create( $this->context, 'product' )->find( 'CNC' );

		$tags = array( 'product', 'product-' . $item->getId() );
		$view = \TestHelperJqadm::view();
		$view->item = $item;

		$this->cache->expects( $this->once() )->method( 'deleteByTags' )->with( $this->equalTo( $tags ) );
		$this->mock->expects( $this->once() )->method( 'save' )->will( $this->returnValue( 'test' ) );

		$this->object->setView( $view );
		$result = $this->object->save();

		$this->assertEquals( 'test', $result );
	}
}
