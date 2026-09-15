<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2026
 */

namespace Aimeos\Admin\JQAdm;

class CouponTemplateTest extends \PHPUnit\Framework\TestCase
{
	public static function templates() : array
	{
		return [['order'], ['basket']];
	}


	/**
	 * @dataProvider templates
	 */
	public function testCouponCodesAreSafeText( string $domain )
	{
		$locale = new \Aimeos\MShop\Locale\Item\Standard( ['locale.siteid' => '1.', 'locale.languageid' => 'en'] );
		$price = new \Aimeos\MShop\Price\Item\Standard( ['price.currencyid' => 'EUR'] );
		$order = new \Aimeos\MShop\Order\Item\Standard( $price, $locale );

		foreach( ['<svg/onload=alert(1)>', '<img/src/onerror=alert(1)>', '{{constructor.constructor("alert(1)")()}}', 'SAVE&WIN', 'été-10'] as $code )
		{
			$coupon = new \Aimeos\MShop\Coupon\Item\Code\Standard();
			$order->addCoupon( $coupon->setCode( $code )->getCode() );
		}

		$view = \TestHelper::view( 'unittest', new \Aimeos\Base\Config\PHPArray() );
		$view->pageSiteItem = new \Aimeos\MShop\Locale\Item\Site\Standard( ['locale.site.id' => '1', 'locale.site.label' => 'Test'] );
		$view->pageSitePath = map( [$view->pageSiteItem] );
		$view->item = $domain === 'order' ? $order : new \Aimeos\MShop\Order\Item\Basket\Standard( [], $order );
		$output = $view->render( $domain . '/item' );

		$doc = new \DOMDocument();
		@$doc->loadHTML( '<?xml encoding="UTF-8">' . $output );
		$nodes = ( new \DOMXPath( $doc ) )->query( '//span[@class="item-coupon"]' );
		$this->assertCount( 5, $nodes );
		$values = [];

		foreach( $nodes as $node )
		{
			$this->assertSame( 0, $node->childElementCount );
			$values[] = $node->textContent;
		}

		$this->assertSame( ['', '', 'constructor.constructor("alert(1)")()', 'SAVE&WIN', 'été-10'], $values );
	}
}
