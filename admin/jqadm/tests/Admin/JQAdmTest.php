<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2021
 */


namespace Aimeos\Admin;


class JQAdmTest extends \PHPUnit\Framework\TestCase
{
	private $aimeos;
	private $context;


	protected function setUp() : void
	{
		$this->aimeos = \TestHelperJqadm::getAimeos();
		$this->context = \TestHelperJqadm::getContext();
		$this->context->setView( \TestHelperJqadm::view() );
	}


	public function testCreateClient()
	{
		$this->context->getConfig()->set( 'admin/jqadm/resources', ['product'] );
		$client = \Aimeos\Admin\JQAdm::create( $this->context, $this->aimeos, 'product' );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreateClientName()
	{
		$this->context->getConfig()->set( 'admin/jqadm/resources', ['product'] );
		$client = \Aimeos\Admin\JQAdm::create( $this->context, $this->aimeos, 'product', 'Standard' );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreateSubClient()
	{
		$this->context->getConfig()->set( 'admin/jqadm/resources', ['type/attribute'] );
		$client = \Aimeos\Admin\JQAdm::create( $this->context, $this->aimeos, 'type/attribute' );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreateClientNameEmpty()
	{
		$this->expectException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm::create( $this->context, $this->aimeos, '' );
	}


	public function testCreateClientNameInvalid()
	{
		$this->expectException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm::create( $this->context, $this->aimeos, '%product' );
	}


	public function testCreateClientNameNotFound()
	{
		$this->expectException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm::create( $this->context, $this->aimeos, 'unknown' );
	}

}
