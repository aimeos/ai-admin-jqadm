<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
 */


namespace Aimeos\Admin\JQAdm;


class FactoryTest extends \PHPUnit\Framework\TestCase
{
	private $aimeos;
	private $context;


	protected function setUp()
	{
		$this->aimeos = \TestHelperJqadm::getAimeos();
		$this->context = \TestHelperJqadm::getContext();
		$this->context->setView( \TestHelperJqadm::getView() );
	}


	public function testCreateClient()
	{
		$this->context->getConfig()->set( 'admin/jqadm/resources', ['product'] );
		$client = \Aimeos\Admin\JQAdm\Factory::createClient( $this->context, $this->aimeos, 'product' );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreateClientName()
	{
		$this->context->getConfig()->set( 'admin/jqadm/resources', ['product'] );
		$client = \Aimeos\Admin\JQAdm\Factory::createClient( $this->context, $this->aimeos, 'product', 'Standard' );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreateSubClient()
	{
		$this->context->getConfig()->set( 'admin/jqadm/resources', ['locale/site'] );
		$client = \Aimeos\Admin\JQAdm\Factory::createClient( $this->context, $this->aimeos, 'locale/site' );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreateClientNameEmpty()
	{
		$this->setExpectedException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Factory::createClient( $this->context, $this->aimeos, '' );
	}


	public function testCreateClientNameInvalid()
	{
		$this->setExpectedException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Factory::createClient( $this->context, $this->aimeos, '%product' );
	}


	public function testCreateClientNameNotFound()
	{
		$this->setExpectedException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Factory::createClient( $this->context, $this->aimeos, 'unknown' );
	}

}
