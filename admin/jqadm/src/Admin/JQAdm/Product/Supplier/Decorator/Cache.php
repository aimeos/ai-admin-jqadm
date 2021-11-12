<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Supplier\Decorator;


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
		$tags = ['supplier'];

		foreach( $this->view()->param( 'supplier', [] ) as $entry ) {
			$tags[] = 'supplier-' . $entry['supplier.id'];
		}

		$this->getContext()->getCache()->deleteByTags( $tags );

		return $result;
	}
}
