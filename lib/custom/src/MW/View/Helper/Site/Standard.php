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
class Standard extends \Aimeos\MW\View\Helper\Base implements Iface
{
	private $siteItem;


	/**
	 * Initializes the view helper
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 */
	public function __construct( \Aimeos\MW\View\Iface $view )
	{
		parent::__construct( $view );
		$this->siteItem = $view->pageSiteItem;
	}


	/**
	 * Returns the site view helper
	 *
	 * @return Aimeos\MW\View\Helper\Site\Iface Site view helper
	 */
	public function transform()
	{
		return $this;
	}


	/**
	 * Returns the site label of the current site
	 *
	 * @return string|null Label of the site item or null if not available
	 */
	public function label()
	{
		return $this->siteItem->getLabel();
	}


	/**
	 * Returns the label of the matching site
	 *
	 * @param string $siteid ID of a site item
	 * @return string|null Label of the site item or null if not found
	 */
	public function match( $siteid )
	{
		if( $this->siteItem->getId() == $siteid ) {
			return $this->siteItem->getLabel();
		}
	}


	/**
	 * Returns "readonly" if the item is inherited from another site
	 *
	 * @param string $siteid ID of a site item
	 * @return string|null "readonly" if item is from a parent site, null if not
	 */
	public function readonly( $siteid )
	{
		if( $this->siteItem->getId() != $siteid ) {
			return 'readonly';
		}
	}


	/**
	 * Returns the site ID of the current site
	 *
	 * @return string|null Site ID or null if not available
	 */
	public function siteid()
	{
		return $this->siteItem->getId();
	}
}
