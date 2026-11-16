<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2026
 */


namespace Aimeos\Admin\JQAdm\Locale\Site;

use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;


#[AllowMockObjectsWithoutExpectations]
class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelper::view();
		$this->context = \TestHelper::context();

		$helper = new \Aimeos\Base\View\Helper\Access\All( $this->view );
		$this->view->addHelper( 'access', $helper );

		$this->object = new \Aimeos\Admin\JQAdm\Locale\Site\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelper::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testCreate()
	{
		$this->assertStringContainsString( 'item-locale-site', $this->object->create() );
	}


	public function testCreateException()
	{
		$object = $this->getClientMock( 'getSubClients' );

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->create();
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'locale/site' );

		$param = ['site' => 'unittest', 'id' => $manager->find( 'unittest' )->getId()];
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->copy();

		$this->assertStringContainsString( 'unittest', $result );
	}


	public function testCopyException()
	{
		$object = $this->getClientMock( 'getSubClients' );

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->copy();
	}


	public function testBatchEditor()
	{
		$view = $this->userView( ['editor'], ['id' => [$this->siteId( 'unittest' )], 'item' => ['locale.site.label' => 'editor']] );
		$object = $this->policy( $this->getClientMock( 'redirect' ), $view );

		$this->expectException( \Aimeos\Admin\JQAdm\Exception::class );
		$this->expectExceptionCode( 403 );
		$object->batch();
	}


	public function testBatchOtherSite()
	{
		// no changes passed, so the site stays untouched if it's wrongly included
		$view = $this->userView( ['admin'], ['id' => [$this->siteId( 'unittest' )], 'item' => []], '-1.' );
		$object = new \Aimeos\Admin\JQAdm\Locale\Site\Standard( $view->context );
		$this->policy( $object->setAimeos( \TestHelper::getAimeos() ), $view )->batch();

		$this->assertCount( 0, $view->items );
	}


	public function testDelete()
	{
		$this->assertNull( $this->getClientMock( ['redirect'], false )->delete() );
	}


	public function testDeleteAdmin()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'locale/site' );
		$item = $manager->insert( $manager->create()->setCode( 'jqadm-admin' )->setLabel( 'jqadm' ) );

		try
		{
			$mock = $this->getClientMock( ['redirect', 'search'] );
			$mock->expects( $this->never() )->method( 'redirect' );

			$view = $this->userView( ['admin'], ['id' => [$item->getId()]], $item->getSiteId() );
			$this->policy( $mock, $view )->delete();

			$this->fail( 'Exception expected' );
		}
		catch( \Aimeos\Admin\JQAdm\Exception $e )
		{
			// only super users are allowed to delete sites, even their own ones
			$this->assertEquals( 403, $e->getCode() );
			$this->assertEquals( 'jqadm-admin', $manager->get( $item->getId() )->getCode() );
		}
		finally
		{
			$manager->delete( $item->getId() );
		}
	}


	public function testDeleteException()
	{
		$object = $this->getClientMock( ['getSubClients', 'search'] );

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );
		$object->expects( $this->once() )->method( 'search' );

		$object->delete();
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'locale/site' );

		$param = ['site' => 'unittest', 'id' => $manager->find( 'unittest' )->getId()];
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->get();

		$this->assertStringContainsString( 'unittest', $result );
	}


	public function testGetException()
	{
		$object = $this->getClientMock( 'getSubClients' );

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->get();
	}


	public function testSave()
	{
		$param = array(
			'site' => 'unittest',
			'item' => array(
				'locale.site.id' => '',
				'locale.site.status' => '1',
				'locale.site.code' => 'jqadm@test',
				'locale.site.label' => 'jqadm test',
				'config' => [
					['key' => 'test', 'val' => 'value'],
					['key' => 'zero', 'val' => '0'],
					['key' => 'off', 'val' => 'false'],
					['key' => 'empty', 'val' => ''],
				],
			),
		);

		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$this->object->save();

		$manager = \Aimeos\MShop::create( $this->context, 'locale/site' );
		$item = $manager->find( 'jqadm@test' );
		$manager->delete( $item->getId() );

		$this->assertSame( ['test' => 'value', 'zero' => 0, 'off' => false], $item->getConfig() );
	}


	public function testSaveAdmin()
	{
		$this->assertEquals( 'jqadm admin', $this->saveAdmin( fn( $item ) => $item->getSiteId() )->getLabel() );
	}


	public function testSaveAdminInsert()
	{
		$view = $this->userView( ['admin'], ['item' => ['locale.site.code' => 'jqadm-insert', 'locale.site.label' => 'jqadm']], $this->siteId( 'unittest' ) );

		$object = new \Aimeos\Admin\JQAdm\Locale\Site\Standard( $view->context );
		$this->policy( $object->setAimeos( \TestHelper::getAimeos() ), $view )->save();

		$manager = \Aimeos\MShop::create( $this->context, 'locale/site' );
		$items = $manager->search( $manager->filter()->add( 'locale.site.code', '==', 'jqadm-insert' ) );
		$manager->delete( $items );

		// only super users are allowed to add new sites
		$this->assertCount( 0, $items );
	}


	public function testSaveAdminOtherSite()
	{
		$this->assertEquals( 'jqadm', $this->saveAdmin( fn( $item ) => '-1.' )->getLabel() );
	}


	public function testSaveEditor()
	{
		$this->expectException( \Aimeos\Admin\JQAdm\Exception::class );
		$this->expectExceptionCode( 403 );
		$this->saveAdmin( fn( $item ) => $item->getSiteId(), ['editor'] );
	}


	public function testSaveException()
	{
		$object = $this->getClientMock( 'fromArray' );

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->save();
	}


	public function testSearch()
	{
		$param = array(
			'site' => 'unittest', 'locale' => 'de',
			'filter' => array(
				'key' => array( 0 => 'locale.site.code' ),
				'op' => array( 0 => '==' ),
				'val' => array( 0 => 'unittest' ),
			),
			'sort' => array( '-locale.site.id' ),
		);
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->search();

		$this->assertStringContainsString( '>unittest<', $result );
	}


	public function testSearchException()
	{
		$object = $this->getClientMock( 'initCriteria' );

		$object->expects( $this->once() )->method( 'initCriteria' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->search();
	}


	public function testGetSubClientInvalid()
	{
		$this->expectException( \LogicException::class );
		$this->object->getSubClient( '$unknown$' );
	}


	public function testGetSubClientUnknown()
	{
		$this->expectException( \LogicException::class );
		$this->object->getSubClient( 'unknown' );
	}


	public function getClientMock( $methods, $real = true )
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Locale\Site\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelper::getTemplatePaths() ) )
			->onlyMethods( (array) $methods )
			->getMock();

		$object->setAimeos( \TestHelper::getAimeos() );
		$object->setView( $this->getViewNoRender( $real ) );

		return $object;
	}


	protected function saveAdmin( \Closure $siteFcn, array $groups = ['admin'] ) : \Aimeos\MShop\Locale\Item\Site\Iface
	{
		$manager = \Aimeos\MShop::create( $this->context, 'locale/site' );
		$item = $manager->insert( $manager->create()->setCode( 'jqadm-admin' )->setLabel( 'jqadm' ) );

		$view = $this->userView( $groups, [
			'item' => ['locale.site.id' => $item->getId(), 'locale.site.label' => 'jqadm admin']
		], $siteFcn( $item ) );

		$object = new \Aimeos\Admin\JQAdm\Locale\Site\Standard( $view->context );
		$object = $this->policy( $object->setAimeos( \TestHelper::getAimeos() ), $view );

		try
		{
			$object->save();
			return $manager->get( $item->getId() );
		}
		finally
		{
			$manager->delete( $item->getId() );
		}
	}


	protected function policy( \Aimeos\Admin\JQAdm\Iface $client, \Aimeos\Base\View\Iface $view ) : \Aimeos\Admin\JQAdm\Iface
	{
		// the policy decorator added by JQAdm::create() checks the actions of the user
		$object = new \Aimeos\Admin\JQAdm\Common\Decorator\Policy( $client, $view->context, 'locale/site' );
		return $object->setAimeos( \TestHelper::getAimeos() )->setView( $view )->setObject( $object );
	}


	protected function siteId( string $code ) : string
	{
		return \Aimeos\MShop::create( $this->context, 'locale/site' )->find( $code )->getId();
	}


	protected function userView( array $groups, array $params, string $siteid = '' ) : \Aimeos\Base\View\Iface
	{
		$context = clone $this->context;
		$view = clone $this->view;
		$view->addHelper( 'access', new \Aimeos\Base\View\Helper\Access\Standard( $view, $groups ) );
		$view->addHelper( 'param', new \Aimeos\Base\View\Helper\Param\Standard( $view, $params ) );

		$user = \Aimeos\MShop::create( $context, 'customer' )->create( ['customer.siteid' => $siteid] );
		$context->setView( $view )->setUser( $user );
		$view->context = $context;

		return $view;
	}


	protected function getViewNoRender( $real = true )
	{
		$view = $this->getMockBuilder( \Aimeos\Base\View\Standard::class )
			->setConstructorArgs( array( [] ) )
			->onlyMethods( array( 'render' ) )
			->getMock();

		$view->method( 'render' )->willReturn( '' );

		$manager = \Aimeos\MShop::create( $this->context, 'locale/site' );

		$param = ['site' => 'unittest', 'id' => $real ? $manager->find( 'unittest' )->getId() : -1];
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $view, $param );
		$view->addHelper( 'param', $helper );

		$helper = new \Aimeos\Base\View\Helper\Config\Standard( $view, $this->context->config() );
		$view->addHelper( 'config', $helper );

		$helper = new \Aimeos\Base\View\Helper\Access\All( $this->view );
		$view->addHelper( 'access', $helper );

		return $view;
	}

}
