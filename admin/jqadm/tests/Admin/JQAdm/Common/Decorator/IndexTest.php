<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


class IndexTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $mock;


	protected function setUp()
	{
		$this->context = \TestHelperJqadm::getContext();

		$this->mock = $this->getMockBuilder( 'Aimeos\Admin\JQAdm\Product\Standard' )
			->setMethods( array( 'delete', 'save' ) )
			->disableOriginalConstructor()
			->getMock();

		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Index( $this->mock, $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( \TestHelperJqadm::getView() );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->mock, $this->context );
	}


	public function testDelete()
	{
		$view = \TestHelperJqadm::getView();
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, array( 'id' => -1 ) );
		$view->addHelper( 'param', $helper );

		$this->mock->expects( $this->once() )->method( 'delete' )->will( $this->returnValue( 'test' ) );
		$this->object->setView( $view );

		$result = $this->object->delete();

		$this->assertEquals( 'test', $result );
	}


	public function testSave()
	{
		$view = \TestHelperJqadm::getView();
		$view->item = \Aimeos\MShop\Factory::createManager( $this->context, 'product' )->findItem( 'CNC' );

		$this->mock->expects( $this->once() )->method( 'save' )->will( $this->returnValue( 'test' ) );
		$this->object->setView( $view );

		$result = $this->object->save();

		$this->assertEquals( 'test', $result );
	}
}
