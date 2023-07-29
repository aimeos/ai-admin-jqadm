<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Bundle;

sprintf( 'bundle' ); // for translation


/**
 * Default implementation of product bundle JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/bundle/name
	 * Name of the bundle subpart used by the JQAdm product implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Product\Bundle\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2016.04
	 */


	/**
	 * Copies a resource
	 *
	 * @return string|null HTML output
	 */
	public function copy() : ?string
	{
		$view = $this->object()->data( $this->view() );

		$view->bundleData = $this->toArray( $view->item, true );
		$view->bundleBody = parent::copy();

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
		$data = $view->param( 'bundle', [] );

		foreach( $view->value( $data, 'product.lists.id', [] ) as $idx => $value ) {
			$data[$idx]['product.lists.siteid'] = $siteid;
		}

		$view->bundleData = $data;
		$view->bundleBody = parent::create();

		return $this->render( $view );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null HTML output
	 */
	public function get() : ?string
	{
		$view = $this->object()->data( $this->view() );

		$view->bundleData = $this->toArray( $view->item );
		$view->bundleBody = parent::get();

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

		if( $view->item->getType() === 'bundle' )
		{
			$this->fromArray( $view->item, $view->param( 'bundle', [] ) );
			$view->bundleBody = parent::save();
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
		/** admin/jqadm/product/bundle/decorators/excludes
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
		 *  admin/jqadm/product/bundle/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/bundle/decorators/global
		 * @see admin/jqadm/product/bundle/decorators/local
		 */

		/** admin/jqadm/product/bundle/decorators/global
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
		 *  admin/jqadm/product/bundle/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/bundle/decorators/excludes
		 * @see admin/jqadm/product/bundle/decorators/local
		 */

		/** admin/jqadm/product/bundle/decorators/local
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
		 *  admin/jqadm/product/bundle/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/bundle/decorators/excludes
		 * @see admin/jqadm/product/bundle/decorators/global
		 */
		return $this->createSubClient( 'product/bundle/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/product/bundle/subparts
		 * List of JQAdm sub-clients rendered within the product bundle section
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
		return $this->context()->config()->get( 'admin/jqadm/product/bundle/subparts', [] );
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
		$manager = \Aimeos\MShop::create( $this->context(), 'product' );
		$listItems = $item->getListItems( 'product', 'default', null, false );

		foreach( $data as $idx => $entry )
		{
			$listid = $this->val( $entry, 'product.lists.id' );
			$litem = $listItems->pull( $listid ) ?: $manager->createListItem();
			$litem->setRefId( $this->val( $entry, 'product.lists.refid' ) )->setPosition( $idx );

			$item->addListItem( 'product', $litem );
		}

		return $item->deleteListItems( $listItems );
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
		if( $item->getType() !== 'bundle' ) {
			return [];
		}

		$data = [];
		$siteId = $this->context()->locale()->getSiteId();

		foreach( $item->getListItems( 'product', 'default', null, false ) as $listItem )
		{
			if( ( $refItem = $listItem->getRefItem() ) === null ) {
				continue;
			}

			$list = $listItem->toArray( true ) + $refItem->toArray( true );

			if( $copy === true )
			{
				$list['product.lists.siteid'] = $siteId;
				$list['product.lists.id'] = '';
			}

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
		/** admin/jqadm/product/bundle/template-item
		 * Relative path to the HTML body template of the bundle subpart for products.
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
		$tplconf = 'admin/jqadm/product/bundle/template-item';
		$default = 'product/item-bundle';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
