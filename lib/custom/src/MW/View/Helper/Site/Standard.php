<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
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
	public function transform() : \Aimeos\MW\View\Helper\Site\Iface
	{
		return $this;
	}


	/**
	 * Returns the site label of the current site
	 *
	 * @return string|null Label of the site item or null if not available
	 */
	public function label() : ?string
	{
		return $this->siteItem->getLabel();
	}


	/**
	 * Returns the label of the matching site
	 *
	 * @param string|null $siteid ID of a site item
	 * @return string|null Label of the site item or null if not found
	 */
	public function match( string $siteid = null ) : ?string
	{
		if( $this->siteItem->getSiteId() == $siteid ) {
			return $this->siteItem->getLabel();
		}

		return null;
	}


	/**
	 * Returns "readonly" if the item is inherited from another site
	 *
	 * @param string|null $siteid ID of a site item
	 * @return string|null "readonly" if item is from a parent site, null if not
	 */
	public function readonly( string $siteid = null ) : ?string
	{
		if( $this->siteItem->getSiteId() != $siteid ) {
			return 'readonly';
		}

		return null;
	}


	/**
	 * Returns the site ID of the current site
	 *
	 * @return string|null Site ID or null if not available
	 */
	public function siteid() : ?string
	{
		return $this->siteItem->getSiteId();
	}
}
