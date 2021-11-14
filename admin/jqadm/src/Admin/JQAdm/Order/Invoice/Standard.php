<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Order\Invoice;

sprintf( 'invoice' ); // for translation


/**
 * Default implementation of order invoice JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/order/invoice/name
	 * Name of the invoice subpart used by the JQAdm order implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Order\Invoice\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2017.07
	 * @category Developer
	 */


	/**
	 * Copies a resource
	 *
	 * @return string|null HTML output
	 */
	public function copy() : ?string
	{
		return $this->get();
	}


	/**
	 * Creates a new resource
	 *
	 * @return string|null HTML output
	 */
	public function create() : ?string
	{
		return $this->get();
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null HTML output
	 */
	public function get() : ?string
	{
		$view = $this->getObject()->data( $this->view() );

		$total = 0;
		$params = $this->storeFilter( $view->param( 'oi', [] ), 'orderinvoice' );
		$orderItems = $this->getOrderItems( $view->item, $params, $total );

		$view->invoiceData = $this->toArray( $orderItems );
		$view->invoiceTotal = parent::get();
		$view->invoiceTotal = $total;

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

		$manager = \Aimeos\MShop::create( $this->getContext(), 'order' );
		$manager->begin();

		try
		{
			$this->storeFilter( $view->param( 'oi', [] ), 'orderinvoice' );
			$this->fromArray( $view->item, $view->param( 'invoice', [] ) );
			$view->invoiceBody = parent::save();

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
		/** admin/jqadm/order/invoice/decorators/excludes
		 * Excludes decorators added by the "common" option from the order JQAdm client
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
		 *  admin/jqadm/order/invoice/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/order/invoice/decorators/global
		 * @see admin/jqadm/order/invoice/decorators/local
		 */

		/** admin/jqadm/order/invoice/decorators/global
		 * Adds a list of globally available decorators only to the order JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/order/invoice/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/order/invoice/decorators/excludes
		 * @see admin/jqadm/order/invoice/decorators/local
		 */

		/** admin/jqadm/order/invoice/decorators/local
		 * Adds a list of local decorators only to the order JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Order\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/order/invoice/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Order\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/order/invoice/decorators/excludes
		 * @see admin/jqadm/order/invoice/decorators/global
		 */
		return $this->createSubClient( 'order/invoice/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/order/invoice/subparts
		 * List of JQAdm sub-clients rendered within the order invoice section
		 *
		 * The output of the frontend is composed of the code generated by the JQAdm
		 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
		 * that are responsible for rendering certain sub-parts of the output. The
		 * sub-clients can contain JQAdm clients themselves and therefore a
		 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
		 * the output that is placed inside the container of its parent.
		 *
		 * At first, always the JQAdm code generated by the parent is printed, then
		 * the JQAdm code of its sub-clients. The invoice of the JQAdm sub-clients
		 * determines the invoice of the output of these sub-clients inside the parent
		 * container. If the configured list of clients is
		 *
		 *  array( "subclient1", "subclient2" )
		 *
		 * you can easily change the invoice of the output by reinvoiceing the subparts:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
		 *
		 * You can also remove one or more parts if they shouldn't be rendered:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
		 *
		 * As the clients only generates structural JQAdm, the layout defined via CSS
		 * should support adding, removing or reinvoiceing content by a fluid like
		 * design.
		 *
		 * @param array List of sub-client names
		 * @since 2017.07
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/order/invoice/subparts', [] );
	}


	/**
	 * Returns the order items (invoice records) for the given order base (basket) ID
	 *
	 * @param \Aimeos\MShop\Order\Item\Base\Iface $order Current order base item
	 * @param string $params GET/POST parameters containing the filter values
	 * @param integer $total Value/result parameter that will contain the item total afterwards
	 * @return \Aimeos\Map List of order IDs as keys and items implementing \Aimeos\MShop\Order\Item\Iface
	 */
	protected function getOrderItems( \Aimeos\MShop\Order\Item\Base\Iface $order, array $params, &$total ) : \Aimeos\Map
	{
		$manager = \Aimeos\MShop::create( $this->getContext(), 'order' );

		$search = $manager->filter( false, true );
		$search->setSortations( [$search->sort( '-', 'order.ctime' )] );

		$search = $this->initCriteria( $search, $params, 'orderinvoice' );
		$expr = [
			$search->compare( '==', 'order.baseid', $order->getId() ),
			$search->getConditions(),
		];
		$search->setConditions( $search->and( $expr ) );

		return $manager->search( $search, [], $total );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Order\Item\Base\Iface $order Order base item object
	 * @param array $data Data array
	 */
	protected function fromArray( \Aimeos\MShop\Order\Item\Base\Iface $order, array $data )
	{
		$invoiceIds = $this->getValue( $data, 'order.id', [] );
		$manager = \Aimeos\MShop::create( $this->getContext(), 'order' );

		$search = $manager->filter()->slice( 0, count( $invoiceIds ) );
		$search->setConditions( $search->compare( '==', 'order.id', $invoiceIds ) );

		$items = $manager->search( $search );


		foreach( $invoiceIds as $idx => $id )
		{
			if( !isset( $items[$id] ) ) {
				$item = $manager->create();
			} else {
				$item = $items[$id];
			}

			$pstatus = $this->getValue( $data, 'order.statuspayment/' . $idx );
			$dstatus = $this->getValue( $data, 'order.statusdelivery/' . $idx );

			$item->setStatusPayment( is_numeric( $pstatus ) ? (int) $pstatus : null );
			$item->setStatusDelivery( is_numeric( $dstatus ) ? (int) $dstatus : null );
			$item->setType( $this->getValue( $data, 'order.type/' . $idx, $item->getType() ) );
			$item->setDateDelivery( $this->getValue( $data, 'order.datedelivery/' . $idx ) );
			$item->setDatePayment( $this->getValue( $data, 'order.datepayment/' . $idx ) );
			$item->setRelatedId( $this->getValue( $data, 'order.relatedid/' . $idx ) );
			$item->setBaseId( $order->getId() );

			$manager->save( $item );
		}
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\Map $invoices List of invoices implementing \Aimeos\MShop\Order\Item\Iface belonging to the order
	 * @return array Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\Map $invoices ) : array
	{
		$data = [];

		foreach( $invoices as $item )
		{
			foreach( $item->toArray( true ) as $key => $value ) {
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
	protected function render( \Aimeos\MW\View\Iface $view ) : string
	{
		/** admin/jqadm/order/invoice/template-item
		 * Relative path to the HTML body template of the invoice subpart for orders.
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
		$tplconf = 'admin/jqadm/order/invoice/template-item';
		$default = 'order/item-invoice-standard';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
