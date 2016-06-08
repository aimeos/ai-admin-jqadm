<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


/**
 * Languages decorator for JQAdm clients
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Languages extends Base
{
	/**
	 * Sets the view object and adds the available languages
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the admin output
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	public function setView( \Aimeos\MW\View\Iface $view )
	{
		$extdir = dirname( dirname( dirname( dirname( dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) ) ) ) );
		$aimeos = new \Aimeos\Bootstrap( array( $extdir ) );

		$view->languagesList = $aimeos->getI18nList( 'admin' );

		$this->getClient()->setView( $view );
		return $this;
	}
}
