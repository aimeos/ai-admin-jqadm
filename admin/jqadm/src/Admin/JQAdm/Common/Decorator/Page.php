<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
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
	public function setView( \Aimeos\MW\View\Iface $view ) : \Aimeos\Admin\JQAdm\Iface
	{
		parent::setView( $view );

		// set first to be able to show errors occuring afterwards
		$view->pageParams = $this->getClientParams();
		$context = $this->getContext();

		$siteManager = \Aimeos\MShop::create( $context, 'locale/site' );
		$langManager = \Aimeos\MShop::create( $context, 'locale/language' );
		$customerManager = \Aimeos\MShop::create( $context, 'customer' );

		try
		{
			if( ( $custid = $context->getUserId() ) !== null
				&& ( $siteid = $customerManager->get( $custid )->getSiteId() ) !== null
			) {
				$search = $siteManager->filter()->slice( 0, 1 );
				$search->setConditions( $search->compare( '==', 'locale.site.siteid', $siteid ) );
				$view->pageSiteItem = $siteManager->search( $search )->first();
			}
		}
		catch( \Exception $e ) {}

		if( !isset( $view->pageSiteItem ) ) {
			$view->pageSiteItem = $siteManager->find( $view->param( 'site', 'default' ) );
		}

		if( $view->access( ['super'] ) )
		{
			$search = $siteManager->filter()->add( ['locale.site.level' => 0] )
				->order( 'locale.site.id' )->slice( 0, 25 );

			$view->pageSiteList = $siteManager->search( $search );
		}

		$view->pageInfo = $context->getSession()->pull( 'info', [] );
		$view->pageI18nList = $this->getAimeos()->getI18nList( 'admin' );
		$view->pageLangItems = $langManager->search( $langManager->filter( true ) );

		$this->getClient()->setView( $view );
		return $this;
	}
}
