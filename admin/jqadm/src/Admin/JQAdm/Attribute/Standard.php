<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Attribute;

sprintf( 'attribute' ); // for translation


/**
 * Default implementation of attribute JQAdm client.
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

			$manager = \Aimeos\MShop\Factory::createManager( $context, 'attribute' );
			$view->item = $manager->getItem( $id, $this->getDomains() );

			$view->itemData = $this->toArray( $view->item, true );
			$view->itemSubparts = $this->getSubClientNames();
			$view->itemTypes = $this->getTypeItems();
			$view->itemBody = '';

			foreach( $this->getSubClients() as $idx => $client )
			{
				$view->tabindex = ++$idx + 1;
				$view->itemBody .= $client->copy();
			}
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'attribute-item' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'attribute-item' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
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
				$view->item = \Aimeos\MShop\Factory::createManager( $context, 'attribute' )->createItem();
			}

			$data['attribute.siteid'] = $view->item->getSiteId();

			$view->itemSubparts = $this->getSubClientNames();
			$view->itemTypes = $this->getTypeItems();
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
			$error = array( 'attribute-item' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'attribute-item' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		return $this->render( $view );
	}


	/**
	 * Deletes a resource
	 *
	 * @return string|null HTML output
	 */
	public function delete()
	{
		$view = $this->getView();
		$context = $this->getContext();

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'attribute' );
		$manager->begin();

		try
		{
			if( ( $id = $view->param( 'id' ) ) === null ) {
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Required parameter "%1$s" is missing', 'id' ) );
			}

			$view->item = $manager->getItem( $id, $this->getDomains() );

			foreach( $this->getSubClients() as $client ) {
				$client->delete();
			}

			$manager->saveItem( $view->item );
			$manager->deleteItem( $id );
			$manager->commit();

			$this->nextAction( $view, 'search', 'attribute', null, 'delete' );
			return;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'attribute-item' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'attribute-item' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		$manager->rollback();

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

			$manager = \Aimeos\MShop\Factory::createManager( $context, 'attribute' );

			$view->item = $manager->getItem( $id, $this->getDomains() );
			$view->itemSubparts = $this->getSubClientNames();
			$view->itemData = $this->toArray( $view->item );
			$view->itemTypes = $this->getTypeItems();
			$view->itemBody = '';

			foreach( $this->getSubClients() as $idx => $client )
			{
				$view->tabindex = ++$idx + 1;
				$view->itemBody .= $client->get();
			}
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'attribute-item' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'attribute-item' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
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

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'attribute' );
		$manager->begin();

		try
		{
			$item = $this->fromArray( $view->param( 'item', [] ) );
			$view->item = $item->getId() ? $item : $manager->saveItem( $item );
			$view->itemBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->itemBody .= $client->save();
			}

			$manager->saveItem( clone $view->item );
			$manager->commit();

			$this->nextAction( $view, $view->param( 'next' ), 'attribute', $view->item->getId(), 'save' );
			return;
		}
		catch( \Aimeos\Admin\JQAdm\Exception $e )
		{
			// fall through to create
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'attribute-item' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'attribute-item' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		$manager->rollback();

		return $this->create();
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
			$params = $this->storeSearchParams( $view->param(), 'attribute' );
			$manager = \Aimeos\MShop\Factory::createManager( $context, 'attribute' );
			$search = $this->initCriteria( $manager->createSearch(), $params );

			$view->items = $manager->searchItems( $search, $this->getDomains(), $total );
			$view->filterAttributes = $manager->getSearchAttributes( true );
			$view->filterOperators = $search->getOperators();
			$view->itemTypes = $this->getTypeItems();
			$view->total = $total;
			$view->itemBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->itemBody .= $client->search();
			}
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'attribute-item' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'attribute-item' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		/** admin/jqadm/attribute/template-list
		 * Relative path to the HTML body template for the attribute list.
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
		 * @since 2017.07
		 * @category Developer
		 */
		$tplconf = 'admin/jqadm/attribute/template-list';
		$default = 'attribute/list-standard.php';

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
		/** admin/jqadm/attribute/decorators/excludes
		 * Excludes decorators added by the "common" option from the attribute JQAdm client
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
		 *  admin/jqadm/attribute/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "client/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/attribute/decorators/global
		 * @see admin/jqadm/attribute/decorators/local
		 */

		/** admin/jqadm/attribute/decorators/global
		 * Adds a list of globally available decorators only to the attribute JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/attribute/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/attribute/decorators/excludes
		 * @see admin/jqadm/attribute/decorators/local
		 */

		/** admin/jqadm/attribute/decorators/local
		 * Adds a list of local decorators only to the attribute JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Attribute\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/attribute/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Attribute\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/attribute/decorators/excludes
		 * @see admin/jqadm/attribute/decorators/global
		 */
		return $this->createSubClient( 'attribute/' . $type, $name );
	}


	/**
	 * Returns the domain names whose items should be fetched too
	 *
	 * @return string[] List of domain names
	 */
	protected function getDomains()
	{
		/** admin/jqadm/attribute/domains
		 * List of domain items that should be fetched along with the attribute
		 *
		 * If you need to display additional content, you can configure your own
		 * list of domains (attribute, media, price, attribute, text, etc. are
		 * domains) whose items are fetched from the storage.
		 *
		 * @param array List of domain names
		 * @since 2017.07
		 * @category Developer
		 */
		$domains = array( 'attribute/property', 'media', 'price', 'text' );

		return $this->getContext()->getConfig()->get( 'admin/jqadm/attribute/domains', $domains );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames()
	{
		/** admin/jqadm/attribute/standard/subparts
		 * List of JQAdm sub-clients rendered within the attribute section
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
		return $this->getContext()->getConfig()->get( 'admin/jqadm/attribute/standard/subparts', [] );
	}


	/**
	 * Returns the available attribute type items
	 *
	 * @return array List of item implementing \Aimeos\MShop\Common\Type\Iface
	 */
	protected function getTypeItems()
	{
		$typeManager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'attribute/type' );

		$search = $typeManager->createSearch()->setSlice( 0, 0x7fffffff );
		$search->setSortations( array( $search->sort( '+', 'attribute.type.code' ) ) );

		return $typeManager->searchItems( $search );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param string[] Data array
	 * @return \Aimeos\MShop\Attribute\Item\Iface New attribute item object
	 */
	protected function fromArray( array $data )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'attribute' );

		if( isset( $data['attribute.id'] ) && $data['attribute.id'] != '' ) {
			$item = $manager->getItem( $data['attribute.id'], $this->getDomains() );
		} else {
			$typeManager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'attribute/type' );
			$item = $manager->createItem( $typeManager->getItem( $data['attribute.typeid'] )->getCode(), $data['attribute.domain'] );
		}

		$item->fromArray( $data );

		return $item;
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Attribute\Item\Iface $item Attribute item object
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Attribute\Item\Iface $item, $copy = false )
	{
		$data = $item->toArray( true );

		if( $copy === true )
		{
			$data['attribute.siteid'] = $this->getContext()->getLocale()->getSiteId();
			$data['attribute.code'] = $data['attribute.code'] . '_copy';
			$data['attribute.id'] = '';
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
		/** admin/jqadm/attribute/template-item
		 * Relative path to the HTML body template for the attribute item.
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
		 * @since 2017.07
		 * @category Developer
		 */
		$tplconf = 'admin/jqadm/attribute/template-item';
		$default = 'attribute/item-standard.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
