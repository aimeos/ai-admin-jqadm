<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Supplier\Product;

sprintf( 'product' ); // for translation


/**
 * Default implementation of supplier product JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/supplier/product/name
	 * Name of the product subpart used by the JQAdm supplier implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Supplier\Product\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2017.10
	 * @category Developer
	 */


	/**
	 * Copies a resource
	 *
	 * @return string HTML output
	 */
	public function copy()
	{
		$view = $this->getView();

		try
		{
			$view->productListTypes = $this->getListTypes();
			$view->productBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->productBody .= $client->copy();
			}
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'supplier-product' => $this->getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'supplier-product' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		return $this->render( $view );
	}


	/**
	 * Creates a new resource
	 *
	 * @return string HTML output
	 */
	public function create()
	{
		$view = $this->getView();

		try
		{
			$view->productListTypes = $this->getListTypes();
			$view->productBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->productBody .= $client->create();
			}
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'supplier-product' => $this->getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'supplier-product' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		return $this->render( $view );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string HTML output
	 */
	public function get()
	{
		$view = $this->getView();

		try
		{
			$total = 0;
			$params = $this->storeSearchParams( $view->param( 'sp', [] ), 'supplierproduct' );
			$listItems = $this->getListItems( $view->item, $params, $total );

			$view->productItems = $this->getProductItems( $listItems );
			$view->productData = $this->toArray( $listItems );
			$view->productListTypes = $this->getListTypes();
			$view->productTotal = $total;
			$view->productBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->productBody .= $client->search();
			}
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'supplier-product' => $this->getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'supplier-product' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		return $this->render( $view );
	}


	/**
	 * Saves the data
	 */
	public function save()
	{
		$view = $this->getView();
		$context = $this->getContext();

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'supplier/lists' );

		$manager->begin();

		try
		{
			$this->storeSearchParams( $view->param( 'sp', [] ), 'supplierproduct' );
			$this->fromArray( $view->item, $view->param( 'product', [] ) );
			$view->productBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->productBody .= $client->save();
			}

			$manager->commit();
			return;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'supplier-item-image' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'supplier-item-image' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		$manager->rollback();

		throw new \Aimeos\Admin\JQAdm\Exception();
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		/** admin/jqadm/supplier/product/decorators/excludes
		 * Excludes decorators added by the "common" option from the supplier JQAdm client
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
		 *  admin/jqadm/supplier/product/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/supplier/product/decorators/global
		 * @see admin/jqadm/supplier/product/decorators/local
		 */

		/** admin/jqadm/supplier/product/decorators/global
		 * Adds a list of globally available decorators only to the supplier JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/supplier/product/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/supplier/product/decorators/excludes
		 * @see admin/jqadm/supplier/product/decorators/local
		 */

		/** admin/jqadm/supplier/product/decorators/local
		 * Adds a list of local decorators only to the supplier JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Supplier\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/supplier/product/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Supplier\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/supplier/product/decorators/excludes
		 * @see admin/jqadm/supplier/product/decorators/global
		 */
		return $this->createSubClient( 'supplier/product/' . $type, $name );
	}


	/**
	 * Returns the supplier list items referencing the products
	 *
	 * @param \Aimeos\MShop\Supplier\Item\Iface $item Supplier item object
	 * @param array $params Associative list of GET/POST parameters
	 * @param integer $total Value/result parameter that will contain the item total afterwards
	 * @return \Aimeos\MShop\Common\Item\List\Iface[] Supplier list items referencing the products
	 */
	protected function getListItems( \Aimeos\MShop\Supplier\Item\Iface $item, array $params = [], &$total )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'supplier/lists' );

		$search = $manager->createSearch();
		$search->setSortations( [$search->sort( '-', 'supplier.lists.ctime' )] );

		$search = $this->initCriteria( $search, $params );
		$expr = [
			$search->getConditions(),
			$search->compare( '==', 'supplier.lists.parentid', $item->getId() ),
			$search->compare( '==', 'supplier.lists.domain', 'product' ),
		];
		$search->setConditions( $search->combine( '&&', $expr ) );

		return $manager->searchItems( $search, [], $total );
	}


	/**
	 * Returns the available product list types
	 *
	 * @return \Aimeos\MShop\Common\Item\Type\Iface[] Associative list of type IDs as keys and type codes as values
	 */
	protected function getListTypes()
	{
		$list = [];
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'supplier/lists/type' );

		$search = $manager->createSearch()->setSlice( 0, 0x7fffffff );
		$search->setConditions( $search->compare( '==', 'supplier.lists.type.domain', 'product' ) );
		$search->setSortations( [$search->sort( '+', 'supplier.lists.type.code' )] );

		foreach( $manager->searchItems( $search ) as $id => $item ) {
			$list[$id] = $item->getCode();
		}

		return $list;
	}


	/**
	 * Returns the product items referenced by the given list items
	 *
	 * @param \Aimeos\MShop\Common\Item\List\Iface[] $listItems Supplier list items referencing the products
	 * @return \Aimeos\MShop\Product\Item\Iface[] Associative list of product IDs as keys and items as values
	 */
	protected function getProductItems( array $listItems )
	{
		$list = [];

		foreach( $listItems as $listItem ) {
			$list[] = $listItem->getRefId();
		}

		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'product' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'product.id', $list ) );
		$search->setSlice( 0, 0x7fffffff );

		return $manager->searchItems( $search );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames()
	{
		/** admin/jqadm/supplier/product/standard/subparts
		 * List of JQAdm sub-clients rendered within the supplier product section
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
		return $this->getContext()->getConfig()->get( 'admin/jqadm/supplier/product/standard/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Supplier\Item\Iface $item Supplier item object without referenced domain items
	 * @param string[] $data Data array
	 */
	protected function fromArray( \Aimeos\MShop\Supplier\Item\Iface $item, array $data )
	{
		$context = $this->getContext();
		$listIds = $this->getValue( $data, 'supplier.lists.id', [] );

		$listManager = \Aimeos\MShop\Factory::createManager( $context, 'supplier/lists' );

		$search = $listManager->createSearch();
		$search->setConditions( $search->compare( '==', 'supplier.lists.id', $listIds ) );
		$search->setSlice( 0, 0x7fffffff );

		$listItems = $listManager->searchItems( $search );


		foreach( (array) $listIds as $idx => $listid )
		{
			if( isset( $listItems[$listid] ) ) {
				$litem = $listItems[$listid];
			} else {
				$litem = $listManager->createItem();
			}

			$litem->setParentId( $item->getId() );
			$litem->setDomain( 'product' );

			if( isset( $data['supplier.lists.refid'][$idx] ) ) {
				$litem->setRefId( $this->getValue( $data, 'supplier.lists.refid/' . $idx ) );
			}

			if( isset( $data['supplier.lists.status'][$idx] ) ) {
				$litem->setStatus( $this->getValue( $data, 'supplier.lists.status/' . $idx ) );
			}

			if( isset( $data['supplier.lists.typeid'][$idx] ) ) {
				$litem->setTypeId( $this->getValue( $data, 'supplier.lists.typeid/' . $idx ) );
			}

			if( isset( $data['supplier.lists.position'][$idx] ) ) {
				$litem->setPosition( $this->getValue( $data, 'supplier.lists.position/' . $idx ) );
			}

			if( isset( $data['supplier.lists.datestart'][$idx] ) ) {
				$litem->setDateStart( $this->getValue( $data, 'supplier.lists.datestart/' . $idx ) );
			}

			if( isset( $data['supplier.lists.dateend'][$idx] ) ) {
				$litem->setDateEnd( $this->getValue( $data, 'supplier.lists.dateend/' . $idx ) );
			}

			if( isset( $data['supplier.lists.config'][$idx] )
				&& ( $conf = json_decode( $this->getValue( $data, 'supplier.lists.config/' . $idx ), true ) ) !== null
			) {
				$litem->setConfig( $conf );
			}

			if( $litem->getId() === null && isset( $data['config'][$idx]['key'] ) )
			{
				$conf = [];

				foreach( (array) $data['config'][$idx]['key'] as $pos => $key )
				{
					if( trim( $key ) !== '' && isset( $data['config'][$idx]['val'][$pos] ) ) {
						$conf[$key] = $data['config'][$idx]['val'][$pos];
					}
				}

				$litem->setConfig( $conf );
			}

			$listManager->saveItem( $litem, false );
		}
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Common\Item\Lists\Iface[] $listItems Supplier list items referencing the products
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( array $listItems )
	{
		$data = [];

		foreach( $listItems as $listItem )
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
	 * @param \Aimeos\MW\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	protected function render( \Aimeos\MW\View\Iface $view )
	{
		/** admin/jqadm/supplier/product/template-item
		 * Relative path to the HTML body template of the product subpart for suppliers.
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
		$tplconf = 'admin/jqadm/supplier/product/template-item';
		$default = 'supplier/item-product-standard.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
