<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
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
		$view = $this->getObject()->data( $this->view() );
		$data['product.lists.siteid'] = $this->getContext()->getLocale()->getSiteId();
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
		$fs = $this->getContext()->getFilesystemManager()->get( 'fs-secure' );

		foreach( $item->getListItems( 'attribute', 'hidden', 'download', false ) as $listItem )
		{
			$refItem = $listItem->getRefItem();

			if( $refItem !== null && $refItem->getCode() != '' && $fs->has( $refItem->getCode() ) ) {
				$fs->rm( $refItem->getCode() );
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
		$view = $this->getObject()->data( $this->view() );
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

		$this->fromArray( $view->item, $view->param( 'download', [] ) );
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
	public function getSubClient( string $type, string $name = null ) : \Aimeos\Admin\JQAdm\Iface
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
		 * @category Developer
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
		 * @category Developer
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
		 * @category Developer
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
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/product/download/subparts', [] );
	}


	/**
	 * Stores the uploaded file in the "fs-secure" file system
	 *
	 * @param \Psr\Http\Message\UploadedFileInterface $file
	 * @param string|null $path Path the file should be stored at
	 * @return string Path to the uploaded file
	 */
	protected function storeFile( \Psr\Http\Message\UploadedFileInterface $file, string $path = null ) : string
	{
		$fs = $this->getContext()->getFilesystemManager()->get( 'fs-secure' );

		if( $path === null )
		{
			$ext = pathinfo( $file->getClientFilename(), PATHINFO_EXTENSION );
			$hash = md5( $file->getClientFilename() . microtime( true ) );
			$path = sprintf( '%s/%s/%s.%s', $hash[0], $hash[1], $hash, $ext );

			if( !$fs->isdir( $hash[0] . '/' . $hash[1] ) ) {
				$fs->mkdir( $hash[0] . '/' . $hash[1] );
			}
		}

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
		$context = $this->getContext();
		$fs = $context->getFilesystemManager()->get( 'fs-secure' );

		$prodManager = \Aimeos\MShop::create( $context, 'product' );
		$attrManager = \Aimeos\MShop::create( $context, 'attribute' );
		$listItems = $item->getListItems( 'attribute', 'hidden', 'download', false );

		if( $this->getValue( $data, 'attribute.label' ) != '' )
		{
			$listId = $this->getValue( $data, 'product.lists.id' );

			if( isset( $listItems[$listId] ) ) {
				$litem = $listItems[$listId]; unset( $listItems[$listId] );
			} else {
				$litem = $prodManager->createListItem()->setType( 'hidden' );
			}

			if( ( $refItem = $litem->getRefItem() ) === null ) {
				$refItem = $attrManager->create()->setType( 'download' );
			}

			$litem->fromArray( $data, true );
			$refItem->fromArray( $data, true );

			if( ( $file = $this->getValue( (array) $this->view()->request()->getUploadedFiles(), 'download/file' ) ) !== null
				&& $file->getError() === UPLOAD_ERR_OK
			) {
				$path = ( $this->getValue( $data, 'overwrite' ) == 1 ? $refItem->getCode() : null );
				$refItem->setCode( $this->storeFile( $file, $path ) );
			}

			$item->addListItem( 'attribute', $litem, $refItem );
		}

		foreach( $listItems as $listItem )
		{
			$refItem = $listItem->getRefItem();

			if( $refItem !== null && ( $path = $refItem->getCode() ) != '' && $fs->has( $path ) ) {
				$fs->rm( $path );
			}

			$item->deleteListItem( 'attribute', $listItem, $refItem );
		}

		return $item;
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object including referenced domain items
	 * @param bool $copy True if items should be copied, false if not
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Product\Item\Iface $item, bool $copy = false ) : array
	{
		$siteId = $this->getContext()->getLocale()->getSiteId();
		$data = ['product.lists.siteid' => $siteId];

		foreach( $item->getListItems( 'attribute', 'hidden', 'download', false ) as $listItem )
		{
			if( ( $refItem = $listItem->getRefItem() ) === null ) {
				continue;
			}

			$list = $listItem->toArray( true ) + $refItem->toArray( true );

			if( $copy === true ) {
				$list['product.lists.id'] = '';
			}

			foreach( $list as $key => $value ) {
				$data[$key] = $value;
			}

			try
			{
				$fs = $this->getContext()->getFilesystemManager()->get( 'fs-secure' );

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
	 * @param \Aimeos\MW\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	protected function render( \Aimeos\MW\View\Iface $view ) : string
	{
		/** admin/jqadm/product/download/template-item
		 * Relative path to the HTML body template of the download subpart for products.
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
		$tplconf = 'admin/jqadm/product/download/template-item';
		$default = 'product/item-download-standard';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
