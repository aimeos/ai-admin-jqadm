<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Image\Property;

sprintf( 'property' ); // for translation


/**
 * Default implementation of product image JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/image/property/name
	 * Name of the property subpart used by the JQAdm product image implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Product\Image\Property\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2018.04
	 * @category Developer
	 */


	/**
	 * Copies a resource
	 *
	 * @return string HTML output
	 */
	public function copy()
	{
		$view = $this->addViewData( $this->getView() );

		$view->imageData = $this->toArray( $view->item, $view->get( 'imageData', [] ), true );
		$view->propertyBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->propertyBody .= $client->copy();
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
		$view = $this->addViewData( $this->getView() );
		$siteid = $this->getContext()->getLocale()->getSiteId();
		$data = $view->get( 'imageData', [] );

		foreach( $data as $index => $entry )
		{
			foreach( $view->value( $entry, 'property', [] ) as $idx => $y ) {
				$data[$index]['property'][$idx]['product.lists.siteid'] = $siteid;
			}
		}

		$view->propertyData = $data;
		$view->propertyBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->propertyBody .= $client->create();
		}

		return $this->render( $view );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string HTML output
	 */
	public function get()
	{
		$view = $this->addViewData( $this->getView() );

		$view->imageData = $this->toArray( $view->item, $view->get( 'imageData', [] ) );
		$view->propertyBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->propertyBody .= $client->get();
		}

		return $this->render( $view );
	}


	/**
	 * Saves the data
	 */
	public function save()
	{
		$view = $this->getView();

		$view->item = $this->fromArray( $view->item, $view->param( 'image', [] ) );
		$view->propertyBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->propertyBody .= $client->save();
		}
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
		/** admin/jqadm/product/image/property/decorators/excludes
		 * Excludes decorators added by the "common" option from the product JQAdm client
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
		 *  admin/jqadm/product/image/property/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/image/property/decorators/global
		 * @see admin/jqadm/product/image/property/decorators/local
		 */

		/** admin/jqadm/product/image/property/decorators/global
		 * Adds a list of globally available decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/image/property/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/image/property/decorators/excludes
		 * @see admin/jqadm/product/image/property/decorators/local
		 */

		/** admin/jqadm/product/image/property/decorators/local
		 * Adds a list of local decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Product\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/image/property/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/image/property/decorators/excludes
		 * @see admin/jqadm/product/image/property/decorators/global
		 */
		return $this->createSubClient( 'product/image/property/' . $type, $name );
	}


	/**
	 * Adds the required data used in the product template
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @return \Aimeos\MW\View\Iface View object with assigned parameters
	 */
	protected function addViewData( \Aimeos\MW\View\Iface $view )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'media/property/type' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'media.property.type.domain', 'product' ) );
		$search->setSlice( 0, 0x7fffffff );

		$view->propertyTypes = $manager->searchItems( $search );

		return $view;
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames()
	{
		/** admin/jqadm/product/image/property/standard/subparts
		 * List of JQAdm sub-clients rendered within the product image property section
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
		 * @since 2018.01
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/product/image/property/standard/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object without referenced domain items
	 * @param string[] $data Data array
	 */
	protected function fromArray( \Aimeos\MShop\Product\Item\Iface $item, array $data )
	{
		$propManager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'media/property' );
		$typeManager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'media/property/type' );
		$index = 0;

		foreach( $item->getRefItems( 'media', null, null, false ) as $refItem )
		{
			$propItems = $refItem->getPropertyItems( null, false );

			foreach( (array) $this->getValue( $data, $index . '/property', [] ) as $entry )
			{
				if( isset( $propItems[$entry['media.property.id']] ) )
				{
					$propItem = $propItems[$entry['media.property.id']];
					unset( $propItems[$entry['media.property.id']] );
				}
				else
				{
					$typeCode = $typeManager->getItem( $entry['media.property.typeid'] )->getCode();
					$propItem = $propManager->createItem( $typeCode, 'media' );
				}

				$propItem->fromArray( $entry );
				$refItem->addPropertyItem( $propItem );
			}

			$refItem->deletePropertyItems( $propItems );
			$index++;
		}

		return $item;
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object including referenced domain items
	 * @param array $data Associative list of media data
	 * @param boolean $copy True if items should be copied, false if not
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Product\Item\Iface $item, array $data, $copy = false )
	{
		$idx = 0;
		$siteId = $this->getContext()->getLocale()->getSiteId();

		foreach( $item->getRefItems( 'media', null, null, false ) as $mediaItem )
		{
			foreach( $mediaItem->getPropertyItems( null, false )  as $propItem )
			{
				$list = $propItem->toArray( true );

				if( $copy === true )
				{
					$list['media.property.siteid'] = $siteId;
					$list['media.property.id'] = '';
				}

				$data[$idx]['property'][] = $list;
			}

			$idx++;
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
		/** admin/jqadm/product/image/property/template-item
		 * Relative path to the HTML body template of the image subpart for products.
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
		$tplconf = 'admin/jqadm/product/image/property/template-item';
		$default = 'product/item-image-property-standard.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}