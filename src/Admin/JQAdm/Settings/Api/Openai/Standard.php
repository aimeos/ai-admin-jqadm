<?php

/**
 * @copyright Aimeos GmbH (aimeos.com), 2024
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Settings\Api\Openai;

sprintf( 'api' ); // for translation


/**
 * Default implementation of settings api DeepL JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/settings/api/openai/name
	 * Name of the api DeepL subpart used by the JQAdm settings implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Settings\Api\Openai\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2024.10
	 */


	/**
	 * Saves the data
	 *
	 * @return string|null HTML output
	 */
	public function save() : ?string
	{
		$data = array_filter( $this->view()->param( 'api/openai', [] ), fn( $val ) => $val !== '' && $val !== null );
		$this->context()->locale()->getSiteItem()->setConfigValue( 'admin/jqadm/api/openai', $data );

		return null;
	}


	/**
	 * Returns the resource
	 *
	 * @return string|null HTML output
	 */
	public function search() : ?string
	{
		$view = $this->object()->data( $this->view() );
		$view->openaiData = $this->context()->locale()->getSiteItem()->getConfigValue( 'admin/jqadm/api/openai', [] );

		return $this->render( $view );
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( string $type, ?string $name = null ) : \Aimeos\Admin\JQAdm\Iface
	{
		/** admin/jqadm/settings/api/openai/decorators/excludes
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
		 *  admin/jqadm/settings/api/openai/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2024.10
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/settings/api/openai/decorators/global
		 * @see admin/jqadm/settings/api/openai/decorators/local
		 */

		/** admin/jqadm/settings/api/openai/decorators/global
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
		 *  admin/jqadm/settings/api/openai/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2024.10
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/settings/api/openai/decorators/excludes
		 * @see admin/jqadm/settings/api/openai/decorators/local
		 */

		/** admin/jqadm/settings/api/openai/decorators/local
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
		 *  admin/jqadm/settings/api/openai/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Settings\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2024.10
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/settings/api/openai/decorators/excludes
		 * @see admin/jqadm/settings/api/openai/decorators/global
		 */
		return $this->createSubClient( 'settings/api/openai/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		return $this->context()->config()->get( 'admin/jqadm/settings/api/openai/subparts', [] );
	}


	/**
	 * Returns the rendered template including the view data
	 *
	 * @param \Aimeos\Base\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	protected function render( \Aimeos\Base\View\Iface $view ) : string
	{
		/** admin/jqadm/settings/api/openai/template-item
		 * Relative path to the HTML body template of the api DeepL subpart for settings.
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
		 * @since 2016.04
		 */
		$tplconf = 'admin/jqadm/settings/api/openai/template-item';
		$default = 'settings/item-api-openai';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
