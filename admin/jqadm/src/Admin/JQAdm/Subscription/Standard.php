<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Subscription;

sprintf( 'sales' ); // for translation
sprintf( 'subscription' ); // for translation


/**
 * Default implementation of subscription JQAdm client.
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
		$view->itemSubparts = $this->getSubClientNames();
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
		$context = $this->getContext();

		try
		{
			if( ( $id = $view->param( 'id' ) ) === null )
			{
				$msg = $this->getContext()->translate( 'admin', 'Required parameter "%1$s" is missing' );
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, 'id' ) );
			}

			$manager = \Aimeos\MShop::create( $context, 'subscription' );
			$baseManager = \Aimeos\MShop::create( $context, 'order/base' );

			$view->item = $manager->get( $id );
			$view->itemBase = $baseManager->get( $view->item->getOrderBaseId(), ['order/base/address', 'order/base/product'] );
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
		$context = $this->getContext();

		try
		{
			$data = $view->param( 'item', [] );

			if( !isset( $view->item ) ) {
				$view->item = \Aimeos\MShop::create( $context, 'subscription' )->create();
			}

			$baseManager = \Aimeos\MShop::create( $context, 'order/base' );
			$baseId = ( $view->item->getOrderBaseId() ?: $view->param( 'item/subscription.ordbaseid' ) );

			if( $baseId ) {
				$view->itemBase = $baseManager->get( $baseId, ['order/base/address', 'order/base/product'] );
			} else {
				$view->itemBase = $baseManager->create();
			}

			$data['subscription.siteid'] = $view->item->getSiteId();

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
	 * Deletes a resource
	 *
	 * @return string|null HTML output
	 */
	public function delete() : ?string
	{
		$view = $this->view();

		$manager = \Aimeos\MShop::create( $this->getContext(), 'subscription' );
		$manager->begin();

		try
		{
			if( ( $ids = $view->param( 'id' ) ) === null )
			{
				$msg = $this->getContext()->translate( 'admin', 'Required parameter "%1$s" is missing' );
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, 'id' ) );
			}

			$search = $manager->filter()->slice( 0, count( (array) $ids ) );
			$search->setConditions( $search->compare( '==', 'subscription.id', $ids ) );
			$items = $manager->search( $search );

			foreach( $items as $item )
			{
				$view->item = $item;
				parent::delete();
			}

			$manager->delete( $items->toArray() );
			$manager->commit();

			return $this->redirect( 'subscription', 'search', null, 'delete' );
		}
		catch( \Exception $e )
		{
			$manager->rollback();
			$this->report( $e, 'delete' );
		}

		return $this->search();
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
			$params = $this->storeFilter( $view->param(), 'subscription' );
			$msg = ['sitecode' => $context->getLocale()->getSiteItem()->getCode()];

			if( isset( $params['filter'] ) ) {
				$msg['filter'] = $this->getCriteriaConditions( (array) $params['filter'] );
			}

			if( isset( $params['sort'] ) ) {
				$msg['sort'] = (array) $params['sort'];
			}

			$mq = $context->getMessageQueueManager()->get( 'mq-admin' )->getQueue( 'subscription-export' );
			$mq->add( json_encode( $msg ) );

			$msg = $context->translate( 'admin', 'Your export will be available in a few minutes for download' );
			$view->info = $view->get( 'info', [] ) + ['subscription-item' => $msg];
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
		$context = $this->getContext();

		try
		{
			if( ( $id = $view->param( 'id' ) ) === null )
			{
				$msg = $this->getContext()->translate( 'admin', 'Required parameter "%1$s" is missing' );
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, 'id' ) );
			}

			$manager = \Aimeos\MShop::create( $context, 'subscription' );
			$baseManager = \Aimeos\MShop::create( $context, 'order/base' );

			$view->item = $manager->get( $id );
			$view->itemBase = $baseManager->get( $view->item->getOrderBaseId(), ['order/base/address', 'order/base/product'] );
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

		$manager = \Aimeos\MShop::create( $this->getContext(), 'subscription' );
		$manager->begin();

		try
		{
			$item = $this->fromArray( $view->param( 'item', [] ) );
			$view->item = $item->getId() ? $item : $manager->save( $item );
			$view->itemBody = parent::save();

			$manager->save( clone $view->item );
			$manager->commit();

			return $this->redirect( 'subscription', $view->param( 'next' ), $view->item->getId(), 'save' );
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
			$params = $this->storeFilter( $view->param(), 'subscription' );
			$manager = \Aimeos\MShop::create( $this->getContext(), 'subscription' );

			$search = $manager->filter( false, true );
			$search->setSortations( [$search->sort( '-', 'subscription.ctime' )] );
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

		/** admin/jqadm/subscription/template-list
		 * Relative path to the HTML body template for the subscription list.
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
		$tplconf = 'admin/jqadm/subscription/template-list';
		$default = 'subscription/list-standard';

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
		/** admin/jqadm/subscription/decorators/excludes
		 * Excludes decorators added by the "common" option from the subscription JQAdm client
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
		 *  admin/jqadm/subscription/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "client/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.04
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/subscription/decorators/global
		 * @see admin/jqadm/subscription/decorators/local
		 */

		/** admin/jqadm/subscription/decorators/global
		 * Adds a list of globally available decorators only to the subscription JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/subscription/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.04
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/subscription/decorators/excludes
		 * @see admin/jqadm/subscription/decorators/local
		 */

		/** admin/jqadm/subscription/decorators/local
		 * Adds a list of local decorators only to the subscription JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Subscription\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/subscription/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Subscription\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.04
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/subscription/decorators/excludes
		 * @see admin/jqadm/subscription/decorators/global
		 */
		return $this->createSubClient( 'subscription/' . $type, $name );
	}


	/**
	 * Returns the base order items (baskets) for the given subscription items
	 *
	 * @param \Aimeos\Map $items List of subscription items implementing \Aimeos\MShop\Subscription\Item\Iface
	 * @return \Aimeos\Map List of order base items implementing \Aimeos\MShop\Order\Item\Base\Iface
	 */
	protected function getOrderBaseItems( \Aimeos\Map $items ) : \Aimeos\Map
	{
		$baseIds = $items->getOrderBaseId()->toArray();
		$manager = \Aimeos\MShop::create( $this->getContext(), 'order/base' );

		$search = $manager->filter()->slice( 0, count( $baseIds ) );
		$search->setConditions( $search->compare( '==', 'order.base.id', $baseIds ) );

		return $manager->search( $search, ['order/base/address', 'order/base/product'] );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/subscription/subparts
		 * List of JQAdm sub-clients rendered within the subscription section
		 *
		 * The output of the frontend is composed of the code generated by the JQAdm
		 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
		 * that are responsible for rendering certain sub-parts of the output. The
		 * sub-clients can contain JQAdm clients themselves and therefore a
		 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
		 * the output that is placed inside the container of its parent.
		 *
		 * At first, always the JQAdm code generated by the parent is printed, then
		 * the JQAdm code of its sub-clients. The subscription of the JQAdm sub-clients
		 * determines the subscription of the output of these sub-clients inside the parent
		 * container. If the configured list of clients is
		 *
		 *  array( "subclient1", "subclient2" )
		 *
		 * you can easily change the subscription of the output by resubscriptioning the subparts:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
		 *
		 * You can also remove one or more parts if they shouldn't be rendered:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
		 *
		 * As the clients only generates structural JQAdm, the layout defined via CSS
		 * should support adding, removing or resubscriptioning content by a fluid like
		 * design.
		 *
		 * @param array List of sub-client names
		 * @since 2018.04
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/subscription/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Subscription\Item\Iface New subscription item object
	 */
	protected function fromArray( array $data ) : \Aimeos\MShop\Subscription\Item\Iface
	{
		$manager = \Aimeos\MShop::create( $this->getContext(), 'subscription' );

		if( isset( $data['subscription.id'] ) && $data['subscription.id'] != '' ) {
			$item = $manager->get( $data['subscription.id'] );
		} else {
			$item = $manager->create();
		}

		$item->fromArray( $data, true );

		return $item;
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Subscription\Item\Iface $item Subscription item object
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Subscription\Item\Iface $item, bool $copy = false ) : array
	{
		$siteId = $this->getContext()->getLocale()->getSiteId();
		$data = $item->toArray( true );

		if( $copy === true )
		{
			$data['subscription.siteid'] = $siteId;
			$data['subscription.id'] = '';
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
		/** admin/jqadm/subscription/template-item
		 * Relative path to the HTML body template for the subscription item.
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
		$tplconf = 'admin/jqadm/subscription/template-item';
		$default = 'subscription/item-standard';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
