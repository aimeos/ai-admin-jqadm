<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
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
			$this->view()->param( 'product/catalog.lists.refid', [] ),
			$this->view()->param( 'product/supplier.lists.refid', [] )
		) );

		if( !empty( $prodIds ) )
		{
			$context = $this->getContext();
			$manager = \Aimeos\MShop::create( $context, 'product' );
			$domains = $context->getConfig()->get( 'admin/jqadm/product/domains', [] );

			$search = $manager->filter( true )->slice( 0, count( $prodIds ) );
			$search->setConditions( $search->compare( '==', 'product.id', $prodIds ) );

			$items = $manager->search( $search, $domains );

			if( !$items->isEmpty() ) {
				\Aimeos\MShop::create( $context, 'index' )->rebuild( $items );
			}
		}

		return $result;
	}
}
