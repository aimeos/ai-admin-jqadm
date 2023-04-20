<?php

/**
 * @copyright Aimeos GmbH (aimeos.com), 2022-2023
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Settings\Theme;

sprintf( 'theme' ); // for translation


/**
 * Default implementation of settings theme JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/settings/theme/name
	 * Name of the theme subpart used by the JQAdm settings implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Settings\Theme\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2022.10
	 */


	/**
	 * Saves the data
	 *
	 * @return string|null HTML output
	 */
	public function save() : ?string
	{
		$context = $this->context();
		$site = $context->locale()->getSiteItem();
		$site->setConfigValue( 'theme', $this->view()->param( 'theme', [] ) );

		return null;
	}


	/**
	 * Returns the resource
	 *
	 * @return string|null HTML output
	 */
	public function search() : ?string
	{
		$context = $this->context();
		$site = $context->locale()->getSiteItem();

		$themeData = $context->config()->get( 'client/html/theme-presets', [] );
		$themeData = array_replace_recursive( $themeData, $site->getConfigValue( 'theme', [] ) );

		$view = $this->object()->data( $this->view() );
		$view->themeData = $themeData;

		return $this->render( $view );
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( string $type, string $name = null ) : \Aimeos\Admin\JQAdm\Iface
	{
		return $this->createSubClient( 'settings/theme/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		return $this->context()->config()->get( 'admin/jqadm/settings/theme/subparts', [] );
	}


	/**
	 * Returns the rendered template including the view data
	 *
	 * @param \Aimeos\Base\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	protected function render( \Aimeos\Base\View\Iface $view ) : string
	{
		/** admin/jqadm/settings/theme/template-item
		 * Relative path to the HTML body template of the theme subpart for settings.
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
		$tplconf = 'admin/jqadm/settings/theme/template-item';
		$default = 'settings/item-theme';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
