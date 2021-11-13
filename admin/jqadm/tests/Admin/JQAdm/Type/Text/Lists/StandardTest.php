<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */


namespace Aimeos\Admin\JQAdm\Type\Text\Lists;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelperJqadm::view();
		$this->context = \TestHelperJqadm::getContext();

		$this->object = new \Aimeos\Admin\JQAdm\Type\Text\Lists\Standard( $this->context );
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

		$this->assertStringContainsString( 'text/lists', $result );
		$this->assertEmpty( $this->view->get( 'errors' ) );
	}


	public function testCreateException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Type\Text\Lists\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClients' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->create();
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'text/lists/type' );

		$param = ['type' => 'unittest', 'id' => $manager->find( 'default', [], 'product' )->getId()];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->copy();

		$this->assertStringContainsString( 'default', $result );
	}


	public function testCopyException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Type\Text\Lists\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClients' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

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
		$manager = \Aimeos\MShop::create( $this->context, 'text/lists/type' );

		$param = ['type' => 'unittest', 'id' => $manager->find( 'default', [], 'product' )->getId()];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->get();

		$this->assertStringContainsString( 'default', $result );
	}


	public function testGetException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Type\Text\Lists\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClients' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->get();
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'text/lists/type' );

		$param = array(
			'type' => 'unittest',
			'item' => array(
				'text.lists.type.id' => '',
				'text.lists.type.status' => '1',
				'text.lists.type.domain' => 'product',
				'text.lists.type.code' => 'jqadm@test',
				'text.lists.type.label' => 'jqadm test',
			),
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->save();

		$manager->delete( $manager->find( 'jqadm@test', [], 'product' )->getId() );

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertNull( $result );
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Type\Text\Lists\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->save();
	}


	public function testSearch()
	{
		$param = array(
			'type' => 'unittest', 'locale' => 'de',
			'filter' => array(
				'key' => array( 0 => 'text.lists.type.code' ),
				'op' => array( 0 => '==' ),
				'val' => array( 0 => 'default' ),
			),
			'sort' => array( '-text.lists.type.id' ),
		);
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->search();

		$this->assertStringContainsString( '>default<', $result );
	}


	public function testSearchException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Type\Text\Lists\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'initCriteria' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'initCriteria' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->search();
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
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Type\Text\Lists\Standard::class )
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

		$manager = \Aimeos\MShop::create( $this->context, 'text/lists/type' );

		$param = ['site' => 'unittest', 'id' => $real ? $manager->find( 'default', [], 'product' )->getId() : -1];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, $param );
		$view->addHelper( 'param', $helper );

		$helper = new \Aimeos\MW\View\Helper\Config\Standard( $view, $this->context->getConfig() );
		$view->addHelper( 'config', $helper );

		$helper = new \Aimeos\MW\View\Helper\Access\Standard( $view, [] );
		$view->addHelper( 'access', $helper );

		return $view;
	}

}
