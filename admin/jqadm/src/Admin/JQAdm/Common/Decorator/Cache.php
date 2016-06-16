<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


/**
 * Cache cleanup decorator for JQAdm clients
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Cache extends Base
{
	/**
	 * Clears the cache after deleting the item
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function delete()
	{
		$result = $this->getClient()->delete();

		$ids = (array) $this->getView()->param( 'id' );
		$tags = array( 'product' );

		foreach( $ids as $id ) {
			$tags[] = 'product-' . $id;
		}

		$this->getContext()->getCache()->deleteByTags( $tags );

		return $result;
	}


	/**
	 * Clears the cache after saving the item
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function save()
	{
		$result = $this->getClient()->save();
		$item = $this->getView()->item;

		if( $item->getId() !== null )
		{
			$idtag = 'product-' . $item->getId();
			$this->getContext()->getCache()->deleteByTags( array( 'product', $idtag ) );
		}

		return $result;
	}
}
