<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2023
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Settings;

sprintf( 'settings' ); // for translation


/**
 * Default implementation of settings JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/settings/name
	 * Class name of the used account favorite client implementation
	 *
	 * Each default admin client can be replace by an alternative imlementation.
	 * To use this implementation, you have to set the last part of the class
	 * name as configuration value so the client factory knows which class it
	 * has to instantiate.
	 *
	 * For example, if the name of the default class is
	 *
	 *  \Aimeos\Admin\JQAdm\Settings\Standard
	 *
	 * and you want to replace it with your own version named
	 *
	 *  \Aimeos\Admin\JQAdm\Settings\Myfavorite
	 *
	 * then you have to set the this configuration option:
	 *
	 *  admin/jqadm/settings/name = Myfavorite
	 *
	 * The value is the last part of your own class name and it's case sensitive,
	 * so take care that the configuration value is exactly named like the last
	 * part of the class name.
	 *
	 * The allowed characters of the class name are A-Z, a-z and 0-9. No other
	 * characters are possible! You should always start the last part of the class
	 * name with an upper case character and continue only with lower case characters
	 * or numbers. Avoid chamel case names like "MyFavorite"!
	 *
	 * @param string Last part of the class name
	 * @since 2021.07
	 */


	/**
	 * Adds the required data used in the template
	 *
	 * @param \Aimeos\Base\View\Iface $view View object
	 * @return \Aimeos\Base\View\Iface View object with assigned parameters
	 */
	public function data( \Aimeos\Base\View\Iface $view ) : \Aimeos\Base\View\Iface
	{
		$view->themes = $this->context()->config()->get( 'client/html/themes', [] );
		$view->itemSubparts = $this->getSubClientNames();
		return $view;
	}


	/**
	 * Saves the data
	 *
	 * @return string|null HTML output
	 */
	public function save() : ?string
	{
		$view = $this->object()->data( $this->view() );
		$context = $this->context();

		$manager = \Aimeos\MShop::create( $context, 'locale/site' );
		$manager->begin();

		try
		{
			$view->item = $this->fromArray( $view->param( 'item', [] ) );
			$view->itemBody = parent::save();

			$manager->save( clone $view->item );
			$manager->commit();

			$params = $this->getClientParams();
			$params['site'] = $view->item->getCode();

			$context->session()->set( 'info', [$context->translate( 'admin', 'Item saved successfully' )] );

			$view->response()->withStatus( 302 );
			$view->response()->withHeader( 'Cache-Control', 'no-store' );
			$view->response()->withHeader( 'Location', $view->link( 'admin/jqadm/url/search', $params ) );

			return null;
		}
		catch( \Exception $e )
		{
			$manager->rollback();
			$this->report( $e, 'save' );
		}

		return $this->search();
	}


	/**
	 * Returns the settings root node
	 *
	 * @return string|null HTML output
	 */
	public function search() : ?string
	{
		$view = $this->object()->data( $this->view() );

		try
		{
			$view->item = $this->context()->locale()->getSiteItem();
			$view->itemData = array_replace_recursive( $this->toArray( $view->item ), $view->param( 'item', [] ) );
			$view->itemBody = parent::search();
		}
		catch( \Exception $e )
		{
			$this->report( $e, 'search' );
		}

		return $this->render( $view );
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
		/** admin/jqadm/settings/decorators/excludes
		 * Excludes decorators added by the "common" option from the settings JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "client/jqadm/common/decorators/default" before they are wrapped
		 * around the JQAdm client.
		 *
		 *  admin/jqadm/settings/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "client/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2021.07
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/settings/decorators/global
		 * @see admin/jqadm/settings/decorators/local
		 */

		/** admin/jqadm/settings/decorators/global
		 * Adds a list of globally available decorators only to the settings JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/settings/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2021.07
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/settings/decorators/excludes
		 * @see admin/jqadm/settings/decorators/local
		 */

		/** admin/jqadm/settings/decorators/local
		 * Adds a list of local decorators only to the settings JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Settings\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/settings/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Settings\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2021.07
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/settings/decorators/excludes
		 * @see admin/jqadm/settings/decorators/global
		 */
		return $this->createSubClient( 'settings/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/settings/subparts
		 * List of JQAdm sub-clients rendered within the settings section
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
		 * @since 2021.07
		 */
		return $this->context()->config()->get( 'admin/jqadm/settings/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Locale\Item\Site\Iface New settings item object
	 */
	protected function fromArray( array $data ) : \Aimeos\MShop\Locale\Item\Site\Iface
	{
		$item = $this->context()->locale()->getSiteItem();

		$config = $data['locale.site.config'] ?? [];
		$config['resource']['email']['from-name'] = $data['locale.site.label'] ?? '';

		$files = (array) $this->view()->request()->getUploadedFiles();

		$item = $this->fromArrayIcon( $item, $files );
		$item = $this->fromArrayLogo( $item, $files );

		return $item->setConfig( array_replace_recursive( $item->getConfig(), $config ) )
			->setTheme( $data['locale.site.theme'] ?? '' )
			->setLabel( $data['locale.site.label'] ?? '' )
			->setCode( $data['locale.site.code'] ?? '' );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Locale\Item\Site\Iface Site item object
	 * @param array $files Uploaded files
	 * @return \Aimeos\MShop\Locale\Item\Site\Iface New settings item object
	 */
	protected function fromArrayIcon( \Aimeos\MShop\Locale\Item\Site\Iface $item, array $files ) : \Aimeos\MShop\Locale\Item\Site\Iface
	{
		$file = $this->val( $files, 'media/icon' );

		if( $file && $file->getError() === UPLOAD_ERR_OK )
		{
			$context = $this->context();
			$siteId = $context->locale()->getSiteId();

			$options = $context->config()->get( 'controller/common/media/options', [] );
			$image = \Aimeos\MW\Media\Factory::get( $file->getStream(), $options );

			if( !in_array( $image->getMimetype(), ['image/webp', 'image/jpeg', 'image/png', 'image/gif'] ) )
			{
				$msg = $context->i18n()->dt( 'admin', 'Only .jpg, .png and .gif are allowed for icons' );
				throw new \Aimeos\Admin\JQAdm\Exception( $msg );
			}

			$filepath = $siteId . 'd/icon.' . $this->extension( $image->getMimetype() );
			$context->fs( 'fs-media' )->write( $filepath, $image->save() );

			$item->setIcon( $filepath );
		}

		return $item;
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Locale\Item\Site\Iface Site item object
	 * @param array $files Uploaded files
	 * @return \Aimeos\MShop\Locale\Item\Site\Iface New settings item object
	 */
	protected function fromArrayLogo( \Aimeos\MShop\Locale\Item\Site\Iface $item, array $files ) : \Aimeos\MShop\Locale\Item\Site\Iface
	{
		$file = $this->val( $files, 'media/logo' );

		if( $file && $file->getError() === UPLOAD_ERR_OK )
		{
			$context = $this->context();
			$siteId = $context->locale()->getSiteId();

			$options = $context->config()->get( 'controller/common/media/options', [] );
			$image = \Aimeos\MW\Media\Factory::get( $file->getStream(), $options );
			$filepaths = [];

			if( !in_array( $image->getMimetype(), ['image/webp', 'image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'] ) )
			{
				$msg = $context->i18n()->dt( 'admin', 'Only .jpg, .png, .gif or .svg are allowed for logos' );
				throw new \Aimeos\Admin\JQAdm\Exception( $msg );
			}

			foreach( $context->config()->get( 'admin/jqadm/settings/logo-size', ['maxwidth' => null, 'maxheight' => null] ) as $size )
			{
				$w = $size['maxwidth'] ?? null;
				$h = $size['maxheight'] ?? null;

				$filepath = $siteId . 'd/logo' . $w . '.' . $this->extension( $image->getMimetype() );
				$context->fs( 'fs-media' )->write( $filepath, $image->scale( $w, $h )->save() );
				$filepaths[(int) $w ?: 1] = $filepath;
			}

			$item->setLogos( $filepaths );
		}

		return $item;
	}


	/**
	 * Returns the file extension for the passed mime type
	 *
	 * @param string $mimetype Mime type, e.g. "image/svg+xml"
	 * @return string File extension
	 */
	protected function extension( string $mimetype ) : string
	{
		switch( $mimetype )
		{
			case 'image/webp': return 'webp';
			case 'image/jpeg': return 'jpg';
			case 'image/png': return 'png';
			case 'image/gif': return 'gif';
			case 'image/svg+xml': return 'svg';
		}

		return 'txt';
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Locale\Item\Site\Iface $item Settings item object
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Locale\Item\Site\Iface $item, bool $copy = false ) : array
	{
		$context = $this->context();

		if( $context->user() && !$item->getConfigValue( 'resource/email/from-email' ) )
		{
			$user = \Aimeos\MShop::create( $context, 'customer' )->get( $context->user() );
			$item->setConfigValue( 'resource/email/from-email', $user->getPaymentAddress()->getEmail() );
		}

		return [
			'locale.site.code' => $item->getCode(),
			'locale.site.icon' => $item->getIcon(),
			'locale.site.logo' => $item->getLogos(),
			'locale.site.label' => $item->getLabel(),
			'locale.site.theme' => $item->getTheme(),
			'locale.site.config' => $item->getConfig(),
			'locale.site.refid' => $item->getRefId(),
			'locale.site.ctime' => $item->getTimeCreated(),
			'locale.site.mtime' => $item->getTimeModified(),
			'locale.site.editor' => $item->editor(),
		];
	}


	/**
	 * Returns the rendered template including the view data
	 *
	 * @param \Aimeos\Base\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	protected function render( \Aimeos\Base\View\Iface $view ) : string
	{
		/** admin/jqadm/settings/template-item
		 * Relative path to the HTML body template for the settings item.
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
		 * @since 2021.07
		 */
		$tplconf = 'admin/jqadm/settings/template-item';
		$default = 'settings/item';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
