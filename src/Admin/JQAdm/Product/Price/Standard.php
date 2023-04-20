<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Price;

sprintf( 'price' ); // for translation


/**
 * Default implementation of product price JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/price/name
	 * Name of the price subpart used by the JQAdm product implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Product\Price\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2017.07
	 */


	/**
	 * Adds the required data used in the price template
	 *
	 * @param \Aimeos\Base\View\Iface $view View object
	 * @return \Aimeos\Base\View\Iface View object with assigned parameters
	 */
	public function data( \Aimeos\Base\View\Iface $view ) : \Aimeos\Base\View\Iface
	{
		$context = $this->context();

		$priceTypeManager = \Aimeos\MShop::create( $context, 'price/type' );
		$listTypeManager = \Aimeos\MShop::create( $context, 'product/lists/type' );
		$currencyManager = \Aimeos\MShop::create( $context, 'locale/currency' );

		$search = $priceTypeManager->filter( true )->slice( 0, 10000 );
		$search->setConditions( $search->compare( '==', 'price.type.domain', 'product' ) );
		$search->setSortations( array( $search->sort( '+', 'price.type.position' ) ) );

		$listSearch = $listTypeManager->filter( true )->slice( 0, 10000 );
		$listSearch->setConditions( $listSearch->compare( '==', 'product.lists.type.domain', 'price' ) );
		$listSearch->setSortations( array( $listSearch->sort( '+', 'product.lists.type.position' ) ) );

		$view->priceTypes = $priceTypeManager->search( $search );
		$view->priceListTypes = $listTypeManager->search( $listSearch );
		$view->priceCurrencies = $currencyManager->search( $currencyManager->filter( true )->slice( 0, 10000 ) );

		if( $view->priceCurrencies->isEmpty() )
		{
			$msg = $context->translate( 'admin', 'No currencies available. Please enable at least one currency' );
			throw new \Aimeos\Admin\JQAdm\Exception( $msg );
		}

		return $view;
	}


	/**
	 * Batch update of a resource
	 *
	 * @return string|null Output to display
	 */
	public function batch() : ?string
	{
		$view = $this->view();
		$data = $view->param( 'price', [] );

		foreach( $view->get( 'items', [] ) as $item )
		{
			foreach( $item->getRefItems( 'price' ) as $price )
			{
				$temp = $data; $price->fromArray( $temp, true );

				if( isset( $data['valuepercent'] ) ) {
					$price->setValue( $price->getValue() + $price->getValue() * $data['valuepercent'] / 100 );
				}

				if( isset( $data['rebatepercent'] ) ) {
					$price->setRebate( $price->getRebate() + $price->getRebate() * $data['rebatepercent'] / 100 );
				}

				if( isset( $data['costspercent'] ) ) {
					$price->setCosts( $price->getCosts() + $price->getCosts() * $data['costspercent'] / 100 );
				}
			}
		}

		return null;
	}


	/**
	 * Copies a resource
	 *
	 * @return string|null HTML output
	 */
	public function copy() : ?string
	{
		$view = $this->object()->data( $this->view() );
		$view->priceCustom = $this->isCustom( $view->item );
		$view->priceData = $this->toArray( $view->item, true );
		$view->priceBody = parent::copy();

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
		$data = $view->param( 'price', [] );

		foreach( $data as $idx => $entry )
		{
			$data[$idx]['product.lists.siteid'] = $siteid;
			$data[$idx]['price.siteid'] = $siteid;
		}

		$view->priceCustom = $this->isCustom( $view->item );
		$view->priceBody = parent::create();
		$view->priceData = $data;

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

		$item = $this->view()->item;
		$item->deleteListItems( $item->getListItems( 'price', null, null, false )->toArray(), true );

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
		$view->priceCustom = $this->isCustom( $view->item );
		$view->priceData = $this->toArray( $view->item );
		$view->priceBody = parent::get();

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

		$view->item = $this->setCustom( $view->item, $view->param( 'pricecustom' ) );
		$view->item = $this->fromArray( $view->item, $view->param( 'price', [] ) );
		$view->priceBody = parent::save();

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
		/** admin/jqadm/product/price/decorators/excludes
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
		 *  admin/jqadm/product/price/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/price/decorators/global
		 * @see admin/jqadm/product/price/decorators/local
		 */

		/** admin/jqadm/product/price/decorators/global
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
		 *  admin/jqadm/product/price/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/price/decorators/excludes
		 * @see admin/jqadm/product/price/decorators/local
		 */

		/** admin/jqadm/product/price/decorators/local
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
		 *  admin/jqadm/product/price/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/price/decorators/excludes
		 * @see admin/jqadm/product/price/decorators/global
		 */
		return $this->createSubClient( 'product/price/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/product/price/subparts
		 * List of JQAdm sub-clients rendered within the product price section
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
		return $this->context()->config()->get( 'admin/jqadm/product/price/subparts', [] );
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
		$context = $this->context();

		$priceManager = \Aimeos\MShop::create( $context, 'price' );
		$listManager = \Aimeos\MShop::create( $context, 'product/lists' );

		$listItems = $item->getListItems( 'price', null, null, false );


		foreach( $data as $idx => $entry )
		{
			$id = $this->val( $entry, 'price.id', '' );
			$type = $this->val( $entry, 'product.lists.type', 'default' );

			$listItem = $item->getListItem( 'price', $type, $id, false ) ?: $listManager->create();
			$refItem = $listItem->getRefItem() ?: $priceManager->create();

			$refItem->fromArray( $entry, true );
			$listItem->fromArray( $entry, true )->setPosition( $idx )->setConfig( [] );

			foreach( (array) $this->val( $entry, 'config', [] ) as $cfg )
			{
				if( ( $key = trim( $cfg['key'] ?? '' ) ) !== '' ) {
					$listItem->setConfigValue( $key, trim( $cfg['val'] ?? '' ) );
				}
			}

			$item->addListItem( 'price', $listItem, $refItem );
			unset( $listItems[$listItem->getId()] );
		}

		return $item->deleteListItems( $listItems->toArray(), true );
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
		$siteId = $this->context()->locale()->getSiteId();

		foreach( $item->getListItems( 'price', null, null, false ) as $listItem )
		{
			if( ( $refItem = $listItem->getRefItem() ) === null ) {
				continue;
			}

			$list = $listItem->toArray( true ) + $refItem->toArray( true );

			if( $copy === true )
			{
				$list['product.lists.siteid'] = $siteId;
				$list['product.lists.id'] = '';
				$list['price.siteid'] = $siteId;
				$list['price.id'] = null;
			}

			$list['product.lists.datestart'] = str_replace( ' ', 'T', $list['product.lists.datestart'] ?? '' );
			$list['product.lists.dateend'] = str_replace( ' ', 'T', $list['product.lists.dateend'] ?? '' );
			$list['config'] = [];

			foreach( $listItem->getConfig() as $key => $value ) {
				$list['config'][] = ['key' => $key, 'val' => $value];
			}

			if( empty( $refItem->getTaxRates() ) ) {
				$list['price.taxrates'] = ['' => ''];
			}

			$data[] = $list;
		}

		return $data;
	}


	/**
	 * Returns if the prices can be chosen by the customers themselves
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item including attribute items
	 * @return bool True if price value can be entered by the customer, false if not
	 */
	protected function isCustom( \Aimeos\MShop\Product\Item\Iface $item ) : bool
	{
		return !$item->getRefItems( 'attribute', 'price', 'custom', false )->isEmpty();
	}


	/**
	 * Sets the flag if the price is customizable by the customer
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item including attribute items
	 * @param mixed $value Zero, empty, null or false to remove the flag, otherwise add the flag
	 * @return \Aimeos\MShop\Product\Item\Iface $item Modified product item
	 */
	protected function setCustom( \Aimeos\MShop\Product\Item\Iface $item, $value ) : \Aimeos\MShop\Product\Item\Iface
	{
		$context = $this->context();

		try
		{
			$attrManager = \Aimeos\MShop::create( $context, 'attribute' );
			$attrItem = $attrManager->find( 'custom', [], 'product', 'price' );
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$attrItem = $attrManager->create()->setDomain( 'product' )->setType( 'price' )->setCode( 'custom' );
			$attrItem = $attrManager->save( $attrItem );
		}

		if( $value )
		{
			if( $item->getListItem( 'attribute', 'custom', $attrItem->getId(), false ) === null )
			{
				$listItem = \Aimeos\MShop::create( $context, 'product' )->createListItem();
				$item = $item->addListItem( 'attribute', $listItem->setType( 'custom' ), $attrItem );
			}
		}
		else
		{
			if( ( $litem = $item->getListItem( 'attribute', 'custom', $attrItem->getId(), false ) ) !== null ) {
				$item = $item->deleteListItem( 'attribute', $litem );
			}
		}

		return $item;
	}


	/**
	 * Returns the rendered template including the view data
	 *
	 * @param \Aimeos\Base\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	protected function render( \Aimeos\Base\View\Iface $view ) : string
	{
		/** admin/jqadm/product/price/template-item
		 * Relative path to the HTML body template of the price subpart for products.
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
		$tplconf = 'admin/jqadm/product/price/template-item';
		$default = 'product/item-price';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
