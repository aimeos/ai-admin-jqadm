<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


/**
 * Page decorator for JQAdm clients
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Page extends Base
{
	/**
	 * Sets the view object and adds the available sites and languages
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the admin output
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	public function setView( \Aimeos\MW\View\Iface $view )
	{
		$extdir = dirname( dirname( dirname( dirname( dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) ) ) ) );
		$aimeos = new \Aimeos\Bootstrap( array( $extdir ) );

		$view->pageSites = $this->getSites();
		$view->pageLanguages = $aimeos->getI18nList( 'admin' );

		$this->getClient()->setView( $view );
		return $this;
	}


	/**
	 * Returns the available site code/label pairs
	 *
	 * @return array Associative list of site codes as keys and site labels as values
	 */
	protected function getSites()
	{
		$list = array();
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'locale/site' );

		$search = $manager->createSearch();
		$search->setSortations( array( $search->sort( '+', 'locale.site.label' ) ) );

		foreach( $manager->searchItems( $search ) as $item ) {
			$list[$item->getCode()] = $item->getLabel();
		}

		return $list;
	}
}
