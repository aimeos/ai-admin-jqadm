<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2026
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


/**
 * Policy decorator for JQAdm clients
 *
 * Checks if the authorization policy allows changing or deleting the items of
 * the client. Opening the client ("get" action) is checked by JQAdm::create().
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Policy extends Base
{
	private string $path;


	/**
	 * Initializes a new client decorator object.
	 *
	 * @param \Aimeos\Admin\JQAdm\Iface $client Admin object
	 * @param \Aimeos\MShop\ContextIface $context Context object with required objects
	 * @param string $path Path of the client, e.g. "product"
	 */
	public function __construct( \Aimeos\Admin\JQAdm\Iface $client, \Aimeos\MShop\ContextIface $context, string $path )
	{
		parent::__construct( $client, $context );
		$this->path = $path;
	}


	/**
	 * Batch update of resources
	 *
	 * @return string|null HTML output
	 */
	public function batch() : ?string
	{
		$this->authorize( 'save' );
		return parent::batch();
	}


	/**
	 * Deletes a resource
	 *
	 * @return string|null HTML output
	 */
	public function delete() : ?string
	{
		$this->authorize( 'delete' );
		return parent::delete();
	}


	/**
	 * Imports a resource
	 *
	 * @return string|null HTML output
	 */
	public function import() : ?string
	{
		$this->authorize( 'save' );
		return parent::import();
	}


	/**
	 * Saves the data
	 *
	 * @return string|null HTML output
	 */
	public function save() : ?string
	{
		$this->authorize( 'save' );
		return parent::save();
	}


	/**
	 * Ensures that the user is allowed to perform the action for the resource of the client
	 *
	 * @param string $action Action name, i.e. "delete" or "save"
	 * @throws \Aimeos\Admin\JQAdm\Exception If the action isn't allowed
	 */
	protected function authorize( string $action ) : void
	{
		\Aimeos\Admin\JQAdm::check( $this->context(), $this->path, $action );
	}
}
