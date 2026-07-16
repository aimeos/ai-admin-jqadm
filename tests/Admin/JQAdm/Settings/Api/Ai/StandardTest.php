<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2026
 */


namespace Aimeos\Admin\JQAdm\Settings\Api\Ai;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private \Aimeos\MShop\ContextIface $context;
	private Standard $object;
	private \Aimeos\Base\View\Iface $view;


	protected function setUp() : void
	{
		$this->context = \TestHelper::context();
		$this->view = \TestHelper::view();
		$this->object = new Standard( $this->context );
		$this->object->setAimeos( \TestHelper::getAimeos() );
		$this->object->setView( $this->view );
	}


	public function testSearchDoesNotRenderApiKey()
	{
		$this->context->locale()->getSiteItem()->setConfigValue( 'admin/ai', [
			'write' => ['provider' => 'openai', 'model' => 'test', 'api_key' => 'secret'],
		] );

		$result = $this->object->search();

		$this->assertStringContainsString( 'value="openai"', (string) $result );
		$this->assertStringNotContainsString( 'secret', (string) $result );
	}


	public function testSaveStoresFlatProviderSettings()
	{
		$this->context->locale()->getSiteItem()->setConfigValue( 'admin/ai', [
			'write' => ['provider' => 'openai', 'api_key' => 'secret'],
		] );
		$params = ['api' => ['ai' => [
			'write' => [
				'provider' => 'azure',
				'model' => 'gpt',
				'api_key' => '',
				'url' => 'https://example.test',
				'extra' => '{"resource":"test"}',
			],
			'translate' => ['provider' => 'deepl', 'api_key' => '', 'url' => '', 'extra' => ''],
			'imagine' => ['provider' => 'openai', 'model' => 'image', 'api_key' => '', 'url' => '', 'extra' => ''],
			'isolate' => ['provider' => 'removebg', 'api_key' => '', 'url' => '', 'extra' => ''],
		]]];
		$this->view->addHelper( 'param', new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $params ) );

		$this->object->save();
		$result = $this->context->locale()->getSiteItem()->getConfigValue( 'admin/ai/write' );

		$this->assertSame( [
			'resource' => 'test',
			'provider' => 'azure',
			'model' => 'gpt',
			'url' => 'https://example.test',
			'api_key' => 'secret',
		], $result );
	}


	public function testSaveRequiresApiKeyForCustomUrl()
	{
		$params = ['api' => ['ai' => [
			'write' => [
				'provider' => 'openai',
				'api_key' => '',
				'url' => 'https://example.test',
				'extra' => '',
			],
		]]];
		$this->view->addHelper( 'param', new \Aimeos\Base\View\Helper\Param\Standard( $this->view, $params ) );

		$this->expectException( \Aimeos\Admin\JQAdm\Exception::class );
		$this->expectExceptionMessage( 'An API key is required for custom AI endpoints' );

		$this->object->save();
	}
}
