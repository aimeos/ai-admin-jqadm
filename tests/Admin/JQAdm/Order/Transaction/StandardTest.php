<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2022-2023
 */


namespace Aimeos\Admin\JQAdm\Order\Transaction;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelper::view();
		$this->context = \TestHelper::context();

		$this->object = new \Aimeos\Admin\JQAdm\Order\Transaction\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelper::getAimeos() );
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

		$this->assertStringContainsString( 'item-transaction', $result );
	}


	public function testCreate()
	{
		$this->view->item = $this->getOrderBaseItem();

		$result = $this->object->create();

		$this->assertStringContainsString( 'item-transaction', $result );
	}


	public function testGet()
	{
		$this->view->item = $this->getOrderBaseItem();

		$result = $this->object->get();

		$this->assertStringContainsString( 'item-transaction', $result );
	}


	public function testSave()
	{
		$this->view->item = $this->getOrderBaseItem();
		$serviceId = current( $this->view->item->getService( 'payment' ) )->getId();

		$param = [
			'site' => 'unittest',
			'transaction' => [
				$serviceId => [
					'order.service.transaction.value' => 10,
					'order.service.transaction.costs' => 1,
				]
			],
		];

		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->save();

		$this->assertEmpty( $result );
	}


	public function testSavePayPal()
	{
		$this->view->item = $this->getOrderBaseItem( '13.50' );
		$serviceId = current( $this->view->item->getService( 'payment' ) )->getId();

		$param = [
			'site' => 'unittest',
			'transaction' => [
				$serviceId => [
					'order.service.transaction.value' => 10,
					'order.service.transaction.costs' => 1,
				]
			],
		];

		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$this->expectException( \Aimeos\MShop\Service\Exception::class );
		$this->object->save();
	}


	public function testGetSubClient()
	{
		$this->expectException( \LogicException::class );
		$this->object->getSubClient( 'unknown' );
	}


	protected function getViewNoRender()
	{
		$view = $this->getMockBuilder( \Aimeos\Base\View\Standard::class )
			->setConstructorArgs( array( [] ) )
			->onlyMethods( ['render'] )->addMethods( ['config'] )
			->getMock();

		$view->item = $this->getOrderBaseItem();

		return $view;
	}


	protected function getOrderBaseItem( $value = '672.00' )
	{
		$manager = \Aimeos\MShop::create( $this->context, 'order' );
		$search = $manager->filter()->add( 'order.price', '==', $value );

		return $manager->search( $search, ['order/service'] )
			->first( new \RuntimeException( 'No order item found' ) );
	}
}
