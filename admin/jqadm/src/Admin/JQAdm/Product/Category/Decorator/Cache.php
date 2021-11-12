<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Category\Decorator;


/**
 * Cache cleanup decorator for product category JQAdm client
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Cache extends \Aimeos\Admin\JQAdm\Common\Decorator\Base
{
	/**
	 * Clears the cache after saving the item
	 *
	 * @return string|null Output to display or null for none
	 */
	public function save() : ?string
	{
		$result = $this->getClient()->save();
		$tags = ['catalog'];

		foreach( $this->view()->param( 'category', [] ) as $entry ) {
			$tags[] = 'catalog-' . $entry['catalog.id'];
		}

		$this->getContext()->getCache()->deleteByTags( $tags );

		return $result;
	}
}
