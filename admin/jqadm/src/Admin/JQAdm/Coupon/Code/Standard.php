<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Coupon\Code;

sprintf( 'code' ); // for translation


/**
 * Default implementation of coupon code JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/coupon/code/name
	 * Name of the code subpart used by the JQAdm coupon implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Coupon\Code\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2017.07
	 * @category Developer
	 */


	/**
	 * Copies a resource
	 *
	 * @return string HTML output
	 */
	public function copy()
	{
		return $this->render( $this->getView() );
	}


	/**
	 * Creates a new resource
	 *
	 * @return string HTML output
	 */
	public function create()
	{
		return $this->render( $this->getView() );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string HTML output
	 */
	public function get()
	{
		$view = $this->getView();

		try
		{
			$total = 0;
			$params = $this->storeSearchParams( $view->param( 'vc', [] ), 'couponcode' );
			$codeItems = $this->getCodeItems( $view->item, $params, $total );

			$view->codeData = $this->toArray( $codeItems );
			$view->codeTotal = $total;
			$view->codeBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->codeBody .= $client->search();
			}
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'coupon-item-code' => $this->getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'coupon-item-code' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		return $this->render( $view );
	}


	/**
	 * Saves the data
	 */
	public function save()
	{
		$view = $this->getView();
		$context = $this->getContext();

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'coupon/code' );
		$manager->begin();

		try
		{
			$this->storeSearchParams( $view->param( 'vc', [] ), 'couponcode' );
			$this->storeFile( $view->item, (array) $view->request()->getUploadedFiles() );
			$this->fromArray( $view->item, $view->param( 'code', [] ) );
			$view->couponBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->couponBody .= $client->save();
			}

			$manager->commit();
			return;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'coupon-item-code' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'coupon-item-code' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		$manager->rollback();

		throw new \Aimeos\Admin\JQAdm\Exception();
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
		/** admin/jqadm/coupon/code/decorators/excludes
		 * Excludes decorators added by the "common" option from the coupon JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "admin/jqadm/common/decorators/default" before they are wrapped
		 * around the JQAdm client.
		 *
		 *  admin/jqadm/coupon/code/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/coupon/code/decorators/global
		 * @see admin/jqadm/coupon/code/decorators/local
		 */

		/** admin/jqadm/coupon/code/decorators/global
		 * Adds a list of globally available decorators only to the coupon JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/coupon/code/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/coupon/code/decorators/excludes
		 * @see admin/jqadm/coupon/code/decorators/local
		 */

		/** admin/jqadm/coupon/code/decorators/local
		 * Adds a list of local decorators only to the coupon JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Coupon\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/coupon/code/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Coupon\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/coupon/code/decorators/excludes
		 * @see admin/jqadm/coupon/code/decorators/global
		 */
		return $this->createSubClient( 'coupon/code/' . $type, $name );
	}


	/**
	 * Checks if an error during upload occured
	 *
	 * @param \Psr\Http\Message\UploadedFileInterface $file Uploaded file
	 * @throws \Aimeos\Admin\JQAdm\Exception If an error occured during upload
	 */
	protected function checkFileUpload( \Psr\Http\Message\UploadedFileInterface $file )
	{
		if( $file->getError() !== UPLOAD_ERR_OK )
		{
			switch( $file->getError() )
			{
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					throw new \Aimeos\Admin\JQAdm\Exception( 'The uploaded file exceeds the max. allowed filesize' );
				case UPLOAD_ERR_PARTIAL:
					throw new \Aimeos\Admin\JQAdm\Exception( 'The uploaded file was only partially uploaded' );
				case UPLOAD_ERR_NO_FILE:
					throw new \Aimeos\Admin\JQAdm\Exception( 'No file was uploaded' );
				case UPLOAD_ERR_NO_TMP_DIR:
					throw new \Aimeos\Admin\JQAdm\Exception( 'Temporary folder is missing' );
				case UPLOAD_ERR_CANT_WRITE:
					throw new \Aimeos\Admin\JQAdm\Exception( 'Failed to write file to disk' );
				case UPLOAD_ERR_EXTENSION:
					throw new \Aimeos\Admin\JQAdm\Exception( 'File upload stopped by extension' );
				default:
					throw new \Aimeos\Admin\JQAdm\Exception( 'Unknown upload error' );
			}
		}
	}


	/**
	 * Returns the coupon code items associated by the coupon item
	 *
	 * @param \Aimeos\MShop\Coupon\Item\Iface $item Coupon item object
	 * @param array $params Associative list of GET/POST parameters
	 * @param integer $total Value/result parameter that will contain the item total afterwards
	 * @return \Aimeos\MShop\Coupon\Item\Code\Iface[] Coupon code items associated to the coupon item
	 */
	protected function getCodeItems( \Aimeos\MShop\Coupon\Item\Iface $item, array $params = [], &$total )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'coupon/code' );

		$search = $manager->createSearch();
		$search->setSortations( [$search->sort( '+', 'coupon.code.code' )] );

		$search = $this->initCriteria( $search, $params );
		$expr = [
			$search->compare( '==', 'coupon.code.parentid', $item->getId() ),
			$search->getConditions(),
		];
		$search->setConditions( $search->combine( '&&', $expr ) );

		return $manager->searchItems( $search, [], $total );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames()
	{
		/** admin/jqadm/coupon/code/standard/subparts
		 * List of JQAdm sub-clients rendered within the coupon code section
		 *
		 * The output of the frontend is composed of the code generated by the JQAdm
		 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
		 * that are responsible for rendering certain sub-parts of the output. The
		 * sub-clients can contain JQAdm clients themselves and therefore a
		 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
		 * the output that is placed inside the container of its parent.
		 *
		 * At first, always the JQAdm code generated by the parent is printed, then
		 * the JQAdm code of its sub-clients. The code of the JQAdm sub-clients
		 * determines the code of the output of these sub-clients inside the parent
		 * container. If the configured list of clients is
		 *
		 *  array( "subclient1", "subclient2" )
		 *
		 * you can easily change the code of the output by recodeing the subparts:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
		 *
		 * You can also remove one or more parts if they shouldn't be rendered:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
		 *
		 * As the clients only generates structural JQAdm, the layout defined via CSS
		 * should support adding, removing or recodeing content by a fluid like
		 * design.
		 *
		 * @param array List of sub-client names
		 * @since 2017.07
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/coupon/code/standard/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Coupon\Item\Iface $item Coupon item object
	 * @param string[] $data Data array
	 */
	protected function fromArray( \Aimeos\MShop\Coupon\Item\Iface $item, array $data )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'coupon/code' );

		foreach( $this->getValue( $data, 'coupon.code.id', [] ) as $idx => $id )
		{
			$citem = $manager->createItem();

			$citem->setId( $id );
			$citem->setParentId( $item->getId() );
			$citem->setCode( $this->getValue( $data, 'coupon.code.code/' . $idx ) );
			$citem->setCount( $this->getValue( $data, 'coupon.code.count/' . $idx ) );
			$citem->setDateStart( $this->getValue( $data, 'coupon.code.datestart/' . $idx ) );
			$citem->setDateEnd( $this->getValue( $data, 'coupon.code.dateend/' . $idx ) );

			$manager->saveItem( $citem, false );
		}
	}


	/**
	 * Stores the uploaded CSV file containing the coupon codes
	 *
	 * @param \Aimeos\MShop\Coupon\Item\Iface $item Coupon item object
	 * @param array $files File upload array including the PSR-7 file upload objects
	 */
	protected function storeFile( \Aimeos\MShop\Coupon\Item\Iface $item, array $files )
	{
		$file = $this->getValue( $files, 'code/file' );

		if( $file == null || $file->getError() === UPLOAD_ERR_NO_FILE ) {
			return;
		}

		$this->checkFileUpload( $file );

		$context = $this->getContext();
		$fs = $context->getFilesystemManager()->get( 'fs-import' );
		$dir = 'couponcode/' . $context->getLocale()->getSite()->getCode();

		if( $fs->isdir( $dir ) === false ) {
			$fs->mkdir( $dir );
		}

		$fs->writes( $dir . '/' . $item->getId() . '.csv', $file->getStream()->detach() );
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Coupon\Item\Code\Iface[] $items Coupon code items
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( array $items )
	{
		$data = [];

		foreach( $items as $item )
		{
			foreach( $item->toArray( true ) as $key => $value ) {
				$data[$key][] = $value;
			}
		}

		return $data;
	}


	/**
	 * Returns the rendered template including the view data
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	protected function render( \Aimeos\MW\View\Iface $view )
	{
		/** admin/jqadm/coupon/code/template-item
		 * Relative path to the HTML body template of the code subpart for coupons.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in admin/jqadm/templates).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "default" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "default"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating the HTML code
		 * @since 2016.04
		 * @category Developer
		 */
		$tplconf = 'admin/jqadm/coupon/code/template-item';
		$default = 'coupon/item-code-standard.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
