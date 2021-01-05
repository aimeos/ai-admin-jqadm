<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2019-2021
 * @package MW
 * @subpackage View
 */


namespace Aimeos\MW\View\Helper\Datetime;


/**
 * View helper class for formatting datetime values for the frontend.
 *
 * @package MW
 * @subpackage View
 */
interface Iface extends \Aimeos\MW\View\Helper\Iface
{
	/**
	 * Returns the formatted date and time.
	 *
	 * @param string $datetime ISO date and time
	 * @return string Formatted date
	 */
	public function transform( string $datetime ) : string;
}
