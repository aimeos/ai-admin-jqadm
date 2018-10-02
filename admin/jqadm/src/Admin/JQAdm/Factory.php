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
class Factory
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
	public static function createClient( \Aimeos\MShop\Context\Item\Iface $context, \Aimeos\Bootstrap $aimeos, $type, $name = null )
	{
		if( empty( $type ) ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Admin JQAdm type is empty' ) );
		}

		$parts = explode( '/', $type );

		foreach( $parts as $idx => $part )
		{
			if( ctype_alnum( $part ) === false ) {
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Invalid characters in client name "%1$s"', $type ) );
			}

			$parts[$idx] = ucwords( $part );
		}

		$view = $context->getView();
		$config = $context->getConfig();

		if( $view->access( $config->get( 'admin/jqadm/resource/' . $type . '/groups', [] ) ) !== true ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Not allowed to access JQAdm "%1$s" client', $type ) );
		}


		$factory = '\\Aimeos\\Admin\\JQAdm\\' . implode( '\\', $parts ) . '\\Factory';

		if( class_exists( $factory ) === false ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Class "%1$s" not available', $factory ) );
		}

		$client = @call_user_func_array( array( $factory, 'createClient' ), array( $context, $name ) );

		if( $client === false ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Invalid factory "%1$s"', $factory ) );
		}

		$client->setAimeos( $aimeos );
		$client->setView( $view );

		return $client;
	}

}