<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
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
	 * @return string|null HTML output
	 */
	public function copy() : ?string
	{
		$view = $this->getObject()->data( $this->view() );
		$view->codeBody = parent::copy();

		return $this->render( $view );
	}


	/**
	 * Creates a new resource
	 *
	 * @return string|null HTML output
	 */
	public function create() : ?string
	{
		$view = $this->getObject()->data( $this->view() );
		$view->codeBody = parent::create();

		return $this->render( $view );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null HTML output
	 */
	public function get() : ?string
	{
		$view = $this->getObject()->data( $this->view() );

		$total = 0;
		$params = $this->storeFilter( $view->param( 'vc', [] ), 'couponcode' );
		$codeItems = $this->getCodeItems( $view->item, $params, $total );

		$view->codeData = $this->toArray( $codeItems );
		$view->codeBody = parent::search();
		$view->codeTotal = $total;

		return $this->render( $view );
	}


	/**
	 * Saves the data
	 *
	 * @return string|null HTML output
	 */
	public function save() : ?string
	{
		$view = $this->view();

		$manager = \Aimeos\MShop::create( $this->getContext(), 'coupon/code' );
		$manager->begin();

		try
		{
			$this->storeFilter( $view->param( 'vc', [] ), 'couponcode' );
			$this->storeFile( $view->item, (array) $view->request()->getUploadedFiles() );
			$this->fromArray( $view->item, $view->param( 'code', [] ) );
			$view->codeBody = parent::save();

			$manager->commit();
		}
		catch( \Exception $e )
		{
			$manager->rollback();
			throw $e;
		}

		return null;
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
			$ctx = $this->getContext();

			switch( $file->getError() )
			{
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					$msg = $ctx->translate( 'admin', 'The uploaded file exceeds the max. allowed filesize' ); break;
				case UPLOAD_ERR_PARTIAL:
					$msg = $ctx->translate( 'admin', 'The uploaded file was only partially uploaded' ); break;
				case UPLOAD_ERR_NO_FILE:
					$msg = $ctx->translate( 'admin', 'No file was uploaded' ); break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$msg = $ctx->translate( 'admin', 'Temporary folder is missing' ); break;
				case UPLOAD_ERR_CANT_WRITE:
					$msg = $ctx->translate( 'admin', 'Failed to write file to disk' ); break;
				case UPLOAD_ERR_EXTENSION:
					$msg = $ctx->translate( 'admin', 'File upload stopped by extension' ); break;
				default:
					$msg = $ctx->translate( 'admin', 'Unknown upload error' );
			}

			throw new \Aimeos\Admin\JQAdm\Exception( $msg );
		}
	}


	/**
	 * Returns the coupon code items associated by the coupon item
	 *
	 * @param \Aimeos\MShop\Coupon\Item\Iface $item Coupon item object
	 * @param array $params Associative list of GET/POST parameters
	 * @param int $total Value/result parameter that will contain the item total afterwards
	 * @return \Aimeos\Map Coupon code items implementing \Aimeos\MShop\Coupon\Item\Code\Iface associated to the coupon item
	 */
	protected function getCodeItems( \Aimeos\MShop\Coupon\Item\Iface $item, array $params = [], int &$total = null ) : \Aimeos\Map
	{
		$manager = \Aimeos\MShop::create( $this->getContext(), 'coupon/code' );

		$search = $manager->filter();
		$search->setSortations( [$search->sort( '+', 'coupon.code.code' )] );

		$search = $this->initCriteria( $search, $params );
		$expr = [
			$search->compare( '==', 'coupon.code.parentid', $item->getId() ),
			$search->getConditions(),
		];
		$search->setConditions( $search->and( $expr ) );

		return $manager->search( $search, [], $total );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/coupon/code/subparts
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
		return $this->getContext()->getConfig()->get( 'admin/jqadm/coupon/code/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Coupon\Item\Iface $item Coupon item object
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Coupon\Item\Iface Modified coupon item
	 */
	protected function fromArray( \Aimeos\MShop\Coupon\Item\Iface $item, array $data ) : \Aimeos\MShop\Coupon\Item\Iface
	{
		if( ( $ids = $this->getValue( $data, 'coupon.code.id', [] ) ) === [] ) {
			return $item;
		}

		$manager = \Aimeos\MShop::create( $this->getContext(), 'coupon/code' );
		$filter = $manager->filter()->add( ['coupon.code.id' => $ids] )->slice( 0, count( $ids ) );
		$items = $manager->search( $filter );

		foreach( $ids as $idx => $id )
		{
			if( !isset( $items[$id] ) ) {
				$citem = $manager->create();
			} else {
				$citem = $items[$id];
			}

			$citem->setId( $id );
			$citem->setParentId( $item->getId() );
			$citem->setCode( $this->getValue( $data, 'coupon.code.code/' . $idx, $citem->getCode() ) );
			$citem->setCount( $this->getValue( $data, 'coupon.code.count/' . $idx, $citem->getCount() ) );
			$citem->setDateStart( $this->getValue( $data, 'coupon.code.datestart/' . $idx, $citem->getDateStart() ) );
			$citem->setDateEnd( $this->getValue( $data, 'coupon.code.dateend/' . $idx, $citem->getDateEnd() ) );
			$citem->setRef( $this->getValue( $data, 'coupon.code.ref/' . $idx, $citem->getRef() ) );

			$manager->save( $citem, false );
		}

		return $item;
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
		$dir = 'couponcode/' . $context->getLocale()->getSiteItem()->getCode();

		if( $fs->isdir( $dir ) === false ) {
			$fs->mkdir( $dir );
		}

		$fs->writes( $dir . '/' . $item->getId() . '.csv', $file->getStream()->detach() );
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\Map $items Coupon code items implementing \Aimeos\MShop\Coupon\Item\Code\Iface
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\Map $items )
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
	protected function render( \Aimeos\MW\View\Iface $view ) : string
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
		$default = 'coupon/item-code-standard';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
