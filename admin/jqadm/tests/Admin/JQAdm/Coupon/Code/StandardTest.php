<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */


namespace Aimeos\Admin\JQAdm\Coupon\Code;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp()
	{
		$this->view = \TestHelperJqadm::getView();
		$this->context = \TestHelperJqadm::getContext();
		$templatePaths = \TestHelperJqadm::getTemplatePaths();

		$this->object = new \Aimeos\Admin\JQAdm\Coupon\Code\Standard( $this->context, $templatePaths );
		$this->object->setView( $this->view );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->context, $this->view );
	}


	public function testCopy()
	{
		$this->view->item = $this->getCouponItem();

		$result = $this->object->copy();

		$this->assertContains( 'item-code', $result );
	}


	public function testCreate()
	{
		$result = $this->object->create();

		$this->assertContains( 'item-code', $result );
	}


	public function testGet()
	{
		$this->view->item = $this->getCouponItem();

		$result = $this->object->get();

		$this->assertContains( 'item-code', $result );
		$this->assertContains( 'OPQR', $result );
	}


	public function testGetException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Code\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'initCriteria' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'initCriteria' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->get();
	}


	public function testGetMShopException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Coupon\Code\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'initCriteria' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'initCriteria' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$object->setView( $this->getViewNoRender() );

		$object->get();
	}


	public function testSave()
	{
		$this->assertNull( $this->object->save() );
	}


	public function testGetSubClient()
	{
		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$this->object->getSubClient( 'unknown' );
	}


	protected function getViewNoRender()
	{
		$view = $this->getMockBuilder( '\Aimeos\MW\View\Standard' )
			->setConstructorArgs( array( [] ) )
			->setMethods( array( 'render', 'config' ) )
			->getMock();

		return $view;
	}


	protected function getCouponItem( $provider = 'Example,BasketValues' )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'coupon' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'coupon.provider', $provider ) );

		$items = $manager->searchItems( $search );

		if( ( $item = reset( $items ) ) === false ) {
			throw new \RuntimeException( 'No coupon item found' );
		}

		return $item;
	}
}
