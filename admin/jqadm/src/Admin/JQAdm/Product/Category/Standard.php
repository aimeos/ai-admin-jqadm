<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Category;

sprintf( 'category' ); // for translation


/**
 * Default implementation of product category JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/category/name
	 * Name of the category subpart used by the JQAdm product implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Product\Category\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2016.04
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

		$view->categoryData = $this->toArray( $view->item, true );
		$view->categoryListTypes = $this->getListTypes();
		$view->categoryBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->categoryBody .= $client->copy();
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
		$data = $view->param( 'category', [] );
		$siteid = $this->getContext()->getLocale()->getSiteId();

		foreach( $view->value( $data, 'catalog.lists.id', [] ) as $idx => $value ) {
			$data['catalog.lists.siteid'][$idx] = $siteid;
		}

		$view->categoryData = $data;
		$view->categoryListTypes = $this->getListTypes();
		$view->categoryBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->categoryBody .= $client->create();
		}

		return $this->render( $view );
	}


	/**
	 * Deletes a resource
	 *
	 * @return string HTML output
	 */
	public function delete()
	{
		$view = $this->getView();
		$context = $this->getContext();

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'catalog/lists' );

		$search = $manager->createSearch();
		$expr = array(
			$search->compare( '==', 'catalog.lists.refid', $view->param( 'id' ) ),
			$search->compare( '==', 'catalog.lists.domain', 'product' )
		);
		$search->setConditions( $search->combine( '&&', $expr ) );
		$search->setSlice( 0, 0x7fffffff );

		$start = 0;

		do
		{
			$search->setSlice( $start );

			$result = $manager->searchItems( $search );
			$manager->deleteItems( array_keys( $result ) );

			$count = count( $result );
			$start += $count;
		}
		while( $count >= $search->getSliceSize() );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string HTML output
	 */
	public function get()
	{
		$view = $this->getView();

		$view->categoryData = $this->toArray( $view->item );
		$view->categoryListTypes = $this->getListTypes();
		$view->categoryBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->categoryBody .= $client->get();
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

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists' );
		$manager->begin();

		try
		{
			$this->fromArray( $view->item, $view->param( 'category', [] ) );
			$view->categoryBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->categoryBody .= $client->save();
			}

			$manager->commit();
			return;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'product-item-category' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'product-item-category' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
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
		/** admin/jqadm/product/category/decorators/excludes
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
		 *  admin/jqadm/product/category/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/category/decorators/global
		 * @see admin/jqadm/product/category/decorators/local
		 */

		/** admin/jqadm/product/category/decorators/global
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
		 *  admin/jqadm/product/category/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/category/decorators/excludes
		 * @see admin/jqadm/product/category/decorators/local
		 */

		/** admin/jqadm/product/category/decorators/local
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
		 *  admin/jqadm/product/category/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/category/decorators/excludes
		 * @see admin/jqadm/product/category/decorators/global
		 */
		return $this->createSubClient( 'product/category/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames()
	{
		/** admin/jqadm/product/category/standard/subparts
		 * List of JQAdm sub-clients rendered within the product category section
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
		return $this->getContext()->getConfig()->get( 'admin/jqadm/product/category/standard/subparts', [] );
	}


	/**
	 * Returns the category items for the given catalog list items
	 *
	 * @param array $listItems List of items implementing \Aimeos\Common\Item\Lists\Iface
	 * @return array Associative list of catalog IDs as keys and items implementing \Aimeos\Catalog\Item\Iface as values
	 */
	protected function getCatalogItems( array $listItems )
	{
		$ids = [];

		foreach( $listItems as $listItem ) {
			$ids[] = $listItem->getParentId();
		}

		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'catalog' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'catalog.id', $ids ) );

		return $manager->searchItems( $search );
	}


	/**
	 * Returns the category list items for the given product ID
	 *
	 * @param string $prodid Unique product ID
	 * @return array Associative list of category list IDs as keys and list items as values
	 */
	protected function getListItems( $prodid )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'catalog/lists' );

		$search = $manager->createSearch()->setSlice( 0, 0x7fffffff );
		$expr = array(
			$search->compare( '==', 'catalog.lists.refid', $prodid ),
			$search->compare( '==', 'catalog.lists.domain', 'product' ),
			$search->compare( '==', 'catalog.lists.type.domain', 'product' ),
		);
		$search->setConditions( $search->combine( '&&', $expr ) );

		return $manager->searchItems( $search );
	}


	/**
	 * Returns the available catalog list types
	 *
	 * @return array Associative list of catalog list type codes as keys and list type IDs as values
	 */
	protected function getListTypes()
	{
		$list = [];
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'catalog/lists/type' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'catalog.lists.type.domain', 'product' ) );
		$search->setSlice( 0, 0x7fffffff );

		foreach( $manager->searchItems( $search ) as $item ) {
			$list[$item->getCode()] = $item->getId();
		}

		return $list;
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object without referenced domain items
	 * @param string[] $data Data array
	 */
	protected function fromArray( \Aimeos\MShop\Product\Item\Iface $item, array $data )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'catalog/lists' );
		$listIds = (array) $this->getValue( $data, 'catalog.lists.id', [] );
		$listItems = $map = $this->getListItems( $item->getId() );


		foreach( $listIds as $idx => $listid )
		{
			if( isset( $map[$listid] ) ) {
				unset( $map[$listid] );
			}
		}

		$manager->deleteItems( array_keys( $map ) );


		foreach( $listIds as $idx => $listid )
		{
			if( isset( $listItems[$listid] ) ) {
				$litem = $listItems[$listid];
			} else {
				$litem = $manager->createItem();
			}

			$litem->setDomain( 'product' );
			$litem->setRefId( $item->getId() );
			$litem->setParentId( $this->getValue( $data, 'catalog.id/' . $idx ) );
			$litem->setTypeId( $this->getValue( $data, 'catalog.lists.typeid/' . $idx ) );

			$manager->saveItem( $litem, false );
		}
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object including referenced domain items
	 * @param boolean $copy True if items should be copied, false if not
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Product\Item\Iface $item, $copy = false )
	{
		$siteId = $this->getContext()->getLocale()->getSiteId();
		$listItems = $this->getListItems( $item->getId() );
		$catItems = $this->getCatalogItems( $listItems );
		$data = [];

		foreach( $listItems as $listItem )
		{
			$catId = $listItem->getParentId();

			if( !isset( $catItems[$catId] ) ) {
				continue;
			}

			$list = $listItem->toArray( true );

			if( $copy === true )
			{
				$list['catalog.lists.siteid'] = $siteId;
				$list['catalog.lists.id'] = '';
			}

			foreach( $list as $key => $value ) {
				$data[$key][] = $value;
			}

			foreach( $catItems[$catId]->toArray( true ) as $key => $value ) {
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
		/** admin/jqadm/product/category/template-item
		 * Relative path to the HTML body template of the category subpart for products.
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
		$tplconf = 'admin/jqadm/product/category/template-item';
		$default = 'product/item-category-standard.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}