<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2024
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Common\Admin\Factory;


/**
 * Common factory interface for all JQAdm client classes.
 *
 * @package Admin
 * @subpackage JQAdm
 */
interface Iface
	extends \Aimeos\Admin\JQAdm\Iface
{
	/**
	 * Initializes the class instance.
	 *
	 * @param \Aimeos\MShop\ContextIface $context Context object
	 */
	public function __construct( \Aimeos\MShop\ContextIface $context );
}
