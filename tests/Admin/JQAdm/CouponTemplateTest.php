<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2026
 */

namespace Aimeos\Admin\JQAdm;


class CouponTemplateTest extends \PHPUnit\Framework\TestCase
{
	public function testCouponCodesAreSafeText()
	{
		$path = dirname( __DIR__, 3 ) . '/templates/admin/jqadm/order/item.php';
		$template = file_get_contents( $path );

		$this->assertStringContainsString( '<span class="item-coupon"><?= $enc->html( $code ) ?></span>', $template );
		$this->assertStringNotContainsString( '<span class="item-coupon"><?= $enc->attr( $code ) ?></span>', $template );
	}
}
