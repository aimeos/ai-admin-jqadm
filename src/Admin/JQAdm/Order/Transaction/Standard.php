<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2022-2023
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Order\Transaction;

sprintf( 'transaction' ); // for translation


/**
 * Default implementation of order transaction JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/order/transaction/name
	 * Name of the transaction subpart used by the JQAdm order implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Order\Transaction\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2023.01
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
		$view = $this->object()->data( $this->view() );

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

		$this->fromArray( $view->item, $view->param( 'transaction', [] ) );
		$view->transactionBody = parent::save();

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
		/** admin/jqadm/order/transaction/decorators/excludes
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
		 *  admin/jqadm/order/transaction/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2023.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/order/transaction/decorators/global
		 * @see admin/jqadm/order/transaction/decorators/local
		 */

		/** admin/jqadm/order/transaction/decorators/global
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
		 *  admin/jqadm/order/transaction/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2023.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/order/transaction/decorators/excludes
		 * @see admin/jqadm/order/transaction/decorators/local
		 */

		/** admin/jqadm/order/transaction/decorators/local
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
		 *  admin/jqadm/order/transaction/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Order\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2023.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/order/transaction/decorators/excludes
		 * @see admin/jqadm/order/transaction/decorators/global
		 */
		return $this->createSubClient( 'order/transaction/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/order/transaction/subparts
		 * List of JQAdm sub-clients rendered within the order transaction section
		 *
		 * The output of the frontend is composed of the code generated by the JQAdm
		 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
		 * that are responsible for rendering certain sub-parts of the output. The
		 * sub-clients can contain JQAdm clients themselves and therefore a
		 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
		 * the output that is placed inside the container of its parent.
		 *
		 * At first, always the JQAdm code generated by the parent is printed, then
		 * the JQAdm code of its sub-clients. The transaction of the JQAdm sub-clients
		 * determines the transaction of the output of these sub-clients inside the parent
		 * container. If the configured list of clients is
		 *
		 *  array( "subclient1", "subclient2" )
		 *
		 * you can easily change the transaction of the output by retransactioning the subparts:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
		 *
		 * You can also remove one or more parts if they shouldn't be rendered:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
		 *
		 * As the clients only generates structural JQAdm, the layout defined via CSS
		 * should support adding, removing or retransactioning content by a fluid like
		 * design.
		 *
		 * @param array List of sub-client names
		 * @since 2023.01
		 */
		return $this->context()->config()->get( 'admin/jqadm/order/transaction/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Order\Item\Iface $order Order item object
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Order\Item\Iface Modified order item
	 */
	protected function fromArray( \Aimeos\MShop\Order\Item\Iface $order, array $data ) : \Aimeos\MShop\Order\Item\Iface
	{
		$context = $this->context();
		$manager = \Aimeos\MShop::create( $context, 'order' );
		$services = map( $order->getService( 'payment' ) )->col( null, 'order.service.id' );

		foreach( $data as $serviceId => $entry )
		{
			if( array_sum( $entry ) === 0 || ( $service = $services->get( $serviceId ) ) === null ) {
				continue;
			}

			$price = \Aimeos\MShop::create( $context, 'price' )->create()
				->setValue( -$entry['order.service.transaction.value'] ?? 0 )
				->setCosts( -$entry['order.service.transaction.costs'] ?? 0 )
				->setCurrencyId( $service->getPrice()->getCurrencyId() );

			$txItem = \Aimeos\MShop::create( $context, 'order/service' )->createTransaction()
				->setType( 'payment' )->setPrice( $price )->setStatus( \Aimeos\MShop\Order\Item\Base::PAY_REFUND );

			$serviceItem = \Aimeos\MShop::create( $context, 'service' )->get( $service->getServiceId() );

			$provider = \Aimeos\MShop::create( $context, 'service' )->getProvider( $serviceItem, 'payment' );

			if( $provider->isImplemented( \Aimeos\MShop\Service\Provider\Payment\Base::FEAT_REFUND ) ) {
					$manager->save( $provider->refund( $order, $price ) );
			} else {
				$txItem->setConfigValue( 'info', $context->translate( 'admin', 'Manual transfer' ) );
			}

			$service->addTransaction( $txItem );
		}

		return $order;
	}


	/**
	 * Returns the rendered template including the view data
	 *
	 * @param \Aimeos\Base\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	protected function render( \Aimeos\Base\View\Iface $view ) : string
	{
		/** admin/jqadm/order/transaction/template-item
		 * Relative path to the HTML body template of the transaction subpart for orders.
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
		$tplconf = 'admin/jqadm/order/transaction/template-item';
		$default = 'order/item-transaction';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
