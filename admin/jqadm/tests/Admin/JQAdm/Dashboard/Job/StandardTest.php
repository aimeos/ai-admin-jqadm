<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */


namespace Aimeos\Admin\JQAdm\Dashboard\Job;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp()
	{
		$this->view = \TestHelperJqadm::getView();
		$this->context = \TestHelperJqadm::getContext();

		$this->object = new \Aimeos\Admin\JQAdm\Dashboard\Job\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testDelete()
	{
		$manager = \Aimeos\MAdmin\Factory::createManager( $this->context, 'job' );
		$item = $manager->createItem();
		$item->setLabel( 'jobdeletetest.csv' );
		$item->setResult( ['file' => 'jobdeletetest.csv'] );
		$manager->saveItem( $item );

		$param = ['site' => 'unittest', 'id' => $item->getId()];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->delete();

		$this->assertContains( 'dashboard-job', $result );
		$this->assertNotContains( 'jobdeletetest.csv', $result );
	}


	public function testDeleteException()
	{
		$mock = $this->getMockBuilder( '\Aimeos\MW\Filesystem\Manager\Standard' )
			->disableOriginalConstructor()
			->setMethods( ['get'] )
			->getMock();

		$mock->expects( $this->once() )->method( 'get' )
			->will( $this->throwException( new \RuntimeException() ) );

		$this->context->setFileSystemManager( $mock );
		$this->object->setView( $this->getViewNoRender() );
		$this->object->delete();
	}


	public function testDeleteMAdminException()
	{
		$mock = $this->getMockBuilder( '\Aimeos\MW\Filesystem\Manager\Standard' )
			->disableOriginalConstructor()
			->setMethods( ['get'] )
			->getMock();

		$mock->expects( $this->once() )->method( 'get' )
			->will( $this->throwException( new \Aimeos\MAdmin\Exception() ) );

		$this->context->setFileSystemManager( $mock );
		$this->object->setView( $this->getViewNoRender() );
		$this->object->delete();
	}


	public function testGet()
	{
		$dir = dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . '/tmp';
		$path = $dir . '/jobtest.csv';

		if( file_exists( $dir ) === false && mkdir( $dir, 0755, true ) === false ) {
			throw new \Exception( 'Cannot create temp directory' );
		}

		if( touch( $path ) === false ) {
			throw new \Exception( 'Cannot create test file' );
		}

		$manager = \Aimeos\MAdmin\Factory::createManager( $this->context, 'job' );
		$item = $manager->createItem();
		$item->setLabel( 'jobtest.csv' );
		$item->setResult( ['file' => 'jobtest.csv'] );
		$manager->saveItem( $item );

		$result = $this->object->get();

		$this->assertNull( $result );

		$manager->deleteItem( $item->getId() );
		unlink( $path );
	}


	public function testGetException()
	{
		$mock = $this->getMockBuilder( '\Aimeos\MW\Filesystem\Manager\Standard' )
			->disableOriginalConstructor()
			->setMethods( ['get'] )
			->getMock();

		$mock->expects( $this->once() )->method( 'get' )
			->will( $this->throwException( new \RuntimeException() ) );

		$this->context->setFileSystemManager( $mock );
		$this->object->setView( $this->getViewNoRender() );
		$this->object->get();
	}


	public function testGetMAdminException()
	{
		$mock = $this->getMockBuilder( '\Aimeos\MW\Filesystem\Manager\Standard' )
			->disableOriginalConstructor()
			->setMethods( ['get'] )
			->getMock();

		$mock->expects( $this->once() )->method( 'get' )
			->will( $this->throwException( new \Aimeos\MAdmin\Exception() ) );

		$this->context->setFileSystemManager( $mock );
		$this->object->setView( $this->getViewNoRender() );
		$this->object->get();
	}


	public function testSearch()
	{
		$result = $this->object->search();

		$this->assertContains( 'dashboard-job', $result );
		$this->assertContains( 'unittest job', $result );
	}


	public function testSearchException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Dashboard\Job\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClients' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \RuntimeException() ) );

		$object->setView( $this->getViewNoRender() );

		$object->search();
	}


	public function testSearchMAdminException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Admin\JQAdm\Dashboard\Job\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'getSubClients' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'getSubClients' )
			->will( $this->throwException( new \Aimeos\MAdmin\Exception() ) );

		$object->setView( $this->getViewNoRender() );

		$object->search();
	}


	protected function getViewNoRender()
	{
		$view = $this->getMockBuilder( '\Aimeos\MW\View\Standard' )
			->setConstructorArgs( array( [] ) )
			->setMethods( array( 'render', 'config' ) )
			->getMock();

		$param = ['site' => 'unittest', 'id' => -1];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, $param );
		$view->addHelper( 'param', $helper );

		$helper = new \Aimeos\MW\View\Helper\Access\Standard( $view, [] );
		$view->addHelper( 'access', $helper );

		return $view;
	}
}
