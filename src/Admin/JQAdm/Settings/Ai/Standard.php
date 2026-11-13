<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2026
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Settings\Ai;

sprintf( 'ai' ); // for translation


/**
 * AI settings implementation.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/settings/ai/name
	 * Name of the ai subpart used by the JQAdm settings implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Settings\Ai\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2026.10
	 */


	/** admin/jqadm/settings/ai/decorators/excludes
	 * Excludes decorators added by the "common" option from the settings JQAdm client
	 *
	 * Decorators extend the functionality of a class by adding new aspects
	 * (e.g. log what is currently done), executing the methods of the underlying
	 * class only in certain conditions (e.g. only for logged in users) or
	 * modify what is returned to the caller.
	 *
	 * This option allows you to remove a decorator added via
	 * "admin/jqadm/common/decorators/default" before they are wrapped
	 * around the JQAdm client.
	 *
	 *  admin/jqadm/settings/ai/decorators/excludes = array( 'decorator1' )
	 *
	 * This would remove the decorator named "decorator1" from the list of
	 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
	 * "admin/jqadm/common/decorators/default" to the JQAdm client.
	 *
	 * @param array List of decorator names
	 * @since 2026.10
	 * @see admin/jqadm/common/decorators/default
	 * @see admin/jqadm/settings/ai/decorators/global
	 * @see admin/jqadm/settings/ai/decorators/local
	 */

	/** admin/jqadm/settings/ai/decorators/global
	 * Adds a list of globally available decorators only to the settings JQAdm client
	 *
	 * Decorators extend the functionality of a class by adding new aspects
	 * (e.g. log what is currently done), executing the methods of the underlying
	 * class only in certain conditions (e.g. only for logged in users) or
	 * modify what is returned to the caller.
	 *
	 * This option allows you to wrap global decorators
	 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
	 *
	 *  admin/jqadm/settings/ai/decorators/global = array( 'decorator1' )
	 *
	 * This would add the decorator named "decorator1" defined by
	 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
	 *
	 * @param array List of decorator names
	 * @since 2026.10
	 * @see admin/jqadm/common/decorators/default
	 * @see admin/jqadm/settings/ai/decorators/excludes
	 * @see admin/jqadm/settings/ai/decorators/local
	 */

	/** admin/jqadm/settings/ai/decorators/local
	 * Adds a list of local decorators only to the settings JQAdm client
	 *
	 * Decorators extend the functionality of a class by adding new aspects
	 * (e.g. log what is currently done), executing the methods of the underlying
	 * class only in certain conditions (e.g. only for logged in users) or
	 * modify what is returned to the caller.
	 *
	 * This option allows you to wrap local decorators
	 * ("\Aimeos\Admin\JQAdm\Settings\Decorator\*") around the JQAdm client.
	 *
	 *  admin/jqadm/settings/ai/decorators/local = array( 'decorator2' )
	 *
	 * This would add the decorator named "decorator2" defined by
	 * "\Aimeos\Admin\JQAdm\Settings\Decorator\Decorator2" only to the JQAdm client.
	 *
	 * @param array List of decorator names
	 * @since 2026.10
	 * @see admin/jqadm/common/decorators/default
	 * @see admin/jqadm/settings/ai/decorators/excludes
	 * @see admin/jqadm/settings/ai/decorators/global
	 */


	/**
	 * Saves the AI provider settings.
	 *
	 * @return string|null HTML output
	 */
	public function save() : ?string
	{
		$current = $this->settings();
		$data = (array) $this->view()->param( 'ai', [] );

		$result = [];

		foreach( ['write', 'translate', 'imagine', 'isolate'] as $action )
		{
			$entry = $this->filter( (array) ( $data[$action] ?? [] ) );
			$extra = json_decode( (string) ( $entry['extra'] ?? '{}' ), true, 512, JSON_THROW_ON_ERROR );
			unset( $entry['extra'] );

			$entry = array_replace( (array) $extra, $entry );

			// empty settings inherit those of the application config
			if( empty( $entry ) ) {
				continue;
			}

			if( empty( $entry['provider'] ) )
			{
				$msg = $this->context()->i18n()->dt( 'admin', 'A provider is required for custom AI settings' );
				throw new \Aimeos\Admin\JQAdm\Exception( $msg );
			}

			$old = $current[$action] ?? [];

			// the stored API key is only kept for the same endpoint, so it's never sent to other ones
			if( isset( $old['api_key'] ) && $entry['provider'] === ( $old['provider'] ?? null )
				&& ( $entry['url'] ?? null ) === ( $old['url'] ?? null )
			) {
				$entry['api_key'] ??= $old['api_key'];
			}

			$result[$action] = $entry;
		}

		$this->context()->locale()->getSiteItem()->setConfigValue( 'admin/ai', $result );

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
	 * Removes empty settings.
	 *
	 * @param array $entry Provider settings
	 * @return array Provider settings without empty values
	 */
	protected function filter( array $entry ) : array
	{
		return array_filter( $entry, fn( $value ) => $value !== '' && $value !== null );
	}


	/**
	 * Returns the settings stored in the site config including legacy settings.
	 *
	 * Legacy settings are only returned until the AI settings of the site have been saved once,
	 * so the form always shows the settings which are used by the AI requests.
	 *
	 * @return array AI settings of the site
	 */
	protected function settings() : array
	{
		$site = $this->context()->locale()->getSiteItem();

		// saved settings replace the legacy ones even if they are empty
		if( array_key_exists( 'ai', (array) ( $site->getConfig()['admin'] ?? [] ) ) ) {
			return (array) $site->getConfigValue( 'admin/ai', [] );
		}

		$openai = (array) $site->getConfigValue( 'admin/jqadm/api/openai', [] );

		return [
			'write' => $this->legacy( $openai, 'openai', 'model', 'url' ),
			'translate' => $this->legacy( (array) $site->getConfigValue( 'admin/jqadm/api/translate', [] ), 'deepl', null, 'url' ),
			'imagine' => $this->legacy( $openai, 'openai', 'image-model', 'image-url' ),
			'isolate' => $this->legacy( (array) $site->getConfigValue( 'admin/jqadm/api/removebg', [] ), 'removebg' ),
		];
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
		if( empty( $data ) ) {
			return [];
		}

		$result = ['provider' => $provider, 'api_key' => $data['key'] ?? null];

		if( $modelKey ) {
			$result['model'] = $data[$modelKey] ?? null;
		}

		if( $urlKey && !empty( $data[$urlKey] ) ) {
			$result['url'] = preg_replace( '#/(?:v1/(?:chat/completions|images/generations)|v2)/?$#', '', (string) $data[$urlKey] );
		}

		return $this->filter( $result );
	}


	/**
	 * Renders the settings template.
	 *
	 * @param \Aimeos\Base\View\Iface $view View object
	 * @return string HTML output
	 */
	protected function render( \Aimeos\Base\View\Iface $view ) : string
	{
		/** admin/jqadm/settings/ai/template-item
		 * Relative path to the HTML body template of the ai subpart for settings.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in templates/admin/jqadm).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "default" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "default"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating the HTML code
		 * @since 2026.10
		 */

		$path = $view->config( 'admin/jqadm/settings/ai/template-item', 'settings/item-ai' );
		return $view->render( $path );
	}
}
