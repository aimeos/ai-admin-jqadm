<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2024
 */


namespace Aimeos\Admin\JQAdm\Settings;


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
		$param = array(
			'site' => 'unittest',
			'item' => array(
				'key' => [
					'subkey' => 'value',
				],
			),
		);

		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );

		$image = $this->getMockBuilder( \Intervention\Image\Interfaces\ImageInterface::class )->disableOriginalConstructor()->getMock();

		$stream1 = $this->getMockBuilder( \Nyholm\Psr7\Stream::class )->disableOriginalConstructor()->getMock();
		$stream2 = $this->getMockBuilder( \Nyholm\Psr7\Stream::class )->disableOriginalConstructor()->getMock();

		$icon = new \Nyholm\Psr7\UploadedFile( $stream1, 1000, UPLOAD_ERR_OK, 'test.gif' );
		$logo = new \Nyholm\Psr7\UploadedFile( $stream2, 1000, UPLOAD_ERR_OK, 'test.gif' );

		$request = $this->getMockBuilder( \Psr\Http\Message\ServerRequestInterface::class )->getMock();

		$request->expects( $this->any() )->method( 'getUploadedFiles' )
			->willReturn( ['media' => ['icon' => $icon, 'logo' => $logo]] );

		$helper = new \Aimeos\Base\View\Helper\Request\Standard( $this->view, $request, '127.0.0.1', 'test' );
		$this->view->addHelper( 'request', $helper );

		$object = $this->getClientMock( ['createPreviews', 'image', 'mimetype', 'storeFile'] );
		$object->expects( $this->exactly( 2 ) )->method( 'mimetype' )->willReturn( 'image/gif' );
		$object->expects( $this->once() )->method( 'image' )->willReturn( $image );
		$object->expects( $this->once() )->method( 'createPreviews' );
		$object->expects( $this->exactly( 2 ) )->method( 'storeFile' );
		$object->setView( $this->view );

		$mock = $this->getMockBuilder( \Aimeos\MShop\Locale\Manager\Site\Standard::class )
			->setConstructorArgs( [$this->context] )
			->getMock();

		\Aimeos\MShop::inject( \Aimeos\MShop\Locale\Manager\Site\Standard::class, $mock );

		$object->save();

		\Aimeos\MShop::inject( \Aimeos\MShop\Locale\Manager\Site\Standard::class, null );
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
