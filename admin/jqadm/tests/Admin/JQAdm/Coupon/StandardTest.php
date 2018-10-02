<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
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

		$this->object = new \Aimeos\Admin\JQAdm\Coupon\Standard( $this->context );
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
		$result = $this->object->create();

		$this->assertContains( 'item-coupon', $result );
	}


	public function testCreateException()
	{
		$object = $this->getClientMock( 'getSubClientNames' );

		$object->expects( $this->once() )->method( 'getSubClientNames' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->create();
	}


	public function testCreateMShopException()
	{
		$object = $this->getClientMock( 'getSubClientNames' );

		$object->expects( $this->once() )->method( 'getSubClientNames' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

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
		$object = $this->getClientMock( 'getSubClientNames' );

		$object->expects( $this->once() )->method( 'getSubClientNames' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->copy();
	}


	public function testCopyMShopException()
	{
		$object = $this->getClientMock( 'getSubClientNames' );

		$object->expects( $this->once() )->method( 'getSubClientNames' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$object->copy();
	}


	public function testDelete()
	{
		$this->assertNotNull( $this->object->delete() );
	}


	public function testDeleteException()
	{
		$object = $this->getClientMock( 'getSubClients' );

		$object->expects( $this->exactly( 2 ) )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->delete();
	}


	public function testDeleteMShopException()
	{
		$object = $this->getClientMock( 'getSubClients' );

		$object->expects( $this->exactly( 2 ) )->method( 'getSubClients' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

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
		$object = $this->getClientMock( 'getSubClientNames' );

		$object->expects( $this->once() )->method( 'getSubClientNames' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->get();
	}


	public function testGetMShopException()
	{
		$object = $this->getClientMock( 'getSubClientNames' );

		$object->expects( $this->once() )->method( 'getSubClientNames' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

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
		$object = $this->getClientMock( 'fromArray' );

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->save();
	}


	public function testSaveMShopException()
	{
		$object = $this->getClientMock( 'fromArray' );

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->save();
	}


	public function testSaveJQAdmException()
	{
		$object = $this->getClientMock( 'fromArray' );

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

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
		$object = $this->getClientMock( 'initCriteria' );

		$object->expects( $this->once() )->method( 'initCriteria' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->search();
	}


	public function testSearchMShopException()
	{
		$object = $this->getClientMock( 'initCriteria' );

		$object->expects( $this->once() )->method( 'initCriteria' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

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
		$this->context->getConfig()->set( 'admin/jqadm/coupon/code/decorators/global', array( 'Invalid' ) );

		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$this->object->getSubClient( 'code' );
	}


	public function getClientMock( $method )
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( [$method] )
			->getMock();

		$object->setAimeos( \TestHelperJqadm::getAimeos() );
		$object->setView( $this->getViewNoRender() );

		return $object;
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
