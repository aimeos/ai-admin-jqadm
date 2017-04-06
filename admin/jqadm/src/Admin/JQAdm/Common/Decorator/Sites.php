<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


/**
 * Sites decorator for JQAdm clients
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Sites extends Base
{
	/**
	 * Sets the view object and adds the available sites
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the admin output
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	public function setView( \Aimeos\MW\View\Iface $view )
	{
		$list = [];
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'locale/site' );

		$search = $manager->createSearch();
		$search->setSortations( array( $search->sort( '+', 'locale.site.label' ) ) );

		foreach( $manager->searchItems( $search ) as $item ) {
			$list[$item->getCode()] = $item->getLabel();
		}

		$view->sitesList = $list;

		$this->getClient()->setView( $view );
		return $this;
	}
}
