<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2025
 */


namespace Aimeos\Admin\JQAdm\Attribute;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelper::view();
		$request = $this->getMockBuilder( \Psr\Http\Message\ServerRequestInterface::class )->getMock();
		$request->expects( $this->any() )->method( 'getUploadedFiles' )->willReturn( [] );

		$helper = new \Aimeos\Base\View\Helper\Request\Standard( $this->view, $request, '127.0.0.1', 'test' );
		$this->view ->addHelper( 'request', $helper );

		$this->context = \TestHelper::context();

		$this->object = new \Aimeos\Admin\JQAdm\Attribute\Standard( $this->context );
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
		$param = array(
			'site' => 'unittest',
			'item' => array(
				'attribute.domain' => 'product',
				'attribute.type' => 'color',
				'attribute.position' => 1,
				'attribute.status' => -1,
			),
			'id' => [-1, -2]
		);

		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->batch();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertNull( $result );
	}


	public function testCreate()
	{
		$result = $this->object->create();

		$this->assertStringContainsString( 'attribute', $result );
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
		$manager = \Aimeos\MShop::create( $this->context, 'attribute' );

		$param = ['site' => 'unittest', 'id' => $manager->find( '30', [], 'product', 'length' )->getId()];
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->copy();

		$this->assertStringContainsString( '30_', $result );
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
		$manager = \Aimeos\MShop::create( $this->context, 'attribute' );

		$param = ['site' => 'unittest', 'id' => $manager->find( '30', [], 'product', 'length' )->getId()];
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->get();

		$this->assertStringContainsString( '"30"', $result );
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
		$manager = \Aimeos\MShop::create( $this->context, 'attribute' );

		$param = array(
			'site' => 'unittest',
			'item' => array(
				'attribute.id' => '',
				'attribute.domain' => 'product',
				'attribute.type' => 'color',
				'attribute.code' => 'test',
				'attribute.label' => 'test label',
				'attribute.position' => 1,
				'attribute.status' => -1,
			),
		);

		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->save();

		$manager->delete( $manager->find( 'test', [], 'product', 'color' )->getId() );

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
				'key' => array( 0 => 'attribute.code' ),
				'op' => array( 0 => '==' ),
				'val' => array( 0 => '30' ),
			),
			'sort' => array( 'attribute.label', '-attribute.id' ),
		);
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->search();

		$this->assertStringContainsString( '>30<', $result );
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
		$this->expectException( \LogicException::class );
		$this->object->getSubClient( '$unknown$' );
	}


	public function testGetSubClientUnknown()
	{
		$this->expectException( \LogicException::class );
		$this->object->getSubClient( 'unknown' );
	}


	public function getClientMock( $methods, $real = true )
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Attribute\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelper::getTemplatePaths() ) )
			->onlyMethods( (array) $methods )
			->getMock();

		$object->setAimeos( \TestHelper::getAimeos() );
		$object->setView( $this->getViewNoRender( $real ) );

		return $object;
	}


	protected function getViewNoRender( $real = true )
	{
		$view = $this->getMockBuilder( \Aimeos\Base\View\Standard::class )
			->setConstructorArgs( array( [] ) )
			->onlyMethods( array( 'render' ) )
			->getMock();

		$manager = \Aimeos\MShop::create( $this->context, 'attribute' );

		$param = ['site' => 'unittest', 'id' => $real ? $manager->find( '30', [], 'product', 'length' )->getId() : -1];
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $view, $param );
		$view->addHelper( 'param', $helper );

		$helper = new \Aimeos\Base\View\Helper\Config\Standard( $view, $this->context->config() );
		$view->addHelper( 'config', $helper );

		$helper = new \Aimeos\Base\View\Helper\Access\Standard( $view, [] );
		$view->addHelper( 'access', $helper );

		return $view;
	}
}
