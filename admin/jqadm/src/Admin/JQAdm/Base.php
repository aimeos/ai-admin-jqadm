<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2020
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
	implements \Aimeos\Admin\JQAdm\Iface
{
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
	 * Catch unknown methods
	 *
	 * @param string $name Name of the method
	 * @param array $param List of method parameter
	 * @throws \Aimeos\Admin\JQAdm\Exception If method call failed
	 */
	public function __call( string $name, array $param )
	{
		throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Unable to call method "%1$s"', $name ) );
	}


	/**
	 * Adds the required data used in the attribute template
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @return \Aimeos\MW\View\Iface View object with assigned parameters
	 */
	public function addData( \Aimeos\MW\View\Iface $view ) : \Aimeos\MW\View\Iface
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
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Aimeos object not available' ) );
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
	public function getView() : \Aimeos\MW\View\Iface
	{
		if( !isset( $this->view ) ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'No view available' ) );
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
		foreach( $this->getSubClients() as $client ) {
			$client->copy();
		}

		return null;
	}


	/**
	 * Creates a new resource
	 *
	 * @return string|null Output to display
	 */
	public function create() : ?string
	{
		foreach( $this->getSubClients() as $client ) {
			$client->create();
		}

		return null;
	}


	/**
	 * Deletes a resource
	 *
	 * @return string|null Output to display
	 */
	public function delete() : ?string
	{
		foreach( $this->getSubClients() as $client ) {
			$client->delete();
		}

		return null;
	}


	/**
	 * Exports a resource
	 *
	 * @return string|null Output to display
	 */
	public function export() : ?string
	{
		foreach( $this->getSubClients() as $client ) {
			$client->export();
		}

		return null;
	}


	/**
	 * Returns a resource
	 *
	 * @return string|null Output to display
	 */
	public function get() : ?string
	{
		foreach( $this->getSubClients() as $client ) {
			$client->get();
		}

		return null;
	}


	/**
	 * Imports a resource
	 *
	 * @return string|null Output to display
	 */
	public function import() : ?string
	{
		foreach( $this->getSubClients() as $client ) {
			$client->import();
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
		foreach( $this->getSubClients() as $client ) {
			$client->save();
		}

		return null;
	}


	/**
	 * Returns a list of resource according to the conditions
	 *
	 * @return string|null Output to display
	 */
	public function search() : ?string
	{
		foreach( $this->getSubClients() as $client ) {
			$client->search();
		}

		return null;
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
			if( ctype_alnum( $name ) === false )
			{
				$classname = is_string( $name ) ? $classprefix . $name : '<not a string>';
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Invalid class name "%1$s"', $classname ) );
			}

			$classname = $classprefix . $name;

			if( class_exists( $classname ) === false ) {
				throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Class "%1$s" not found', $classname ) );
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
		if( !is_string( $path ) || $path === '' ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Invalid domain "%1$s"', $path ) );
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

		if( empty( $name ) || ctype_alnum( $name ) === false ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Invalid characters in client name "%1$s"', $name ) );
		}

		$subnames = str_replace( '/', '\\', ucwords( $path, '/' ) );

		$classname = '\\Aimeos\\Admin\\JQAdm\\' . $subnames . '\\' . $name;

		if( class_exists( $classname ) === false ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Class "%1$s" not available', $classname ) );
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
	protected function getClientParams( $names = ['id', 'resource', 'site', 'lang'] ) : array
	{
		$list = [];

		foreach( $names as $name )
		{
			if( ( $val = $this->view->param( $name ) ) !== null ) {
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
	 * Returns the array of criteria sortations based on the given parameters
	 *
	 * @param array $params List of criteria data with condition, sorting and paging
	 * @return array Associative list of criteria sortations
	 */
	protected function getCriteriaSortations( array $params ) : array
	{
		$sortation = [];

		foreach( $params as $sort )
		{
			if( $sort[0] === '-' ) {
				$sortation[substr( $sort, 1 )] = '-';
			} else {
				$sortation[$sort] = '+';
			}
		}

		return $sortation;
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
		if( isset( $params['filter'] ) ) {
			$criteria = $this->initCriteriaConditions( $criteria, (array) $params['filter'] )
				->setSlice( 0, $criteria->getSliceSize() );
		}

		if( isset( $params['sort'] ) ) {
			$criteria = $this->initCriteriaSortations( $criteria, (array) $params['sort'] );
		}

		$page = [];
		if( isset( $params['page'] ) ) {
			$page = (array) $params['page'];
		}

		return $this->initCriteriaSlice( $criteria, $page );
	}


	/**
	 * Writes the exception details to the log
	 *
	 * @param \Exception $e Exception object
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	protected function log( \Exception $e ) : Iface
	{
		$logger = $this->context->getLogger();
		$logger->log( $e->getMessage(), \Aimeos\MW\Logger\Base::ERR, 'admin/jqadm' );
		$logger->log( $e->getTraceAsString(), \Aimeos\MW\Logger\Base::ERR, 'admin/jqadm' );

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
		$view = $this->getView();

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
				$context->getSession()->set( 'info', [$context->getI18n()->dt( 'admin', 'Item saved successfully' )] ); break;
			case 'delete':
				$context->getSession()->set( 'info', [$context->getI18n()->dt( 'admin', 'Item deleted successfully' )] ); break;
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
			return $this;
		}
		elseif( $e instanceof \Aimeos\MShop\Exception )
		{
			$view->errors = array_merge( $view->get( 'errors', [] ), [$i18n->dt( 'mshop', $e->getMessage() )] );
			return $this;
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
	 * Stores and returns the parameters used for searching items
	 *
	 * @param array $params GET/POST parameter set
	 * @param string $name Name of the panel/subpanel
	 * @return array Associative list of parameters for searching items
	 */
	protected function storeSearchParams( array $params, string $name ) : array
	{
		$key = 'aimeos/admin/jqadm/' . $name;
		$session = $this->getContext()->getSession();

		if( isset( $params['filter'] ) ) {
			$session->set( $key . '/filter', $params['filter'] );
		}

		if( isset( $params['sort'] ) ) {
			$session->set( $key . '/sort', $params['sort'] );
		}

		if( isset( $params['page'] ) ) {
			$session->set( $key . '/page', $params['page'] );
		}

		if( isset( $params['fields'] ) ) {
			$session->set( $key . '/fields', $params['fields'] );
		}

		return [
			'fields' => $session->get( $key . '/fields' ),
			'filter' => $session->get( $key . '/filter' ),
			'page' => $session->get( $key . '/page' ),
			'sort' => $session->get( $key . '/sort' ),
		];
	}


	/**
	 * Initializes the criteria object with conditions based on the given parameter
	 *
	 * @param \Aimeos\MW\Criteria\Iface $criteria Criteria object
	 * @param array $params List of criteria data with condition, sorting and paging
	 * @return \Aimeos\MW\Criteria\Iface Initialized criteria object
	 */
	private function initCriteriaConditions( \Aimeos\MW\Criteria\Iface $criteria, array $params ) : \Aimeos\MW\Criteria\Iface
	{
		if( ( $cond = $criteria->toConditions( $this->getCriteriaConditions( $params ) ) ) !== null ) {
			return $criteria->setConditions( $criteria->combine( '&&', [$cond, $criteria->getConditions()] ) );
		}

		return $criteria;
	}


	/**
	 * Initializes the criteria object with the slice based on the given parameter.
	 *
	 * @param \Aimeos\MW\Criteria\Iface $criteria Criteria object
	 * @param array $params List of criteria data with condition, sorting and paging
	 * @return \Aimeos\MW\Criteria\Iface Initialized criteria object
	 */
	private function initCriteriaSlice( \Aimeos\MW\Criteria\Iface $criteria, array $params ) : \Aimeos\MW\Criteria\Iface
	{
		$start = ( isset( $params['offset'] ) ? $params['offset'] : 0 );
		$size = ( isset( $params['limit'] ) ? $params['limit'] : 25 );

		return $criteria->setSlice( $start, $size );
	}


	/**
	 * Initializes the criteria object with sortations based on the given parameter
	 *
	 * @param \Aimeos\MW\Criteria\Iface $criteria Criteria object
	 * @param array $params List of criteria data with condition, sorting and paging
	 * @return \Aimeos\MW\Criteria\Iface Initialized criteria object
	 */
	private function initCriteriaSortations( \Aimeos\MW\Criteria\Iface $criteria, array $params ) : \Aimeos\MW\Criteria\Iface
	{
		return $criteria->setSortations( $criteria->toSortations( $this->getCriteriaSortations( $params ) ) );
	}
}
