<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2020
 */

$enc = $this->encoder();

$target = $this->request()->getTarget();
$searchTarget = $this->config( 'admin/jqadm/url/search/target' );
$cntl = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/search/action', 'search' );
$config = $this->config( 'admin/jqadm/url/search/config', [] );


/** admin/jsonadm/url/options/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.04
 * @category Developer
 * @see admin/jsonadm/url/options/controller
 * @see admin/jsonadm/url/options/action
 * @see admin/jsonadm/url/options/config
 */
$jsonTarget = $this->config( 'admin/jsonadm/url/options/target' );

/** admin/jsonadm/url/options/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @category Developer
 * @see admin/jsonadm/url/options/target
 * @see admin/jsonadm/url/options/action
 * @see admin/jsonadm/url/options/config
 */
$jsonCntl = $this->config( 'admin/jsonadm/url/options/controller', 'Jsonadm' );

/** admin/jsonadm/url/options/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @category Developer
 * @see admin/jsonadm/url/options/target
 * @see admin/jsonadm/url/options/controller
 * @see admin/jsonadm/url/options/config
 */
$jsonAction = $this->config( 'admin/jsonadm/url/options/action', 'options' );

/** admin/jsonadm/url/options/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  admin/jsonadm/url/options/config = array( 'absoluteUri' => true )
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2016.04
 * @category Developer
 * @see admin/jsonadm/url/options/target
 * @see admin/jsonadm/url/options/controller
 * @see admin/jsonadm/url/options/action
 */
$jsonConfig = $this->config( 'admin/jsonadm/url/options/config', [] );


/** admin/jqadm/navbar
 * List of JQAdm client names shown in the navigation bar of the admin interface
 *
 * You can add, remove or reorder the links in the navigation bar by
 * setting a new list of client resource names.
 * In the configuration files of extensions, you should only add entries using
 * one of these lines:
 *
 *  'myclient' => 'myclient',
 *  'myclient-subclient' => 'myclient/subclient',
 *
 * The key of the new client must be unique in the extension configuration so
 * it's not overwritten by other extensions. Don't use slashes in keys (/)
 * because they are interpreted as keys of sub-arrays in the configuration.
 *
 * @param array List of resource client names
 * @since 2017.10
 * @category Developer
 * @see admin/jqadm/navbar-limit
 */
$navlist = $this->config( 'admin/jqadm/navbar', [] );

/** admin/jqadm/navbar-limit
 * Number of JQAdm client links in the navigation bar shown by default
 *
 * The navigation bar is divided into the basic and advanced section.
 * All admin client links in the basic section are always shown
 * while the links in the advanced section are hidden by default. The
 * entries in the navigation bar are defined by admin/jqadm/navbar. Using
 * this setting you can change how many links are always shown.
 *
 * @param integer Number of client resource links
 * @since 2017.10
 * @category Developer
 * @see admin/jqadm/navbar
 */
$navlimit = $this->config( 'admin/jqadm/navbar-limit', 6 );


$navfirst = reset( $navlist );
if( is_array( $navfirst ) ) {
	$navfirst = key( $navlist );
}

$resource = $this->param( 'resource', 'dashboard' );
$site = $this->param( 'site', 'default' );
$lang = $this->param( 'lang' );

$params = ['resource' => $resource, 'site' => $site];
$extParams = ['site' => $site];

if( $lang ) {
	$params['lang'] = $extParams['lang'] = $lang;
}

$title = $this->translate( 'admin', '%1$s (Ctrl+Alt+%2$s)' );
$infoMsgs = array_merge( $this->get( 'pageInfo', [] ), $this->get( 'info', [] ) );

