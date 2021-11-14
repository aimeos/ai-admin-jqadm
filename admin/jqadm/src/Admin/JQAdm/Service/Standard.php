<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Service;

sprintf( 'configuration' ); // for translation
sprintf( 'service' ); // for translation


/**
 * Default implementation of service JQAdm client.
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
		$ds = DIRECTORY_SEPARATOR;

		$view->itemDecorators = $this->getClassNames( 'MShop' . $ds . 'Service' . $ds . 'Provider' . $ds . 'Decorator' );
		$view->itemProviders = [
			'delivery' => $this->getClassNames( 'MShop' . $ds . 'Service' . $ds . 'Provider' . $ds . 'Delivery' ),
			'payment' => $this->getClassNames( 'MShop' . $ds . 'Service' . $ds . 'Provider' . $ds . 'Payment' ),
		];

		$view->itemSubparts = $this->getSubClientNames();
		$view->itemTypes = $this->getTypeItems();

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

			$manager = \Aimeos\MShop::create( $this->getContext(), 'service' );

			$view->item = $manager->get( $id, $this->getDomains() );
			$view->itemAttributes = $this->getConfigAttributes( $view->item );
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
				$view->item = \Aimeos\MShop::create( $this->getContext(), 'service' )->create();
			}

			$data['service.siteid'] = $view->item->getSiteId();

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

		$manager = \Aimeos\MShop::create( $this->getContext(), 'service' );
		$manager->begin();

		try
		{
			if( ( $ids = $view->param( 'id' ) ) === null )
			{
				$msg = $this->getContext()->translate( 'admin', 'Required parameter "%1$s" is missing' );
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, 'id' ) );
			}

			$search = $manager->filter()->slice( 0, count( (array) $ids ) );
			$search->setConditions( $search->compare( '==', 'service.id', $ids ) );
			$items = $manager->search( $search, $this->getDomains() );

			foreach( $items as $item )
			{
				$view->item = $item;
				parent::delete();
			}

			$manager->delete( $items->toArray() );
			$manager->commit();

			return $this->redirect( 'service', 'search', null, 'delete' );
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

			$manager = \Aimeos\MShop::create( $this->getContext(), 'service' );

			$view->item = $manager->get( $id, $this->getDomains() );
			$view->itemAttributes = $this->getConfigAttributes( $view->item );
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

		$manager = \Aimeos\MShop::create( $this->getContext(), 'service' );
		$manager->begin();

		try
		{
			$item = $this->fromArray( $view->param( 'item', [] ) );
			$view->item = $item->getId() ? $item : $manager->save( $item );
			$view->itemBody = parent::save();

			$manager->save( clone $view->item );
			$manager->commit();

			return $this->redirect( 'service', $view->param( 'next' ), $view->item->getId(), 'save' );
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
			$params = $this->storeFilter( $view->param(), 'service' );
			$manager = \Aimeos\MShop::create( $this->getContext(), 'service' );

			$search = $manager->filter();
			$search->setSortations( [$search->sort( '+', 'service.type' ), $search->sort( '+', 'service.position' )] );
			$search = $this->initCriteria( $search, $params );

			$view->items = $manager->search( $search, $this->getDomains(), $total );
			$view->filterAttributes = $manager->getSearchAttributes( true );
			$view->filterOperators = $search->getOperators();
			$view->itemTypes = $this->getTypeItems();
			$view->itemBody = parent::search();
			$view->total = $total;
		}
		catch( \Exception $e )
		{
			$this->report( $e, 'search' );
		}

		/** admin/jqadm/service/template-list
		 * Relative path to the HTML body template for the service list.
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
		$tplconf = 'admin/jqadm/service/template-list';
		$default = 'service/list-standard';

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
		/** admin/jqadm/service/decorators/excludes
		 * Excludes decorators added by the "common" option from the service JQAdm client
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
		 *  admin/jqadm/service/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "client/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.10
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/service/decorators/global
		 * @see admin/jqadm/service/decorators/local
		 */

		/** admin/jqadm/service/decorators/global
		 * Adds a list of globally available decorators only to the service JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/service/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.10
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/service/decorators/excludes
		 * @see admin/jqadm/service/decorators/local
		 */

		/** admin/jqadm/service/decorators/local
		 * Adds a list of local decorators only to the service JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Service\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/service/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Service\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.10
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/service/decorators/excludes
		 * @see admin/jqadm/service/decorators/global
		 */
		return $this->createSubClient( 'service/' . $type, $name );
	}


	/**
	 * Returns the backend configuration attributes of the provider and decorators
	 *
	 * @param \Aimeos\MShop\Service\Item\Iface $item Service item incl. provider/decorator property
	 * @return \Aimeos\MW\Common\Critera\Attribute\Iface[] List of configuration attributes
	 */
	public function getConfigAttributes( \Aimeos\MShop\Service\Item\Iface $item ) : array
	{
		$manager = \Aimeos\MShop::create( $this->getContext(), 'service' );

		try {
			return $manager->getProvider( $item, $item->getType() )->getConfigBE();
		} catch( \Aimeos\MShop\Exception $e ) {
			return [];
		}
	}


	/**
	 * Returns the domain names whose items should be fetched too
	 *
	 * @return string[] List of domain names
	 */
	protected function getDomains() : array
	{
		/** admin/jqadm/service/domains
		 * List of domain items that should be fetched along with the service
		 *
		 * If you need to display additional content, you can configure your own
		 * list of domains (attribute, media, price, service, text, etc. are
		 * domains) whose items are fetched from the storage.
		 *
		 * @param array List of domain names
		 * @since 2017.10
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/service/domains', [] );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/service/subparts
		 * List of JQAdm sub-clients rendered within the service section
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
		 * @since 2017.10
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/service/subparts', [] );
	}


	/**
	 * Returns the available service type items
	 *
	 * @return \Aimeos\Map List of item implementing \Aimeos\MShop\Common\Type\Iface
	 */
	protected function getTypeItems() : \Aimeos\Map
	{
		$typeManager = \Aimeos\MShop::create( $this->getContext(), 'service/type' );

		$search = $typeManager->filter( true )->slice( 0, 10000 );
		$search->setSortations( [$search->sort( '+', 'service.type.position' )] );

		return $typeManager->search( $search );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Service\Item\Iface New service item object
	 */
	protected function fromArray( array $data ) : \Aimeos\MShop\Service\Item\Iface
	{
		$conf = [];

		if( isset( $data['config']['key'] ) )
		{
			foreach( (array) $data['config']['key'] as $idx => $key )
			{
				if( trim( $key ) !== '' && isset( $data['config']['val'][$idx] ) )
				{
					if( ( $val = json_decode( $data['config']['val'][$idx], true ) ) === null ) {
						$conf[$key] = $data['config']['val'][$idx];
					} else {
						$conf[$key] = $val;
					}
				}
			}
		}

		$manager = \Aimeos\MShop::create( $this->getContext(), 'service' );

		if( isset( $data['service.id'] ) && $data['service.id'] != '' ) {
			$item = $manager->get( $data['service.id'], $this->getDomains() );
		} else {
			$item = $manager->create();
		}

		$item = $item->fromArray( $data, true )->setConfig( $conf );

		$this->notify( $manager->getProvider( $item, $item->getType() )->checkConfigBE( $conf ) );

		return $item;
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Service\Item\Iface $item Service item object
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Service\Item\Iface $item, bool $copy = false ) : array
	{
		$config = $item->getConfig();
		$data = $item->toArray( true );
		$data['config'] = [];

		if( $copy === true )
		{
			$data['service.siteid'] = $this->getContext()->getLocale()->getSiteId();
			$data['service.code'] = $data['service.code'] . '_' . substr( md5( microtime( true ) ), -5 );
			$data['service.id'] = '';
		}

		ksort( $config );

		foreach( $config as $key => $value )
		{
			$data['config']['key'][] = $key;
			$data['config']['val'][] = $value;
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
		/** admin/jqadm/service/template-item
		 * Relative path to the HTML body template for the service item.
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
		$tplconf = 'admin/jqadm/service/template-item';
		$default = 'service/item-standard';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
