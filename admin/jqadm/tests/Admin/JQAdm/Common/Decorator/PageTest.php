<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


class PageTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;


	protected function setUp() : void
	{
		$this->context = \TestHelperJqadm::getContext();

		$client = new \Aimeos\Admin\JQAdm\Product\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $client, $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( \TestHelperJqadm::view() );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->context );
	}


	public function testSetView()
	{
		$view = \TestHelperJqadm::view();

		$this->object->setView( $view );

		$this->assertEmpty( $view->get( 'pageUser' ) );
		$this->assertIsArray( $view->pageParams );
		$this->assertIsArray( $view->pageI18nList );
		$this->assertInstanceOf( \Aimeos\Map::class, $view->pageLangItems );
		$this->assertInstanceOf( \Aimeos\MShop\Locale\Item\Site\Iface::class, $view->pageSiteItem );
	}
}
