<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Customer\Property;

sprintf( 'property' ); // for translation


/**
 * Default implementation of customer property JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/customer/property/name
	 * Name of the property subpart used by the JQAdm customer implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Customer\Property\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2017.07
	 * @category Developer
	 */


	/**
	 * Adds the required data used in the template
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @return \Aimeos\MW\View\Iface View object with assigned parameters
	 */
	public function data( \Aimeos\MW\View\Iface $view ) : \Aimeos\MW\View\Iface
	{
		$manager = \Aimeos\MShop::create( $this->getContext(), 'customer/property/type' );

		$search = $manager->filter( true )->slice( 0, 10000 );
		$search->setSortations( [$search->sort( '+', 'customer.property.type.position' )] );

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
		$view = $this->getObject()->data( $this->view() );
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
		$view = $this->getObject()->data( $this->view() );
		$siteid = $this->getContext()->getLocale()->getSiteId();
		$data = $view->param( 'property', [] );

		foreach( $data as $idx => $entry ) {
			$data[$idx]['customer.lists.siteid'] = $siteid;
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
		$view = $this->getObject()->data( $this->view() );
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
		$view = $this->view();

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
		/** admin/jqadm/customer/property/decorators/excludes
		 * Excludes decorators added by the "common" option from the customer JQAdm client
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
		 *  admin/jqadm/customer/property/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/customer/property/decorators/global
		 * @see admin/jqadm/customer/property/decorators/local
		 */

		/** admin/jqadm/customer/property/decorators/global
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
		 *  admin/jqadm/customer/property/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/customer/property/decorators/excludes
		 * @see admin/jqadm/customer/property/decorators/local
		 */

		/** admin/jqadm/customer/property/decorators/local
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
		 *  admin/jqadm/customer/property/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Customer\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/customer/property/decorators/excludes
		 * @see admin/jqadm/customer/property/decorators/global
		 */
		return $this->createSubClient( 'customer/property/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/customer/property/subparts
		 * List of JQAdm sub-clients rendered within the customer property section
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
		 * @since 2018.07
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/customer/property/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Customer\Item\Iface $item Customer item object without referenced domain items
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Customer\Item\Iface Modified coupon item
	 */
	protected function fromArray( \Aimeos\MShop\Customer\Item\Iface $item, array $data ) : \Aimeos\MShop\Customer\Item\Iface
	{
		$manager = \Aimeos\MShop::create( $this->getContext(), 'customer/property' );

		$propItems = $item->getPropertyItems( null, false );

		foreach( $data as $entry )
		{
			if( isset( $propItems[$entry['customer.property.id']] ) )
			{
				$propItem = $propItems[$entry['customer.property.id']];
				unset( $propItems[$entry['customer.property.id']] );
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
	 * @param \Aimeos\MShop\Customer\Item\Iface $item Customer item object including referenced domain items
	 * @param bool $copy True if items should be copied, false if not
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Customer\Item\Iface $item, bool $copy = false ) : array
	{
		$siteId = $this->getContext()->getLocale()->getSiteId();
		$data = [];

		foreach( $item->getPropertyItems( null, false ) as $item )
		{
			$list = $item->toArray( true );

			if( $copy === true )
			{
				$list['customer.property.siteid'] = $siteId;
				$list['customer.property.id'] = '';
			}

			$data[] = $list;
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
		/** admin/jqadm/customer/property/template-item
		 * Relative path to the HTML body template of the property subpart for customers.
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
		 * @since 2018.07
		 * @category Developer
		 */
		$tplconf = 'admin/jqadm/customer/property/template-item';
		$default = 'customer/item-property-standard';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
