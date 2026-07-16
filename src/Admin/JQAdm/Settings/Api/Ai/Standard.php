<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2026
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Settings\Api\Ai;

sprintf( 'api' ); // for translation


/**
 * AI API settings implementation.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/**
	 * Saves the AI provider settings.
	 *
	 * @return string|null HTML output
	 */
	public function save() : ?string
	{
		$current = $this->settings();
		$data = (array) $this->view()->param( 'api/ai', [] );

		foreach( ['write', 'translate', 'imagine', 'isolate'] as $action )
		{
			$entry = array_filter( (array) ( $data[$action] ?? [] ), fn( $value ) => $value !== '' && $value !== null );
			$extra = json_decode( (string) ( $entry['extra'] ?? '{}' ), true, 512, JSON_THROW_ON_ERROR );
			unset( $entry['extra'] );

			if( empty( $entry['api_key'] ) && !empty( $current[$action]['api_key'] ) ) {
				$entry['api_key'] = $current[$action]['api_key'];
			}

			$entry = array_replace( (array) $extra, $entry );

			if( trim( (string) ( $entry['url'] ?? '' ) ) !== ''
				&& trim( (string) ( $entry['api_key'] ?? '' ) ) === ''
			) {
				$msg = $this->context()->i18n()->dt( 'admin', 'An API key is required for custom AI endpoints' );
				throw new \Aimeos\Admin\JQAdm\Exception( $msg );
			}

			$data[$action] = $entry;
		}

		$this->context()->locale()->getSiteItem()->setConfigValue( 'admin/ai', $data );

		return null;
	}


	/**
	 * Returns the resource.
	 *
	 * @return string|null HTML output
	 */
	public function search() : ?string
	{
		$view = $this->object()->data( $this->view() );
		$view->aiData = $this->settings();

		return $this->render( $view );
	}


	/**
	 * Returns a sub-client by name.
	 *
	 * @param string $type Sub-client type
	 * @param string|null $name Sub-client implementation name
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( string $type, ?string $name = null ) : \Aimeos\Admin\JQAdm\Iface
	{
		return $this->createSubClient( 'settings/api/ai/' . $type, $name );
	}


	/**
	 * Returns the configured sub-client names.
	 *
	 * @return array Sub-client names
	 */
	protected function getSubClientNames() : array
	{
		return (array) $this->context()->config()->get( 'admin/jqadm/settings/api/ai/subparts', [] );
	}


	/**
	 * Returns the consolidated settings with legacy fallbacks.
	 *
	 * @return array AI settings
	 */
	protected function settings() : array
	{
		$site = $this->context()->locale()->getSiteItem();
		$data = (array) $site->getConfigValue( 'admin/ai', [] );
		$defaults = (array) $this->context()->config()->get( 'admin/ai', [] );
		$openai = (array) $site->getConfigValue( 'admin/jqadm/api/openai', [] );

		$data += [
			'write' => array_replace(
				(array) ( $defaults['write'] ?? [] ),
				$this->legacy( $openai, 'openai', 'model', 'url' )
			),
			'translate' => array_replace(
				(array) ( $defaults['translate'] ?? [] ),
				$this->legacy( (array) $site->getConfigValue( 'admin/jqadm/api/translate', [] ), 'deepl', null, 'url' )
			),
			'imagine' => array_replace(
				(array) ( $defaults['imagine'] ?? [] ),
				$this->legacy( $openai, 'openai', 'image-model', 'image-url' )
			),
			'isolate' => array_replace(
				(array) ( $defaults['isolate'] ?? [] ),
				$this->legacy( (array) $site->getConfigValue( 'admin/jqadm/api/removebg', [] ), 'removebg' )
			),
		];

		return $data;
	}


	/**
	 * Converts legacy provider settings.
	 *
	 * @param array $data Legacy settings
	 * @param string $provider Provider name
	 * @param string|null $modelKey Legacy model key
	 * @param string|null $urlKey Legacy URL key
	 * @return array Flat provider settings
	 */
	protected function legacy( array $data, string $provider, ?string $modelKey = null,
		?string $urlKey = null ) : array
	{
		if( !$data ) {
			return [];
		}

		$result = ['provider' => $provider, 'api_key' => $data['key'] ?? null];

		if( $modelKey ) {
			$result['model'] = $data[$modelKey] ?? null;
		}

		if( $urlKey && !empty( $data[$urlKey] ) ) {
			$result['url'] = preg_replace( '#/(?:v1/(?:chat/completions|images/generations)|v2)/?$#', '', (string) $data[$urlKey] );
		}

		return array_filter( $result, fn( $value ) => $value !== '' && $value !== null );
	}


	/**
	 * Renders the settings template.
	 *
	 * @param \Aimeos\Base\View\Iface $view View object
	 * @return string HTML output
	 */
	protected function render( \Aimeos\Base\View\Iface $view ) : string
	{
		$path = $view->config( 'admin/jqadm/settings/api/ai/template-item', 'settings/item-api-ai' );
		return $view->render( $path );
	}
}
