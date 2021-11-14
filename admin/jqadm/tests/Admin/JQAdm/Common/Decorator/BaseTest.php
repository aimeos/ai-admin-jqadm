<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


class BaseTest extends \PHPUnit\Framework\TestCase
{
	private $object;
	private $mock;


	protected function setUp() : void
	{
		$this->mock = $this->getMockBuilder( 'Aimeos\Admin\JQAdm\Product\Standard' )
			->setMethods( array( 'copy', 'create', 'delete', 'get', 'save', 'search', 'getSubClient' ) )
			->disableOriginalConstructor()
			->getMock();

		$context = \TestHelperJqadm::getContext();

		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->mock, $context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( \TestHelperJqadm::view() );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->mock );
	}


	public function testCopy()
	{
		$this->mock->expects( $this->once() )->method( 'copy' )->will( $this->returnValue( 'test' ) );

		$this->assertEquals( 'test', $this->object->copy() );
	}


	public function testCreate()
	{
		$this->mock->expects( $this->once() )->method( 'create' )->will( $this->returnValue( 'test' ) );

		$this->assertEquals( 'test', $this->object->create() );
	}


	public function testDelete()
	{
		$this->mock->expects( $this->once() )->method( 'delete' )->will( $this->returnValue( 'test' ) );

		$this->assertEquals( 'test', $this->object->delete() );
	}


	public function testGet()
	{
		$this->mock->expects( $this->once() )->method( 'get' )->will( $this->returnValue( 'test' ) );

		$this->assertEquals( 'test', $this->object->get() );
	}


	public function testResponse()
	{
		$this->assertInstanceOf( '\Psr\Http\Message\ResponseInterface', $this->object->response() );
	}


	public function testSave()
	{
		$this->mock->expects( $this->once() )->method( 'save' )->will( $this->returnValue( 'test' ) );

		$this->assertEquals( 'test', $this->object->save() );
	}


	public function testSearch()
	{
		$this->mock->expects( $this->once() )->method( 'search' )->will( $this->returnValue( 'test' ) );

		$this->assertEquals( 'test', $this->object->search() );
	}


	public function testGetSubClient()
	{
		$this->mock->expects( $this->once() )->method( 'getSubClient' )->will( $this->returnSelf() );
		$this->assertInstanceOf( \Aimeos\Admin\JQAdm\Iface::class, $this->object->getSubClient( 'unknown' ) );
	}
}
