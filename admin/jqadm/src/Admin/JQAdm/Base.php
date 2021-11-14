<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm;

sprintf( 'type' ); // for translation


/**
 * Common abstract class for all admin client classes.
 *
 * @package Admin
 * @subpackage JQAdm
 */
abstract class Base
	implements \Aimeos\Admin\JQAdm\Iface, \Aimeos\MW\Macro\Iface
{
	use \Aimeos\MW\Macro\Traits;


	private $view;
	private $aimeos;
	private $context;
	private $subclients;
	private $object;


	/**
	 * Initializes the class instance.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 */
	public function __construct( \Aimeos\MShop\Context\Item\Iface $context )
	{
		$this->context = $context;
	}


	/**
	 * Adds the required data used in the attribute template
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @return \Aimeos\MW\View\Iface View object with assigned parameters
	 */
	public function data( \Aimeos\MW\View\Iface $view ) : \Aimeos\MW\View\Iface
	{
		return $view;
	}


	/**
	 * Returns the Aimeos bootstrap object
	 *
	 * @return \Aimeos\Bootstrap The Aimeos bootstrap object
	 */
	public function getAimeos() : \Aimeos\Bootstrap
	{
		if( !isset( $this->aimeos ) ) {
			throw new \Aimeos\Admin\JQAdm\Exception( $this->context->translate( 'admin', 'Aimeos object not available' ) );
		}

		return $this->aimeos;
	}


	/**
	 * Sets the Aimeos bootstrap object
	 *
	 * @param \Aimeos\Bootstrap $aimeos The Aimeos bootstrap object
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	public function setAimeos( \Aimeos\Bootstrap $aimeos ) : \Aimeos\Admin\JQAdm\Iface
	{
		$this->aimeos = $aimeos;
		return $this;
	}


	/**
	 * Makes the outer decorator object available to inner objects
	 *
	 * @param \Aimeos\Admin\JQAdm\Iface $object Outmost object
	 * @return \Aimeos\Admin\JQAdm\Iface Same object for fluent interface
	 */
	public function setObject( \Aimeos\Admin\JQAdm\Iface $object ) : \Aimeos\Admin\JQAdm\Iface
	{
		$this->object = $object;
		return $this;
	}


	/**
	 * Returns the view object that will generate the admin output.
	 *
	 * @return \Aimeos\MW\View\Iface The view object which generates the admin output
	 */
	protected function view() : \Aimeos\MW\View\Iface
	{
		if( !isset( $this->view ) ) {
			throw new \Aimeos\Admin\JQAdm\Exception( $this->context->translate( 'admin', 'No view available' ) );
		}

		return $this->view;
	}


	/**
	 * Sets the view object that will generate the admin output.
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the admin output
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	public function setView( \Aimeos\MW\View\Iface $view ) : \Aimeos\Admin\JQAdm\Iface
	{
		$this->view = $view;
		return $this;
	}


	/**
	 * Copies a resource
	 *
	 * @return string|null Output to display
	 */
	public function copy() : ?string
	{
		$body = null;
		$view = $this->view();

		foreach( $this->getSubClients() as $idx => $client )
		{
			$view->tabindex = ++$idx + 1;
			$body .= $client->copy();
		}

		return $body;
	}


	/**
	 * Creates a new resource
	 *
	 * @return string|null Output to display
	 */
	public function create() : ?string
	{
		$body = null;
		$view = $this->view();

		foreach( $this->getSubClients() as $idx => $client )
		{
			$view->tabindex = ++$idx + 1;
			$body .= $client->create();
		}

		return $body;
	}


	/**
	 * Deletes a resource
	 *
	 * @return string|null Output to display
	 */
	public function delete() : ?string
	{
		$body = null;

		foreach( $this->getSubClients() as $client ) {
			$body .= $client->delete();
		}

		return $body;
	}


	/**
	 * Exports a resource
	 *
	 * @return string|null Output to display
	 */
	public function export() : ?string
	{
		$body = null;

		foreach( $this->getSubClients() as $client ) {
			$body .= $client->export();
		}

		return $body;
	}


	/**
	 * Returns a resource
	 *
	 * @return string|null Output to display
	 */
	public function get() : ?string
	{
		$body = null;
		$view = $this->view();

		foreach( $this->getSubClients() as $idx => $client )
		{
			$view->tabindex = ++$idx + 1;
			$body .= $client->get();
		}

		return $body;
	}


	/**
	 * Imports a resource
	 *
	 * @return string|null Output to display
	 * @deprecated 2021.01
	 */
	public function import() : ?string
	{
		$body = null;

		foreach( $this->getSubClients() as $client ) {
			$body .= $client->import();
		}

		return null;
	}


	/**
	 * Saves the data
	 *
	 * @return string|null Output to display
	 */
	public function save() : ?string
	{
		$body = null;

		foreach( $this->getSubClients() as $client ) {
			$body .= $client->save();
		}

		return $body;
	}


	/**
	 * Returns a list of resource according to the conditions
	 *
	 * @return string|null Output to display
	 */
	public function search() : ?string
	{
		$body = null;

		foreach( $this->getSubClients() as $client ) {
			$body .= $client->search();
		}

		return $body;
	}


	/**
	 * Returns the PSR-7 response object for the request
	 *
	 * @return \Psr\Http\Message\ResponseInterface Response object
	 */
	public function response() : \Psr\Http\Message\ResponseInterface
	{
		return $this->view()->response();
	}


	/**
	 * Adds the decorators to the client object
	 *
	 * @param \Aimeos\Admin\JQAdm\Iface $client Admin object
	 * @param array $decorators List of decorator name that should be wrapped around the client
	 * @param string $classprefix Decorator class prefix, e.g. "\Aimeos\Admin\JQAdm\Catalog\Decorator\"
	 * @return \Aimeos\Admin\JQAdm\Iface Admin object
	 */
	protected function addDecorators( \Aimeos\Admin\JQAdm\Iface $client, array $decorators, string $classprefix ) : \Aimeos\Admin\JQAdm\Iface
	{
		foreach( $decorators as $name )
		{
			$classname = $classprefix . $name;

			if( ctype_alnum( $name ) === false )
			{
				$msg = $this->context->translate( 'admin', 'Invalid class name "%1$s"' );
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, $classname ) );
			}

			if( class_exists( $classname ) === false )
			{
				$msg = $this->context->translate( 'admin', 'Class "%1$s" not found' );
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, $classname ) );
			}

			$client = new $classname( $client, $this->context );

			\Aimeos\MW\Common\Base::checkClass( '\\Aimeos\\Admin\\JQAdm\\Common\\Decorator\\Iface', $client );
		}

		return $client;
	}


	/**
	 * Adds the decorators to the client object
	 *
	 * @param \Aimeos\Admin\JQAdm\Iface $client Admin object
	 * @param string $path Admin string in lower case, e.g. "catalog/detail/basic"
	 * @return \Aimeos\Admin\JQAdm\Iface Admin object
	 */
	protected function addClientDecorators( \Aimeos\Admin\JQAdm\Iface $client, string $path ) : \Aimeos\Admin\JQAdm\Iface
	{
		if( !is_string( $path ) || $path === '' )
		{
			$msg = $this->context->translate( 'admin', 'Invalid domain "%1$s"' );
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, $path ) );
		}

		$localClass = str_replace( '/', '\\', ucwords( $path, '/' ) );
		$config = $this->context->getConfig();

		$classprefix = '\\Aimeos\\Admin\\JQAdm\\Common\\Decorator\\';
		$decorators = $config->get( 'admin/jqadm/' . $path . '/decorators/global', [] );
		$client = $this->addDecorators( $client, $decorators, $classprefix );

		$classprefix = '\\Aimeos\\Admin\\JQAdm\\' . $localClass . '\\Decorator\\';
		$decorators = $config->get( 'admin/jqadm/' . $path . '/decorators/local', [] );
		$client = $this->addDecorators( $client, $decorators, $classprefix );

		return $client;
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $path Name of the sub-part in lower case (can contain a path like catalog/filter/tree)
	 * @param string|null $name Name of the implementation, will be from configuration (or Default) if null
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-part object
	 */
	protected function createSubClient( string $path, string $name = null ) : \Aimeos\Admin\JQAdm\Iface
	{
		$path = strtolower( $path );

		if( $name === null ) {
			$name = $this->context->getConfig()->get( 'admin/jqadm/' . $path . '/name', 'Standard' );
		}

		if( empty( $name ) || ctype_alnum( $name ) === false )
		{
			$msg = $this->context->translate( 'admin', 'Invalid characters in client name "%1$s"' );
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, $name ) );
		}

		$subnames = str_replace( '/', '\\', ucwords( $path, '/' ) );
		$classname = '\\Aimeos\\Admin\\JQAdm\\' . $subnames . '\\' . $name;

		if( class_exists( $classname ) === false )
		{
			$msg = $this->context->translate( 'admin', 'Class "%1$s" not available' );
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, $classname ) );
		}

		$object = new $classname( $this->context );
		$object = \Aimeos\MW\Common\Base::checkClass( '\\Aimeos\\Admin\\JQAdm\\Iface', $object );
		$object = $this->addClientDecorators( $object, $path );

		return $object->setObject( $object )->setAimeos( $this->aimeos )->setView( $this->view );
	}


	/**
	 * Returns the value for the given key in the array
	 *
	 * @param array $values Multi-dimensional associative list of key/value pairs
	 * @param string $key Parameter key like "name" or "list/test" for associative arrays
	 * @param mixed $default Returned value if no one for key is available
	 * @return mixed Value from the array or default value if not present in array
	 */
	protected function getValue( array $values, $key, $default = null )
	{
		foreach( explode( '/', trim( $key, '/' ) ) as $part )
		{
			if( array_key_exists( $part, $values ) ) {
				$values = $values[$part];
			} else {
				return $default;
			}
		}

		return $values;
	}


	/**
	 * Returns the known client parameters and their values
	 *
	 * @param array $names List of parameter names
	 * @return array Associative list of parameters names as key and their values
	 */
	protected function getClientParams( $names = ['id', 'resource', 'site', 'locale'] ) : array
	{
		$list = [];

		foreach( $names as $name )
		{
			if( ( $val = $this->view->param( $name ) ) !== null && !is_array( $val ) ) {
				$list[$name] = $val;
			}
		}

		return $list;
	}


	/**
	 * Returns the context object.
	 *
	 * @return \Aimeos\MShop\Context\Item\Iface Context object
	 */
	protected function getContext() : \Aimeos\MShop\Context\Item\Iface
	{
		return $this->context;
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of admin client names
	 */
	abstract protected function getSubClientNames() : array;


	/**
	 * Returns the available class names without namespace that are stored in the given path
	 *
	 * @param string $relpath Path relative to the include paths
	 * @param string[] $excludes List of file names to execlude
	 * @return string[] List of available class names
	 */
	protected function getClassNames( string $relpath, array $excludes = ['Base.php', 'Iface.php', 'Example.php', 'None.php'] ) : array
	{
		$list = [];

		foreach( $this->getAimeos()->getIncludePaths() as $path )
		{
			$path .= DIRECTORY_SEPARATOR . $relpath;

			if( is_dir( $path ) )
			{
				foreach( new \DirectoryIterator( $path ) as $entry )
				{
					if( $entry->isFile() && !in_array( $entry->getFileName(), $excludes ) ) {
						$list[] = pathinfo( $entry->getFileName(), PATHINFO_FILENAME );
					}
				}
			}
		}

		sort( $list );
		return $list;
	}


	/**
	 * Returns the array of criteria conditions based on the given parameters
	 *
	 * @param array $params List of criteria data with condition, sorting and paging
	 * @return array Multi-dimensional associative list of criteria conditions
	 */
	protected function getCriteriaConditions( array $params ) : array
	{
		$expr = [];

		if( isset( $params['key'] ) )
		{
			foreach( (array) $params['key'] as $idx => $key )
			{
				if( $key != '' && isset( $params['op'][$idx] ) && $params['op'][$idx] != ''
					&& isset( $params['val'][$idx] ) && $params['val'][$idx] != ''
				) {
					$expr[] = [$params['op'][$idx] => [$key => $params['val'][$idx]]];
				}
			}

			if( !empty( $expr ) ) {
				$expr = ['&&' => $expr];
			}
		}

		return $expr;
	}


	/**
	 * Returns the outer decoratorator of the object
	 *
	 * @return \Aimeos\Admin\JQAdm\Iface Outmost object
	 */
	protected function getObject() : Iface
	{
		if( isset( $this->object ) ) {
			return $this->object;
		}

		return $this;
	}


	/**
	 * Returns the configured sub-clients or the ones named in the default parameter if none are configured.
	 *
	 * @return array List of sub-clients implementing \Aimeos\Admin\JQAdm\Iface ordered in the same way as the names
	 */
	protected function getSubClients() : array
	{
		if( !isset( $this->subclients ) )
		{
			$this->subclients = [];

			foreach( $this->getSubClientNames() as $name ) {
				$this->subclients[] = $this->getSubClient( $name );
			}
		}

		return $this->subclients;
	}


	/**
	 * Initializes the criteria object based on the given parameter
	 *
	 * @param \Aimeos\MW\Criteria\Iface $criteria Criteria object
	 * @param array $params List of criteria data with condition, sorting and paging
	 * @return \Aimeos\MW\Criteria\Iface Initialized criteria object
	 */
	protected function initCriteria( \Aimeos\MW\Criteria\Iface $criteria, array $params ) : \Aimeos\MW\Criteria\Iface
	{
		if( isset( $params['sort'] ) && !empty( $params['sort'] ) ) {
			$criteria->order( $params['sort'] );
		}

		return $criteria->slice( $params['page']['offset'] ?? 0, $params['page']['limit'] ?? 25 )
			->add( $criteria->parse( $this->getCriteriaConditions( $params['filter'] ?? [] ) ) );
	}


	/**
	 * Flattens the nested configuration array
	 *
	 * @param array $config Multi-dimensional list of key/value pairs
	 * @param string $path Path of keys separated by slashes (/) to add new values for
	 * @return array List of arrays with "key" and "val" keys
	 */
	protected function flatten( array $config, string $path = '' ) : array
	{
		$list = [];

		foreach( $config as $key => $val )
		{
			if( is_array( $val ) ) {
				$list = array_merge( $list, $this->flatten( $val, $path . '/' . $key ) );
			} else {
				$list[] = ['key' => trim( $path . '/' . $key, '/' ), 'val' => $val];
			}
		}

		return $list;
	}


	/**
	 * Writes the exception details to the log
	 *
	 * @param \Exception $e Exception object
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	protected function log( \Exception $e ) : Iface
	{
		$msg = $e->getMessage() . PHP_EOL;

		if( $e instanceof \Aimeos\Admin\JQAdm\Exception ) {
			$msg .= print_r( $e->getDetails(), true ) . PHP_EOL;
		}

		$this->context->getLogger()->log( $msg . $e->getTraceAsString(), \Aimeos\MW\Logger\Base::ERR, 'admin/jqadm' );

		return $this;
	}


	/**
	 * Returns a map of code/item pairs
	 *
	 * @param \Aimeos\MShop\Common\Item\Type\Iface[] $items Associative list of type items
	 * @return \Aimeos\MShop\Common\Item\Type\Iface[] Associative list of codes as keys and items as values
	 * @deprecated 2021.01
	 */
	protected function map( \Aimeos\Map $items ) : array
	{
		$list = [];

		foreach( $items as $item ) {
			$list[$item->getCode()] = $item;
		}

		return $list;
	}


	/**
	 * Adds a redirect to the response for the next action
	 *
	 * @param string $resource Resource name
	 * @param string|null $action Next action
	 * @param string|null $id ID of the next resource item
	 * @param string|null $act Current action name
	 * @return string|null Returns value for the actions
	 */
	protected function redirect( string $resource, ?string $action, string $id = null,
		string $method = null ) : ?string
	{
		$params = $this->getClientParams();
		$context = $this->getContext();
		$view = $this->view();

		$params['resource'] = $resource;
		unset( $params['id'] );

		switch( $action )
		{
			case 'search':
				$target = $view->config( 'admin/jqadm/url/search/target' );
				$cntl = $view->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
				$action = $view->config( 'admin/jqadm/url/search/action', 'search' );
				$conf = $view->config( 'admin/jqadm/url/search/config', [] );
				$url = $view->url( $target, $cntl, $action, $params, [], $conf );
				break;
			case 'create':
				$params['parentid'] = $id;
				$target = $view->config( 'admin/jqadm/url/create/target' );
				$cntl = $view->config( 'admin/jqadm/url/create/controller', 'Jqadm' );
				$action = $view->config( 'admin/jqadm/url/create/action', 'create' );
				$conf = $view->config( 'admin/jqadm/url/create/config', [] );
				$url = $view->url( $target, $cntl, $action, $params, [], $conf );
				break;
			case 'copy':
				$target = $view->config( 'admin/jqadm/url/copy/target' );
				$cntl = $view->config( 'admin/jqadm/url/copy/controller', 'Jqadm' );
				$action = $view->config( 'admin/jqadm/url/copy/action', 'copy' );
				$conf = $view->config( 'admin/jqadm/url/copy/config', [] );
				$url = $view->url( $target, $cntl, $action, ['id' => $id] + $params, [], $conf );
				break;
			default:
				$target = $view->config( 'admin/jqadm/url/get/target' );
				$cntl = $view->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
				$action = $view->config( 'admin/jqadm/url/get/action', 'get' );
				$conf = $view->config( 'admin/jqadm/url/get/config', [] );
				$url = $view->url( $target, $cntl, $action, ['id' => $id] + $params, [], $conf );
		}

		switch( $method )
		{
			case 'save':
				$context->getSession()->set( 'info', [$context->translate( 'admin', 'Item saved successfully' )] ); break;
			case 'delete':
				$context->getSession()->set( 'info', [$context->translate( 'admin', 'Item deleted successfully' )] ); break;
		}

		$view->response()->withStatus( 302 );
		$view->response()->withHeader( 'Location', $url );
		$view->response()->withHeader( 'Cache-Control', 'no-store' );

		return null;
	}


	/**
	 * Writes the exception details to the log
	 *
	 * @param \Exception $e Exception object
	 * @param string $method Method it's called from
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	protected function report( \Exception $e, string $method ) : Iface
	{
		$view = $this->view;
		$i18n = $this->context->getI18n();

		if( $e instanceof \Aimeos\Admin\JQAdm\Exception )
		{
			$view->errors = array_merge( $view->get( 'errors', [] ), [$e->getMessage()] );
			return $this->log( $e );
		}
		elseif( $e instanceof \Aimeos\MShop\Exception )
		{
			$view->errors = array_merge( $view->get( 'errors', [] ), [$i18n->dt( 'mshop', $e->getMessage() )] );
			return $this->log( $e );
		}

		switch( $method )
		{
			case 'save': $msg = $i18n->dt( 'admin', 'Error saving data' ); break;
			case 'delete': $msg = $i18n->dt( 'admin', 'Error deleting data' ); break;
			default: $msg = $i18n->dt( 'admin', 'Error retrieving data' ); break;
		}

		$view->errors = array_merge( $view->get( 'errors', [] ), [$msg] );

		return $this->log( $e );
	}


	/**
	 * Checks and returns the request parameter for the given name
	 *
	 * @param string $name Name of the request parameter, can be a path like 'page/limit'
	 * @return mixed Parameter value
	 * @throws \Aimeos\Admin\JQAdm\Exception If the parameter is missing
	 */
	protected function require( string $name )
	{
		if( ( $value = $this->view()->param( $name ) ) !== null ) {
			return $value;
		}

		$msg = $this->context->translate( 'admin', 'Required parameter "%1$s" is missing' );
		throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, $name ) );
	}


	/**
	 * Stores and returns the parameters used for searching items
	 *
	 * @param array $params GET/POST parameter set
	 * @param string $name Name of the panel/subpanel
	 * @return array Associative list of parameters for searching items
	 */
	protected function storeFilter( array $params, string $name ) : array
	{
		$key = 'aimeos/admin/jqadm/' . $name;
		$session = $this->getContext()->getSession();

		foreach( ['fields', 'filter', 'page', 'sort'] as $part )
		{
			if( isset( $params[$part] ) ) {
				$session->set( $key . '/' . $part, $params[$part] );
			}
		}

		return [
			'fields' => $session->get( $key . '/fields' ),
			'filter' => $session->get( $key . '/filter' ),
			'page' => $session->get( $key . '/page' ),
			'sort' => $session->get( $key . '/sort' ),
		];
	}


	/**
	 * Throws an exception with given details
	 *
	 * @param array $errors List of key/message pairs of errors
	 * @throws \Aimeos\Admin\JQAdm\Exception Exception with error details
	 */
	protected function notify( array $errors ) : Iface
	{
		$list = [];
		$i18n = $this->context->getI18n();

		foreach( $errors as $key => $error )
		{
			if( $error ) {
				$list[] = $key . ': ' . $i18n->dt( 'mshop', $error );
			}
		}

		if( !empty( $list ) ) {
			throw new \Aimeos\Admin\JQAdm\Exception( join( "\n", $list ) );
		}

		return $this;
	}
}
