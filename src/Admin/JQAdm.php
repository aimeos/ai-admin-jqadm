<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
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
	use \Aimeos\MShop\PolicyTrait;

	/** @var array<string, \Aimeos\Admin\JQAdm\Iface|null> */
	private static array $objects = [];


	/**
	 * Creates a new client object.
	 *
	 * The user must be allowed to perform the "get" action for the client path to open
	 * the client. Saving and deleting items requires the "save" and "delete" actions too.
	 *
	 * @param \Aimeos\MShop\ContextIface $context Shop context instance with necessary objects
	 * @param \Aimeos\Bootstrap $aimeos Aimeos object
	 * @param string $path Type of the client, e.g 'product' for \Aimeos\Admin\JQAdm\Product\Standard
	 * @param string|null $name Admin name (default: "Standard")
	 * @return \Aimeos\Admin\JQAdm\Iface admin client implementing \Aimeos\Admin\JQAdm\Iface
	 * @throws \Aimeos\Admin\JQAdm\Exception If requested client implementation couldn't be found or initialisation fails
	 */
	public static function create( \Aimeos\MShop\ContextIface $context, \Aimeos\Bootstrap $aimeos,
		string $path, ?string $name = null ) : \Aimeos\Admin\JQAdm\Iface
	{
		if( empty( $path ) ) {
			throw new \Aimeos\Admin\JQAdm\Exception( 'Component path is empty', 400 );
		}

		$context = static::withPolicy( $context );

		static::check( $context, $path, 'get' );

		$view = $context->view();
		$config = $context->config();

		if( empty( $name ) ) {
			$name = $config->get( 'admin/jqadm/' . $path . '/name', 'Standard' );
		}

		$interface = '\\Aimeos\\Admin\JQAdm\\Iface';
		$classname = '\\Aimeos\\Admin\\JQAdm\\' . str_replace( '/', '\\', ucwords( $path, '/' ) ) . '\\' . $name;

		if( class_exists( $classname ) === false ) {
			throw new \LogicException( sprintf( 'Class "%1$s" not found', $classname ), 404 );
		}

		$client = self::createComponent( $context, $classname, $interface, $path );
		$client = new \Aimeos\Admin\JQAdm\Common\Decorator\Policy( $client, $context, $path );

		// @phpstan-ignore return.type, method.notFound
		return $client->setAimeos( $aimeos )->setView( $view )->setObject( $client );
	}


	/**
	 * Injects a client object.
	 * The object is returned via create() if an instance of the class
	 * with the name name is requested.
	 *
	 * @param string $classname Full name of the class for which the object should be returned
	 * @param \Aimeos\Admin\JQAdm\Iface|null $client ExtJS client object
	 */
	public static function inject( string $classname, ?\Aimeos\Admin\JQAdm\Iface $client = null ) : void
	{
		self::$objects['\\' . ltrim( $classname, '\\' )] = $client;
	}


	/**
	 * Returns the default authorization policy of the JQAdm interface
	 *
	 * The interface uses it if the passed context contains no policy, so it also protects
	 * shared items like prices of domains the user isn't allowed to access. Host applications
	 * can use their own policy by setting it in the context with $context->setPolicy().
	 *
	 * @return \Aimeos\MShop\PolicyIface Authorization policy
	 */
	public static function policy() : \Aimeos\MShop\PolicyIface
	{
		return new \Aimeos\MShop\Policy\Groups( static::prefix() );
	}


	/**
	 * Creates the exception of the interface
	 *
	 * @param string $msg Translated error message
	 * @param int $code Error code, e.g. 403 for actions which aren't allowed
	 * @param \Throwable|null $previous Previous exception
	 * @return \Exception Exception with the passed code
	 */
	protected static function exception( string $msg, int $code, ?\Throwable $previous = null ) : \Exception
	{
		return new \Aimeos\Admin\JQAdm\Exception( $msg, $code, previous: $previous );
	}


	/**
	 * Returns the configuration key prefix of the resources protected by the interface
	 *
	 * @return string Configuration key prefix
	 */
	protected static function prefix() : string
	{
		return 'admin/jqadm/resource';
	}


	/**
	 * Adds the decorators to the client object.
	 *
	 * @param \Aimeos\MShop\ContextIface $context Context instance with necessary objects
	 * @param \Aimeos\Admin\JQAdm\Iface $client Admin object
	 * @param string $path Path of the client in lower case, e.g. "catalog/detail"
	 * @return \Aimeos\Admin\JQAdm\Iface Admin object
	 */
	protected static function addComponentDecorators( \Aimeos\MShop\ContextIface $context,
		\Aimeos\Admin\JQAdm\Iface $client, string $path ) : \Aimeos\Admin\JQAdm\Iface
	{
		$config = $context->config();
		$localClass = str_replace( '/', '\\', ucwords( $path, '/' ) );

		$classprefix = '\\Aimeos\\Admin\\JQAdm\\' . $localClass . '\\Decorator\\';
		$decorators = array_reverse( (array) $config->get( 'admin/jqadm/' . $path . '/decorators/local', [] ) );
		$client = self::addDecorators( $context, $client, $decorators, $classprefix );

		$classprefix = '\\Aimeos\\Admin\\JQAdm\\Common\\Decorator\\';
		$decorators = array_reverse( (array) $config->get( 'admin/jqadm/' . $path . '/decorators/global', [] ) );
		$client = self::addDecorators( $context, $client, $decorators, $classprefix );

		/** admin/jqadm/common/decorators/default
		 * Configures the list of decorators applied to all jqadm clients
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to configure a list of decorator names that should
		 * be wrapped around the original instance of all created clients:
		 *
		 *  admin/jqadm/common/decorators/default = array( 'decorator1', 'decorator2' )
		 *
		 * This would wrap the decorators named "decorator1" and "decorator2" around
		 * all client instances in that order. The decorator classes would be
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" and
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator2".
		 *
		 * @param array List of decorator names
		 * @since 2014.03
		 */
		$decorators = array_reverse( (array) $config->get( 'admin/jqadm/common/decorators/default', [] ) );
		$excludes = $config->get( 'admin/jqadm/' . $path . '/decorators/excludes', [] );

		foreach( $decorators as $key => $name )
		{
			if( in_array( $name, (array) $excludes ) ) {
				unset( $decorators[$key] );
			}
		}

		$classprefix = '\\Aimeos\\Admin\\JQAdm\\Common\\Decorator\\';
		$client = self::addDecorators( $context, $client, $decorators, $classprefix );

		return $client;
	}


	/**
	 * Adds the decorators to the client object.
	 *
	 * @param \Aimeos\MShop\ContextIface $context Context instance with necessary objects
	 * @param \Aimeos\Admin\JQAdm\Iface $client Admin object
	 * @param array $decorators List of decorator name that should be wrapped around the client
	 * @param string $classprefix Decorator class prefix, e.g. "\Aimeos\Admin\JQAdm\Catalog\Decorator\"
	 * @return \Aimeos\Admin\JQAdm\Iface Admin object
	 * @throws \LogicException If class can't be instantiated
	 */
	protected static function addDecorators( \Aimeos\MShop\ContextIface $context,
		\Aimeos\Admin\JQAdm\Iface $client, array $decorators, string $classprefix ) : \Aimeos\Admin\JQAdm\Iface
	{
		$interface = \Aimeos\Admin\JQAdm\Common\Decorator\Iface::class;

		foreach( $decorators as $name )
		{
			if( ctype_alnum( $name ) === false ) {
				// @phpstan-ignore argument.type
				throw new \LogicException( sprintf( 'Invalid class name "%1$s"', $name ), 400 );
			}

			$client = \Aimeos\Utils::create( $classprefix . $name, [$client, $context], $interface );
		}

		// @phpstan-ignore return.type
		return $client;
	}


	/**
	 * Creates a client object.
	 *
	 * @param \Aimeos\MShop\ContextIface $context Context instance with necessary objects
	 * @param string $classname Name of the client class
	 * @param string $interface Name of the client interface
	 * @param string $path Type of the client, e.g 'product' for \Aimeos\Admin\JQAdm\Product\Standard
	 * @return \Aimeos\Admin\JQAdm\Iface Admin object
	 * @throws \Aimeos\Admin\JQAdm\Exception If client couldn't be found or doesn't implement the interface
	 */
	protected static function createComponent( \Aimeos\MShop\ContextIface $context,
		string $classname, string $interface, string $path ) : \Aimeos\Admin\JQAdm\Iface
	{
		if( isset( self::$objects[$classname] ) ) {
			return self::$objects[$classname];
		}

		$client = \Aimeos\Utils::create( $classname, [$context], $interface );

		// @phpstan-ignore argument.type
		return self::addComponentDecorators( $context, $client, $path );
	}
}
