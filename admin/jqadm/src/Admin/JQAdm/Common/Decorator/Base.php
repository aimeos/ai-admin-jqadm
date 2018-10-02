<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
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
	public function __construct( \Aimeos\Admin\JQAdm\Iface $client,
		\Aimeos\MShop\Context\Item\Iface $context )
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
	public function __call( $name, array $param )
	{
		return @call_user_func_array( array( $this->client, $name ), $param );
	}


	/**
	 * Copies a resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function copy()
	{
		return $this->client->copy();
	}


	/**
	 * Creates a new resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	*/
	public function create()
	{
		return $this->client->create();
	}


	/**
	 * Deletes a resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	*/
	public function delete()
	{
		return $this->client->delete();
	}


	/**
	 * Exports a resource
	 *
	 * @return string Admin output to display
	 */
	public function export()
	{
		return $this->client->export();
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	*/
	public function get()
	{
		return $this->client->get();
	}


	/**
	 * Imports a resource
	 *
	 * @return string Admin output to display
	 */
	public function import()
	{
		return $this->client->import();
	}


	/**
	 * Saves the data
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	*/
	public function save()
	{
		return $this->client->save();
	}


	/**
	 * Returns a list of resource according to the conditions
	 *
	 * @return string admin output to display
	*/
	public function search()
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
	public function getSubClient( $type, $name = null )
	{
		return $this->client->getSubClient( $type, $name );
	}


	/**
	 * Returns the view object that will generate the admin output.
	 *
	 * @return \Aimeos\MW\View\Iface $view The view object which generates the admin output
	 */
	public function getView()
	{
		return $this->client->getView();
	}


	/**
	 * Sets the view object that will generate the admin output.
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the admin output
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	public function setView( \Aimeos\MW\View\Iface $view )
	{
		parent::setView( $view );

		$this->client->setView( $view );
		return $this;
	}


	/**
	 * Returns the Aimeos bootstrap object
	 *
	 * @return \Aimeos\Bootstrap The Aimeos bootstrap object
	 */
	public function getAimeos()
	{
		return $this->client->getAimeos();
	}


	/**
	 * Sets the Aimeos bootstrap object
	 *
	 * @param \Aimeos\Bootstrap $aimeos The Aimeos bootstrap object
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	public function setAimeos( \Aimeos\Bootstrap $aimeos )
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
	protected function getClient()
	{
		return $this->client;
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of admin client names
	 */
	protected function getSubClientNames()
	{
		return [];
	}
}