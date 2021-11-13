<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


/**
 * Provides common methods for JQAdm client decorators.
 *
 * @package Admin
 * @subpackage JQAdm
 */
abstract class Base
	extends \Aimeos\Admin\JQAdm\Base
	implements \Aimeos\Admin\JQAdm\Common\Decorator\Iface
{
	private $client;


	/**
	 * Initializes a new client decorator object.
	 *
	 * @param \Aimeos\Admin\JQAdm\Iface $client Admin object
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object with required objects
	 */
	public function __construct( \Aimeos\Admin\JQAdm\Iface $client, \Aimeos\MShop\Context\Item\Iface $context )
	{
		parent::__construct( $context );

		$this->client = $client;
	}


	/**
	 * Passes unknown methods to wrapped objects.
	 *
	 * @param string $name Name of the method
	 * @param array $param List of method parameter
	 * @return mixed Returns the value of the called method
	 * @throws \Aimeos\Admin\JQAdm\Exception If method call failed
	 */
	public function __call( string $name, array $param )
	{
		return @call_user_func_array( array( $this->client, $name ), $param );
	}


	/**
	 * Copies a resource
	 *
	 * @return string|null HTML output
	 */
	public function copy() : ?string
	{
		return $this->client->copy();
	}


	/**
	 * Creates a new resource
	 *
	 * @return string|null HTML output
	 */
	public function create() : ?string
	{
		return $this->client->create();
	}


	/**
	 * Deletes a resource
	 *
	 * @return string|null HTML output
	 */
	public function delete() : ?string
	{
		return $this->client->delete();
	}


	/**
	 * Exports a resource
	 *
	 * @return string|null HTML output
	 */
	public function export() : ?string
	{
		return $this->client->export();
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null HTML output
	 */
	public function get() : ?string
	{
		return $this->client->get();
	}


	/**
	 * Imports a resource
	 *
	 * @return string|null HTML output
	 */
	public function import() : ?string
	{
		return $this->client->import();
	}


	/**
	 * Saves the data
	 *
	 * @return string|null HTML output
	 */
	public function save() : ?string
	{
		return $this->client->save();
	}


	/**
	 * Returns a list of resource according to the conditions
	 *
	 * @return string|null HTML output
	 */
	public function search() : ?string
	{
		return $this->client->search();
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
		return $this->client->getSubClient( $type, $name );
	}


	/**
	 * Sets the view object that will generate the admin output.
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the admin output
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	public function setView( \Aimeos\MW\View\Iface $view ) : \Aimeos\Admin\JQAdm\Iface
	{
		parent::setView( $view );

		$this->client->setView( $view );
		return $this;
	}


	/**
	 * Sets the Aimeos bootstrap object
	 *
	 * @param \Aimeos\Bootstrap $aimeos The Aimeos bootstrap object
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	public function setAimeos( \Aimeos\Bootstrap $aimeos ) : \Aimeos\Admin\JQAdm\Iface
	{
		parent::setAimeos( $aimeos );

		$this->client->setAimeos( $aimeos );
		return $this;
	}


	/**
	 * Returns the inner client object
	 *
	 * @return \Aimeos\Admin\JQAdm\Iface admin client
	 */
	protected function getClient() : \Aimeos\Admin\JQAdm\Iface
	{
		return $this->client;
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of admin client names
	 */
	protected function getSubClientNames() : array
	{
		return [];
	}
}
