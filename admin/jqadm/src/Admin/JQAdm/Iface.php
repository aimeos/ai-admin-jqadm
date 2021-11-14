<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm;


/**
 * Common interface for all JQAdm client classes.
 *
 * @package Admin
 * @subpackage JQAdm
 */
interface Iface
{
	/**
	 * Copies a resource
	 *
	 * @return string Output to display
	 */
	public function copy() : ?string;

	/**
	 * Creates a new resource
	 *
	 * @return string Output to display
	 */
	public function create() : ?string;

	/**
	 * Deletes a resource
	 *
	 * @return string|null Output to display or null for none
	 */
	public function delete() : ?string;

	/**
	 * Exports a resource
	 *
	 * @return string Output to display or null for none
	 */
	public function export() : ?string;

	/**
	 * Returns a single resource
	 *
	 * @return string Output to display
	 */
	public function get() : ?string;

	/**
	 * Imports a resource
	 *
	 * @return string|null Output to display or null for none
	 */
	public function import() : ?string;

	/**
	 * Saves the data
	 *
	 * @return string|null Output to display or null for none
	 */
	public function save() : ?string;

	/**
	 * Returns a list of resource according to the conditions
	 *
	 * @return string Output to display
	 */
	public function search() : ?string;

	/**
	 * Returns the PSR-7 response object for the request
	 *
	 * @return \Psr\Http\Message\ResponseInterface Response object
	 */
	public function response() : \Psr\Http\Message\ResponseInterface;

	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( string $type, string $name = null ) : \Aimeos\Admin\JQAdm\Iface;

	/**
	 * Sets the view object that will generate the admin output.
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the admin output
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	public function setView( \Aimeos\MW\View\Iface $view );
}
