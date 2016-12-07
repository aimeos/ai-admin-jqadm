<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */


namespace Aimeos\Admin\JQAdm\Product\Image;


class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp()
	{
		$this->view = \TestHelperJqadm::getView();
		$this->context = \TestHelperJqadm::getContext();
		$templatePaths = \TestHelperJqadm::getTemplatePaths();

		$this->object = new \Aimeos\Admin\JQAdm\Product\Image\Standard( $this->context, $templatePaths );
		$this->object->setView( $this->view );
	}


	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testCreate()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );

		$this->view->item = $manager->createItem();
		$result = $this->object->create();

		$this->assertContains( 'Images', $result );
		$this->assertNull( $this->view->get( 'errors' ) );
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );

		$this->view->item = $manager->findItem( 'CNC', array( 'media' ) );
		$result = $this->object->copy();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertContains( 'src="/prod_123x103/195_prod_123x103.jpg"', $result );
		$this->assertContains( 'src="/prod_266x221/198_prod_266x221.jpg"', $result );
	}


	public function testDelete()
	{
		$result = $this->object->delete();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertNull( $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );

		$this->view->item = $manager->findItem( 'CNC', array( 'media' ) );
		$result = $this->object->get();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertContains( 'src="/prod_123x103/195_prod_123x103.jpg"', $result );
		$this->assertContains( 'src="/prod_266x221/198_prod_266x221.jpg"', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );

		$item = $manager->findItem( 'CNC' );
		$item->setCode( 'jqadm-test-image' );
		$item->setId( null );

		$manager->saveItem( $item );


		$param = array(
			'image' => array(
				'product.lists.id' => array( '' ),
				'media.languageid' => array( 'de' ),
				'media.label' => array( 'test' ),
			),
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$file = $this->getMockBuilder( '\Psr\Http\Message\UploadedFileInterface' )->getMock();
		$request = $this->getMockBuilder( '\Psr\Http\Message\ServerRequestInterface' )->getMock();
		$request->expects( $this->any() )->method( 'getUploadedFiles' )
			->will( $this->returnValue( array( 'image' => array( 'files' => array( $file ) ) ) ) );

		$helper = new \Aimeos\MW\View\Helper\Request\Standard( $this->view, $request, '127.0.0.1', 'test' );
		$this->view ->addHelper( 'request', $helper );

		$this->view->item = $item;


		$name = 'AdminJQAdmProductImageSave';
		$this->context->getConfig()->set( 'controller/common/media/name', $name );

		$cntlStub = $this->getMockBuilder( '\\Aimeos\\Controller\\Common\\Media\\Standard' )
			->setConstructorArgs( array( $this->context ) )
			->setMethods( array( 'add' ) )
			->getMock();

		\Aimeos\Controller\Common\Media\Factory::injectController( '\\Aimeos\\Controller\\Common\\Media\\' . $name, $cntlStub );

		$cntlStub->expects( $this->once() )->method( 'add' );


		$mediaStub = $this->getMockBuilder( '\\Aimeos\\MShop\\Media\\Manager\\Standard' )
			->setConstructorArgs( array( $this->context ) )
			->setMethods( array( 'saveItem' ) )
			->getMock();

		\Aimeos\MShop\Factory::injectManager( $this->context, 'media', $mediaStub );

		$mediaStub->expects( $this->once() )->method( 'saveItem' );


		\Aimeos\MShop\Factory::setCache( true );

		$result = $this->object->save();

		\Aimeos\MShop\Factory::setCache( false );

		$item = $manager->getItem( $item->getId(), array( 'media' ) );
		$manager->deleteItem( $item->getId() );

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertNull( $result );
		$this->assertEquals( 1, count( $item->getListItems( 'media' ) ) );
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Product\Image\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'updateItems' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'updateItems' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( \TestHelperJqadm::getView() );

		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$object->save();
	}


	public function testSaveMShopException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Product\Image\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'updateItems' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'updateItems' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$object->setView( \TestHelperJqadm::getView() );

		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$object->save();
	}


	public function testSearch()
	{
		$this->assertNull( $this->object->search() );
	}


	public function testGetSubClient()
	{
		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$this->object->getSubClient( 'unknown' );
	}
}
