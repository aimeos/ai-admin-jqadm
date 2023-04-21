<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


namespace Aimeos\Admin\JQAdm\Order;


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

		$this->object = new \Aimeos\Admin\JQAdm\Order\Standard( $this->context );
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
		$result = $this->object->create();

		$this->assertStringContainsString( 'order', $result );
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
		$param = ['site' => 'unittest', 'id' => $this->getOrderBaseItem()->getId()];
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->copy();

		$this->assertStringContainsString( 'This is another comment.', $result );
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
		$this->assertEmpty( $this->object->delete() );
	}


	public function testGet()
	{
		$param = ['site' => 'unittest', 'id' => $this->getOrderBaseItem()->getId()];
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->get();

		$this->assertStringContainsString( 'This is another comment.', $result );
	}


	public function testGetException()
	{
		$object = $this->getClientMock( 'getSubClients' );

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->get();
	}


	public function testExport()
	{
		$param = ['site' => 'unittest',
			'filter' => [
				'key' => [0 => 'order.id'],
				'op' => [0 => '=='],
				'val' => [0 => '1'],
			],
			'sort' => ['-order.ctime', 'order.id'],
		];
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->export();

		$mq = $this->context->queue( 'mq-admin', 'order-export' );

		$msg = $mq->get();
		$this->assertNotNull( $msg );
		$mq->del( $msg );

		$expected = '{"sitecode":"unittest","filter":{"&&":[{"==":{"order.id":"1"}}]},"sort":["-order.ctime","order.id"]}';
		$this->assertEquals( $expected, $msg->getBody() );
	}


	public function testExportException()
	{
		$object = $this->getClientMock( 'storeFilter' );

		$object->expects( $this->atLeastOnce() )->method( 'storeFilter' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->export();
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'order' );
		$this->view->item = $manager->create();

		$param = array(
			'site' => 'unittest',
			'item' => array(
				'order.comment' => 'jqadm test comment',
			),
		);

		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->save();

		$manager->delete( $this->getOrderBaseItem( 'jqadm test comment' )->getId() );

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
				'key' => array( 0 => 'order.channel' ),
				'op' => array( 0 => '==' ),
				'val' => array( 0 => 'web' ),
			),
			'sort' => array( '-order.id' ),
		);
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->search();

		$this->assertStringContainsString( '>Unittest<', $result );
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
		$result = $this->object->getSubClient( 'transaction' );
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


	public function getClientMock( $method )
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Order\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelper::getTemplatePaths() ) )
			->onlyMethods( [$method] )
			->getMock();

		$object->setAimeos( \TestHelper::getAimeos() );
		$object->setView( $this->getViewNoRender() );

		return $object;
	}


	protected function getViewNoRender()
	{
		$view = $this->getMockBuilder( \Aimeos\Base\View\Standard::class )
			->setConstructorArgs( array( [] ) )
			->onlyMethods( ['render'] )->addMethods( ['config'] )
			->getMock();

		$trans = new \Aimeos\Base\Translation\None( 'de_DE' );
		$helper = new \Aimeos\Base\View\Helper\Translate\Standard( $view, $trans );
		$view->addHelper( 'translate', $helper );

		$param = ['site' => 'unittest', 'id' => $this->getOrderBaseItem()->getId()];
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $view, $param );
		$view->addHelper( 'param', $helper );

		$helper = new \Aimeos\Base\View\Helper\Config\Standard( $view, $this->context->config() );
		$view->addHelper( 'config', $helper );

		$helper = new \Aimeos\Base\View\Helper\Access\Standard( $view, [] );
		$view->addHelper( 'access', $helper );

		return $view;
	}


	protected function getOrderBaseItem( $comment = 'This is another comment.' )
	{
		$manager = \Aimeos\MShop::create( $this->context, 'order' );
		$search = $manager->filter()->add( 'order.comment', '==', $comment );

		return $manager->search( $search )->first( new \RuntimeException( 'No order item found' ) );
	}
}
