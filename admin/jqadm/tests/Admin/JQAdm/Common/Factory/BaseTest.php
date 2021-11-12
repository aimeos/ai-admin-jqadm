<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */


namespace Aimeos\Admin\JQAdm\Common\Factory;


class BaseTest extends \PHPUnit\Framework\TestCase
{
	private $context;


	protected function setUp() : void
	{
		$this->context = \TestHelperJqadm::getContext();
		$this->context->setView( \TestHelperJqadm::view() );

		$config = $this->context->getConfig();
		$config->set( 'admin/jqadm/common/decorators/default', [] );
		$config->set( 'admin/jqadm/product/decorators/global', [] );
		$config->set( 'admin/jqadm/product/decorators/local', [] );
	}


	public function testInjectClient()
	{
		$client = \Aimeos\Admin\JQAdm\Product\Factory::create( $this->context, 'Standard' );
		\Aimeos\Admin\JQAdm\Product\Factory::injectClient( '\\Aimeos\\Admin\\JQAdm\\Product\\Standard', $client );

		$iClient = \Aimeos\Admin\JQAdm\Product\Factory::create( $this->context, 'Standard' );

		$this->assertSame( $client, $iClient );
	}


	public function testInjectClientReset()
	{
		$client = \Aimeos\Admin\JQAdm\Product\Factory::create( $this->context, 'Standard' );
		\Aimeos\Admin\JQAdm\Product\Factory::injectClient( '\\Aimeos\\Admin\\JQAdm\\Product\\Standard', $client );
		\Aimeos\Admin\JQAdm\Product\Factory::injectClient( '\\Aimeos\\Admin\\JQAdm\\Product\\Standard', null );

		$new = \Aimeos\Admin\JQAdm\Product\Factory::create( $this->context, 'Standard' );

		$this->assertNotSame( $client, $new );
	}


	public function testAddDecorators()
	{
		$client = \Aimeos\Admin\JQAdm\Product\Factory::create( $this->context, 'Standard' );

		$result = \Aimeos\Admin\JQAdm\Common\Factory\TestAbstract::addDecoratorsPublic( $this->context, $client,
			['Page'], '\\Aimeos\\Admin\\JQAdm\\Common\\Decorator\\' );

		$this->assertInstanceOf( \Aimeos\Admin\JQAdm\Iface::class, $result );
	}


	public function testAddDecoratorsInvalidName()
	{
		$client = \Aimeos\Admin\JQAdm\Product\Factory::create( $this->context, 'Standard' );

		$this->expectException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Common\Factory\TestAbstract::addDecoratorsPublic( $this->context, $client, ['$'], 'Test' );
	}


	public function testAddDecoratorsInvalidClass()
	{
		$client = \Aimeos\Admin\JQAdm\Product\Factory::create( $this->context, 'Standard' );

		$this->expectException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Common\Factory\TestAbstract::addDecoratorsPublic( $this->context, $client,
			array( 'Test' ), 'TestDecorator' );
	}


	public function testAddDecoratorsInvalidInterface()
	{
		$client = \Aimeos\Admin\JQAdm\Product\Factory::create( $this->context, 'Standard' );

		$this->expectException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Common\Factory\TestAbstract::addDecoratorsPublic( $this->context, $client,
			array( 'Test' ), '\\Aimeos\\Admin\\JQAdm\\Common\\Decorator\\' );
	}


	public function testAddClientDecoratorsExcludes()
	{
		$this->context->getConfig()->set( 'admin/jqadm/decorators/excludes', array( 'TestDecorator' ) );
		$this->context->getConfig()->set( 'admin/jqadm/common/decorators/default', array( 'TestDecorator' ) );

		$this->expectException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Product\Factory::create( $this->context, 'Standard' );
	}


	public function testAddClientDecoratorsEmptyPath()
	{
		$client = \Aimeos\Admin\JQAdm\Product\Factory::create( $this->context, 'Standard' );

		$this->expectException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Common\Factory\TestAbstract::addClientDecoratorsPublic( $this->context, $client, '' );
	}


	public function testCreateClientBase()
	{
		$this->expectException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Common\Factory\TestAbstract::createAdminPublic( $this->context, 'Test', 'Test' );
	}
}


class TestAbstract
	extends \Aimeos\Admin\JQAdm\Common\Factory\Base
{
	public static function addDecoratorsPublic( \Aimeos\MShop\Context\Item\Iface $context,
		\Aimeos\Admin\JQAdm\Iface $client, array $decorators, string $classprefix ) : \Aimeos\Admin\JQAdm\Iface
	{
		return self::addDecorators( $context, $client, $decorators, $classprefix );
	}

	public static function addClientDecoratorsPublic( \Aimeos\MShop\Context\Item\Iface $context,
		\Aimeos\Admin\JQAdm\Iface $client, string $path ) : \Aimeos\Admin\JQAdm\Iface
	{
		return self::addClientDecorators( $context, $client, $path );
	}

	public static function createAdminPublic( \Aimeos\MShop\Context\Item\Iface $context,
		string $classname, string $interface ) : \Aimeos\Admin\JQAdm\Iface
	{
		return self::createAdmin( $context, $classname, $interface );
	}
}


class TestDecorator
{
}
