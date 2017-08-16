<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
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
	 * @param array List of file system paths where the templates are stored
	 * @param string $type Type of the client, e.g 'product' for \Aimeos\Admin\JQAdm\Product\Standard
	 * @param string|null $name Admin name (default: "Standard")
	 * @return \Aimeos\Admin\JQAdm\Iface admin client implementing \Aimeos\Admin\JQAdm\Iface
	 * @throws \Aimeos\Admin\JQAdm\Exception If requested client implementation couldn't be found or initialisation fails
	 */
	public static function createClient( \Aimeos\MShop\Context\Item\Iface $context, array $templatePaths, $type, $name = null )
	{
		$config = $context->getConfig();
		$admin = $context->getView()->access( 'admin' );

		if( empty( $type ) ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Admin JQAdm type is empty' ) );
		}

		if( !in_array( $type, $config->get( 'admin/jqadm/resources', [] ) )
			&& !in_array( $type, $config->get( 'admin/jqadm/resources-admin', [] ) )
		) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Not allowed to access JQAdm "%1$s" client', $type ) );
		}

		if( $admin === false && in_array( $type, $config->get( 'admin/jqadm/resources-admin', [] ) ) ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Not allowed to access JQAdm "%1$s" client', $type ) );
		}


		$parts = explode( '/', $type );

		foreach( $parts as $idx => $part )
		{
			if( ctype_alnum( $part ) === false ) {
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Invalid characters in client name "%1$s"', $type ) );
			}

			$parts[$idx] = ucwords( $part );
		}


		$factory = '\\Aimeos\\Admin\\JQAdm\\' . implode( '\\', $parts ) . '\\Factory';

		if( class_exists( $factory ) === false ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Class "%1$s" not available', $factory ) );
		}

		$client = @call_user_func_array( array( $factory, 'createClient' ), array( $context, $templatePaths, $name ) );

		if( $client === false ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Invalid factory "%1$s"', $factory ) );
		}

		$client->setView( $context->getView() );

		return $client;
	}

}