<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2020
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


/**
 * Index rebuild decorator for JQAdm clients
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Index extends Base
{
	/**
	 * Rebuilds the index after saving the item
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function save() : ?string
	{
		$result = $this->getClient()->save();
		$prodIds = array_unique( array_merge(
			$this->getView()->param( 'product/catalog.lists.refid', [] ),
			$this->getView()->param( 'product/supplier.lists.refid', [] )
		) );

		if( !empty( $prodIds ) )
		{
			$context = $this->getContext();
			$manager = \Aimeos\MShop::create( $context, 'product' );
			$domains = $context->getConfig()->get( 'admin/jqadm/product/domains', [] );

			$search = $manager->createSearch( true )->setSlice( count( $prodIds ) );
			$search->setConditions( $search->compare( '==', 'product.id', $prodIds ) );

			$items = $manager->searchItems( $search, $domains );
			\Aimeos\MShop::create( $context, 'index' )->rebuild( $items->toArray() );
		}

		return $result;
	}
}
