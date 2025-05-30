<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2025
 */


namespace Aimeos\Admin\JQAdm\Service\Media;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelper::view();
		$this->context = \TestHelper::context();

		$this->object = new \Aimeos\Admin\JQAdm\Service\Media\Standard( $this->context );
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
		$manager = \Aimeos\MShop::create( $this->context, 'service' );

		$this->view->item = $manager->create();
		$result = $this->object->create();

		$this->assertStringContainsString( 'item-media', $result );
		$this->assertEmpty( $this->view->get( 'errors' ) );
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'service' );

		$this->view->item = $manager->find( 'unitdeliverycode', ['media'] );
		$result = $this->object->copy();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( '&quot;media.preview&quot;:&quot;\/path\/to\/service.png', $result );
	}


	public function testDelete()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'service' );

		$this->view->item = $manager->create();
		$result = $this->object->delete();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'service' );

		$this->view->item = $manager->find( 'unitdeliverycode', ['media'] );
		$result = $this->object->get();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( '&quot;media.preview&quot;:&quot;\/path\/to\/service.png', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'service' );
		$this->view->item = $manager->create();


		$param = array(
			'site' => 'unittest',
			'media' => [[
				'media.id' => '',
				'media.type' => 'default',
				'media.languageid' => 'de',
				'media.label' => 'test',
				'service.lists.type' => 'default',
			]],
		);

		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$file = $this->getMockBuilder( \Psr\Http\Message\UploadedFileInterface::class )->getMock();
		$request = $this->getMockBuilder( \Psr\Http\Message\ServerRequestInterface::class )->getMock();
		$request->expects( $this->any() )->method( 'getUploadedFiles' )
			->willReturn( ['media' => [0 => ['file' => $file]]] );

		$helper = new \Aimeos\Base\View\Helper\Request\Standard( $this->view, $request, '127.0.0.1', 'test' );
		$this->view ->addHelper( 'request', $helper );


		$managerStub = $this->getMockBuilder( \Aimeos\MShop\Media\Manager\Standard::class )
			->setConstructorArgs( array( $this->context ) )
			->onlyMethods( ['upload', 'type'] )
			->getMock();

		\Aimeos\MShop::inject( \Aimeos\MShop\Media\Manager\Standard::class, $managerStub );

		$managerStub->method( 'type' )->willReturn( ['media'] );
		$managerStub->expects( $this->once() )->method( 'upload' )->willReturnArgument( 0 );


		$result = $this->object->save();


		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );
		$this->assertEquals( 1, count( $this->view->item->getListItems() ) );

		foreach( $this->view->item->getListItems( 'media' ) as $listItem )
		{
			$this->assertEquals( 'media', $listItem->getDomain() );

			$refItem = $listItem->getRefItem();
			$this->assertEquals( 'de', $refItem->getLanguageId() );
			$this->assertEquals( 'test', $refItem->getLabel() );
		}


		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, ['site' => 'unittest', 'media' => []] );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->save();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );
		$this->assertEquals( 0, count( $this->view->item->getListItems() ) );
	}


	public function testSaveException()
	{
		$object = $this->getClientMock( 'fromArray' );

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$this->expectException( \RuntimeException::class );
		$object->save();
	}


	public function testSaveMShopException()
	{
		$object = $this->getClientMock( 'fromArray' );

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

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


	public function getClientMock( $method )
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Service\Media\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelper::getTemplatePaths() ) )
			->onlyMethods( [$method] )
			->getMock();

		$view = \TestHelper::view();
		$view->item = \Aimeos\MShop::create( $this->context, 'service' )->create();

		$object->setAimeos( \TestHelper::getAimeos() );
		$object->setView( $view );

		return $object;
	}
}
