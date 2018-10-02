<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */


namespace Aimeos\Admin\JQAdm\Order\Invoice;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp()
	{
		$this->view = \TestHelperJqadm::getView();
		$this->context = \TestHelperJqadm::getContext();

		$this->object = new \Aimeos\Admin\JQAdm\Order\Invoice\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testCopy()
	{
		$this->view->item = $this->getOrderBaseItem();

		$result = $this->object->copy();

		$this->assertContains( 'item-invoice', $result );
	}


	public function testCreate()
	{
		$result = $this->object->create();

		$this->assertContains( 'item-invoice', $result );
	}


	public function testGet()
	{
		$this->view->item = $this->getOrderBaseItem();

		$result = $this->object->get();

		$this->assertContains( 'item-invoice', $result );
		$this->assertContains( 'phone', $result );
	}


	public function testGetException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Order\Invoice\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'toArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'toArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->get();
	}


	public function testGetMShopException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Order\Invoice\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'toArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'toArray' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$object->setView( $this->getViewNoRender() );

		$object->get();
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'order' );

		$param = array(
			'site' => 'unittest',
			'invoice' => array(
				'order.id' => [''],
				'order.type' => ['jqadmtst'],
			),
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$this->view->item = $this->getOrderBaseItem();

		$result = $this->object->save();

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'order.type', 'jqadmtst' ) );
		$items = $manager->searchItems( $search );

		if( ( $item = reset( $items ) ) === false ) {
			$this->fail( 'No saved order item found' );
		}

		$manager->deleteItem( $item->getId() );

		$this->assertNull( $result );
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

		$view->item = $this->getOrderBaseItem();

		return $view;
	}


	protected function getOrderBaseItem( $comment = 'This is another comment.' )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'order/base' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'order.base.comment', $comment ) );

		$items = $manager->searchItems( $search );

		if( ( $item = reset( $items ) ) === false ) {
			throw new \RuntimeException( 'No order base item found' );
		}

		return $item;
	}
}
