<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm;


/**
 * Generic exception thrown by JQAdm Admin objects if no specialized exception is available.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Exception extends \Exception
{
	private $details;


	/**
	 * Initializes the object.
	 *
	 * @param string $message Exception message
	 * @param int $code Custom exception code
	 * @param mixed $details Custom exception details
	 */
	public function __construct( string $message = '', int $code = 0, $details = null )
	{
		parent::__construct( $message, $code );

		$this->details = $details;
	}


	/**
	 * Returns the custom exception details
	 *
	 * @return mixed Custom exception details
	 */
	public function getDetails()
	{
		return $this->details;
	}
}
