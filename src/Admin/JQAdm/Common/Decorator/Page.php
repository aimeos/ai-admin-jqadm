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

		$siteManager = \Aimeos\MShop::create( $context, 'locale/site' );
		$langManager = \Aimeos\MShop::create( $context, 'locale/language' );
		$siteItem = $this->site( $siteManager, (string) $view->param( 'site', 'default' ) );

		$view->pageInfo = $context->session()->pull( 'info', [] );
		$view->pageI18nList = $this->getAimeos()->getI18nList( 'admin' );
		$view->pageLangItems = $langManager->search( $langManager->filter( true ) );
		$view->pageNumberStep = 1 / pow( 10, (float) $context->config()->get( 'mshop/price/precision', 2 ) );
		$view->pageSiteTop = $this->top();
		$view->pageSitePath = $siteManager->getPath( $siteItem->getId() );
		$view->pageSiteItem = $siteItem;
		$view->pageUserSiteid = $context->user()?->getSiteId();

		$this->getClient()->setView( $view );
		return $this;
	}


	/**
	 * Returns the site the user is allowed to manage
	 *
	 * @param \Aimeos\MShop\Common\Manager\Iface $manager Locale site manager
	 * @param string $code Unique code of the site
	 * @return \Aimeos\MShop\Locale\Item\Site\Iface Site item
	 * @throws \Aimeos\Admin\JQAdm\Exception If the site doesn't exist or isn't within the site tree of the user
	 */
	protected function site( \Aimeos\MShop\Common\Manager\Iface $manager, string $code ) : \Aimeos\MShop\Locale\Item\Site\Iface
	{
		$context = $this->context();
		$filter = $manager->filter()->add( 'locale.site.code', '==', $code )->order( 'locale.site.siteid' )->slice( 0, 1 );
		// limited to the site tree of the user if not a super user
		\Aimeos\Admin\JQAdm::restrict( $context, $manager, $filter );

		// @phpstan-ignore return.type
		return $manager->search( $filter )->first( new \Aimeos\Admin\JQAdm\Exception( sprintf( 'Site "%1$s" not found', $code ), 404 ) );
	}


	/**
	 * Returns the condition for the top-level nodes of the site tree
	 *
	 * @return array|null Condition for searching the top-level sites or NULL for the root sites if all sites are allowed
	 */
	protected function top() : ?array
	{
		// the site tree of users who aren't allowed to access all sites starts at their own site
		if( ( $root = \Aimeos\MShop\Locale\SiteScope::root( $this->context() ) ) !== null ) {
			return ['==' => ['locale.site.siteid' => $root]];
		}

		return null;
	}
}
