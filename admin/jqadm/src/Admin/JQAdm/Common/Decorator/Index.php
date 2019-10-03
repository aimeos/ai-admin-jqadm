<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


/**
 * Index rebuild decorator for JQAdm clients
 *
 * @package Admin
 * @subpackage JQAdm
 * @deprecated 2020.01 Not required any more
 */
class Index extends Base
{
	/**
	 * Rebuilds the index after saving the item
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function save()
	{
		$result = $this->getClient()->save();
		$prodIds = array_unique( $this->getView()->param( 'product/catalog.lists.refid', [] ) );

		if( !empty( $prodIds ) )
		{
			$context = $this->getContext();
			$manager = \Aimeos\MShop::create( $context, 'product' );
			$domains = ['attribute', 'price', 'product', 'supplier', 'text'];

			$search = $manager->createSearch( true )->setSlice( count( $prodIds ) );
			$search->setConditions( $search->compare( '==', 'product.id', $prodIds ) );

			$items = $manager->searchItems( $search, $domains );
			\Aimeos\MShop::create( $context, 'index' )->rebuildIndex( $items );
		}

		return $result;
	}
}
