<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm;


/**
 * Common factory for JQAdm clients.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Factory extends \Aimeos\Admin\JQAdm
{
	/**
	 * Creates a new client object.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Shop context instance with necessary objects
	 * @param \Aimeos\Bootstrap $aimeos Aimeos object
	 * @param string $type Type of the client, e.g 'product' for \Aimeos\Admin\JQAdm\Product\Standard
	 * @param string|null $name Admin name (default: "Standard")
	 * @return \Aimeos\Admin\JQAdm\Iface admin client implementing \Aimeos\Admin\JQAdm\Iface
	 * @throws \Aimeos\Admin\JQAdm\Exception If requested client implementation couldn't be found or initialisation fails
	 */
	public static function create( \Aimeos\MShop\Context\Item\Iface $context, \Aimeos\Bootstrap $aimeos, $type, $name = null )
	{
		return parent::create( $context, $aimeos, $type, $name );
	}
}