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
	 * @type string Last part of the JQAdm class name
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
	 * @type array List of decorator names
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
	 * @type array List of decorator names
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
	 * @type array List of decorator names
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
		 * @type string Relative path to the template creating the HTML code
		 * @since 2026.10
		 */

		$path = $view->config( 'admin/jqadm/settings/ai/template-item', 'settings/item-ai' );
		return $view->render( $path );
	}
}
