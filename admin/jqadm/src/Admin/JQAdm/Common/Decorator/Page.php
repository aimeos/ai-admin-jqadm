<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Common\Decorator;


/**
 * Page decorator for JQAdm clients
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Page extends Base
{
	/**
	 * Sets the view object and adds the required page data to the view
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the admin output
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	public function setView( \Aimeos\MW\View\Iface $view )
	{
		parent::setView( $view );

		// set first to be able to show errors occuring afterwards
		$view->pageParams = $this->getClientParams();

		$context = $this->getContext();
		$aimeos = new \Aimeos\Bootstrap( array( dirname( __DIR__, 7 ) ) );
		$customerManager = \Aimeos\MShop\Factory::createManager( $context, 'customer' );
		$siteManager = \Aimeos\MShop\Factory::createManager( $context, 'locale/site' );

		$view->pageLangList = $aimeos->getI18nList( 'admin' );

		try
		{
			$view->pageUser = $customerManager->getItem( $context->getUserId() );
			$siteid = $view->pageUser->getSiteId();
		}
		catch( \Exception $e )
		{
			$siteid = $siteManager->findItem( $view->param( 'site', 'default' ) )->getId();
		}

		$view->pageSite = $siteManager->getTree( $siteid, [], \Aimeos\MW\Tree\Manager\Base::LEVEL_TREE );

		$this->getClient()->setView( $view );
		return $this;
	}
}
