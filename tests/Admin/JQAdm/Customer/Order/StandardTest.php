<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


namespace Aimeos\Admin\JQAdm\Customer\Order;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelper::view();
		$this->context = \TestHelper::context();

		$this->object = new \Aimeos\Admin\JQAdm\Customer\Order\Standard( $this->context );
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
		$manager = \Aimeos\MShop::create( $this->context, 'customer' );
		$this->view->item = $manager->find( 'test@example.com' );

		$result = $this->object->copy();

		$this->assertStringContainsString( 'item-order', $result );
		$this->assertStringContainsString( '2400.00', $result );
	}


	public function testCreate()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'customer' );
		$this->view->item = $manager->find( 'test@example.com' );

		$result = $this->object->create();

		$this->assertStringContainsString( 'item-order', $result );
		$this->assertStringContainsString( '2400.00', $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'customer' );
		$this->view->item = $manager->find( 'test@example.com' );

		$result = $this->object->get();

		$this->assertStringContainsString( 'item-order', $result );
		$this->assertStringContainsString( '2400.00', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'customer' );
		$this->view->item = $manager->find( 'test@example.com' );

		$result = $this->object->save();

		$this->assertStringContainsString( 'item-order', $result );
		$this->assertStringContainsString( '2400.00', $result );
	}


	public function testGetSubClient()
	{
		$this->expectException( \LogicException::class );
		$this->object->getSubClient( 'unknown' );
	}


	protected function getViewNoRender()
	{
		return $this->getMockBuilder( \Aimeos\Base\View\Standard::class )
			->setConstructorArgs( array( [] ) )
			->onlyMethods( ['render'] )->addMethods( ['config'] )
			->getMock();
	}
}
