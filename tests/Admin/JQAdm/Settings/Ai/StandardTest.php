<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2026
 */


namespace Aimeos\Admin\JQAdm\Settings\Ai;

use PHPUnit\Framework\Attributes\DataProvider;


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


	public static function otherEndpoints() : array
	{
		return [
			'provider' => [['provider' => 'ollama']],
			'url' => [['provider' => 'openai', 'url' => 'https://example.test']],
		];
	}


	public function testSearchDoesNotRenderApiKey()
	{
		$this->context->locale()->getSiteItem()->setConfigValue( 'admin/ai', [
			'write' => ['provider' => 'openai', 'model' => 'test', 'api_key' => 'secret'],
		] );

		$result = (string) $this->object->search();

		$this->assertStringContainsString( 'id="ai"', $result );
		$this->assertStringContainsString( 'name="ai[write][provider]"', $result );
		$this->assertStringContainsString( 'value="openai"', $result );
		$this->assertStringNotContainsString( 'secret', $result );
	}


	public function testSearchShowsSiteSettingsOnly()
	{
		$this->context->locale()->getSiteItem()->setConfigValue( 'admin/ai', ['write' => ['provider' => 'mistral']] );
		$this->context->config()->set( 'admin/ai', [
			'write' => ['provider' => 'config-provider', 'model' => 'config-model'],
			'imagine' => ['provider' => 'config-provider', 'api_key' => 'config-secret', 'url' => 'https://config.test', 'access_token' => 'config-token'],
		] );

		$result = (string) $this->object->search();

		$this->assertStringContainsString( 'value="mistral"', $result );
		$this->assertStringNotContainsString( 'config-', $result );
	}


	public function testSearchUsesLegacySettings()
	{
		$this->context->locale()->getSiteItem()->setConfig( [] )->setConfigValue( 'admin/jqadm/api/openai', ['key' => 'legacy-secret', 'model' => 'legacy-model'] );

		$result = (string) $this->object->search();

		$this->assertStringContainsString( 'value="legacy-model"', $result );
		$this->assertStringNotContainsString( 'legacy-secret', $result );
	}


	public function testSearchIgnoresLegacyAfterSave()
	{
		$this->context->locale()->getSiteItem()->setConfigValue( 'admin/ai', [] );
		$this->context->locale()->getSiteItem()->setConfigValue( 'admin/jqadm/api/openai', ['key' => 'legacy-secret', 'model' => 'legacy-model'] );

		$this->assertStringNotContainsString( 'legacy-model', (string) $this->object->search() );
	}


	public function testSaveStoresFlatProviderSettings()
	{
		$result = $this->save( [
			'write' => ['provider' => 'azure', 'model' => 'gpt', 'url' => 'https://example.test', 'extra' => '{"resource":"test"}'],
			'translate' => ['provider' => 'deepl'],
		], ['write' => ['provider' => 'azure', 'api_key' => 'secret', 'url' => 'https://example.test']] );

		$this->assertSame( [
			'write' => ['resource' => 'test', 'provider' => 'azure', 'model' => 'gpt', 'url' => 'https://example.test', 'api_key' => 'secret'],
			'translate' => ['provider' => 'deepl'],
		], $result );
	}


	#[DataProvider('otherEndpoints')]
	public function testSaveDropsApiKeyForOtherEndpoint( array $entry )
	{
		$result = $this->save( ['write' => $entry], ['write' => ['provider' => 'openai', 'api_key' => 'secret']] );

		$this->assertSame( ['write' => $entry], $result );
	}


	public function testSaveDoesNotCopyConfigApiKey()
	{
		$this->context->config()->set( 'admin/ai/write', ['provider' => 'openai', 'api_key' => 'config-secret'] );

		$result = $this->save( ['write' => ['provider' => 'ollama', 'url' => 'https://ollama.example.test']] );

		$this->assertSame( ['write' => ['provider' => 'ollama', 'url' => 'https://ollama.example.test']], $result );
	}


	public function testSaveIgnoresUnknownActions()
	{
		$result = $this->save( ['write' => ['provider' => 'openai'], 'unknown' => ['provider' => 'openai']] );

		$this->assertSame( ['write' => ['provider' => 'openai']], $result );
	}


	public function testSaveEmptyInheritsConfigSettings()
	{
		$result = $this->save( ['write' => ['provider' => '', 'model' => '', 'api_key' => '', 'url' => '', 'extra' => '']],
			['write' => ['provider' => 'openai', 'api_key' => 'secret']] );

		$this->assertSame( [], $result );
	}


	public function testSaveRequiresProvider()
	{
		$this->expectException( \Aimeos\Admin\JQAdm\Exception::class );
		$this->expectExceptionMessage( 'A provider is required for custom AI settings' );

		$this->save( ['write' => ['model' => 'gpt-mini']] );
	}


	/**
	 * Saves the submitted AI settings and returns the AI settings stored in the site config
	 *
	 * @param array $ai Submitted AI settings by action
	 * @param array $stored AI settings already stored in the site config
	 * @return mixed AI settings stored in the site config after saving
	 */
	protected function save( array $ai, array $stored = [] )
	{
		$site = $this->context->locale()->getSiteItem()->setConfigValue( 'admin/ai', $stored );
		$this->view->addHelper( 'param', new \Aimeos\Base\View\Helper\Param\Standard( $this->view, ['ai' => $ai] ) );

		$this->object->save();

		return $site->getConfigValue( 'admin/ai' );
	}
}
