<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Stock;

sprintf( 'stock' ); // for translation


/**
 * Default implementation of product stock JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/stock/name
	 * Name of the stock subpart used by the JQAdm product implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Product\Stock\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2016.04
	 */


	/**
	 * Adds the required data used in the stock template
	 *
	 * @param \Aimeos\Base\View\Iface $view View object
	 * @return \Aimeos\Base\View\Iface View object with assigned parameters
	 */
	public function data( \Aimeos\Base\View\Iface $view ) : \Aimeos\Base\View\Iface
	{
		$typeManager = \Aimeos\MShop::create( $this->context(), 'stock/type' );

		$search = $typeManager->filter( true )->slice( 0, 10000 );
		$search->setConditions( $search->compare( '==', 'stock.type.domain', 'product' ) );
		$search->setSortations( [$search->sort( '+', 'stock.type.position' )] );

		$view->stockTypes = $typeManager->search( $search );

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
		$view->stockData = $this->toArray( $view->item, true );
		$view->stockBody = parent::copy();

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
		$siteid = $this->context()->locale()->getSiteId();
		$data = $view->param( 'stock', [] );

		foreach( $view->value( $data, 'stock.id', [] ) as $idx => $value ) {
			$data[$idx]['stock.siteid'] = $siteid;
		}

		$view->stockData = $data;
		$view->stockBody = parent::copy();

		return $this->render( $view );
	}


	/**
	 * Deletes a resource
	 *
	 * @return string|null HTML output
	 */
	public function delete() : ?string
	{
		parent::delete();

		$manager = \Aimeos\MShop::create( $this->context(), 'stock' );
		$filter = $manager->filter()->add( ['stock.productid' => $this->view()->item->getId()] );
		$manager->delete( $manager->search( $filter )->toArray() );

		return null;
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null HTML output
	 */
	public function get() : ?string
	{
		$view = $this->object()->data( $this->view() );
		$view->stockData = $this->toArray( $view->item );
		$view->stockBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->stockBody .= $client->get();
		}

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

		$manager = \Aimeos\MShop::create( $this->context(), 'stock' );
		$manager->begin();

		try
		{
			$this->fromArray( $view->item, $view->param( 'stock', [] ) );
			$view->stockBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->stockBody .= $client->save();
			}

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
		/** admin/jqadm/product/stock/decorators/excludes
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
		 *  admin/jqadm/product/stock/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/stock/decorators/global
		 * @see admin/jqadm/product/stock/decorators/local
		 */

		/** admin/jqadm/product/stock/decorators/global
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
		 *  admin/jqadm/product/stock/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/stock/decorators/excludes
		 * @see admin/jqadm/product/stock/decorators/local
		 */

		/** admin/jqadm/product/stock/decorators/local
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
		 *  admin/jqadm/product/stock/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/stock/decorators/excludes
		 * @see admin/jqadm/product/stock/decorators/global
		 */
		return $this->createSubClient( 'product/stock/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/product/stock/subparts
		 * List of JQAdm sub-clients rendered within the product stock section
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
		 */
		return $this->context()->config()->get( 'admin/jqadm/product/stock/subparts', [] );
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
		$stocks = map();
		$stockItems = [];

		$ids = map( $data )->col( 'stock.id' )->filter();
		$manager = \Aimeos\MShop::create( $this->context(), 'stock' );

		if( !$ids->isEmpty() ) {
			$stocks = $manager->search( $manager->filter()->add( ['stock.id' => $ids->all()] ) );
		}

		foreach( $data as $entry )
		{
			$id = $this->val( $entry, 'stock.id' );
			$stockItem = $stocks->get( $id ) ?: $manager->create();
			$stockItem->fromArray( $entry )->setProductId( $item->getId() );

			if( $entry['stock.stockflag'] ?? false ) {
				$stockItem->setStockLevel( $stockItem->getStockLevel() + $entry['stock.stockdiff'] ?? 0 );
			}

			$item->setInStock( (int) $stockItem->getStockLevel() > 0 || $stockItem->getStockLevel() === null );

			$stockItems[] = $stockItem;
			$stocks->remove( $id );
		}

		$manager->delete( $stocks );
		$manager->save( $stockItems, false );

		return $item;
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object including referenced domain items
	 * @param bool $copy True if items should be copied, false if not
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Product\Item\Iface $item, bool $copy = false ) : array
	{
		$data = [];
		$context = $this->context();
		$siteId = $context->locale()->getSiteId();

		$manager = \Aimeos\MShop::create( $context, 'stock' );
		$filter = $manager->filter()->add( ['stock.productid' => $item->getId()] )->order( 'stock.type' );

		foreach( $manager->search( $filter ) as $stockItem )
		{
			$list = $stockItem->toArray( true );

			if( $copy === true )
			{
				$list['stock.siteid'] = $siteId;
				$list['stock.id'] = '';
			}

			$list['stock.dateback'] = str_replace( ' ', 'T', $list['stock.dateback'] ?? '' );

			$data[] = $list;
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
		/** admin/jqadm/product/stock/template-item
		 * Relative path to the HTML body template of the stock subpart for products.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in templates/admin/jqadm).
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
		 */
		$tplconf = 'admin/jqadm/product/stock/template-item';
		$default = 'product/item-stock';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
