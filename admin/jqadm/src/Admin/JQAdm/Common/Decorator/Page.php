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

		$ext = dirname( dirname( dirname( dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) ) ) );
		$aimeos = new \Aimeos\Bootstrap( array( $ext ) );
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

		$view->pageLanguages = $langManager->searchItems( $langManager->createSearch( true ) );
		$view->pageSite = $siteManager->getTree( $siteid, [], \Aimeos\MW\Tree\Manager\Base::LEVEL_TREE );
		$view->pageSitePath = $siteManager->getPath( $siteItem->getId() );
		$view->pageLangList = $aimeos->getI18nList( 'admin' );

		$this->getClient()->setView( $view );
		return $this;
	}
}
