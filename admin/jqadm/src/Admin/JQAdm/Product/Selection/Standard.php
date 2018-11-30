<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
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
	 * @return string HTML output
	 */
	public function copy()
	{
		$view = $this->getView();

		$view->selectionData = $this->toArray( $view->item, true );
		$view->selectionBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->selectionBody .= $client->copy();
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
		$siteid = $this->getContext()->getLocale()->getSiteId();
		$data = $view->param( 'selection', [] );

		foreach( $data as $idx => $entry )
		{
			$data[$idx]['product.lists.siteid'] = $siteid;
			$data[$idx]['product.siteid'] = $siteid;
		}

		$view->selectionData = $data;
		$view->selectionBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->selectionBody .= $client->create();
		}

		return $this->render( $view );
	}


	/**
	 * Deletes a resource
	 */
	public function delete()
	{
		parent::delete();

		$item = $this->getView()->item;
		$item->deleteListItems( $item->getListItems( 'product', 'default', null, false ) );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string HTML output
	 */
	public function get()
	{
		$view = $this->getView();

		$view->selectionData = $this->toArray( $view->item );
		$view->selectionBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->selectionBody .= $client->get();
		}

		return $this->render( $view );
	}


	/**
	 * Saves the data
	 */
	public function save()
	{
		$view = $this->getView();

		try
		{
			if( $view->item->getType() === 'select' )
			{
				$this->fromArray( $view->item, $view->param( 'selection', [] ) );
				$view->selectionBody = '';

				foreach( $this->getSubClients() as $client ) {
					$view->selectionBody .= $client->save();
				}
			}

			return;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'product-item-selection' => $this->getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'product-item-selection' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

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
	protected function getSubClientNames()
	{
		/** admin/jqadm/product/selection/standard/subparts
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
		return $this->getContext()->getConfig()->get( 'admin/jqadm/product/selection/standard/subparts', [] );
	}


	/**
	 * Returns the selection articles including attributes for the given product item
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item including reference product articles
	 * @return \Aimeos\MShop\Product\Item\Iface[] Associative list of article items with IDs as keys and items as values
	 */
	protected function getArticleItems( \Aimeos\MShop\Product\Item\Iface $item )
	{
		return $item->getRefItems( 'product', null, 'default', false );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object without referenced domain items
	 * @param array $data Data array
	 */
	protected function fromArray( \Aimeos\MShop\Product\Item\Iface $item, array $data )
	{
		$context = $this->getContext();
		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product' );
		$listManager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists' );

		$articles = $this->getArticleItems( $item );
		$prodItem = $manager->createItem( 'default', 'product' );
		$listItem = $listManager->createItem( 'default', 'product' );
		$listItems = $item->getListItems( 'product', 'default', null, false );

		foreach( $data as $idx => $entry )
		{
			if( ( $litem = $item->getListItem( 'product', 'default', $entry['product.id'], false ) ) === null ) {
				$litem = clone $listItem;
			}

			if( isset( $articles[$litem->getRefId()] ) ) {
				$refItem = $articles[$litem->getRefId()];
			} else {
				$refItem = clone $prodItem;
			}

			$litem->fromArray( $entry );
			$litem->setPosition( $idx );

			$refItem->fromArray( $entry );

			if( isset( $entry['attr'] ) ) {
				$refItem = $this->fromArrayAttributes( $refItem, $entry['attr'] );
			}

			$item->addListItem( 'product', $litem, $refItem );
			unset( $listItems[$litem->getId()] );
		}

		return $item->deleteListItems( $listItems );
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
		$listManager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'product/lists' );

		$listItem = $listManager->createItem( 'variant', 'attribute' );
		$litems = $refItem->getListItems( 'attribute', 'variant', null, false );

		foreach( $entry as $pos => $attr )
		{
			if( !isset( $attr['product.lists.refid'] ) || $attr['product.lists.refid'] == '' ) {
				continue;
			}

			if( ( $litem = $refItem->getListItem( 'attribute', 'variant', $attr['product.lists.refid'], false ) ) === null ) {
				$litem = clone $listItem;
			}

			$litem->fromArray( $attr );
			$litem->setPosition( $pos );

			$refItem->addListItem( 'attribute', $litem, $litem->getRefItem() );
			unset( $litems[$litem->getId()] );
		}

		return $refItem->deleteListItems( $litems );
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object including referenced domain items
	 * @param boolean $copy True if items should be copied
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Product\Item\Iface $item, $copy = false )
	{
		if( $item->getType() !== 'select' ) {
			return [];
		}

		$data = [];
		$context = $this->getContext();
		$siteId = $context->getLocale()->getSiteId();
		$articles = $item->getRefItems( 'product', null, 'default', false );


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
				$list['product.id'] = null;
			}

			if( isset( $articles[$refItem->getId()] ) )
			{
				$idx = 0;

				foreach( $articles[$refItem->getId()]->getListItems( 'attribute', 'variant', null, false ) as $litem )
				{
					if( ( $attrItem = $litem->getRefItem() ) !== null ) {
						$list['attr'][$idx++] = $litem->toArray( true ) + $attrItem->toArray( true );
					}
				}
			}

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
	protected function render( \Aimeos\MW\View\Iface $view )
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
		$default = 'product/item-selection-standard.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}