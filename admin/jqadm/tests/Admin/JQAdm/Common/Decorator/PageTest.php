<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


class PageTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;


	protected function setUp()
	{
		$this->context = \TestHelperJqadm::getContext();

		$client = new \Aimeos\Admin\JQAdm\Product\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $client, $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( \TestHelperJqadm::getView() );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->context );
	}


	public function testSetView()
	{
		$view = \TestHelperJqadm::getView();

		$this->object->setView( $view );

		$this->assertNull( $view->get( 'pageUser' ) );
		$this->assertInternalType( 'array', $view->pageParams );
		$this->assertInternalType( 'array', $view->pageI18nList );
		$this->assertInternalType( 'array', $view->pageLangItems );
		$this->assertInstanceOf( '\Aimeos\MShop\Locale\Item\Site\Iface', $view->pageSiteTree );
		$this->assertInstanceOf( '\Aimeos\MShop\Locale\Item\Site\Iface', $view->pageSiteItem );
	}
}
