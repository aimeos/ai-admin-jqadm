<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */


namespace Aimeos\Admin\JQAdm\Product\Category\Decorator;


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

		$this->mock = $this->getMockBuilder( 'Aimeos\Admin\JQAdm\Product\Category\Standard' )
			->setMethods( array( 'save' ) )
			->disableOriginalConstructor()
			->getMock();

		$this->context = \TestHelperJqadm::getContext();
		$this->context->setCache( $this->cache );

		$this->object = new \Aimeos\Admin\JQAdm\Product\Category\Decorator\Cache( $this->mock, $this->context );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->mock, $this->context, $this->cache );
	}


	public function testSave()
	{
		$view = \TestHelperJqadm::view();
		$tags = array( 'catalog', 'catalog-1', 'catalog-2' );

		$param = ['site' => 'unittest', 'category' => [0 => ['catalog.id' => '1'], 1 => ['catalog.id' => '2']]];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, $param );
		$view->addHelper( 'param', $helper );

		$this->cache->expects( $this->once() )->method( 'deleteByTags' )->with( $this->equalTo( $tags ) );
		$this->mock->expects( $this->once() )->method( 'save' )->will( $this->returnValue( 'test' ) );

		$this->object->setView( $view );
		$result = $this->object->save();

		$this->assertEquals( 'test', $result );
	}
}
