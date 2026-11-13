<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2026
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Download;

sprintf( 'download' ); // for translation


/**
 * Default implementation of product download JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/download/name
	 * Name of the download subpart used by the JQAdm product implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Product\Download\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2016.04
	 */


	/**
	 * Copies a resource
	 *
	 * @return string|null HTML output
	 */
	public function copy() : ?string
	{
		$view = $this->object()->data( $this->view() );
		// @phpstan-ignore argument.type
		$view->downloadData = $this->toArray( $view->item, true );
		$view->downloadBody = parent::copy();

		return $this->render( $view );
	}


	/**
	 * Creates a new resource
	 *
	 * @return string|null HTML output
	 */
	public function create() : ?string
	{
		$view = $this->object()->data( $this->view() );
		$data['product.lists.siteid'] = $this->context()->locale()->getSiteId();
		// @phpstan-ignore argument.type, argument.type
		$data = array_replace_recursive( $this->toArray( $view->item ), $view->param( 'download', [] ) );

		$view->downloadData = $data;
		$view->downloadBody = parent::create();

		return $this->render( $view );
	}


	/**
	 * Deletes a resource
	 *
	 * @return string|null HTML output
	 */
	public function delete() : ?string
	{
		parent::delete();

		$item = $this->view()->item;
		$fs = $this->context()->fs( 'fs-secure' );

		foreach( $item->getListItems( 'attribute', 'hidden', 'download', false ) as $listItem )
		{
			$refItem = $listItem->getRefItem();

			if( $refItem !== null && $refItem->getCode() != '' && $fs->has( (string) $refItem->getCode() ) ) {
				$fs->rm( (string) $refItem->getCode() );
			}

			$item->deleteListItem( 'attribute', $listItem, $refItem );
		}

		return null;
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null HTML output
	 */
	public function get() : ?string
	{
		$view = $this->object()->data( $this->view() );
		// @phpstan-ignore argument.type
		$view->downloadData = $this->toArray( $view->item );
		$view->downloadBody = parent::get();

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

		// @phpstan-ignore argument.type
		$this->fromArray( $view->item, (array) $view->param( 'download', [] ) );
		$view->downloadBody = parent::save();

		return null;
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( string $type, ?string $name = null ) : \Aimeos\Admin\JQAdm\Iface
	{
		/** admin/jqadm/product/download/decorators/excludes
		 * Excludes decorators added by the "common" option from the product JQAdm client
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
		 *  admin/jqadm/product/download/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.03
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/download/decorators/global
		 * @see admin/jqadm/product/download/decorators/local
		 */

		/** admin/jqadm/product/download/decorators/global
		 * Adds a list of globally available decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/download/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.03
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/download/decorators/excludes
		 * @see admin/jqadm/product/download/decorators/local
		 */

		/** admin/jqadm/product/download/decorators/local
		 * Adds a list of local decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Product\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/download/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.03
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/download/decorators/excludes
		 * @see admin/jqadm/product/download/decorators/global
		 */
		return $this->createSubClient( 'product/download/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/product/download/subparts
		 * List of JQAdm sub-clients rendered within the product download section
		 *
		 * The output of the frontend is composed of the code generated by the JQAdm
		 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
		 * that are responsible for rendering certain sub-parts of the output. The
		 * sub-clients can contain JQAdm clients themselves and therefore a
		 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
		 * the output that is placed inside the container of its parent.
		 *
		 * At first, always the JQAdm code generated by the parent is printed, then
		 * the JQAdm code of its sub-clients. The order of the JQAdm sub-clients
		 * determines the order of the output of these sub-clients inside the parent
		 * container. If the configured list of clients is
		 *
		 *  array( "subclient1", "subclient2" )
		 *
		 * you can easily change the order of the output by reordering the subparts:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
		 *
		 * You can also remove one or more parts if they shouldn't be rendered:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
		 *
		 * As the clients only generates structural JQAdm, the layout defined via CSS
		 * should support adding, removing or reordering content by a fluid like
		 * design.
		 *
		 * @param array List of sub-client names
		 * @since 2016.03
		 */
		return (array) $this->context()->config()->get( 'admin/jqadm/product/download/subparts', [] );
	}


	/**
	 * Returns the sanitized file extension of the uploaded file
	 *
	 * @param \Psr\Http\Message\UploadedFileInterface $file Uploaded file
	 * @return string Lower case file extension without dot or empty string if none is available
	 * @throws \Aimeos\Admin\JQAdm\Exception If the file extension is not allowed
	 */
	protected function extension( \Psr\Http\Message\UploadedFileInterface $file ) : string
	{
		$ext = strtolower( pathinfo( (string) $file->getClientFilename(), PATHINFO_EXTENSION ) );
		$ext = (string) preg_replace( '/[^a-z0-9]/', '', $ext );

		/** admin/jqadm/product/download/denied-extensions
		 * List of file extensions which are not allowed for uploaded download files
		 *
		 * Downloadable files are stored in the "fs-secure" file system. If this
		 * file system is located below the document root of the web server, files
		 * with these extensions could be executed by the web server (e.g. PHP files)
		 * or rendered by the browser (e.g. HTML or SVG files) if requested directly.
		 * Uploads of files with one of these extensions are rejected.
		 *
		 * @param array List of lower case file extensions without dot
		 * @since 2026.10
		 */
		$default = [
			'asp', 'aspx', 'cgi', 'htaccess', 'htm', 'html', 'inc', 'jsp', 'jspx', 'phar', 'php', 'php3', 'php4',
			'php5', 'php6', 'php7', 'php8', 'phps', 'pht', 'phtm', 'phtml', 'pl', 'py', 'shtml', 'svg', 'svgz', 'xhtml'
		];
		$denied = (array) $this->context()->config()->get( 'admin/jqadm/product/download/denied-extensions', $default );

		if( in_array( $ext, $denied, true ) )
		{
			$msg = $this->context()->translate( 'admin', 'Uploading files with extension "%1$s" is not allowed' );
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( $msg, $ext ) );
		}

		return $ext;
	}


	/**
	 * Stores the uploaded file in the "fs-secure" file system
	 *
	 * @param \Psr\Http\Message\UploadedFileInterface $file
	 * @param string|null $path Existing path of the file which should be overwritten
	 * @return string Path to the uploaded file
	 */
	protected function storeFile( \Psr\Http\Message\UploadedFileInterface $file, ?string $path = null ) : string
	{
		$fs = $this->context()->fs( 'fs-secure' );
		$ext = $this->extension( $file );

		// Overwrite existing files only if their name matches the generated names incl. the new extension
		if( $path === null || !preg_match( '#^[0-9a-f]/[0-9a-f]/[0-9a-f]{32}' . ( $ext !== '' ? '\.' . $ext : '' ) . '$#', $path ) )
		{
			$hash = md5( $file->getClientFilename() . microtime( true ) );
			$path = sprintf( '%s/%s/%s', $hash[0], $hash[1], $hash ) . ( $ext !== '' ? '.' . $ext : '' );

			if( !$fs->isdir( $hash[0] . '/' . $hash[1] ) ) {
				$fs->mkdir( $hash[0] . '/' . $hash[1] );
			}
		}

		// @phpstan-ignore argument.type
		$fs->writes( $path, $file->getStream()->detach() );

		return $path;
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object without referenced domain items
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Product\Item\Iface Modified product item
	 */
	protected function fromArray( \Aimeos\MShop\Product\Item\Iface $item, array $data ) : \Aimeos\MShop\Product\Item\Iface
	{
		$context = $this->context();
		$fs = $context->fs( 'fs-secure' );

		$prodManager = \Aimeos\MShop::create( $context, 'product' );
		$attrManager = \Aimeos\MShop::create( $context, 'attribute' );
		$listItems = $item->getListItems( 'attribute', 'hidden', 'download', false );

		if( $this->val( $data, 'attribute.label' ) != '' )
		{
			$listId = $this->val( $data, 'product.lists.id' );

			$litem = $listItems->pull( (string) $listId ) ?: $prodManager->createListItem()->setType( 'hidden' );
			$refItem = $litem->getRefItem() ?: $attrManager->create()->setType( 'download' );

			// The file path is generated when uploading the file and must not be set by the client
			unset( $data['attribute.code'] );

			$litem->fromArray( $data, true );
			$refItem->fromArray( $data, true );

			if( ( $file = $this->val( (array) $this->view()->request()->getUploadedFiles(), 'download/file' ) ) !== null
				&& $file->getError() === UPLOAD_ERR_OK
			) {
				$oldpath = $refItem->getCode();
				$path = ( $this->val( $data, 'overwrite' ) == 1 && $oldpath != '' ? $oldpath : null );
				// @phpstan-ignore argument.type
				$refItem->setCode( $newpath = $this->storeFile( $file, $path ) );

				if( $path !== null && $newpath !== $path && $fs->has( $path ) ) {
					$fs->rm( $path );
				}
			}

			// @phpstan-ignore argument.type, argument.type
			$item->addListItem( 'attribute', $litem, $refItem );
		}

		foreach( $listItems as $listItem )
		{
			$refItem = $listItem->getRefItem();

			if( $refItem !== null && ( $path = $refItem->getCode() ) != '' && $fs->has( (string) $path ) ) {
				$fs->rm( (string) $path );
			}

			// @phpstan-ignore argument.type, argument.type
			$item->deleteListItem( 'attribute', $listItem, $refItem );
		}

		return $item;
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object including referenced domain items
	 * @param bool $copy True if items should be copied, false if not
	 * @return array Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Product\Item\Iface $item, bool $copy = false ) : array
	{
		$siteId = $this->context()->locale()->getSiteId();
		$data = ['product.lists.siteid' => $siteId];

		foreach( $item->getListItems( 'attribute', 'hidden', 'download', false ) as $listItem )
		{
			if( $copy || ( $refItem = $listItem->getRefItem() ) === null ) {
				continue;
			}

			$list = $listItem->toArray( true ) + $refItem->toArray( true );

			foreach( $list as $key => $value ) {
				$data[$key] = $value;
			}

			try
			{
				$fs = $this->context()->fs( 'fs-secure' );

				$data['time'] = $fs->time( $data['attribute.code'] );
				$data['size'] = $fs->size( $data['attribute.code'] );
			}
			catch( \Exception $e ) { ; } // Show product even if file isn't available any more
		}

		return $data;
	}


	/**
	 * Returns the rendered template including the view data
	 *
	 * @param \Aimeos\Base\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	protected function render( \Aimeos\Base\View\Iface $view ) : string
	{
		/** admin/jqadm/product/download/template-item
		 * Relative path to the HTML body template of the download subpart for products.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in templates/admin/jqadm).
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
		 */
		$tplconf = 'admin/jqadm/product/download/template-item';
		$default = 'product/item-download';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
