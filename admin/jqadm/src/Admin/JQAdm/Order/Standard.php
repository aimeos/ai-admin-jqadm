<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Order;

sprintf( 'sales' ); // for translation
sprintf( 'order' ); // for translation


/**
 * Default implementation of order JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/**
	 * Adds the required data used in the template
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @return \Aimeos\MW\View\Iface View object with assigned parameters
	 */
	public function data( \Aimeos\MW\View\Iface $view ) : \Aimeos\MW\View\Iface
	{
		$codes = [];

		foreach( $this->getContext()->config()->get( 'common/countries', [] ) as $code ) {
			$codes[$code] = $view->translate( 'country', $code );
		}

		asort( $codes );

		$view->itemSubparts = $this->getSubClientNames();
		$view->countries = $codes;
		return $view;
	}


	/**
	 * Copies a resource
	 *
	 * @return string|null HTML output
	 */
	public function copy() : ?string
	{
		$view = $this->getObject()->data( $this->view() );

		try
		{
			if( ( $id = $view->param( 'id' ) ) === null )
			{
				$msg = $this->getContext()->translate( 'admin', 'Required parameter "%1$s" is missing' );
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, 'id' ) );
			}

			$manager = \Aimeos\MShop::create( $this->getContext(), 'order/base' );
			$view->item = $manager->load( $id );

			$view->itemData = $this->toArray( $view->item, true );
			$view->itemBody = parent::copy();
		}
		catch( \Exception $e )
		{
			$this->report( $e, 'copy' );
		}

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

		try
		{
			$data = $view->param( 'item', [] );

			if( !isset( $view->item ) ) {
				$view->item = \Aimeos\MShop::create( $this->getContext(), 'order/base' )->create();
			}

			$data['order.siteid'] = $view->item->getSiteId();

			$view->itemData = array_replace_recursive( $this->toArray( $view->item ), $data );
			$view->itemBody = parent::create();
		}
		catch( \Exception $e )
		{
			$this->report( $e, 'create' );
		}

		return $this->render( $view );
	}


	/**
	 * Exports a resource
	 *
	 * @return string Admin output to display
	 */
	public function export() : ?string
	{
		$view = $this->view();
		$context = $this->getContext();

		try
		{
			$params = $this->storeFilter( $view->param(), 'order' );
			$msg = ['sitecode' => $context->getLocale()->getSiteItem()->getCode()];

			if( isset( $params['filter'] ) ) {
				$msg['filter'] = $this->getCriteriaConditions( (array) $params['filter'] );
			}

			if( isset( $params['sort'] ) ) {
				$msg['sort'] = (array) $params['sort'];
			}

			$queue = $view->param( 'queue', 'order-export' );
			$mq = $context->getMessageQueueManager()->get( 'mq-admin' )->getQueue( $queue );
			$mq->add( json_encode( $msg ) );

			$msg = $context->translate( 'admin', 'Your export will be available in a few minutes for download' );
			$view->info = $view->get( 'info', [] ) + ['order-item' => $msg];
		}
		catch( \Exception $e )
		{
			$this->report( $e, 'export' );
		}

		return $this->search();
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null HTML output
	 */
	public function get() : ?string
	{
		$view = $this->getObject()->data( $this->view() );

		try
		{
			if( ( $id = $view->param( 'id' ) ) === null )
			{
				$msg = $this->getContext()->translate( 'admin', 'Required parameter "%1$s" is missing' );
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, 'id' ) );
			}

			$manager = \Aimeos\MShop::create( $this->getContext(), 'order/base' );
			$refs = ['order/base/address', 'order/base/coupon', 'order/base/product', 'order/base/service'];

			$view->item = $manager->get( $id, $refs );
			$view->itemData = $this->toArray( $view->item );
			$view->itemBody = parent::get();
		}
		catch( \Exception $e )
		{
			$this->report( $e, 'get' );
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

		$manager = \Aimeos\MShop::create( $this->getContext(), 'order/base' );
		$manager->begin();

		try
		{
			$item = $this->fromArray( $view->param( 'item', [] ) );
			$view->item = $item->getId() ? $item : $manager->store( clone $item );
			$view->itemBody = parent::save();

			$manager->store( clone $view->item );
			$manager->commit();

			return $this->redirect( 'order', $view->param( 'next' ), $view->item->getId(), 'save' );
		}
		catch( \Exception $e )
		{
			$manager->rollback();
			$this->report( $e, 'save' );
		}

		return $this->create();
	}


	/**
	 * Returns a list of resource according to the conditions
	 *
	 * @return string|null HTML output
	 */
	public function search() : ?string
	{
		$view = $this->view();

		try
		{
			$total = 0;
			$context = $this->getContext();
			$manager = \Aimeos\MShop::create( $context, 'order' );
			$params = $this->storeFilter( $view->param(), 'order' );

			$search = $manager->filter( false, true );
			$search->setSortations( [$search->sort( '-', 'order.id' )] );
			$search = $this->initCriteria( $search, $params );

			$view->items = $manager->search( $search, [], $total );
			$view->baseItems = $this->getOrderBaseItems( $view->items );
			$view->filterAttributes = $manager->getSearchAttributes( true );
			$view->filterOperators = $search->getOperators();
			$view->itemBody = parent::search();
			$view->total = $total;
		}
		catch( \Exception $e )
		{
			$this->report( $e, 'search' );
		}

		/** admin/jqadm/order/template-list
		 * Relative path to the HTML body template for the order list.
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
		$tplconf = 'admin/jqadm/order/template-list';
		$default = 'order/list-standard';

		return $view->render( $view->config( $tplconf, $default ) );
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
		/** admin/jqadm/order/decorators/excludes
		 * Excludes decorators added by the "common" option from the order JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "client/jqadm/common/decorators/default" before they are wrapped
		 * around the JQAdm client.
		 *
		 *  admin/jqadm/order/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "client/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/order/decorators/global
		 * @see admin/jqadm/order/decorators/local
		 */

		/** admin/jqadm/order/decorators/global
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
		 *  admin/jqadm/order/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/order/decorators/excludes
		 * @see admin/jqadm/order/decorators/local
		 */

		/** admin/jqadm/order/decorators/local
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
		 *  admin/jqadm/order/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Order\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/order/decorators/excludes
		 * @see admin/jqadm/order/decorators/global
		 */
		return $this->createSubClient( 'order/' . $type, $name );
	}


	/**
	 * Returns the base order items (baskets) for the given order items (invoices)
	 *
	 * @param \Aimeos\Map $orderItems List of order items implementing \Aimeos\MShop\Order\Item\Iface
	 * @return \Aimeos\Map List of order base items implementing \Aimeos\MShop\Order\Item\Base\Iface
	 */
	protected function getOrderBaseItems( \Aimeos\Map $orderItems ) : \Aimeos\Map
	{
		$baseIds = $orderItems->getBaseId()->toArray();
		$manager = \Aimeos\MShop::create( $this->getContext(), 'order/base' );

		$search = $manager->filter( false, true )->slice( 0, count( $baseIds ) );
		$search->setConditions( $search->compare( '==', 'order.base.id', $baseIds ) );

		$domains = ['order/base/address', 'order/base/coupon', 'order/base/product', 'order/base/service'];
		return $manager->search( $search, $domains );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/order/subparts
		 * List of JQAdm sub-clients rendered within the order section
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
		return $this->getContext()->getConfig()->get( 'admin/jqadm/order/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Order\Item\Base\Iface New order item object
	 */
	protected function fromArray( array $data ) : \Aimeos\MShop\Order\Item\Base\Iface
	{
		$manager = \Aimeos\MShop::create( $this->getContext(), 'order/base' );
		$attrManager = \Aimeos\MShop::create( $this->getContext(), 'order/base/service/attribute' );
		$domains = ['order/base/address', 'order/base/product', 'order/base/service'];

		if( isset( $data['order.base.id'] ) ) {
			$basket = $manager->get( $data['order.base.id'], $domains )->off();
		} else {
			$basket = $manager->create()->off();
		}

		$basket->fromArray( $data, true );
		$allowed = array_flip( [
			'order.base.product.statusdelivery',
			'order.base.product.statuspayment',
			'order.base.product.qtyopen',
			'order.base.product.timeframe',
			'order.base.product.notes',
		] );

		foreach( $basket->getProducts() as $pos => $product )
		{
			$list = array_intersect_key( $data['product'][$pos], $allowed );
			$product->fromArray( $list );
		}

		foreach( $basket->getAddresses() as $type => $addresses )
		{
			foreach( $addresses as $pos => $address )
			{
				if( isset( $data['address'][$type][$pos] ) ) {
					$list = (array) $data['address'][$type][$pos];
					$basket->addAddress( $address->fromArray( $list, true ), $type, $pos );
				} else {
					$basket->deleteAddress( $type, $pos );
				}
			}
		}

		foreach( $basket->getServices() as $type => $services )
		{
			foreach( $services as $index => $service )
			{
				$list = [];
				$attrItems = $service->getAttributeItems();

				if( isset( $data['service'][$type][$service->getServiceId()] ) )
				{
					foreach( (array) $data['service'][$type][$service->getServiceId()] as $key => $pair )
					{
						foreach( $pair as $pos => $value ) {
							$list[$pos][$key] = $value;
						}
					}

					foreach( $list as $array )
					{
						if( isset( $attrItems[$array['order.base.service.attribute.id']] ) )
						{
							$attrItem = $attrItems[$array['order.base.service.attribute.id']];
							unset( $attrItems[$array['order.base.service.attribute.id']] );
						}
						else
						{
							$attrItem = $attrManager->create();
						}

						$attrItem->fromArray( $array, true );
						$attrItem->setParentId( $service->getId() );

						$item = $attrManager->save( $attrItem );
					}
				}

				$attrManager->delete( $attrItems->toArray() );
			}
		}

		return $basket;
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Order\Item\Base\Iface $item Order base item object
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Order\Item\Base\Iface $item, bool $copy = false ) : array
	{
		$siteId = $this->getContext()->getLocale()->getSiteId();
		$data = $item->toArray( true );

		if( $item->getCustomerId() != '' )
		{
			$manager = \Aimeos\MShop::create( $this->getContext(), 'customer' );

			try {
				$data += $manager->get( $item->getCustomerId() )->toArray();
			} catch( \Exception $e ) {};
		}


		if( $copy === true )
		{
			$data['order.base.siteid'] = $siteId;
			$data['order.base.id'] = '';
		}

		foreach( $item->getAddresses() as $type => $addresses )
		{
			foreach( $addresses as $pos => $addrItem )
			{
				$list = $addrItem->toArray( true );

				foreach( $list as $key => $value ) {
					$data['address'][$type][$pos][$key] = $value;
				}

				if( $copy === true )
				{
					$data['address'][$type][$pos]['order.base.address.siteid'] = $siteId;
					$data['address'][$type][$pos]['order.base.address.id'] = '';
				}
			}
		}

		if( $copy !== true )
		{
			foreach( $item->getServices() as $type => $services )
			{
				foreach( $services as $serviceItem )
				{
					$serviceId = $serviceItem->getServiceId();

					foreach( $serviceItem->getAttributeItems() as $attrItem )
					{
						foreach( $attrItem->toArray( true ) as $key => $value ) {
							$data['service'][$type][$serviceId][$key][] = $value;
						}
					}
				}
			}
		}

		foreach( $item->getProducts() as $pos => $productItem ) {
			$data['product'][$pos] = $productItem->toArray();
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
		/** admin/jqadm/order/template-item
		 * Relative path to the HTML body template for the order item.
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
		$tplconf = 'admin/jqadm/order/template-item';
		$default = 'order/item-standard';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
