<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2026
 */


namespace Aimeos\Admin\JQAdm\Settings;

use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;


#[AllowMockObjectsWithoutExpectations]
class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		\Aimeos\MShop::cache( true );

		$this->view = \TestHelper::view();
		$this->context = \TestHelper::context();

		$this->object = new \Aimeos\Admin\JQAdm\Settings\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelper::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testSave()
	{
		$image = $this->getMockBuilder( \Intervention\Image\Interfaces\ImageInterface::class )->disableOriginalConstructor()->getMock();

		$icon = new \Nyholm\Psr7\UploadedFile( $this->createStub( \Nyholm\Psr7\Stream::class ), 1000, UPLOAD_ERR_OK, 'test.gif' );
		$logo = new \Nyholm\Psr7\UploadedFile( $this->createStub( \Nyholm\Psr7\Stream::class ), 1000, UPLOAD_ERR_OK, 'test.gif' );

		$object = $this->getClientMock( ['createPreviews', 'image', 'mimetype', 'storeFile'] );
		$object->expects( $this->exactly( 2 ) )->method( 'mimetype' )->willReturn( 'image/gif' );
		$object->expects( $this->once() )->method( 'image' )->willReturn( $image );
		$object->expects( $this->once() )->method( 'createPreviews' );
		$object->expects( $this->exactly( 2 ) )->method( 'storeFile' );

		$this->saveWith( $object, ['icon' => $icon, 'logo' => $logo], ['item' => ['key' => ['subkey' => 'value']]] );
	}


	public function testSaveLogoSvg()
	{
		$svg = '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script><rect width="1"/></svg>';
		$logo = new \Nyholm\Psr7\UploadedFile( \Nyholm\Psr7\Stream::create( $svg ), strlen( $svg ), UPLOAD_ERR_OK, 'test.svg' );

		$object = $this->getClientMock( ['createPreviews'] );
		$object->expects( $this->never() )->method( 'createPreviews' );

		$this->saveWith( $object, ['logo' => $logo] );

		$fs = $this->context->fs( 'fs-media' );
		$path = $this->context->locale()->getSiteId() . 'd/logo.svg';

		$this->assertEquals( [$path], $this->view->item->getLogos() );
		$this->assertStringNotContainsString( 'script', $fs->read( $path ) );

		$fs->rm( $path );
	}


	public function testSaveSettingsConfig()
	{
		$context = clone $this->context;
		$manager = \Aimeos\MShop::create( $context, 'locale/site' );
		$item = $manager->insert( $manager->create()->setCode( 'jqadm-settings' )->setLabel( 'jqadm' ) );

		$view = clone $this->view;
		$view->addHelper( 'access', new \Aimeos\Base\View\Helper\Access\Standard( $view, ['admin'] ) );

		// admins can only change sites within their own site tree
		$user = \Aimeos\MShop::create( $context, 'customer' )->create( ['customer.siteid' => $item->getSiteId()] );
		$context->setView( $view )->setUser( $user );

		try
		{
			$item->setConfigValue( 'theme', ['--bs-primary' => '#000000'] );
			$item->setConfigValue( 'admin/ai', ['text' => ['model' => 'test']] );

			$this->assertEquals( '#000000', $manager->save( $item )->getConfigValue( 'theme/--bs-primary' ) );
		}
		finally
		{
			$manager->delete( $item->getId() );
		}
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
		$param = array( 'site' => 'unittest' );
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$result = $this->object->search();

		$this->assertStringContainsString( 'from-email', $result );
		$this->assertStringContainsString( 'href="#ai"', $result );
		$this->assertStringContainsString( 'id="ai"', $result );
		$this->assertStringNotContainsString( 'href="#api"', $result );
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


	/**
	 * Saves the settings with the given uploaded files and a mocked site manager
	 *
	 * @param \Aimeos\Admin\JQAdm\Iface $object Settings client object
	 * @param array $files Uploaded files with "icon" and/or "logo" as keys
	 * @param array $params Additional request parameters
	 */
	protected function saveWith( \Aimeos\Admin\JQAdm\Iface $object, array $files, array $params = [] )
	{
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, ['site' => 'unittest'] + $params );
		$this->view->addHelper( 'param', $helper );

		$request = $this->createStub( \Psr\Http\Message\ServerRequestInterface::class );
		$request->method( 'getUploadedFiles' )->willReturn( ['media' => $files] );

		$helper = new \Aimeos\Base\View\Helper\Request\Standard( $this->view, $request, '127.0.0.1', 'test' );
		$this->view->addHelper( 'request', $helper );

		$mock = $this->getMockBuilder( \Aimeos\MShop\Locale\Manager\Site\Standard::class )
			->setConstructorArgs( [$this->context] )
			->getMock();

		\Aimeos\MShop::inject( \Aimeos\MShop\Locale\Manager\Site\Standard::class, $mock );

		try {
			$object->setView( $this->view )->save();
		} finally {
			\Aimeos\MShop::inject( \Aimeos\MShop\Locale\Manager\Site\Standard::class, null );
		}
	}


	public function getClientMock( $methods, $real = true )
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Settings\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelper::getTemplatePaths() ) )
			->onlyMethods( (array) $methods )
			->getMock();

		$object->setAimeos( \TestHelper::getAimeos() );
		$object->setView( $this->getViewNoRender( $real ) );

		return $object;
	}


	protected function getViewNoRender( $real = true )
	{
		$view = $this->getMockBuilder( \Aimeos\Base\View\Standard::class )
			->setConstructorArgs( array( [] ) )
			->onlyMethods( array( 'render' ) )
			->getMock();

		$view->method( 'render' )->willReturn( '' );

		$param = ['site' => 'unittest'];
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $view, $param );
		$view->addHelper( 'param', $helper );

		$helper = new \Aimeos\Base\View\Helper\Config\Standard( $view, $this->context->config() );
		$view->addHelper( 'config', $helper );

		$helper = new \Aimeos\Base\View\Helper\Access\Standard( $view, [] );
		$view->addHelper( 'access', $helper );

		$trans = new \Aimeos\Base\Translation\None( 'de_DE' );
		$helper = new \Aimeos\Base\View\Helper\Translate\Standard( $view, $trans );
		$view->addHelper( 'translate', $helper );

		return $view;
	}
}
