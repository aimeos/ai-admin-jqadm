<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2024
 */


namespace Aimeos\Admin;


class BundleTest extends \PHPUnit\Framework\TestCase
{
	public function testGet()
	{
		$manifest = dirname( __DIR__, 3 ) . '/manifest.jsb2';

		$this->assertFalse( empty( \Aimeos\Admin\JQAdm\Bundle::get( [$manifest], 'index-css' ) ) );
		$this->assertFalse( empty( \Aimeos\Admin\JQAdm\Bundle::get( [$manifest], 'index-ltr-css' ) ) );
		$this->assertFalse( empty( \Aimeos\Admin\JQAdm\Bundle::get( [$manifest], 'index-rtl-css' ) ) );
		$this->assertFalse( empty( \Aimeos\Admin\JQAdm\Bundle::get( [$manifest], 'index-js' ) ) );
		$this->assertFalse( empty( \Aimeos\Admin\JQAdm\Bundle::get( [$manifest], 'vendor-js' ) ) );
	}
}
