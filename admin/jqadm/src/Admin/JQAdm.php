<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin;


/**
 * Common factory for JQAdm clients
 *
 * @package Admin
 * @subpackage JQAdm
 */
class JQAdm
{
	/**
	 * Creates a new client object.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Shop context instance with necessary objects
	 * @param \Aimeos\Bootstrap $aimeos Aimeos object
	 * @param string $path Type of the client, e.g 'product' for \Aimeos\Admin\JQAdm\Product\Standard
	 * @param string|null $name Admin name (default: "Standard")
	 * @return \Aimeos\Admin\JQAdm\Iface admin client implementing \Aimeos\Admin\JQAdm\Iface
	 * @throws \Aimeos\Admin\JQAdm\Exception If requested client implementation couldn't be found or initialisation fails
	 */
	public static function create( \Aimeos\MShop\Context\Item\Iface $context, \Aimeos\Bootstrap $aimeos, $path, $name = null )
	{
		if( empty( $path ) ) {
			throw new \Aimeos\Admin\JQAdm\Exception( $context->translate( 'admin', 'Admin JQAdm type is empty' ) );
		}

		$view = $context->view();
		$config = $context->getConfig();
		$parts = explode( '/', $path );

		foreach( $parts as $idx => $part )
		{
			if( ctype_alnum( $part ) === false )
			{
				$msg = $context->translate( 'admin', 'Invalid characters in client name "%1$s"' );
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, $path ) );
			}

			$parts[$idx] = ucwords( $part );
		}

		if( $view->access( $config->get( 'admin/jqadm/resource/' . $path . '/groups', [] ) ) !== true )
		{
			$msg = $context->translate( 'admin', 'Not allowed to access JQAdm "%1$s" client' );
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, $path ) );
		}

		$factory = '\\Aimeos\\Admin\\JQAdm\\' . implode( '\\', $parts ) . '\\Factory';

		if( class_exists( $factory ) === false )
		{
			$msg = $context->translate( 'admin', 'Class "%1$s" not available' );
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, $factory ) );
		}

		if( ( $client = @call_user_func_array( [$factory, 'create'], [$context, $name] ) ) === false )
		{
			$msg = $context->translate( 'admin', 'Invalid factory "%1$s"' );
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, $factory ) );
		}

		return $client->setAimeos( $aimeos )->setView( $view )->setObject( $client );
	}
}
