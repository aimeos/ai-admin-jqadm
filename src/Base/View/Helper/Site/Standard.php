<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 * @package MW
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Site;


/**
 * View helper class for easy access to site information
 *
 * @package MW
 * @subpackage View
 */
class Standard extends \Aimeos\Base\View\Helper\Base implements Iface
{
	private \Aimeos\MShop\Locale\Item\Site\Iface $siteItem;


	/**
	 * Initializes the view helper
	 *
	 * @param \Aimeos\Base\View\Iface $view View object
	 */
	public function __construct( \Aimeos\Base\View\Iface $view )
	{
		parent::__construct( $view );
		$this->siteItem = $view->pageSiteItem;
	}


	/**
	 * Returns the site view helper
	 *
	 * @return \Aimeos\Base\View\Helper\Site\Iface Site view helper
	 */
	public function transform() : \Aimeos\Base\View\Helper\Site\Iface
	{
		return $this;
	}


	/**
	 * Checks if the item can be deleted or modified
	 *
	 * @param string|null $siteid ID of a site item
	 * @return bool TRUE if the item can be deleted/modified
	 */
	public function can( ?string $siteid ) : ?bool
	{
		$current = (string) $this->siteItem->getSiteId();

		if( !strncmp( $current, (string) $siteid, strlen( $current ) ) ) {
			return true;
		}

		return false;
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
	public function match( ?string $siteid ) : ?string
	{
		if( $this->siteItem->getSiteId() == $siteid ) {
			return $this->siteItem->getLabel();
		}

		return null;
	}


	/**
	 * Returns "mismatch" if the item is from another site
	 *
	 * @param string|null $siteid ID of a site item
	 * @return string|null "mismatch" if item is from another site, empty if not
	 */
	public function mismatch( ?string $siteid ) : ?string
	{
		return $this->siteItem->getSiteId() != $siteid ? 'mismatch' : '';
	}


	/**
	 * Returns "readonly" if the item is inherited from another site
	 *
	 * @param string|null $siteid ID of a site item
	 * @return string|null "readonly" if item is from a parent site, empty if not
	 */
	public function readonly( ?string $siteid ) : ?string
	{
		return $this->can( $siteid ) ? '' : 'readonly';
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