?>
<div class="aimeos" lang="<?= $this->param( 'lang' ); ?>" data-url="<?= $enc->attr( $this->url( $jsonTarget, $jsonCntl, $jsonAction, array( 'site' => $site ), [], $jsonConfig ) ); ?>">

	<div class="container-fluid">
		<div class="row flex-nowrap">

			<nav class="main-sidebar">
				<div class="sidebar-wrapper">

					<a class="logo" target="_blank" href="https://aimeos.org/update/?type=<?= $this->get( 'aimeosType' ) ?>&version=<?= $this->get( 'aimeosVersion' ) ?>">
						<img src="https://aimeos.org/check/?type=<?= $this->get( 'aimeosType' ) ?>&version=<?= $this->get( 'aimeosVersion' ) ?>&extensions=<?= $this->get( 'aimeosExtensions' ) ?>" alt="Aimeos update" title="Aimeos update">
					</a>

					<ul class="sidebar-menu basic">

						<?php if( ( $this->get( 'pageSiteList', map() )->count() > 1 || !$this->pageSiteTree->getChildren()->isEmpty() || $this->get( 'pageSitePath', map() )->count() > 1 ) && $this->access( $this->config( 'admin/jqadm/resource/site/groups', [] ) ) ) : ?>
							<li class="site treeview">
								<a href="#">
									<i class="icon"></i>
									<span class="title"><?= $enc->html( $this->site()->label() ); ?></span>
								</a>
								<div class="tree-menu-wrapper">
									<div class="menu-header"><strong><?= $enc->html( $this->translate( 'admin', 'Site' ) ); ?></strong></div>
									<ul class="tree-menu">
										
										<?php $siteFcn = function( \Aimeos\MShop\Locale\Item\Site\Iface $site ) use ( &$siteFcn, $enc, $searchTarget, $cntl, $action, $params, $config ) { ?>
										
											<li class="site-<?= $enc->attr( $site->getCode() ) ?>">
												<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'site' => $site->getCode() ) + $params, [], $config ) ); ?>">
													<span class="name"><?= $enc->html( $site->getLabel() ); ?></span>
												</a>

												<?php if( !$site->getChildren()->isEmpty() ) : ?>
													<ul class="menu-sub">
														<?php foreach( $site->getChildren() as $site ) { $siteFcn( $site ); } ?>
													</ul>
												<?php endif; ?>
											</li>

										<?php }; ?>

										<?php foreach( $this->pageSiteList as $siteItem ) : ?>
											<?php if( $siteItem->getId() === $this->pageSiteTree->getId() ) : ?>
												<?php $siteFcn( $this->pageSiteTree ); ?>
											<?php else : ?>
												<?php $siteFcn( $siteItem ); ?>
											<?php endif; ?>
										<?php endforeach; ?>
									</ul>
								</div>
							</li>
						<?php endif; ?>

						<?php foreach( array_splice( $navlist, 0, $navlimit ) as $nav => $navitem ) : ?>
							<?php if( is_array( $navitem ) ) : ?>
								<?php if( $this->access( $this->config( 'admin/jqadm/resource/' . $nav . '/groups', [] ) ) ) : ?>
									<li class="treeview <?= $enc->attr( $nav ) ?> <?= strncmp( $this->param( 'resource' ), $nav, strlen( $nav ) ) ? '' : 'active' ?>">
										<span>
											<i class="icon"></i>
											<span class="title"><?= $enc->attr( $this->translate( 'admin', $nav ) ); ?></span>
										</span>
										<div class="tree-menu-wrapper">
											<div class="menu-header"><strong><?= $enc->html( $this->translate( 'admin', 'Site' ) ); ?></strong></div>
											<ul class="tree-menu">
												<?php foreach( $navitem as $subresource ) : ?>
													<?php if( $this->access( $this->config( 'admin/jqadm/resource/' . $subresource . '/groups', [] ) ) ) : ?>
														<li class="<?= str_replace( '/', '-', $subresource ); ?>">
															<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, ['resource' => $subresource] + $params, [], $config ) ); ?>">
																<span class="name"><?= $enc->html( $this->translate( 'admin', $subresource ) ); ?></span>
															</a>
														</li>
													<?php endif; ?>
												<?php endforeach; ?>
											</ul>
										</div>
									</li>

								<?php endif; ?>
							<?php else : ?>
								<?php if( $this->access( $this->config( 'admin/jqadm/resource/' . $navitem . '/groups', [] ) ) ) : ?>
									<?php $key = $this->config( 'admin/jqadm/resource/' . $navitem . '/key' ); ?>

									<li class="<?= $enc->attr( $navitem ); ?> <?= $this->param( 'resource', $navfirst ) === $navitem ? 'active' : '' ?>">
										<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => $navitem ) + $params, [], $config ) ); ?>"
											title="<?= $enc->attr( sprintf( $title, $this->translate( 'admin', $navitem ), $key ) ); ?>"
											data-ctrlkey="<?= $enc->attr( strtolower( $key ) ); ?>">
											<i class="icon"></i>
											<span class="title"><?= $enc->html( $this->translate( 'admin', $navitem ) ); ?></span>
										</a>
									</li>

								<?php endif; ?>
							<?php endif; ?>
						<?php endforeach; ?>

					</ul>

					<div class="separator"><i class="icon more"></i></div>

					<ul class="sidebar-menu advanced">

						<?php foreach( $navlist as $nav => $navitem ) : ?>
							<?php if( is_array( $navitem ) ) : ?>
								<?php if( $this->access( $this->config( 'admin/jqadm/resource/' . $nav . '/groups', [] ) ) ) : ?>
									<li class="treeview <?= $enc->attr( $nav ) ?> <?= strncmp( $this->param( 'resource' ), $nav, strlen( $nav ) ) ? '' : 'active' ?>">
										<span>
											<i class="icon"></i>
											<span class="title"><?= $enc->attr( $this->translate( 'admin', $nav ) ); ?></span>
										</span>
										<div class="tree-menu-wrapper">
											<div class="menu-header"><strong><?= $enc->html( $this->translate( 'admin', 'Site' ) ); ?></strong></div>
											<ul class="tree-menu">

												<?php foreach( $navitem as $subresource ) : ?>
													<?php if( $this->access( $this->config( 'admin/jqadm/resource/' . $subresource . '/groups', [] ) ) ) : ?>
														<li class="<?= str_replace( '/', '-', $subresource ); ?>">
															<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, ['resource' => $subresource] + $params, [], $config ) ); ?>">
																<span class="name"><?= $enc->html( $this->translate( 'admin', $subresource ) ); ?></span>
															</a>
														</li>
													<?php endif; ?>
												<?php endforeach; ?>

											</ul>
										</div>
									</li>

								<?php endif; ?>
							<?php else : ?>
								<?php if( $this->access( $this->config( 'admin/jqadm/resource/' . $navitem . '/groups', [] ) ) ) : ?>
									<?php $key = $this->config( 'admin/jqadm/resource/' . $navitem . '/key' ); ?>
									<li class="<?= $enc->attr( $navitem ); ?> <?= $this->param( 'resource', $navfirst ) === $navitem ? 'active' : '' ?>">
										<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => $navitem ) + $params, [], $config ) ); ?>"
											title="<?= $enc->attr( sprintf( $title, $this->translate( 'admin', $navitem ), $key ) ); ?>"
											data-ctrlkey="<?= $enc->attr( strtolower( $key ) ); ?>">
											<i class="icon"></i>
											<span class="title"><?= $enc->html( $this->translate( 'admin', $navitem ) ); ?></span>
										</a>
									</li>

								<?php endif; ?>
							<?php endif; ?>
						<?php endforeach; ?>

						<?php if( $this->access( $this->config( 'admin/jqadm/resource/language/groups', [] ) ) ) : ?>
							<li class="language treeview">
								<span>
									<i class="icon"></i>
									<span class="title"><?= $enc->attr( $this->translate( 'language', $this->param( 'lang', $this->translate( 'admin', 'Language' ) ) ) ); ?></span>
								</span>
								<div class="tree-menu-wrapper">
									<div class="menu-header"><strong><?= $enc->html( $this->translate( 'admin', 'Site' ) ); ?></strong></div>
									<ul class="tree-menu">
										<?php foreach( $this->get( 'pageI18nList', [] ) as $langid ) : ?>
											<li class="lang-<?= $enc->attr( $langid ) ?>">
												<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'lang' => $langid ) + $params, [], $config ) ); ?>">
													<span class="name"><?= $enc->html( $this->translate( 'language', $langid ) ); ?> (<?= $langid ?>)</span>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							</li>
						<?php endif; ?>
					</ul>

				</div>
			</nav>

			<div class="container-fluid no-gutters">
				<div class="col h-stretch">

					<main class="main-content">

						<?= $this->partial( $this->config( 'admin/jqadm/partial/error', 'common/partials/error-standard' ), array( 'errors' => $this->get( 'errors', [] ) ) ); ?>
						<?= $this->partial( $this->config( 'admin/jqadm/partial/info', 'common/partials/info-standard' ), array( 'info' => $infoMsgs ) ); ?>

						<?= $this->block()->get( 'jqadm_content' ); ?>

					</main>

					<footer class="main-footer">
						<a href="https://github.com/aimeos/ai-admin-jqadm/issues" target="_blank">
							<?= $enc->html( $this->translate( 'admin', 'Bug or suggestion?' ) ); ?>
						</a>
					</footer>

				</div>
			</div>

		</div>
	</div>

	<div id="js--toggle-nav-items-text" class="toggle-nav-items-texts">
		<span class="icon"></span>
		<span class="hidden"><?= $enc->html( $this->translate( 'admin', 'Show/hide navigation labels' ) ) ?></span>
	</div>

	<?= $this->partial( $this->config( 'admin/jqadm/partial/confirm', 'common/partials/confirm-standard' ) ); ?>
	<?= $this->partial( $this->config( 'admin/jqadm/partial/problem', 'common/partials/problem-standard' ) ); ?>

</div>
