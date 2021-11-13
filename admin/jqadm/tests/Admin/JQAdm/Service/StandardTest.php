<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */


namespace Aimeos\Admin\JQAdm\Service;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelperJqadm::view();
		$request = $this->getMockBuilder( \Psr\Http\Message\ServerRequestInterface::class )->getMock();
		$helper = new \Aimeos\MW\View\Helper\Request\Standard( $this->view, $request, '127.0.0.1', 'test' );
		$this->view ->addHelper( 'request', $helper );

		$this->context = \TestHelperJqadm::getContext();

		$this->object = new \Aimeos\Admin\JQAdm\Service\Standard( $this->context );
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
		$result = $this->object->create();

		$this->assertStringContainsString( 'service', $result );
		$this->assertEmpty( $this->view->get( 'errors' ) );
	}


	public function testCreateException()
	{
		$object = $this->getClientMock( 'getSubClients' );

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->create();
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'service' );

		$param = ['site' => 'unittest', 'id' => $manager->find( 'unitpaymentcode' )->getId()];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->copy();

		$this->assertStringContainsString( 'unitpaymentcode_', $result );
	}


	public function testCopyException()
	{
		$object = $this->getClientMock( 'getSubClients' );

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->copy();
	}


	public function testDelete()
	{
		$this->assertNull( $this->getClientMock( ['redirect'], false )->delete() );
	}


	public function testDeleteException()
	{
		$object = $this->getClientMock( ['getSubClients', 'search'] );

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );
		$object->expects( $this->once() )->method( 'search' );

		$object->delete();
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'service' );

		$param = ['site' => 'unittest', 'id' => $manager->find( 'unitpaymentcode' )->getId()];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->get();

		$this->assertStringContainsString( 'unitpaymentlabel', $result );
	}


	public function testGetException()
	{
		$object = $this->getClientMock( 'getSubClients' );

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->get();
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'service' );

		$param = array(
			'site' => 'unittest',
			'item' => array(
				'service.id' => '',
				'service.type' => 'delivery',
				'service.code' => 'test',
				'service.provider' => 'Standard',
				'service.label' => 'test label',
				'service.datestart' => null,
				'service.dateend' => null,
				'service.position' => '2',
				'config' => array(
					'key' => array( 0 => 'test key' ),
					'val' => array( 0 => 'test value' ),
				),
			),
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->save();

		$manager->delete( $manager->find( 'test' )->getId() );

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertNull( $result );
	}


	public function testSaveException()
	{
		$object = $this->getClientMock( 'fromArray' );

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->save();
	}


	public function testSearch()
	{
		$param = array(
			'site' => 'unittest', 'locale' => 'de',
			'filter' => array(
				'key' => array( 0 => 'service.code' ),
				'op' => array( 0 => '==' ),
				'val' => array( 0 => 'unitpaymentcode' ),
			),
			'sort' => array( 'service.label', '-service.id' ),
		);
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->search();

		$this->assertStringContainsString( '>unitpaymentlabel<', $result );
	}


	public function testSearchException()
	{
		$object = $this->getClientMock( 'initCriteria' );

		$object->expects( $this->once() )->method( 'initCriteria' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->search();
	}


	public function testGetSubClient()
	{
		$result = $this->object->getSubClient( 'media' );
		$this->assertInstanceOf( \Aimeos\Admin\JQAdm\Iface::class, $result );
	}


	public function testGetSubClientInvalid()
	{
		$this->expectException( \Aimeos\Admin\JQAdm\Exception::class );
		$this->object->getSubClient( '$unknown$' );
	}


	public function testGetSubClientUnknown()
	{
		$this->expectException( \Aimeos\Admin\JQAdm\Exception::class );
		$this->object->getSubClient( 'unknown' );
	}


	public function getClientMock( $methods, $real = true )
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Service\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( (array) $methods )
			->getMock();

		$object->setAimeos( \TestHelperJqadm::getAimeos() );
		$object->setView( $this->getViewNoRender( $real ) );

		return $object;
	}


	protected function getViewNoRender( $real = true )
	{
		$view = $this->getMockBuilder( \Aimeos\MW\View\Standard::class )
			->setConstructorArgs( array( [] ) )
			->setMethods( array( 'render' ) )
			->getMock();

		$manager = \Aimeos\MShop::create( $this->context, 'service' );

		$param = ['site' => 'unittest', 'id' => $real ? $manager->find( 'unitpaymentcode' )->getId() : -1];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, $param );
		$view->addHelper( 'param', $helper );

		$helper = new \Aimeos\MW\View\Helper\Config\Standard( $view, $this->context->getConfig() );
		$view->addHelper( 'config', $helper );

		$helper = new \Aimeos\MW\View\Helper\Access\Standard( $view, [] );
		$view->addHelper( 'access', $helper );

		return $view;
	}
}
