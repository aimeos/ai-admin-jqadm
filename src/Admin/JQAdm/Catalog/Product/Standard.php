<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2022
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Catalog\Product;

sprintf( 'product' ); // for translation


/**
 * Default implementation of catalog product JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/catalog/product/name
	 * Name of the product subpart used by the JQAdm catalog implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Catalog\Product\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2017.07
	 * @category Developer
	 */


	/**
	 * Adds the required data used in the template
	 *
	 * @param \Aimeos\Base\View\Iface $view View object
	 * @return \Aimeos\Base\View\Iface View object with assigned parameters
	 */
	public function data( \Aimeos\Base\View\Iface $view ) : \Aimeos\Base\View\Iface
	{
		$manager = \Aimeos\MShop::create( $this->context(), 'product/lists/type' );

		$search = $manager->filter( true )->slice( 0, 10000 )
			->add( ['product.lists.type.domain' => 'catalog'] )
			->order( 'product.lists.type.position' );

		$view->productListTypes = $manager->search( $search );

		return $view;
	}


	/**
	 * Copies a resource
	 *
	 * @return string|null HTML output
	 */
	public function copy() : ?string
	{
		$view = $this->object()->data( $this->view() );
		$view->productBody = parent::copy();

		return $this->render( $view );
	}


	/**
	 * Creates a new resource
	 *
	 * @return string|null HTML output
	 */
	public function create() : ?string
	{
		$view = $this->object()->data( $this->view() );
		$view->productBody = parent::create();

		return $this->render( $view );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null HTML output
	 */
	public function get() : ?string
	{
		$total = 0;
		$view = $this->object()->data( $this->view() );
		$manager = \Aimeos\MShop::create( $this->context(), 'product' );

		$filter = $manager->filter();
		$func = $filter->make( 'product:has', ['catalog', ['default', 'promotion'], $view->item->getId()] );

		$params = $this->storeFilter( $view->param( 'cp', [] ), 'catalogproduct' );
		$filter = $this->initCriteria( $filter, $params )->add( $func, '!=', null );
		$products = $manager->search( $filter, [], $total );

		$view->productItems = $products;
		$view->productData = $this->toArray( $view->item, $products );
		$view->productBody = parent::get();
		$view->productTotal = $total;

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

		$manager = \Aimeos\MShop::create( $this->context(), 'index' );
		$manager->begin();

		try
		{
			$this->storeFilter( $view->param( 'cp', [] ), 'catalogproduct' );
			$this->fromArray( $view->item, $view->param( 'product', [] ) );
			$view->productBody = parent::save();

			$manager->commit();
		}
		catch( \Exception $e )
		{
			$manager->rollback();
			throw $e;
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
		/** admin/jqadm/catalog/product/decorators/excludes
		 * Excludes decorators added by the "common" option from the catalog JQAdm client
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
		 *  admin/jqadm/catalog/product/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/catalog/product/decorators/global
		 * @see admin/jqadm/catalog/product/decorators/local
		 */

		/** admin/jqadm/catalog/product/decorators/global
		 * Adds a list of globally available decorators only to the catalog JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/catalog/product/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/catalog/product/decorators/excludes
		 * @see admin/jqadm/catalog/product/decorators/local
		 */

		/** admin/jqadm/catalog/product/decorators/local
		 * Adds a list of local decorators only to the catalog JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Catalog\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/catalog/product/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Catalog\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/catalog/product/decorators/excludes
		 * @see admin/jqadm/catalog/product/decorators/global
		 */
		return $this->createSubClient( 'catalog/product/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/catalog/product/subparts
		 * List of JQAdm sub-clients rendered within the catalog product section
		 *
		 * The output of the frontend is composed of the code generated by the JQAdm
		 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
		 * that are responsible for rendering certain sub-parts of the output. The
		 * sub-clients can contain JQAdm clients themselves and therefore a
		 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
		 * the output that is placed inside the container of its parent.
		 *
		 * At first, always the JQAdm code generated by the parent is printed, then
		 * the JQAdm code of its sub-clients. The product of the JQAdm sub-clients
		 * determines the product of the output of these sub-clients inside the parent
		 * container. If the configured list of clients is
		 *
		 *  array( "subclient1", "subclient2" )
		 *
		 * you can easily change the product of the output by reproducting the subparts:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
		 *
		 * You can also remove one or more parts if they shouldn't be rendered:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
		 *
		 * As the clients only generates structural JQAdm, the layout defined via CSS
		 * should support adding, removing or reproducting content by a fluid like
		 * design.
		 *
		 * @param array List of sub-client names
		 * @since 2017.07
		 * @category Developer
		 */
		return $this->context()->config()->get( 'admin/jqadm/catalog/product/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Catalog\Item\Iface $item Catalog item object without referenced domain items
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Catalog\Item\Iface Modified catalog item
	 */
	protected function fromArray( \Aimeos\MShop\Catalog\Item\Iface $item, array $data ) : \Aimeos\MShop\Catalog\Item\Iface
	{
		if( empty( $prodIds = $this->val( $data, 'product.lists.parentid', [] ) ) ) {
			return $item;
		}

		$manager = \Aimeos\MShop::create( $this->context(), 'product' );
		$filter = $manager->filter()->add( ['product.id' => $prodIds] )->slice( 0, count( $prodIds ) );
		$products = $manager->search( $filter, ['catalog'] );

		$id = $item->getId();
		$listItem = $manager->createListItem()->setRefId( $id );
		$listItems = $products->getListItems( 'catalog', null, null, false )
			->flat( 1 )->col( null, 'product.lists.id' );

		foreach( (array) $prodIds as $idx => $prodId )
		{
			if( ( $product = $products->get( $prodId ) ) === null ) {
				continue;
			}

			$listId = $this->val( $data, 'product.lists.id/' . $idx );
			$listItem = $listItems->get( $listId ) ?: clone $listItem;

			$listItem->setType( $this->val( $data, 'product.lists.type/' . $idx, 'default' ) )
				->setConfig( (array) json_decode( $this->val( $data, 'product.lists.config/' . $idx, '{}' ) ) )
				->setPosition( (int) $this->val( $data, 'product.lists.position/' . $idx, 0 ) )
				->setStatus( (int) $this->val( $data, 'product.lists.status/' . $idx, 1 ) )
				->setDateStart( $this->val( $data, 'product.lists.datestart/' . $idx ) )
				->setDateEnd( $this->val( $data, 'product.lists.dateend/' . $idx ) );

			$product->addListItem( 'catalog', $listItem );
			$listItems->remove( $listItem->getId() );
		}

		\Aimeos\MShop::create( $this->context(), 'index' )->save( $products );

		return $item;
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\Map $listItems Catalog list items implementing \Aimeos\MShop\Common\Item\Lists\Iface and referencing the products
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Catalog\Item\Iface $item, \Aimeos\Map $products ) : array
	{
		$data = [];

		foreach( $products->getListItems( 'catalog', null, $item->getId() )->flat( 1 ) as $listItem )
		{
			foreach( $listItem->toArray( true ) as $key => $value ) {
				$data[$key][] = $value;
			}
		}

		return $data;
	}


	/**
	 * Returns the rendered template including the view data
	 *
	 * @param \Aimeos\Base\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	protected function render( \Aimeos\Base\View\Iface $view ) : string
	{
		/** admin/jqadm/catalog/product/template-item
		 * Relative path to the HTML body template of the product subpart for catalogs.
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
		$tplconf = 'admin/jqadm/catalog/product/template-item';
		$default = 'catalog/item-product';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}