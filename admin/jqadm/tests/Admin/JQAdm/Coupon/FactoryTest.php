<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */


namespace Aimeos\Admin\JQAdm\Coupon;


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
		$client = \Aimeos\Admin\JQAdm\Coupon\Factory::createClient( $this->context, $this->templatePaths );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreateClientName()
	{
		$client = \Aimeos\Admin\JQAdm\Coupon\Factory::createClient( $this->context, $this->templatePaths, 'Standard' );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreateClientNameEmpty()
	{
		$this->setExpectedException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Coupon\Factory::createClient( $this->context, $this->templatePaths, '' );
	}


	public function testCreateClientNameInvalid()
	{
		$this->setExpectedException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Coupon\Factory::createClient( $this->context, $this->templatePaths, '%coupon' );
	}


	public function testCreateClientNameNotFound()
	{
		$this->setExpectedException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Coupon\Factory::createClient( $this->context, $this->templatePaths, 'test' );
	}

}
