<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */


namespace Aimeos\Admin;


class JQAdmTest extends \PHPUnit\Framework\TestCase
{
	private $aimeos;
	private $context;


	protected function setUp() : void
	{
		$this->aimeos = \TestHelper::getAimeos();
		$this->context = \TestHelper::context();
		$this->context->setView( \TestHelper::view() );
	}


	public function testCreateClient()
	{
		$this->context->config()->set( 'admin/jqadm/resources', ['product'] );
		$client = \Aimeos\Admin\JQAdm::create( $this->context, $this->aimeos, 'product' );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreatePolicyDefault()
	{
		$this->context->setPolicy( null );
		$client = \Aimeos\Admin\JQAdm::create( $this->context, $this->aimeos, 'product' );

		// the shared context must not be changed, only the copy used by the client
		$context = ( new \ReflectionMethod( $client, 'context' ) )->invoke( $client );
		$this->assertInstanceOf( \Aimeos\MShop\Policy\Groups::class, $context->policy() );
		$this->assertNull( $this->context->policy() );
	}


	public function testCreatePolicyCustom()
	{
		$this->context->setPolicy( new class implements \Aimeos\MShop\PolicyIface {
			public function allowed( \Aimeos\MShop\ContextIface $context, string $resource, string $action ) : ?bool {
				return false;
			}
		} );

		$this->expectException( \Aimeos\Admin\JQAdm\Exception::class );
		$this->expectExceptionCode( 403 );
		\Aimeos\Admin\JQAdm::create( $this->context, $this->aimeos, 'product' );
	}


	public function testCreateClientName()
	{
		$this->context->config()->set( 'admin/jqadm/resources', ['product'] );
		$client = \Aimeos\Admin\JQAdm::create( $this->context, $this->aimeos, 'product', 'Standard' );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreateSubClient()
	{
		$this->context->config()->set( 'admin/jqadm/resources', ['type'] );
		$client = \Aimeos\Admin\JQAdm::create( $this->context, $this->aimeos, 'type' );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreateClientNameEmpty()
	{
		$this->expectException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm::create( $this->context, $this->aimeos, '' );
	}


	public function testCreateClientNameInvalid()
	{
		$this->expectException( \LogicException::class );
		\Aimeos\Admin\JQAdm::create( $this->context, $this->aimeos, '%^unknown' );
	}


	public function testCreateClientNameNotFound()
	{
		$this->expectException( \LogicException::class );
		\Aimeos\Admin\JQAdm::create( $this->context, $this->aimeos, 'unknown' );
	}


	public function testForbidden()
	{
		$previous = new \Aimeos\MShop\PolicyException( 'test' );
		$e = \Aimeos\Admin\JQAdm::forbidden( 'test', $previous );

		$this->assertInstanceOf( \Aimeos\Admin\JQAdm\Exception::class, $e );
		$this->assertSame( $previous, $e->getPrevious() );
		$this->assertEquals( 403, $e->getCode() );
	}


	public function testPolicy()
	{
		// the default policy uses the user groups configured for the interface
		$context = \TestHelper::context();
		$context->config()->set( 'admin/jqadm/resource/product/groups', ['editor'] );
		$context->config()->set( 'admin/jqadm/resource/service/groups', ['admin'] );

		$view = new \Aimeos\Base\View\Standard();
		$view->addHelper( 'access', new \Aimeos\Base\View\Helper\Access\Standard( $view, ['editor'] ) );
		$context->setView( $view );

		$policy = \Aimeos\Admin\JQAdm::policy();

		$this->assertTrue( $policy->allowed( $context, 'product', 'save' ) );
		$this->assertFalse( $policy->allowed( $context, 'service', 'save' ) );
		$this->assertNull( $policy->allowed( $context, 'unknown', 'save' ) );

		// the groups are configured per resource for all actions
		$this->assertTrue( $policy->allowed( $context, 'product', 'delete' ) );
	}
}
