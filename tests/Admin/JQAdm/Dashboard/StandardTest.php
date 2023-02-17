<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Admin\JQAdm\Dashboard;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelper::view();
		$request = $this->getMockBuilder( \Psr\Http\Message\ServerRequestInterface::class )->getMock();
		$helper = new \Aimeos\Base\View\Helper\Request\Standard( $this->view, $request, '127.0.0.1', 'test' );
		$this->view ->addHelper( 'request', $helper );

		$this->context = \TestHelper::context();

		$this->object = new \Aimeos\Admin\JQAdm\Dashboard\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelper::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testSearch()
	{
		$result = $this->object->search();

		$this->assertStringContainsString( '<div class="dashboard', $result );
	}


	public function testSearchException()
	{
		$object = $this->getClientMock( 'getSubClients' );

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->search();
	}


	public function testGetSubClient()
	{
		$result = $this->object->getSubClient( 'order' );
		$this->assertInstanceOf( \Aimeos\Admin\JQAdm\Iface::class, $result );
	}


	public function getClientMock( $method )
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Dashboard\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelper::getTemplatePaths() ) )
			->onlyMethods( [$method] )
			->getMock();

		$object->setAimeos( \TestHelper::getAimeos() );
		$object->setView( $this->getViewNoRender() );

		return $object;
	}


	protected function getViewNoRender()
	{
		return $this->getMockBuilder( \Aimeos\Base\View\Standard::class )
			->setConstructorArgs( array( [] ) )
			->onlyMethods( ['render'] )->addMethods( ['config'] )
			->getMock();
	}
}
