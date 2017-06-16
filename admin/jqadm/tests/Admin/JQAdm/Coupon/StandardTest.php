<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */


namespace Aimeos\Admin\JQAdm\Coupon;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp()
	{
		$this->view = \TestHelperJqadm::getView();
		$request = $this->getMockBuilder( '\Psr\Http\Message\ServerRequestInterface' )->getMock();
		$helper = new \Aimeos\MW\View\Helper\Request\Standard( $this->view, $request, '127.0.0.1', 'test' );
		$this->view ->addHelper( 'request', $helper );

		$this->context = \TestHelperJqadm::getContext();
		$templatePaths = \TestHelperJqadm::getTemplatePaths();

		$this->object = new \Aimeos\Admin\JQAdm\Coupon\Standard( $this->context, $templatePaths );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context, $templatePaths );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testCreate()
	{
		$result = $this->object->create();

		$this->assertContains( 'item-coupon', $result );
	}


	public function testCreateException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClientNames' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClientNames' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->create();
	}


	public function testCreateMShopException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClientNames' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClientNames' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$object->setView( $this->getViewNoRender() );

		$object->create();
	}


	public function testCopy()
	{
		$param = ['site' => 'unittest', 'id' => $this->getCoupon()->getId()];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->copy();

		$this->assertContains( 'Unit test example', $result );
	}


	public function testCopyException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClientNames' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClientNames' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->copy();
	}


	public function testCopyMShopException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClientNames' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClientNames' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$object->setView( $this->getViewNoRender() );

		$object->copy();
	}


	public function testDelete()
	{
		$this->assertNotNull( $this->object->delete() );
	}


	public function testDeleteException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClients', 'search' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->delete();
	}


	public function testDeleteMShopException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClients', 'search' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$object->setView( $this->getViewNoRender() );

		$object->delete();
	}


	public function testGet()
	{
		$param = ['site' => 'unittest', 'id' => $this->getCoupon()->getId()];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->get();

		$this->assertContains( 'Unit test example', $result );
	}


	public function testGetException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClientNames' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClientNames' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->get();
	}


	public function testGetMShopException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClientNames' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClientNames' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$object->setView( $this->getViewNoRender() );

		$object->get();
	}


	public function testGetViewException()
	{
		$object = new \Aimeos\Admin\JQAdm\Coupon\Standard( $this->context, [] );

		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$object->getView();
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'coupon' );

		$param = array(
			'site' => 'unittest',
			'item' => array(
				'coupon.id' => '',
				'coupon.label' => 'jqadm test label',
				'coupon.provider' => 'Example',
				'coupon.datestart' => null,
				'coupon.dateend' => null,
				'config' => array(
					'key' => array( 0 => 'test key' ),
					'val' => array( 0 => 'test value' ),
				),
			),
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$this->object->save();

		$manager->deleteItem( $this->getCoupon( 'jqadm test label' )->getId() );
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->save();
	}


	public function testSaveMShopException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->save();
	}


	public function testSaveJQAdmException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Standard' )
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
			'site' => 'unittest', 'lang' => 'de',
			'filter' => array(
				'key' => array( 0 => 'coupon.label' ),
				'op' => array( 0 => '==' ),
				'val' => array( 0 => 'Unit test example' ),
			),
			'sort' => array( '-coupon.id' ),
		);
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->search();

		$this->assertContains( '>Unit test example<', $result );
	}


	public function testSearchException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'initCriteria' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'initCriteria' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->search();
	}


	public function testSearchMShopException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'initCriteria' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'initCriteria' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$object->setView( $this->getViewNoRender() );

		$object->search();
	}


	public function testGetSubClient()
	{
		$result = $this->object->getSubClient( 'code' );
		$this->assertInstanceOf( '\Aimeos\Admin\JQAdm\Iface', $result );
	}


	public function testGetSubClientInvalid()
	{
		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$this->object->getSubClient( '$unknown$' );
	}


	public function testGetSubClientUnknown()
	{
		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$this->object->getSubClient( 'unknown' );
	}


	public function testGetSubClientDecoratorInvalid()
	{
		$this->context->getConfig()->set( 'admin/jqadm/coupon/image/decorators/global', array( 'Invalid' ) );

		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$this->object->getSubClient( 'image' );
	}


	protected function getViewNoRender()
	{
		$view = $this->getMockBuilder( '\Aimeos\MW\View\Standard' )
			->setConstructorArgs( array( [] ) )
			->setMethods( array( 'render', 'config' ) )
			->getMock();

		$param = ['site' => 'unittest', 'id' => $this->getCoupon()->getId()];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, $param );
		$view->addHelper( 'param', $helper );

		$helper = new \Aimeos\MW\View\Helper\Access\Standard( $view, [] );
		$view->addHelper( 'access', $helper );

		return $view;
	}


	protected function getCoupon( $label = 'Unit test example' )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'coupon' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'coupon.label', $label ) );
		$items = $manager->searchItems( $search );

		if( ( $item = reset( $items ) ) === false ) {
			throw new \RuntimeException( sprintf( 'No coupon for label "%1$s" found', $label ) );
		}

		return $item;
	}
}
