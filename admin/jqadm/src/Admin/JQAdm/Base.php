<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
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
	public function __call( $name, array $param )
	{
		throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Unable to call method "%1$s"', $name ) );
	}


	/**
	 * Returns the Aimeos bootstrap object
	 *
	 * @return \Aimeos\Bootstrap The Aimeos bootstrap object
	 */
	public function getAimeos()
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
	public function setAimeos( \Aimeos\Bootstrap $aimeos )
	{
		$this->aimeos = $aimeos;
		return $this;
	}


	/**
	 * Returns the view object that will generate the admin output.
	 *
	 * @return \Aimeos\MW\View\Iface The view object which generates the admin output
	 */
	public function getView()
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
	public function setView( \Aimeos\MW\View\Iface $view )
	{
		$this->view = $view;
		return $this;
	}


	/**
	 * Copies a resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function copy()
	{
		foreach( $this->getSubClients() as $client ) {
			$client->copy();
		}
	}


	/**
	 * Creates a new resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function create()
	{
		foreach( $this->getSubClients() as $client ) {
			$client->create();
		}
	}


	/**
	 * Deletes a resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function delete()
	{
		foreach( $this->getSubClients() as $client ) {
			$client->delete();
		}
	}


	/**
	 * Exports a resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function export()
	{
		foreach( $this->getSubClients() as $client ) {
			$client->export();
		}
	}


	/**
	 * Returns a resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function get()
	{
		foreach( $this->getSubClients() as $client ) {
			$client->get();
		}
	}


	/**
	 * Imports a resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function import()
	{
		foreach( $this->getSubClients() as $client ) {
			$client->import();
		}
	}


	/**
	 * Saves the data
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function save()
	{
		foreach( $this->getSubClients() as $client ) {
			$client->save();
		}
	}


	/**
	 * Returns a list of resource according to the conditions
	 *
	 * @return string admin output to display
	 */
	public function search()
	{
		foreach( $this->getSubClients() as $client ) {
			$client->search();
		}
	}


	/**
	 * Adds the decorators to the client object
	 *
	 * @param \Aimeos\Admin\JQAdm\Iface $client Admin object
	 * @param array $decorators List of decorator name that should be wrapped around the client
	 * @param string $classprefix Decorator class prefix, e.g. "\Aimeos\Admin\JQAdm\Catalog\Decorator\"
	 * @return \Aimeos\Admin\JQAdm\Iface Admin object
	 */
	protected function addDecorators( \Aimeos\Admin\JQAdm\Iface $client, array $decorators, $classprefix )
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
	protected function addClientDecorators( \Aimeos\Admin\JQAdm\Iface $client, $path )
	{
		if( !is_string( $path ) || $path === '' ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Invalid domain "%1$s"', $path ) );
		}

		$localClass = str_replace( ' ', '\\', ucwords( str_replace( '/', ' ', $path ) ) );
		$config = $this->context->getConfig();

		$decorators = $config->get( 'admin/jqadm/common/decorators/default', [] );
		$excludes = $config->get( 'admin/jqadm/' . $path . '/decorators/excludes', [] );

		foreach( $decorators as $key => $name )
		{
			if( in_array( $name, $excludes ) ) {
				unset( $decorators[$key] );
			}
		}

		$classprefix = '\\Aimeos\\Admin\\JQAdm\\Common\\Decorator\\';
		$client = $this->addDecorators( $client, $decorators, $classprefix );

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
	protected function createSubClient( $path, $name )
	{
		$path = strtolower( $path );

		if( $name === null ) {
			$name = $this->context->getConfig()->get( 'admin/jqadm/' . $path . '/name', 'Standard' );
		}

		if( empty( $name ) || ctype_alnum( $name ) === false ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Invalid characters in client name "%1$s"', $name ) );
		}

		$subnames = str_replace( ' ', '\\', ucwords( str_replace( '/', ' ', $path ) ) );

		$classname = '\\Aimeos\\Admin\\JQAdm\\' . $subnames . '\\' . $name;

		if( class_exists( $classname ) === false ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Class "%1$s" not available', $classname ) );
		}

		$object = new $classname( $this->context );

		\Aimeos\MW\Common\Base::checkClass( '\\Aimeos\\Admin\\JQAdm\\Iface', $object );

		$object = $this->addClientDecorators( $object, $path );
		$object->setAimeos( $this->aimeos );
		$object->setView( $this->view );

		return $object;
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
			if( isset( $values[$part] ) ) {
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
	protected function getClientParams( $names = array( 'id', 'resource', 'site', 'lang' ) )
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
	protected function getContext()
	{
		return $this->context;
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of admin client names
	 */
	abstract protected function getSubClientNames();


	/**
	 * Returns the available class names without namespace that are stored in the given path
	 *
	 * @param string $relpath Path relative to the include paths
	 * @param string[] $excludes List of file names to execlude
	 * @return string[] List of available class names
	 */
	protected function getClassNames( $relpath, array $excludes = ['Base.php', 'Iface.php', 'Example.php', 'None.php'] )
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
	protected function getCriteriaConditions( array $params )
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
	protected function getCriteriaSortations( array $params )
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
	 * Returns the configured sub-clients or the ones named in the default parameter if none are configured.
	 *
	 * @return array List of sub-clients implementing \Aimeos\Admin\JQAdm\Iface ordered in the same way as the names
	 */
	protected function getSubClients()
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
	protected function initCriteria( \Aimeos\MW\Criteria\Iface $criteria, array $params )
	{
		if( isset( $params['filter'] ) ) {
			$criteria = $this->initCriteriaConditions( $criteria, (array) $params['filter'] );
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
	 */
	protected function logException( \Exception $e )
	{
		$logger = $this->context->getLogger();

		$logger->log( $e->getMessage(), \Aimeos\MW\Logger\Base::WARN, 'admin/jqadm' );
		$logger->log( $e->getTraceAsString(), \Aimeos\MW\Logger\Base::WARN, 'admin/jqadm' );
	}


	/**
	 * Adds a redirect to the response for the next action
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param string $action Next action
	 * @param string $resource Resource name
	 * @param string $id ID of the next resource item
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected function nextAction( \Aimeos\MW\View\Iface $view, $action, $resource, $id = null, $act = null )
	{
		$params = $this->getClientParams();
		$params['resource'] = $resource;
		unset( $params['id'] );

		if( $act ) {
			$params['act'] = $act;
		}

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

		$view->response()->withStatus( 302 );
		$view->response()->withHeader( 'Location', $url );
		$view->response()->withHeader( 'Cache-Control', 'no-store' );

		return $view;
	}


	/**
	 * Sorts the type item with code "default" first
	 *
	 * @param \Aimeos\MShop\Common\Item\Type\Iface[] Associative list of type IDs as keys and items as values
	 * @return \Aimeos\MShop\Common\Item\Type\Iface[] Sorted associative list of type IDs as keys and items as values
	 */
	protected function sortType( array $items )
	{
		foreach( $items as $id => $item )
		{
			if( $item instanceof \Aimeos\MShop\Common\Item\Type\Iface && $item->getCode() === 'default' )
			{
				unset( $items[$id] );
				return [$id => $item] + $items;
			}
		}

		return $items;
	}


	/**
	 * Stores and returns the parameters used for searching items
	 *
	 * @param array $params GET/POST parameter set
	 * @param string $name Name of the panel/subpanel
	 * @return array Associative list of parameters for searching items
	 */
	protected function storeSearchParams( array $params, $name )
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
	private function initCriteriaConditions( \Aimeos\MW\Criteria\Iface $criteria, array $params )
	{
		$expr = [
			$criteria->toConditions( $this->getCriteriaConditions( $params ) ),
			$criteria->getConditions(),
		];

		return $criteria->setConditions( $criteria->combine( '&&', $expr ) );
	}


	/**
	 * Initializes the criteria object with the slice based on the given parameter.
	 *
	 * @param \Aimeos\MW\Criteria\Iface $criteria Criteria object
	 * @param array $params List of criteria data with condition, sorting and paging
	 * @return \Aimeos\MW\Criteria\Iface Initialized criteria object
	 */
	private function initCriteriaSlice( \Aimeos\MW\Criteria\Iface $criteria, array $params )
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
	private function initCriteriaSortations( \Aimeos\MW\Criteria\Iface $criteria, array $params )
	{
		return $criteria->setSortations( $criteria->toSortations( $this->getCriteriaSortations( $params ) ) );
	}
}
