<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Customer;

sprintf( 'users' ); // for translation
sprintf( 'customer' ); // for translation


/**
 * Default implementation of customer JQAdm client.
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

			$manager = \Aimeos\MShop::create( $this->getContext(), 'customer' );
			$view->item = $manager->get( $id, $this->getDomains() );

			$view->itemGroups = $this->getGroupItems( $view->item );
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
				$view->item = \Aimeos\MShop::create( $this->getContext(), 'customer' )->create();
			}

			$data['customer.siteid'] = $view->item->getSiteId();

			$view->itemData = array_replace_recursive( $this->toArray( $view->item ), $data );
			$view->itemGroups = $this->getGroupItems( $view->item );
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
		$context = $this->getContext();

		$manager = \Aimeos\MShop::create( $context, 'customer' );
		$manager->begin();

		try
		{
			if( ( $ids = $view->param( 'id' ) ) === null )
			{
				$msg = $context->translate( 'admin', 'Required parameter "%1$s" is missing' );
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, 'id' ) );
			}

			if( !$view->access( ['super', 'admin'] ) )
			{
				$msg = $context->translate( 'admin', 'Only super users and administrators can delete items' );
				throw new \Aimeos\Admin\JQAdm\Exception( $msg );
			}

			$search = $manager->filter()->slice( 0, count( (array) $ids ) );
			$search->add( $search->and( [
				$search->compare( '==', 'customer.id', $ids ),
				$search->compare( '!=', 'customer.siteid', '' )
			] ) );

			$items = $manager->search( $search, $this->getDomains() );

			foreach( $items as $item )
			{
				$view->item = $item;
				parent::delete();
			}

			$manager->delete( $items->toArray() );
			$manager->commit();

			if( $items->count() !== count( (array) $ids ) )
			{
				$msg = $context->translate( 'admin', 'Not all items could be deleted' );
				throw new \Aimeos\Admin\JQAdm\Exception( $msg );
			}

			return $this->redirect( 'customer', 'search', null, 'delete' );
		}
		catch( \Exception $e )
		{
			$manager->rollback();
			$this->report( $e, 'delete' );
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

			$manager = \Aimeos\MShop::create( $this->getContext(), 'customer' );

			$view->item = $manager->get( $id, $this->getDomains() );
			$view->itemGroups = $this->getGroupItems( $view->item );
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

		$manager = \Aimeos\MShop::create( $this->getContext(), 'customer' );
		$manager->begin();

		try
		{
			$item = $this->fromArray( $view->param( 'item', [] ) );
			$view->item = $item->getId() ? $item : $manager->save( $item );
			$view->itemBody = parent::save();

			$manager->save( clone $view->item );
			$manager->commit();

			return $this->redirect( 'customer', $view->param( 'next' ), $view->item->getId(), 'save' );
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
			$params = $this->storeFilter( $view->param(), 'customer' );
			$manager = \Aimeos\MShop::create( $this->getContext(), 'customer' );
			$search = $this->initCriteria( $manager->filter(), $params );

			$view->items = $manager->search( $search, $this->getDomains(), $total );
			$view->filterAttributes = $manager->getSearchAttributes( true );
			$view->filterOperators = $search->getOperators();
			$view->itemBody = parent::search();
			$view->total = $total;
		}
		catch( \Exception $e )
		{
			$this->report( $e, 'search' );
		}

		/** admin/jqadm/customer/template-list
		 * Relative path to the HTML body template for the customer list.
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
		$tplconf = 'admin/jqadm/customer/template-list';
		$default = 'customer/list-standard';

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
		/** admin/jqadm/customer/decorators/excludes
		 * Excludes decorators added by the "common" option from the customer JQAdm client
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
		 *  admin/jqadm/customer/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "client/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/customer/decorators/global
		 * @see admin/jqadm/customer/decorators/local
		 */

		/** admin/jqadm/customer/decorators/global
		 * Adds a list of globally available decorators only to the customer JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/customer/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/customer/decorators/excludes
		 * @see admin/jqadm/customer/decorators/local
		 */

		/** admin/jqadm/customer/decorators/local
		 * Adds a list of local decorators only to the customer JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Customer\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/customer/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Customer\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/customer/decorators/excludes
		 * @see admin/jqadm/customer/decorators/global
		 */
		return $this->createSubClient( 'customer/' . $type, $name );
	}


	/**
	 * Returns the domain names whose items should be fetched too
	 *
	 * @return string[] List of domain names
	 */
	protected function getDomains() : array
	{
		/** admin/jqadm/customer/domains
		 * List of domain items that should be fetched along with the customer
		 *
		 * If you need to display additional content, you can configure your own
		 * list of domains (attribute, media, price, customer, text, etc. are
		 * domains) whose items are fetched from the storage.
		 *
		 * @param array List of domain names
		 * @since 2017.07
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/customer/domains', [] );
	}


	/**
	 * Returns the available group items
	 *
	 * @param \Aimeos\MShop\Customer\Item\Iface|null $item Customer item that should be updated
	 * @return \Aimeos\MShop\Customer\Item\Group\Iface[] Associative list of group IDs as keys and group items as values
	 */
	protected function getGroupItems( \Aimeos\MShop\Customer\Item\Iface $item = null ) : array
	{
		$list = [];
		$view = $this->view();
		$context = $this->getContext();

		$isSuper = $view->access( ['super'] );
		$isAdmin = $view->access( ['admin'] );
		$isEditor = $view->access( ['editor'] );

		$manager = \Aimeos\MShop::create( $context, 'customer/group' );
		$search = $manager->filter( true )->slice( 0, 10000 )->order( 'customer.group.label' );

		foreach( $manager->search( $search ) as $groupId => $groupItem )
		{
			if( !$isSuper && $groupItem->getCode() === 'super' ) {
				continue;
			}

			if( !$isSuper && !$isAdmin && $groupItem->getCode() === 'admin' ) {
				continue;
			}

			if( !$isSuper && !$isAdmin && $groupItem->getCode() === 'editor'
				&& ( !$isEditor || $item === null || (string) $context->getUserId() !== (string) $item->getId() )
			) {
				continue;
			}

			$list[$groupId] = $groupItem;
		}

		return $list;
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/customer/subparts
		 * List of JQAdm sub-clients rendered within the customer section
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
		 * @since 2017.07
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/customer/subparts', [] );
	}



	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Customer\Item\Iface New customer item object
	 */
	protected function fromArray( array $data ) : \Aimeos\MShop\Customer\Item\Iface
	{
		$context = $this->getContext();
		$manager = \Aimeos\MShop::create( $context, 'customer' );

		if( isset( $data['customer.id'] ) && $data['customer.id'] != '' ) {
			$item = $manager->get( $data['customer.id'], $this->getDomains() );
		} else {
			$item = $manager->create();
		}

		$addr = $item->getPaymentAddress();
		$label = ( $addr->getFirstname() ? $addr->getFirstname() . ' ' : '' ) . $addr->getLastname();
		$label .= ( $addr->getCompany() ? ' (' . $addr->getCompany() . ')' : '' );

		$groupIds = $this->getValue( $data, 'customer.groups', [] );
		$gids = array_keys( $this->getGroupItems( $item ) );

		$item->setLabel( $label )->setStatus( $data['customer.status'] ?? 0 )
			->setGroups( array_intersect( $gids, $groupIds ) );

		if( $this->view()->access( ['super'] ) || $item->getId() === $context->getUserId() )
		{
			!isset( $data['customer.password'] ) ?: $item->setPassword( $data['customer.password'] );
			!isset( $data['customer.code'] ) ?: $item->setCode( $data['customer.code'] );
		}

		return $item->fromArray( $data );
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Customer\Item\Iface $item Customer item object
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Customer\Item\Iface $item, bool $copy = false ) : array
	{
		$data = $item->toArray( true );

		if( $this->view()->access( ['super'] ) || $item->getId() === $this->getContext()->getUserId() ) {
			$data['.modify'] = true;
		}

		if( $copy === true )
		{
			$data['customer.siteid'] = $this->getContext()->getLocale()->getSiteId();
			$data['customer.code'] = '';
			$data['customer.id'] = '';
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
		/** admin/jqadm/customer/template-item
		 * Relative path to the HTML body template for the customer item.
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
		$tplconf = 'admin/jqadm/customer/template-item';
		$default = 'customer/item-standard';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
