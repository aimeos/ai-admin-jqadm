<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2017
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

		$siteManager = \Aimeos\MShop\Factory::createManager( $context, 'locale/site' );
		$langManager = \Aimeos\MShop\Factory::createManager( $context, 'locale/language' );
		$customerManager = \Aimeos\MShop\Factory::createManager( $context, 'customer' );

		$siteItem = $siteManager->findItem( $view->param( 'site', 'default' ) );

		try {
			$siteid = $customerManager->getItem( $context->getUserId() )->getSiteId();
		} catch( \Exception $e ) {
			$siteid = $siteItem->getSiteId();
		}

		if( $view->access( ['admin', 'super'] ) ) {
			$level = \Aimeos\MW\Tree\Manager\Base::LEVEL_TREE;
		} else {
			$level = \Aimeos\MW\Tree\Manager\Base::LEVEL_ONE;
		}

		$view->pageI18nList = $this->getAimeos()->getI18nList( 'admin' );
		$view->pageLangItems = $langManager->searchItems( $langManager->createSearch( true ) );
		$view->pageSiteTree = $siteManager->getTree( $siteid, [], $level );
		$view->pageSitePath = $siteManager->getPath( $siteItem->getId() );
		$view->pageSiteItem = $siteItem;

		$this->getClient()->setView( $view );
		return $this;
	}
}
