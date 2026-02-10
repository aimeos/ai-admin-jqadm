<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2026
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
	 * @param \Aimeos\Base\View\Iface $view The view object which generates the admin output
	 * @return \Aimeos\Admin\JQAdm\Iface Reference to this object for fluent calls
	 */
	public function setView( \Aimeos\Base\View\Iface $view ) : \Aimeos\Admin\JQAdm\Iface
	{
		parent::setView( $view );

		// set first to be able to show errors occuring afterwards
		$view->pageParams = $this->getClientParams();
		$context = $this->context();

		$site = $view->param( 'site', 'default' );
		$siteid = $context->user()?->getSiteId();

		$siteManager = \Aimeos\MShop::create( $context, 'locale/site' );
		$langManager = \Aimeos\MShop::create( $context, 'locale/language' );

		if( $siteid )
		{
			$filter = $siteManager->filter();
			$filter->add( $filter->and( [
				$filter->is( 'locale.site.code', '==', $site ),
				$filter->is( 'locale.site.siteid', $view->access( ['admin'] ) ? '=~' : '==', $siteid )
			] ) )->order( 'locale.site.siteid' )->slice( 0, 1 );

			$siteItem = $siteManager->search( $filter )->first();
		}
		else
		{
			$siteItem = $siteManager->find( $site );
		}

		if( !$siteItem ) {
			throw new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Site "%1$s" not found', $site ), 404 );
		}


		$view->pageInfo = $context->session()->pull( 'info', [] );
		$view->pageI18nList = $this->getAimeos()->getI18nList( 'admin' );
		$view->pageLangItems = $langManager->search( $langManager->filter( true ) );
		$view->pageNumberStep = 1 / pow( 10, $context->config()->get( 'mshop/price/precision', 2 ) );
		$view->pageSitePath = $siteManager->getPath( $siteItem->getId() );
		$view->pageSiteItem = $siteItem;
		$view->pageUserSiteid = $siteid;

		$this->getClient()->setView( $view );
		return $this;
	}
}
