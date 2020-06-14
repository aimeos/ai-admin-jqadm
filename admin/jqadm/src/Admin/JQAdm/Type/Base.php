<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2020
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Type;


/**
 * Base implementation of type JQAdm clients.
 *
 * @package Admin
 * @subpackage JQAdm
 */
abstract class Base
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/**
	 * Adds the required data used in the template
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @return \Aimeos\MW\View\Iface View object with assigned parameters
	 */
	public function addData( \Aimeos\MW\View\Iface $view ) : \Aimeos\MW\View\Iface
	{
		$view->itemSubparts = $this->getSubClientNames();
		return $view;
	}


	/**
	 * Returns the rendered template including the view data
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	abstract protected function render( \Aimeos\MW\View\Iface $view ) : string;


	/**
	 * Copies a resource
	 *
	 * @param string $path Path name like "attribute/lists" without "/type" postfix
	 * @return string|null HTML output
	 */
	protected function copyBase( string $path ) : string
	{
		$view = $this->getObject()->addData( $this->getView() );

		try
		{
			if( ( $id = $view->param( 'id' ) ) === null ) {
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Required parameter "%1$s" is missing', 'id' ) );
			}

			$manager = \Aimeos\MShop::create( $this->getContext(), $path . '/type' );
			$view->item = $manager->getItem( $id );

			$view->itemData = $this->toArray( $path, $view->item, true );
			$view->itemBody = '';

			foreach( $this->getSubClients() as $idx => $client )
			{
				$view->tabindex = ++$idx + 1;
				$view->itemBody .= $client->copy();
			}
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
	 * @param string $path Path name like "attribute/lists" without "/type" postfix
	 * @return string|null HTML output
	 */
	public function createBase( string $path ) : string
	{
		$view = $this->getObject()->addData( $this->getView() );

		try
		{
			$data = $view->param( 'item', [] );

			if( !isset( $view->item ) ) {
				$view->item = \Aimeos\MShop::create( $this->getContext(), $path . '/type' )->createItem();
			}

			$data[str_replace( '/', '.', $path ) . '.type.siteid'] = $view->item->getSiteId();

			$view->itemData = array_replace_recursive( $this->toArray( $path, $view->item ), $data );
			$view->itemBody = '';

			foreach( $this->getSubClients() as $idx => $client )
			{
				$view->tabindex = ++$idx + 1;
				$view->itemBody .= $client->create();
			}
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
	 * @param string $path Path name like "attribute/lists" without "/type" postfix
	 * @return string|null HTML output
	 */
	public function deleteBase( string $path ) : ?string
	{
		$view = $this->getView();

		$manager = \Aimeos\MShop::create( $this->getContext(), $path . '/type' );
		$manager->begin();

		try
		{
			if( ( $ids = $view->param( 'id' ) ) === null ) {
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Required parameter "%1$s" is missing', 'id' ) );
			}

			$search = $manager->createSearch()->setSlice( 0, count( (array) $ids ) );
			$search->setConditions( $search->compare( '==', str_replace( '/', '.', $path ) . '.type.id', $ids ) );
			$items = $manager->searchItems( $search );

			foreach( $items as $item )
			{
				$view->item = $item;

				foreach( $this->getSubClients() as $client ) {
					$client->delete();
				}
			}

			$manager->deleteItems( $items->toArray() );
			$manager->commit();

			return $this->redirect( 'type/' . $path, 'search', null, 'delete' );
		}
		catch( \Exception $e )
		{
			$this->report( $e, 'delete' );
		}

		$manager->rollback();

		return $this->search();
	}


	/**
	 * Returns a single resource
	 *
	 * @param string $path Path name like "attribute/lists" without "/type" postfix
	 * @return string|null HTML output
	 */
	public function getBase( string $path ) : string
	{
		$view = $this->getObject()->addData( $this->getView() );

		try
		{
			if( ( $id = $view->param( 'id' ) ) === null ) {
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Required parameter "%1$s" is missing', 'id' ) );
			}

			$manager = \Aimeos\MShop::create( $this->getContext(), $path . '/type' );

			$view->item = $manager->getItem( $id );
			$view->itemData = $this->toArray( $path, $view->item );
			$view->itemBody = '';

			foreach( $this->getSubClients() as $idx => $client )
			{
				$view->tabindex = ++$idx + 1;
				$view->itemBody .= $client->get();
			}
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
	 * @param string $path Path name like "attribute/lists" without "/type" postfix
	 * @return string|null HTML output
	 */
	public function saveBase( string $path ) : ?string
	{
		$view = $this->getView();

		$manager = \Aimeos\MShop::create( $this->getContext(), $path . '/type' );
		$manager->begin();

		try
		{
			$item = $this->fromArray( $path, $view->param( 'item', [] ) );
			$view->item = $item->getId() ? $item : $manager->saveItem( $item );
			$view->itemBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->itemBody .= $client->save();
			}

			$manager->saveItem( clone $view->item );
			$manager->commit();

			return $this->redirect( 'type/' . $path, $view->param( 'next' ), $view->item->getId(), 'save' );
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
	 * @param string $path Path name like "attribute/lists" without "/type" postfix
	 * @return \Aimeos\MW\View\Iface View object ready for rendering
	 */
	public function searchBase( string $path ) : \Aimeos\MW\View\Iface
	{
		$view = $this->getView();

		try
		{
			$total = 0;
			$params = $this->storeSearchParams( $view->param(), 'type/' . $path );
			$manager = \Aimeos\MShop::create( $this->getContext(), $path . '/type' );
			$search = $this->initCriteria( $manager->createSearch(), $params );

			$view->items = $manager->searchItems( $search, [], $total );
			$view->filterAttributes = $manager->getSearchAttributes( true );
			$view->filterOperators = $search->getOperators();
			$view->total = $total;
			$view->itemBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->itemBody .= $client->search();
			}
		}
		catch( \Exception $e )
		{
			$this->report( $e, 'search' );
		}

		return $view;
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param string $path Path name like "attribute/lists" without "/type" postfix
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Common\Item\Type\Iface New type item object
	 */
	protected function fromArray( string $path, array $data ) : \Aimeos\MShop\Common\Item\Type\Iface
	{
		$key = str_replace( '/', '.', $path ) . '.type.id';
		$manager = \Aimeos\MShop::create( $this->getContext(), $path . '/type' );

		if( isset( $data[$key] ) && $data[$key] != '' ) {
			$item = $manager->getItem( $data[$key] );
		} else {
			$item = $manager->createItem();
		}

		$item->fromArray( $data, true );

		return $item;
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param string $path Path name like "attribute/lists" without "/type" postfix
	 * @param \Aimeos\MShop\Common\Item\Type\Iface $item Type item object
	 * @param bool True if item is going to be copied, false if not
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( string $path, \Aimeos\MShop\Common\Item\Type\Iface $item, bool $copy = false )
	{
		$key = str_replace( '/', '.', $path );
		$data = $item->toArray( true );

		if( $copy === true )
		{
			$data[$key . '.type.code'] = $data[$key . '.type.code'] . '_copy';
			$data[$key . '.type.id'] = '';
		}

		return $data;
	}
}
