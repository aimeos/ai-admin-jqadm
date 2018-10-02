<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 * @package MW
 * @subpackage View
 */


namespace Aimeos\MW\View\Helper\Site;


/**
 * View helper class for easy access to site information
 *
 * @package MW
 * @subpackage View
 */
interface Iface extends \Aimeos\MW\View\Helper\Iface
{
	/**
	 * Returns the site view helper
	 *
	 * @return Aimeos\MW\View\Helper\Site\Iface Site view helper
	 */
	public function transform();

	/**
	 * Returns the site label of the current site
	 *
	 * @return string|null Label of the site item or null if not available
	 */
	public function label();

	/**
	 * Returns the label of the matching site
	 *
	 * @param string $siteid ID of a site item
	 * @return string|null Label of the site item or null if not found
	 */
	public function match( $siteid );

	/**
	 * Returns "readonly" if the item is inherited from another site
	 *
	 * @param string $siteid ID of a site item
	 * @return string|null "readonly" if item is from a parent site, null if not
	 */
	public function readonly( $siteid );

	/**
	 * Returns the site ID of the current site
	 *
	 * @return string|null Site ID or null if not available
	 */
	public function siteid();
}
