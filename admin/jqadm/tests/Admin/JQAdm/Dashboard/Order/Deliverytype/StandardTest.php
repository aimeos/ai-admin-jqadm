<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */


namespace Aimeos\Admin\JQAdm\Dashboard\Order\Deliverytype;


class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp()
	{
		$this->view = \TestHelperJqadm::getView();
		$this->context = \TestHelperJqadm::getContext();
		$templatePaths = \TestHelperJqadm::getTemplatePaths();

		$this->object = new \Aimeos\Admin\JQAdm\Dashboard\Order\Deliverytype\Standard( $this->context, $templatePaths );
		$this->object->setView( $this->view );
	}


	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testCreate()
	{
		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$this->object->create();
	}


	public function testCopy()
	{
		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$this->object->copy();
	}


	public function testDelete()
	{
		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$this->object->delete();
	}


	public function testGet()
	{
		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$this->object->get();
	}


	public function testSave()
	{
		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$this->object->save();
	}


	public function testSearch()
	{
		$result = $this->object->search();

		$this->assertContains( '<div class="order-deliverytype', $result );
	}


	public function testSearchException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Dashboard\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClients' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->search();
	}


	public function testSearchMShopException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Dashboard\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClients' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$object->setView( $this->getViewNoRender() );

		$object->search();
	}


	public function testGetSubClient()
	{
		$this->setExpectedException( '\Aimeos\Admin\JQAdm\Exception' );
		$this->object->getSubClient( 'unknown' );
	}


	protected function getViewNoRender()
	{
		return $this->getMockBuilder( '\Aimeos\MW\View\Standard' )
			->setConstructorArgs( array( array() ) )
			->setMethods( array( 'render', 'config' ) )
			->getMock();
	}
}
