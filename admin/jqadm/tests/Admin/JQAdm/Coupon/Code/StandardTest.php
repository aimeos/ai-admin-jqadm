<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */


namespace Aimeos\Admin\JQAdm\Coupon\Code;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelperJqadm::view();
		$this->context = \TestHelperJqadm::getContext();

		$this->object = new \Aimeos\Admin\JQAdm\Coupon\Code\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testCopy()
	{
		$this->view->item = $this->getCouponItem();

		$result = $this->object->copy();

		$this->assertStringContainsString( 'item-code', $result );
	}


	public function testCreate()
	{
		$result = $this->object->create();

		$this->assertStringContainsString( 'item-code', $result );
	}


	public function testGet()
	{
		$this->view->item = $this->getCouponItem();

		$result = $this->object->get();

		$this->assertStringContainsString( 'item-code', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'coupon/code' );

		$param = array(
			'site' => 'unittest',
			'code' => array(
				'coupon.code.id' => [''],
				'coupon.code.code' => ['jqadm test code'],
				'coupon.code.count' => ['10'],
				'coupon.code.datestart' => ['2000-01-01T00:00:00'],
				'coupon.code.dateend' => ['2010-01-01T00:00'],
				'coupon.code.ref' => ['123'],
			),
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$this->view->item = $this->getCouponItem();

		$result = $this->object->save();

		$manager->delete( $manager->find( 'jqadm test code' )->getId() );

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertNull( $result );
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Coupon\Code\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClients' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$this->expectException( \RuntimeException::class );
		$object->save();
	}


	public function testGetSubClient()
	{
		$this->expectException( \Aimeos\Admin\JQAdm\Exception::class );
		$this->object->getSubClient( 'unknown' );
	}


	protected function getViewNoRender()
	{
		$view = $this->getMockBuilder( \Aimeos\MW\View\Standard::class )
			->setConstructorArgs( array( [] ) )
			->setMethods( array( 'render', 'config' ) )
			->getMock();

		$request = $this->getMockBuilder( \Psr\Http\Message\ServerRequestInterface::class )->getMock();
		$helper = new \Aimeos\MW\View\Helper\Request\Standard( $this->view, $request, '127.0.0.1', 'test' );
		$view->addHelper( 'request', $helper );

		$view->item = $this->getCouponItem();

		return $view;
	}


	protected function getCouponItem( $provider = 'None,Basket' )
	{
		$manager = \Aimeos\MShop::create( $this->context, 'coupon' );

		$search = $manager->filter();
		$search->setConditions( $search->compare( '==', 'coupon.provider', $provider ) );

		if( ( $item = $manager->search( $search )->first() ) === null ) {
			throw new \RuntimeException( 'No coupon item found' );
		}

		return $item;
	}
}
