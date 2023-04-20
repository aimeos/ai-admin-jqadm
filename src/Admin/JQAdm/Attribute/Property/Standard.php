<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Attribute\Property;

sprintf( 'property' ); // for translation


/**
 * Default implementation of attribute property JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/attribute/property/name
	 * Name of the property subpart used by the JQAdm attribute implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Attribute\Property\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2017.07
	 */


	/**
	 * Adds the required data used in the template
	 *
	 * @param \Aimeos\Base\View\Iface $view View object
	 * @return \Aimeos\Base\View\Iface View object with assigned parameters
	 */
	public function data( \Aimeos\Base\View\Iface $view ) : \Aimeos\Base\View\Iface
	{
		$manager = \Aimeos\MShop::create( $this->context(), 'attribute/property/type' );
		$search = $manager->filter( true )->slice( 0, 10000 )
			->order( ['attribute.property.type.position', 'attribute.property.type.label'] );

		$view->propertyTypes = $manager->search( $search );

		return $view;
	}


	/**
	 * Copies a resource
	 *
	 * @return string|null HTML output
	 */
	public function copy() : ?string
	{
		$view = $this->object()->data( $this->view() );
		$view->propertyData = $this->toArray( $view->item, true );
		$view->propertyBody = parent::copy();

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
		$siteid = $this->context()->locale()->getSiteId();
		$data = $view->param( 'property', [] );

		foreach( $data as $idx => $entry ) {
			$data[$idx]['attribute.lists.siteid'] = $siteid;
		}

		$view->propertyData = $data;
		$view->propertyBody = parent::create();

		return $this->render( $view );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null HTML output
	 */
	public function get() : ?string
	{
		$view = $this->object()->data( $this->view() );
		$view->propertyData = $this->toArray( $view->item );
		$view->propertyBody = parent::get();

		return $this->render( $view );
	}


	/**
	 * Saves the data
	 *
	 * @return string|null HTML output
	 */
	public function save() : ?string
	{
		$view = $this->object()->data( $this->view() );

		$this->fromArray( $view->item, $view->param( 'property', [] ) );
		$view->propertyBody = parent::save();

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
		/** admin/jqadm/attribute/property/decorators/excludes
		 * Excludes decorators added by the "common" option from the attribute JQAdm client
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
		 *  admin/jqadm/attribute/property/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/attribute/property/decorators/global
		 * @see admin/jqadm/attribute/property/decorators/local
		 */

		/** admin/jqadm/attribute/property/decorators/global
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
		 *  admin/jqadm/attribute/property/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/attribute/property/decorators/excludes
		 * @see admin/jqadm/attribute/property/decorators/local
		 */

		/** admin/jqadm/attribute/property/decorators/local
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
		 *  admin/jqadm/attribute/property/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Attribute\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.01
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/attribute/property/decorators/excludes
		 * @see admin/jqadm/attribute/property/decorators/global
		 */
		return $this->createSubClient( 'attribute/property/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/attribute/property/subparts
		 * List of JQAdm sub-clients rendered within the attribute property section
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
		 */
		return $this->context()->config()->get( 'admin/jqadm/attribute/property/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Attribute\Item\Iface $item Attribute item object without referenced domain items
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Attribute\Item\Iface Modified attribute item
	 */
	protected function fromArray( \Aimeos\MShop\Attribute\Item\Iface $item, array $data ) : \Aimeos\MShop\Attribute\Item\Iface
	{
		$manager = \Aimeos\MShop::create( $this->context(), 'attribute/property' );

		$propItems = $item->getPropertyItems( null, false );

		foreach( $data as $entry )
		{
			if( isset( $propItems[$entry['attribute.property.id']] ) )
			{
				$propItem = $propItems[$entry['attribute.property.id']];
				unset( $propItems[$entry['attribute.property.id']] );
			}
			else
			{
				$propItem = $manager->create();
			}

			$propItem->fromArray( $entry, true );
			$item->addPropertyItem( $propItem );
		}

		return $item->deletePropertyItems( $propItems->toArray() );
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Attribute\Item\Iface $item Attribute item object including referenced domain items
	 * @param bool $copy True if items should be copied, false if not
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Attribute\Item\Iface $item, bool $copy = false ) : array
	{
		$siteId = $this->context()->locale()->getSiteId();
		$data = [];

		foreach( $item->getPropertyItems( null, false ) as $item )
		{
			$list = $item->toArray( true );

			if( $copy === true )
			{
				$list['attribute.property.siteid'] = $siteId;
				$list['attribute.property.id'] = '';
			}

			$data[] = $list;
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
		/** admin/jqadm/attribute/property/template-item
		 * Relative path to the HTML body template of the property subpart for attributes.
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
		 * @since 2018.01
		 */
		$tplconf = 'admin/jqadm/attribute/property/template-item';
		$default = 'attribute/item-property';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
