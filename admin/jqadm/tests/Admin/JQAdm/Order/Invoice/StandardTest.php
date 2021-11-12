<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */


namespace Aimeos\Admin\JQAdm\Order\Invoice;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelperJqadm::view();
		$this->context = \TestHelperJqadm::getContext();

		$this->object = new \Aimeos\Admin\JQAdm\Order\Invoice\Standard( $this->context );
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
		$this->view->item = $this->getOrderBaseItem();

		$result = $this->object->copy();

		$this->assertStringContainsString( 'item-invoice', $result );
	}


	public function testCreate()
	{
		$this->view->item = $this->getOrderBaseItem();

		$result = $this->object->create();

		$this->assertStringContainsString( 'item-invoice', $result );
	}


	public function testGet()
	{
		$this->view->item = $this->getOrderBaseItem();

		$result = $this->object->get();

		$this->assertStringContainsString( 'item-invoice', $result );
		$this->assertStringContainsString( 'phone', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'order' );

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

		$search = $manager->filter();
		$search->setConditions( $search->compare( '==', 'order.type', 'jqadmtst' ) );

		if( ( $item = $manager->search( $search )->first() ) === null ) {
			$this->fail( 'No saved order item found' );
		}

		$manager->delete( $item->getId() );

		$this->assertEmpty( $result );
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

		$view->item = $this->getOrderBaseItem();

		return $view;
	}


	protected function getOrderBaseItem( $comment = 'This is another comment.' )
	{
		$manager = \Aimeos\MShop::create( $this->context, 'order/base' );

		$search = $manager->filter();
		$search->setConditions( $search->compare( '==', 'order.base.comment', $comment ) );

		if( ( $item = $manager->search( $search )->first() ) === null ) {
			throw new \RuntimeException( 'No order base item found' );
		}

		return $item;
	}
}
