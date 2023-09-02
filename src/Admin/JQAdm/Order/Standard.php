<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
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
	/** admin/jqadm/order/name
	 * Class name of the used account favorite client implementation
	 *
	 * Each default admin client can be replace by an alternative imlementation.
	 * To use this implementation, you have to set the last part of the class
	 * name as configuration value so the client factory knows which class it
	 * has to instantiate.
	 *
	 * For example, if the name of the default class is
	 *
	 *  \Aimeos\Admin\JQAdm\Order\Standard
	 *
	 * and you want to replace it with your own version named
	 *
	 *  \Aimeos\Admin\JQAdm\Order\Myfavorite
	 *
	 * then you have to set the this configuration option:
	 *
	 *  admin/jqadm/order/name = Myfavorite
	 *
	 * The value is the last part of your own class name and it's case sensitive,
	 * so take care that the configuration value is exactly named like the last
	 * part of the class name.
	 *
	 * The allowed characters of the class name are A-Z, a-z and 0-9. No other
	 * characters are possible! You should always start the last part of the class
	 * name with an upper case character and continue only with lower case characters
	 * or numbers. Avoid chamel case names like "MyFavorite"!
	 *
	 * @param string Last part of the class name
	 * @since 2016.01
	 */


	/**
	 * Adds the required data used in the template
	 *
	 * @param \Aimeos\Base\View\Iface $view View object
	 * @return \Aimeos\Base\View\Iface View object with assigned parameters
	 */
	public function data( \Aimeos\Base\View\Iface $view ) : \Aimeos\Base\View\Iface
	{
		$codes = [];

		foreach( $this->context()->config()->get( 'common/countries', [] ) as $code ) {
			$codes[$code] = $view->translate( 'country', $code );
		}

		asort( $codes );

		$view->itemSubparts = $this->getSubClientNames();
		$view->countries = $codes;
		return $view;
	}


	/**
	 * Batch update of a resource
	 *
	 * @return string|null Output to display
	 */
	public function batch() : ?string
	{
		return $this->batchBase( 'order' );
	}


	/**
	 * Copies a resource
	 *
	 * @return string|null HTML output
	 */
	public function copy() : ?string
	{
		$context = $this->context();
		$view = $this->object()->data( $this->view() );

		try
		{
			if( ( $id = $view->param( 'id' ) ) === null )
			{
				$msg = $context->translate( 'admin', 'Required parameter "%1$s" is missing' );
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, 'id' ) );
			}

			$manager = \Aimeos\MShop::create( $context, 'order' );
			$refs = $context->config()->get( 'mshop/order/manager/subdomains', [] );

			$view->item = $manager->get( $id, $refs );
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
		$view = $this->object()->data( $this->view() );

		try
		{
			$data = $view->param( 'item', [] );

			if( !isset( $view->item ) ) {
				$view->item = \Aimeos\MShop::create( $this->context(), 'order' )->create();
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
		$context = $this->context();

		try
		{
			$params = $this->storeFilter( $view->param(), 'order' );
			$msg = ['sitecode' => $context->locale()->getSiteItem()->getCode()];

			if( isset( $params['filter'] ) ) {
				$msg['filter'] = $this->getCriteriaConditions( (array) $params['filter'] );
			}

			if( isset( $params['sort'] ) ) {
				$msg['sort'] = (array) $params['sort'];
			}

			$queue = $view->param( 'queue', 'order-export' );
			$mq = $context->queue( 'mq-admin', $queue );
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
		$context = $this->context();
		$view = $this->object()->data( $this->view() );

		try
		{
			if( ( $id = $view->param( 'id' ) ) === null )
			{
				$msg = $context->translate( 'admin', 'Required parameter "%1$s" is missing' );
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, 'id' ) );
			}

			$manager = \Aimeos\MShop::create( $context, 'order' );
			$refs = $context->config()->get( 'mshop/order/manager/subdomains', [] );

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

		$manager = \Aimeos\MShop::create( $this->context(), 'order' );
		$manager->begin();

		try
		{
			$item = $this->fromArray( $view->param( 'item', [] ) );
			$view->item = $item->getId() ? $item : $manager->save( clone $item );
			$view->itemBody = parent::save();

			$manager->save( clone $view->item );
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
			$context = $this->context();
			$refs = $context->config()->get( 'mshop/order/manager/subdomains', [] );

			$manager = \Aimeos\MShop::create( $context, 'order' );
			$params = $this->storeFilter( $view->param(), 'order' );

			$search = $manager->filter( false, true )->order( '-order.id' );
			$search = $this->initCriteria( $search, $params );

			$view->items = $manager->search( $search, $refs, $total );
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
		$tplconf = 'admin/jqadm/order/template-list';
		$default = 'order/list';

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
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/order/decorators/excludes
		 * @see admin/jqadm/order/decorators/global
		 */
		return $this->createSubClient( 'order/' . $type, $name );
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
		 */
		return $this->context()->config()->get( 'admin/jqadm/order/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Order\Item\Iface New order item object
	 */
	protected function fromArray( array $data ) : \Aimeos\MShop\Order\Item\Iface
	{
		$context = $this->context();

		$manager = \Aimeos\MShop::create( $context, 'order' );
		$attrManager = \Aimeos\MShop::create( $context, 'order/service/attribute' );

		if( isset( $data['order.id'] ) ) {
			$refs = $context->config()->get( 'mshop/order/manager/subdomains', [] );
			$basket = $manager->get( $data['order.id'], $refs )->off();
		} else {
			$basket = $manager->create()->off();
		}

		$basket->fromArray( $data, true );
		$allowed = array_flip( [
			'order.product.statusdelivery',
			'order.product.statuspayment',
			'order.product.qtyopen',
			'order.product.timeframe',
			'order.product.notes',
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
						if( isset( $attrItems[$array['order.service.attribute.id']] ) )
						{
							$attrItem = $attrItems[$array['order.service.attribute.id']];
							unset( $attrItems[$array['order.service.attribute.id']] );
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
	 * @param \Aimeos\MShop\Order\Item\Iface $item Order item object
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Order\Item\Iface $item, bool $copy = false ) : array
	{
		$siteId = $this->context()->locale()->getSiteId();
		$data = $item->toArray( true );

		if( $item->getCustomerId() != '' )
		{
			try {
				$data += \Aimeos\MShop::create( $this->context(), 'customer' )->get( $item->getCustomerId() )->toArray();
			} catch( \Exception $e ) {};
		}


		if( $copy === true )
		{
			$data['order.siteid'] = $siteId;
			$data['order.id'] = '';
		}

		foreach( $item->getAddresses() as $type => $addresses )
		{
			foreach( $addresses as $pos => $addrItem )
			{
				$data['address'][$type][$pos] = $addrItem->toArray( true );

				if( $copy === true )
				{
					$data['address'][$type][$pos]['order.address.siteid'] = $siteId;
					$data['address'][$type][$pos]['order.address.id'] = '';
				}
			}
		}

		foreach( $item->getProducts() as $pos => $productItem )
		{
			$data['product'][$pos] = $productItem->toArray( true );

			foreach( $productItem->getAttributeItems() as $attrItem ) {
				$data['product'][$pos]['attributes'][] = $attrItem->toArray( true );
			}

			if( $copy === true )
			{
				$data['product'][$pos]['order.product.siteid'] = $siteId;
				$data['product'][$pos]['order.product.id'] = '';
			}
		}

		foreach( $item->getServices() as $type => $services )
		{
			foreach( $services as $pos => $serviceItem )
			{
				$data['service'][$type][$pos] = $serviceItem->toArray( true );

				foreach( $serviceItem->getAttributeItems() as $attrItem ) {
					$data['service'][$type][$pos]['attributes'][] = $attrItem->toArray( true );
				}

				if( $copy === true )
				{
					$data['service'][$type][$pos]['order.service.siteid'] = $siteId;
					$data['service'][$type][$pos]['order.service.id'] = '';
				}
			}
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
		/** admin/jqadm/order/template-item
		 * Relative path to the HTML body template for the order item.
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
		$tplconf = 'admin/jqadm/order/template-item';
		$default = 'order/item';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
