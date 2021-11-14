<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Attribute\Media;

sprintf( 'media' ); // for translation


/**
 * Default implementation of attribute media JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/attribute/media/name
	 * Name of the media subpart used by the JQAdm attribute implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Attribute\Media\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2017.07
	 * @category Developer
	 */


	/**
	 * Adds the required data used in the attribute template
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @return \Aimeos\MW\View\Iface View object with assigned parameters
	 */
	public function data( \Aimeos\MW\View\Iface $view ) : \Aimeos\MW\View\Iface
	{
		$context = $this->getContext();

		$typeManager = \Aimeos\MShop::create( $context, 'media/type' );
		$listTypeManager = \Aimeos\MShop::create( $context, 'attribute/lists/type' );

		$search = $typeManager->filter( true )->slice( 0, 10000 );
		$search->setConditions( $search->compare( '==', 'media.type.domain', 'attribute' ) );
		$search->setSortations( [$search->sort( '+', 'media.type.position' )] );

		$listSearch = $listTypeManager->filter( true )->slice( 0, 10000 );
		$listSearch->setConditions( $listSearch->compare( '==', 'attribute.lists.type.domain', 'media' ) );
		$listSearch->setSortations( [$listSearch->sort( '+', 'attribute.lists.type.position' )] );

		$view->mediaListTypes = $listTypeManager->search( $listSearch );
		$view->mediaTypes = $typeManager->search( $search );

		return $view;
	}


	/**
	 * Copies a resource
	 *
	 * @return string|null HTML output
	 */
	public function copy() : ?string
	{
		$view = $this->getObject()->data( $this->view() );

		$view->mediaData = $this->toArray( $view->item, true );
		$view->mediaBody = parent::copy();

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
		$siteid = $this->getContext()->getLocale()->getSiteId();

		$itemData = $this->toArray( $view->item );
		$data = array_replace_recursive( $itemData, $view->param( 'media', [] ) );

		foreach( $data as $idx => $entry )
		{
			$data[$idx]['media.siteid'] = $siteid;
			$data[$idx]['media.url'] = $entry['media.url'] ?? null;
			$data[$idx]['media.preview'] = $entry['media.preview'] ?? null;
			$data[$idx]['attribute.lists.siteid'] = $siteid;
		}

		$view->mediaData = $data;
		$view->mediaBody = parent::create();

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
		$this->deleteMediaItems( $item, $item->getListItems( 'media', null, null, false )->toArray() );

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

		$view->mediaData = $this->toArray( $view->item );
		$view->mediaBody = parent::get();

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

		$view->item = $this->fromArray( $view->item, $view->param( 'media', [] ) );
		$view->mediaBody = parent::save();

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
		/** admin/jqadm/attribute/media/decorators/excludes
		 * Excludes decorators added by the "common" option from the attribute JQAdm client
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
		 *  admin/jqadm/attribute/media/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/attribute/media/decorators/global
		 * @see admin/jqadm/attribute/media/decorators/local
		 */

		/** admin/jqadm/attribute/media/decorators/global
		 * Adds a list of globally available decorators only to the attribute JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/attribute/media/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/attribute/media/decorators/excludes
		 * @see admin/jqadm/attribute/media/decorators/local
		 */

		/** admin/jqadm/attribute/media/decorators/local
		 * Adds a list of local decorators only to the attribute JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Attribute\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/attribute/media/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Attribute\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/attribute/media/decorators/excludes
		 * @see admin/jqadm/attribute/media/decorators/global
		 */
		return $this->createSubClient( 'attribute/media/' . $type, $name );
	}


	/**
	 * Removes the media reference and the media item if not shared
	 *
	 * @param \Aimeos\MShop\Attribute\Item\Iface $item Attribute item including media reference
	 * @param array $listItems Media list items to be removed
	 * @return \Aimeos\MShop\Attribute\Item\Iface Modified attribute item
	 */
	protected function deleteMediaItems( \Aimeos\MShop\Attribute\Item\Iface $item, array $listItems ) : \Aimeos\MShop\Attribute\Item\Iface
	{
		$context = $this->getContext();
		$cntl = \Aimeos\Controller\Common\Media\Factory::create( $context );
		$manager = \Aimeos\MShop::create( $context, 'attribute' );
		$search = $manager->filter();

		foreach( $listItems as $listItem )
		{
			$func = $search->make( 'attribute:has', ['media', $listItem->getType(), $listItem->getRefId()] );
			$search->setConditions( $search->compare( '!=', $func, null ) );
			$items = $manager->search( $search );
			$refItem = null;

			if( count( $items ) === 1 && ( $refItem = $listItem->getRefItem() ) !== null ) {
				$cntl->delete( $refItem );
			}

			$item->deleteListItem( 'media', $listItem, $refItem );
		}

		return $item;
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/attribute/media/subparts
		 * List of JQAdm sub-clients rendered within the attribute media section
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
		 * @since 2017.07
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/attribute/media/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Attribute\Item\Iface $item Attribute item object without referenced domain items
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Attribute\Item\Iface Modified attribute item
	 */
	protected function fromArray( \Aimeos\MShop\Attribute\Item\Iface $item, array $data ) : \Aimeos\MShop\Attribute\Item\Iface
	{
		$context = $this->getContext();

		$mediaManager = \Aimeos\MShop::create( $context, 'media' );
		$listManager = \Aimeos\MShop::create( $context, 'attribute/lists' );
		$cntl = \Aimeos\Controller\Common\Media\Factory::create( $context );

		$listItems = $item->getListItems( 'media', null, null, false );
		$files = (array) $this->view()->request()->getUploadedFiles();

		foreach( $data as $idx => $entry )
		{
			$listType = $entry['attribute.lists.type'] ?? 'default';

			if( ( $listItem = $item->getListItem( 'media', $listType, $entry['media.id'] ?? null, false ) ) === null ) {
				$listItem = $listManager->create();
			}

			if( ( $refItem = $listItem->getRefItem() ) === null ) {
				$refItem = $mediaManager->create();
			}

			$refItem->fromArray( $entry, true )->setDomain( 'attribute' );
			$file = $this->getValue( $files, 'media/' . $idx . '/file' );
			$preview = $this->getValue( $files, 'media/' . $idx . '/preview' );

			if( $refItem->getId() === null && $refItem->getUrl() !== '' ) {
				$refItem = $cntl->copy( $refItem );
			}

			if( $file && $file->getError() !== UPLOAD_ERR_NO_FILE )
			{
				$refItem = $cntl->add( $refItem, $file );

				if( $preview && $preview->getError() !== UPLOAD_ERR_NO_FILE ) {
					$refItem = $cntl->addPreview( $refItem, $preview );
				}
			}

			$listItem->fromArray( $entry, true )->setPosition( $idx )->setConfig( [] );

			foreach( (array) $this->getValue( $entry, 'config', [] ) as $cfg )
			{
				if( ( $key = trim( $cfg['key'] ?? '' ) ) !== '' ) {
					$listItem->setConfigValue( $key, trim( $cfg['val'] ?? '' ) );
				}
			}

			$item->addListItem( 'media', $listItem, $refItem );
			unset( $listItems[$listItem->getId()] );
		}

		return $this->deleteMediaItems( $item, $listItems->toArray() );
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Attribute\Item\Iface $item Attribute item object including referenced domain items
	 * @param bool $copy True if items should be copied, false if not
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Attribute\Item\Iface $item, bool $copy = false ) : array
	{
		$data = [];
		$siteId = $this->getContext()->getLocale()->getSiteId();

		foreach( $item->getListItems( 'media', null, null, false ) as $listItem )
		{
			if( ( $refItem = $listItem->getRefItem() ) === null ) {
				continue;
			}

			$list = $listItem->toArray( true ) + $refItem->toArray( true );

			if( $copy === true )
			{
				$list['attribute.lists.siteid'] = $siteId;
				$list['attribute.lists.id'] = '';
				$list['media.siteid'] = $siteId;
				$list['media.id'] = null;
			}

			$list['attribute.lists.datestart'] = str_replace( ' ', 'T', $list['attribute.lists.datestart'] );
			$list['attribute.lists.dateend'] = str_replace( ' ', 'T', $list['attribute.lists.dateend'] );
			$list['config'] = [];

			foreach( $listItem->getConfig() as $key => $value ) {
				$list['config'][] = ['key' => $key, 'val' => $value];
			}

			$data[] = $list;
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
		/** admin/jqadm/attribute/media/template-item
		 * Relative path to the HTML body template of the media subpart for attributes.
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
		 * @since 2017.07
		 * @category Developer
		 */
		$tplconf = 'admin/jqadm/attribute/media/template-item';
		$default = 'attribute/item-media-standard';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
