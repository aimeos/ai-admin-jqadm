<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */


namespace Aimeos\Admin\JQAdm;


class FactoryTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $templatePaths;


	protected function setUp()
	{
		$this->templatePaths = \TestHelperJqadm::getTemplatePaths();
		$this->context = \TestHelperJqadm::getContext();
		$this->context->setView( \TestHelperJqadm::getView() );
	}


	public function testCreateClient()
	{
		$this->context->getConfig()->set( 'admin/jqadm/resources', ['product'] );
		$client = \Aimeos\Admin\JQAdm\Factory::createClient( $this->context, $this->templatePaths, 'product' );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreateClientName()
	{
		$this->context->getConfig()->set( 'admin/jqadm/resources', ['product'] );
		$client = \Aimeos\Admin\JQAdm\Factory::createClient( $this->context, $this->templatePaths, 'product', 'Standard' );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreateClientNameEmpty()
	{
		$this->setExpectedException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Factory::createClient( $this->context, $this->templatePaths, '' );
	}


	public function testCreateClientNameInvalid()
	{
		$this->setExpectedException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Factory::createClient( $this->context, $this->templatePaths, '%product' );
	}


	public function testCreateClientNameNotFound()
	{
		$this->setExpectedException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Factory::createClient( $this->context, $this->templatePaths, 'prod' );
	}

}
