<?php

/**
 * @copyright Aimeos GmbH (aimeos.com), 2022
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
		$tplconf = 'admin/jqadm/settings/theme/template-item';
		$default = 'settings/item-theme';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
