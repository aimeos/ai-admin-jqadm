<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Dashboard\Order\Quick\Countunfinished;

sprintf( 'quick/countunfinished' ); // for translation


/**
 * Default implementation of dashboard quick/countunfinished JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/dashboard/order/quick/countunfinished/name
	 * Name of the order quick/countunfinished subpart used by the JQAdm dashboard implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Dashboard\Order\Quick\Countorder\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2021.04
	 * @category Developer
	 */


	/**
	 * Returns a list of resource according to the conditions
	 *
	 * @return string Output to display
	 */
	public function search() : ?string
	{
		$view = $this->view();
		$view->quickCountorderBody = parent::search();

		/** admin/jqadm/dashboard/order/quick/countunfinished/template-item
		 * Relative path to the HTML body template of the order per quick/countunfinished subpart for the dashboard.
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
		 * @since 2021.04
		 * @category Developer
		 */
		$tplconf = 'admin/jqadm/dashboard/order/quick/countunfinished/template-item';
		$default = 'dashboard/item-order-quick-countunfinished-standard';

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
		/** admin/jqadm/dashboard/order/quick/countunfinished/decorators/excludes
		 * Excludes decorators added by the "common" option from the dashboard JQAdm client
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
		 *  admin/jqadm/dashboard/order/quick/countunfinished/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2021.04
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/dashboard/order/quick/countunfinished/decorators/global
		 * @see admin/jqadm/dashboard/order/quick/countunfinished/decorators/local
		 */

		/** admin/jqadm/dashboard/order/quick/countunfinished/decorators/global
		 * Adds a list of globally available decorators only to the dashboard JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/dashboard/order/quick/countunfinished/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2021.04
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/dashboard/order/quick/countunfinished/decorators/excludes
		 * @see admin/jqadm/dashboard/order/quick/countunfinished/decorators/local
		 */

		/** admin/jqadm/dashboard/order/quick/countunfinished/decorators/local
		 * Adds a list of local decorators only to the dashboard JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Dashboard\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/dashboard/order/quick/countunfinished/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Dashboard\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2021.04
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/dashboard/order/quick/countunfinished/decorators/excludes
		 * @see admin/jqadm/dashboard/order/quick/countunfinished/decorators/global
		 */
		return $this->createSubClient( 'dashboard/order/quick/countunfinished/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/dashboard/order/quick/countunfinished/subparts
		 * List of JQAdm sub-clients rendered within the dashboard quick/countunfinished section
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
		 * @since 2021.04
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/dashboard/order/quick/countunfinished/subparts', [] );
	}
}
