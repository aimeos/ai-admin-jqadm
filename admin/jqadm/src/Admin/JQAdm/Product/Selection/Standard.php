<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Selection;

sprintf( 'selection' ); // for translation


/**
 * Default implementation of product selection JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/selection/name
	 * Name of the selection subpart used by the JQAdm product implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Product\Selection\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2016.04
	 * @category Developer
	 */


	/**
	 * Copies a resource
	 *
	 * @return string|null HTML output
	 */
	public function copy() : ?string
	{
		$view = $this->getObject()->data( $this->view() );
		$view->selectionData = $this->toArray( $view->item, true );
		$view->selectionBody = parent::copy();

		return $this->render( $view );
	}


	/**
	 * Creates a new resource
	 *
	 * @return string|null HTML output
	 */
	public function create() : ?string
	{
		$view = $this->getObject()->data( $this->view() );
		$siteid = $this->getContext()->getLocale()->getSiteId();
		$data = $view->param( 'selection', [] );

		foreach( $data as $idx => $entry )
		{
			$data[$idx]['product.lists.siteid'] = $siteid;
			$data[$idx]['product.siteid'] = $siteid;
		}

		$view->selectionData = $data;
		$view->selectionBody = parent::create();

		return $this->render( $view );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null HTML output
	 */
	public function get() : ?string
	{
		$view = $this->getObject()->data( $this->view() );
		$view->selectionData = $this->toArray( $view->item );
		$view->selectionBody = parent::get();

		return $this->render( $view );
	}


	/**
	 * Saves the data
	 *
	 * @return string|null HTML output
	 */
	public function save() : ?string
	{
		$view = $this->view();

		if( in_array( $view->item->getType(), ['group', 'select'] ) )
		{
			$this->fromArray( $view->item, $view->param( 'selection', [] ) );
			$view->selectionBody = parent::save();
		}

		return null;
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( string $type, string $name = null ) : \Aimeos\Admin\JQAdm\Iface
	{
		/** admin/jqadm/product/selection/decorators/excludes
		 * Excludes decorators added by the "common" option from the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "admin/jqadm/common/decorators/default" before they are wrapped
		 * around the JQAdm client.
		 *
		 *  admin/jqadm/product/selection/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/selection/decorators/global
		 * @see admin/jqadm/product/selection/decorators/local
		 */

		/** admin/jqadm/product/selection/decorators/global
		 * Adds a list of globally available decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/selection/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/selection/decorators/excludes
		 * @see admin/jqadm/product/selection/decorators/local
		 */

		/** admin/jqadm/product/selection/decorators/local
		 * Adds a list of local decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Product\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/selection/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/selection/decorators/excludes
		 * @see admin/jqadm/product/selection/decorators/global
		 */
		return $this->createSubClient( 'product/selection/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/product/selection/subparts
		 * List of JQAdm sub-clients rendered within the product selection section
		 *
		 * The output of the frontend is composed of the code generated by the JQAdm
		 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
		 * that are responsible for rendering certain sub-parts of the output. The
		 * sub-clients can contain JQAdm clients themselves and therefore a
		 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
		 * the output that is placed inside the container of its parent.
		 *
		 * At first, always the JQAdm code generated by the parent is printed, then
		 * the JQAdm code of its sub-clients. The order of the JQAdm sub-clients
		 * determines the order of the output of these sub-clients inside the parent
		 * container. If the configured list of clients is
		 *
		 *  array( "subclient1", "subclient2" )
		 *
		 * you can easily change the order of the output by reordering the subparts:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
		 *
		 * You can also remove one or more parts if they shouldn't be rendered:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
		 *
		 * As the clients only generates structural JQAdm, the layout defined via CSS
		 * should support adding, removing or reordering content by a fluid like
		 * design.
		 *
		 * @param array List of sub-client names
		 * @since 2016.01
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/product/selection/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object without referenced domain items
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Product\Item\Iface Modified product item
	 */
	protected function fromArray( \Aimeos\MShop\Product\Item\Iface $item, array $data ) : \Aimeos\MShop\Product\Item\Iface
	{
		$context = $this->getContext();
		$manager = \Aimeos\MShop::create( $context, 'product' );
		$listManager = \Aimeos\MShop::create( $context, 'product/lists' );

		$prodIds = map( $data )->col( 'product.id' )->toArray();
		$filter = $manager->filter()->add( ['product.id' => $prodIds] );
		$prodItems = $manager->search( $filter, ['attribute' => ['variant']] );

		$listItems = $item->getListItems( 'product', 'default', null, false );
		$prodIds = [];

		foreach( $data as $idx => $entry )
		{
			if( ( $litem = $item->getListItem( 'product', 'default', $entry['product.id'], false ) ) === null ) {
				$litem = $listManager->create()->setType( 'default' );
			}

			if( ( $refItem = $prodItems->get( $entry['product.id'] ) ) === null
				&& ( $refItem = $litem->getRefItem() ) === null
			) {
				$refItem = $manager->create()->setType( 'default' );
			}

			$litem->fromArray( $entry, true )->setPosition( $idx );
			$refItem->fromArray( $entry, true );

			if( isset( $entry['attr'] ) ) {
				$refItem = $this->fromArrayAttributes( $refItem, $entry['attr'] );
			}

			$item->addListItem( 'product', $litem, $manager->save( $refItem ) );

			$prodIds[] = $data[$idx]['stock.productid'] = $refItem->getId();
			unset( $listItems[$litem->getId()] );
		}

		$this->fromArrayStocks( $prodIds, $data );

		return $item->deleteListItems( $listItems->toArray() );
	}


	/**
	 * Updates the variant attributes of the given product item
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $refItem Article item object
	 * @param array $entry Associative list of key/values for product attribute references
	 * @return \Aimeos\MShop\Product\Item\Iface Updated artice item object
	 */
	protected function fromArrayAttributes( \Aimeos\MShop\Product\Item\Iface $refItem, array $entry )
	{
		$listManager = \Aimeos\MShop::create( $this->getContext(), 'product/lists' );
		$litems = $refItem->getListItems( 'attribute', 'variant', null, false );
		$pos = 0;

		foreach( $entry as $attr )
		{
			if( !isset( $attr['product.lists.refid'] ) || $attr['product.lists.refid'] == '' ) {
				continue;
			}

			if( ( $litem = $refItem->getListItem( 'attribute', 'variant', $attr['product.lists.refid'], false ) ) === null ) {
				$litem = $listManager->create()->setType( 'variant' );
			}

			$litem = $litem->fromArray( $attr, true )->setPosition( $pos++ );

			$refItem->addListItem( 'attribute', $litem, $litem->getRefItem() );
			unset( $litems[$litem->getId()] );
		}

		return $refItem->deleteListItems( $litems->toArray() );
	}


	/**
	 * Updates the stocklevels for the products
	 *
	 * @param array $data List of product codes
	 * @param array $data Data array
	 */
	protected function fromArrayStocks( array $prodIds, array $data )
	{
		$manager = \Aimeos\MShop::create( $this->getContext(), 'stock' );

		$search = $manager->filter()->slice( 0, count( $prodIds ) )->add( [
			'stock.productid' => $prodIds,
			'stock.type' => 'default',
		] );

		$stockItems = $manager->search( $search );
		$map = $stockItems->col( 'stock.id', 'stock.productid' );
		$list = [];

		foreach( $data as $entry )
		{
			if( !isset( $entry['stock.stocklevel'] ) ) {
				continue;
			}

			if( ( $stockItem = $stockItems->get( $map[$entry['stock.productid']] ?? null ) ) === null ) {
				$stockItem = $manager->create();
			}

			$stockItem->fromArray( $entry, true )->setType( 'default' );
			unset( $stockItems[$stockItem->getId()] );

			$list[] = $stockItem;
		}

		try
		{
			$manager->begin();
			$manager->delete( $stockItems->toArray() )->save( $list, false );
			$manager->commit();
		}
		catch( \Exception $e )
		{
			$manager->rollback();
			throw $e;
		}
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object including referenced domain items
	 * @param bool $copy True if items should be copied
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Product\Item\Iface $item, bool $copy = false ) : array
	{
		if( !in_array( $item->getType(), ['group', 'select'] ) ) {
			return [];
		}

		$data = [];
		$siteId = $this->getContext()->getLocale()->getSiteId();


		foreach( $item->getListItems( 'product', 'default', null, false ) as $id => $listItem )
		{
			if( ( $refItem = $listItem->getRefItem() ) === null ) {
				continue;
			}

			$list = $listItem->toArray( true ) + $refItem->toArray( true );

			if( $copy === true )
			{
				$list['product.lists.siteid'] = $siteId;
				$list['product.lists.id'] = '';
				$list['product.siteid'] = $siteId;
				$list['product.id'] = '';
				$list['product.code'] = $list['product.code'] . '_' . substr( md5( microtime( true ) ), -5 );
			}

			$idx = 0;

			foreach( $refItem->getListItems( 'attribute', 'variant', null, false ) as $litem )
			{
				if( ( $attrItem = $litem->getRefItem() ) !== null ) {
					$list['attr'][$idx++] = $litem->toArray( true ) + $attrItem->toArray( true );
				}
			}

			$list = array_merge( $list, $refItem->getStockItems( 'default' )->first( map() )->toArray() );

			$data[] = $list;
		}

		return $data;
	}


	/**
	 * Returns the rendered template including the view data
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	protected function render( \Aimeos\MW\View\Iface $view ) : string
	{
		/** admin/jqadm/product/selection/template-item
		 * Relative path to the HTML body template of the selection subpart for products.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in admin/jqadm/templates).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "default" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "default"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating the HTML code
		 * @since 2016.04
		 * @category Developer
		 */
		$tplconf = 'admin/jqadm/product/selection/template-item';
		$default = 'product/item-selection-standard';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
