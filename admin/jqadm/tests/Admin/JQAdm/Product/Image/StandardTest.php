<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
 */


namespace Aimeos\Admin\JQAdm\Product\Image;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp()
	{
		$this->view = \TestHelperJqadm::getView();
		$this->context = \TestHelperJqadm::getContext();

		$this->object = new \Aimeos\Admin\JQAdm\Product\Image\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testCreate()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );

		$this->view->item = $manager->createItem();
		$result = $this->object->create();

		$this->assertContains( 'item-image', $result );
		$this->assertNull( $this->view->get( 'errors' ) );
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );

		$this->view->item = $manager->findItem( 'CNC', ['media'] );
		$result = $this->object->copy();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertContains( '&quot;media.preview&quot;:&quot;prod_114x95\/194_prod_114x95.jpg&quot;', $result );
	}


	public function testDelete()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );

		$this->view->item = $manager->createItem();
		$result = $this->object->delete();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertNull( $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );

		$this->view->item = $manager->findItem( 'CNC', ['media'] );
		$result = $this->object->get();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertContains( '&quot;media.preview&quot;:&quot;prod_114x95\/194_prod_114x95.jpg&quot;', $result );
	}


	public function testSave()
	{
		$listTypeManager = \Aimeos\MShop\Factory::createManager( $this->context, 'product/lists/type' );
		$listTypeId = $listTypeManager->findItem( 'default', [], 'media' )->getId();

		$typeManager = \Aimeos\MShop\Factory::createManager( $this->context, 'media/type' );
		$typeId = $typeManager->findItem( 'default', [], 'product' )->getId();

		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );
		$this->view->item = $manager->createItem();


		$param = array(
			'site' => 'unittest',
			'image' => [[
				'media.id' => '',
				'media.typeid' => $typeId,
				'media.languageid' => 'de',
				'media.label' => 'test',
				'product.lists.type' => 'default',
				'product.lists.typeid' => $listTypeId
			]],
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$file = $this->getMockBuilder( '\Psr\Http\Message\UploadedFileInterface' )->getMock();
		$request = $this->getMockBuilder( '\Psr\Http\Message\ServerRequestInterface' )->getMock();
		$request->expects( $this->any() )->method( 'getUploadedFiles' )
			->will( $this->returnValue( ['image' => [0 => ['file' => $file] ] ] ) );

		$helper = new \Aimeos\MW\View\Helper\Request\Standard( $this->view, $request, '127.0.0.1', 'test' );
		$this->view ->addHelper( 'request', $helper );


		$name = 'AdminJQAdmProductImageSave';
		$this->context->getConfig()->set( 'controller/common/media/name', $name );

		$cntlStub = $this->getMockBuilder( '\\Aimeos\\Controller\\Common\\Media\\Standard' )
			->setConstructorArgs( array( $this->context ) )
			->setMethods( array( 'add' ) )
			->getMock();

		\Aimeos\Controller\Common\Media\Factory::injectController( '\\Aimeos\\Controller\\Common\\Media\\' . $name, $cntlStub );

		$cntlStub->expects( $this->once() )->method( 'add' );


		$result = $this->object->save();


		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertNull( $result );
		$this->assertEquals( 1, count( $this->view->item->getListItems() ) );

		foreach( $this->view->item->getListItems( 'media' ) as $listItem )
		{
			$this->assertEquals( 'media', $listItem->getDomain() );

			$refItem = $listItem->getRefItem();
			$this->assertEquals( 'de', $refItem->getLanguageId() );
			$this->assertEquals( 'test', $refItem->getLabel() );
		}


		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, ['site' => 'unittest', 'image' => []] );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->save();

		$this->assertNull( $this->view->get( 'errors' ) );
		$this->assertNull( $result );
		$this->assertEquals( 0, count( $this->view->item->getListItems() ) );
	}


	public function testSaveException()
	{
		$object = $this->getClientMock( 'fromArray' );

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$object->save();
	}


	public function testSaveMShopException()
	{
		$object = $this->getClientMock( 'fromArray' );

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

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


	public function getClientMock( $method )
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Product\Image\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( [$method] )
			->getMock();

		$view = \TestHelperJqadm::getView();
		$view->item = \Aimeos\MShop\Factory::createManager( $this->context, 'product' )->createItem();

		$object->setAimeos( \TestHelperJqadm::getAimeos() );
		$object->setView( $view );

		return $object;
	}
}
