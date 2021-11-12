<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */


namespace Aimeos\Admin\JQAdm\Dashboard\Job;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelperJqadm::view();
		$this->context = \TestHelperJqadm::getContext();

		$this->object = new \Aimeos\Admin\JQAdm\Dashboard\Job\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testDelete()
	{
		$manager = \Aimeos\MAdmin::create( $this->context, 'job' );
		$item = $manager->create()->setLabel( 'jobdeletetest.csv' )->setPath( 'jobdeletetest.csv' );
		$manager->save( $item );

		$param = ['site' => 'unittest', 'id' => $item->getId()];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->delete();

		$this->assertStringContainsString( 'dashboard-job', $result );
		$this->assertStringNotContainsString( 'jobdeletetest.csv', $result );
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

		$manager = \Aimeos\MAdmin::create( $this->context, 'job' );
		$item = $manager->create()->setLabel( 'jobtest.csv' )->setPath( 'jobtest.csv' );
		$manager->save( $item );

		$param = ['site' => 'unittest', 'id' => $item->getId()];
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->get();

		$this->assertEmpty( $result );

		$manager->delete( $item->getId() );
		unlink( $path );
	}


	public function testSearch()
	{
		$result = $this->object->search();

		$this->assertStringContainsString( 'dashboard-job', $result );
		$this->assertStringContainsString( 'unittest job', $result );
	}


	protected function getViewNoRender()
	{
		$view = $this->getMockBuilder( \Aimeos\MW\View\Standard::class )
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
