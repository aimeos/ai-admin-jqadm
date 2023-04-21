<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


namespace Aimeos\Base\View\Helper\Site;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$view = new \Aimeos\Base\View\Standard();
		$view->pageSiteItem = new \Aimeos\MShop\Locale\Item\Site\Standard( [
			'locale.site.siteid' => '0.',
			'locale.site.label' => 'label1'
		] );

		$this->object = new \Aimeos\Base\View\Helper\Site\Standard( $view );
	}


	protected function tearDown() : void
	{
		$this->object = null;
	}


	public function testTransform()
	{
		$this->assertInstanceOf( '\\Aimeos\\Base\\View\\Helper\\Site\\Iface', $this->object->transform() );
	}


	public function testCan()
	{
		$this->assertEquals( true, $this->object->transform()->can( '0.' ) );
		$this->assertEquals( true, $this->object->transform()->can( '0.2.' ) );
		$this->assertEquals( false, $this->object->transform()->can( '3.' ) );
	}


	public function testLabel()
	{
		$this->assertEquals( 'label1', $this->object->transform()->label() );
	}


	public function testMatch()
	{
		$this->assertEquals( 'label1', $this->object->transform()->match( '0.' ) );
	}


	public function testReadonly()
	{
		$this->assertEquals( 'readonly', $this->object->transform()->readonly( '0.2.' ) );
	}


	public function testSiteid()
	{
		$this->assertEquals( '0.', $this->object->transform()->siteid() );
	}
}
