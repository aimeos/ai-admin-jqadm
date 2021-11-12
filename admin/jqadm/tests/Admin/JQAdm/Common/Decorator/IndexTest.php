<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


class IndexTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $mock;


	protected function setUp() : void
	{
		$this->context = \TestHelperJqadm::getContext();

		$this->mock = $this->getMockBuilder( 'Aimeos\Admin\JQAdm\Catalog\Product\Standard' )
			->disableOriginalConstructor()
			->setMethods( ['save'] )
			->getMock();

		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Index( $this->mock, $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( \TestHelperJqadm::view() );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->mock, $this->context );
	}


	public function testSave()
	{
		$view = \TestHelperJqadm::view();
		$params = ['product' => ['catalog.lists.refid' => [-1]]];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, $params );
		$view->addHelper( 'param', $helper );

		$this->mock->expects( $this->once() )->method( 'save' )->will( $this->returnValue( 'test' ) );
		$this->object->setView( $view );

		$result = $this->object->save();

		$this->assertEquals( 'test', $result );
	}
}
