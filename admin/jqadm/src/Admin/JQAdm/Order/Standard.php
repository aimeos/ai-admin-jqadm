<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Order;

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
	 * Copies a resource
	 *
	 * @return string HTML output
	 */
	public function copy()
	{
		$view = $this->getView();
		$context = $this->getContext();

		try
		{
			if( ( $id = $view->param( 'id' ) ) === null ) {
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Required parameter "%1$s" is missing', 'id' ) );
			}

			$manager = \Aimeos\MShop\Factory::createManager( $context, 'order/base' );
			$view->item = $manager->load( $id );

			$view->itemData = $this->toArray( $view->item, true );
			$view->itemSubparts = $this->getSubClientNames();
			$view->itemBody = '';

			foreach( $this->getSubClients() as $idx => $client )
			{
				$view->tabindex = ++$idx + 1;
				$view->itemBody .= $client->copy();
			}
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'order-item' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'order-item' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
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
		$context = $this->getContext();

		try
		{
			$data = $view->param( 'item', [] );

			if( !isset( $view->item ) ) {
				$view->item = \Aimeos\MShop\Factory::createManager( $context, 'order/base' )->createItem();
			}

			$data['order.siteid'] = $view->item->getSiteId();

			$view->itemSubparts = $this->getSubClientNames();
			$view->itemData = $data;
			$view->itemBody = '';

			foreach( $this->getSubClients() as $idx => $client )
			{
				$view->tabindex = ++$idx + 1;
				$view->itemBody .= $client->create();
			}
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'order-item' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'order-item' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		return $this->render( $view );
	}


	/**
	 * Exports a resource
	 *
	 * @return string Admin output to display
	 */
	public function export()
	{
		$view = $this->getView();
		$context = $this->getContext();

		try
		{
			$params = $this->storeSearchParams( $view->param(), 'order' );
			$msg = ['sitecode' => $context->getLocale()->getSite()->getCode()];

			if( isset( $params['filter'] ) ) {
				$msg['filter'] = $this->getCriteriaConditions( $params['filter'] );
			}

			if( isset( $params['sort'] ) ) {
				$msg['sort'] = $this->getCriteriaSortations( $params['sort'] );
			}

			$mq = $context->getMessageQueueManager()->get( 'mq-admin' )->getQueue( 'order-export' );
			$mq->add( json_encode( $msg ) );

			$msg = $context->getI18n()->dt( 'admin', 'Your export will be available in a few minutes for download' );
			$view->info = $view->get( 'info', [] ) + ['order-item' => $msg];
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'order-item' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'order-item' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		return $this->search();
	}


	/**
	 * Returns a single resource
	 *
	 * @return string HTML output
	 */
	public function get()
	{
		$view = $this->getView();
		$context = $this->getContext();

		try
		{
			if( ( $id = $view->param( 'id' ) ) === null ) {
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Required parameter "%1$s" is missing', 'id' ) );
			}

			$manager = \Aimeos\MShop\Factory::createManager( $context, 'order/base' );
			$refs = ['order/base/address', 'order/base/coupon', 'order/base/product', 'order/base/service'];

			$view->item = $manager->getItem( $id, $refs );
			$view->itemSubparts = $this->getSubClientNames();
			$view->itemData = $this->toArray( $view->item );
			$view->itemBody = '';

			foreach( $this->getSubClients() as $idx => $client )
			{
				$view->tabindex = ++$idx + 1;
				$view->itemBody .= $client->get();
			}
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'order-item' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'order-item' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		return $this->render( $view );
	}


	/**
	 * Saves the data
	 *
	 * @return string HTML output
	 */
	public function save()
	{
		$view = $this->getView();
		$context = $this->getContext();

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'order/base' );
		$manager->begin();

		try
		{
			$item = $this->fromArray( $view->param( 'item', [] ) );
			$view->item = $item->getId() ? $item : $manager->store( clone $item );
			$view->itemBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->itemBody .= $client->save();
			}

			$manager->store( clone $view->item );
			$manager->commit();

			$this->nextAction( $view, $view->param( 'next' ), 'order', $view->item->getId(), 'save' );
			return;
		}
		catch( \Aimeos\Admin\JQAdm\Exception $e )
		{
			// fall through to create
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'order-item' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'order-item' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		$manager->rollback();

		return $this->get();
	}


	/**
	 * Returns a list of resource according to the conditions
	 *
	 * @return string HTML output
	 */
	public function search()
	{
		$view = $this->getView();
		$context = $this->getContext();

		try
		{
			$total = 0;
			$params = $this->storeSearchParams( $view->param(), 'order' );
			$manager = \Aimeos\MShop\Factory::createManager( $context, 'order' );

			$search = $manager->createSearch();
			$search->setSortations( [$search->sort( '-', 'order.id' )] );
			$search = $this->initCriteria( $search, $params );

			$view->items = $manager->searchItems( $search, [], $total );
			$view->baseItems = $this->getOrderBaseItems( $view->items );
			$view->filterAttributes = $manager->getSearchAttributes( true );
			$view->filterOperators = $search->getOperators();
			$view->total = $total;
			$view->itemBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->itemBody .= $client->search();
			}
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'order-item' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'order-item' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
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
		$default = 'order/list-standard.php';

		return $view->render( $view->config( $tplconf, $default ) );
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
	 * @param \Aimeos\MShop\Order\Item\Iface[] $orderItems List of order items
	 * @param \Aimeos\MShop\Order\Item\Base\Iface[] List of order base items
	 */
	protected function getOrderBaseItems( array $orderItems )
	{
		$baseIds = [];
		foreach( $orderItems as $item ) {
			$baseIds[] = $item->getBaseId();
		}

		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'order/base' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'order.base.id', $baseIds ) );
		$search->setSlice( 0, 0x7fffffff );

		return $manager->searchItems( $search, ['order/base/address', 'order/base/service'] );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames()
	{
		/** admin/jqadm/order/standard/subparts
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
		return $this->getContext()->getConfig()->get( 'admin/jqadm/order/standard/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param string[] Data array
	 * @return \Aimeos\MShop\Order\Item\Iface New order item object
	 */
	protected function fromArray( array $data )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'order/base' );
		$attrManager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'order/base/service/attribute' );

		if( isset( $data['order.base.id'] ) ) {
			$basket = $manager->load( $data['order.base.id'] );
		} else {
			$basket = $manager->createItem();
		}

		$basket->fromArray( $data );

		foreach( $basket->getProducts() as $pos => $product )
		{
			if( isset( $data['product'][$pos]['order.base.product.status'] ) ) {
				$product->setStatus( $data['product'][$pos]['order.base.product.status'] );
			}
		}

		foreach( $basket->getAddresses() as $type => $address )
		{
			if( isset( $data['address'][$type] ) ) {
				$address->fromArray( (array) $data['address'][$type] );
				$basket->setAddress( $address, $type );
			} else {
				$basket->deleteAddress( $type );
			}
		}

		foreach( $basket->getServices() as $type => $services )
		{
			foreach( $services as $serviceId => $service )
			{
				$list = [];
				$attrItems = $service->getAttributes();

				if( isset( $data['service'][$type][$serviceId] ) )
				{
					foreach( (array) $data['service'][$type][$serviceId] as $key => $pair )
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
							$attrItem = $attrManager->createItem();
						}

						$attrItem->fromArray( $array );
						$attrItem->setParentId( $service->getId() );

						$item = $attrManager->saveItem( $attrItem );
					}
				}

				$attrManager->deleteItems( array_keys( $attrItems ) );
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
	protected function toArray( \Aimeos\MShop\Order\Item\Base\Iface $item, $copy = false )
	{
		$siteId = $this->getContext()->getLocale()->getSiteId();
		$data = $item->toArray( true );

		if( $item->getCustomerId() != '' )
		{
			$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'customer' );

			try {
				$data += $manager->getItem( $item->getCustomerId() )->toArray();
			} catch( \Exception $e ) {};
		}


		if( $copy === true )
		{
			$data['order.base.siteid'] = $siteId;
			$data['order.base.id'] = '';
		}

		foreach( $item->getAddresses() as $type => $addrItem )
		{
			$list = $addrItem->toArray( true );

			foreach( $list as $key => $value ) {
				$data['address'][$type][$key] = $value;
			}

			if( $copy === true )
			{
				$data['address'][$type]['order.base.address.siteid'] = $siteId;
				$data['address'][$type]['order.base.address.id'] = '';
			}
		}

		if( $copy !== true )
		{
			foreach( $item->getServices() as $type => $services )
			{
				foreach( $services as $serviceItem )
				{
					$serviceId = $serviceItem->getServiceId();

					foreach( $serviceItem->getAttributes() as $attrItem )
					{
						foreach( $attrItem->toArray( true ) as $key => $value ) {
							$data['service'][$type][$serviceId][$key][] = $value;
						}
					}
				}
			}
		}

		foreach( $item->getProducts() as $pos => $productItem ) {
			$data['product'][$pos]['order.base.product.status'] = $productItem->getStatus();
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
		$default = 'order/item-standard.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
