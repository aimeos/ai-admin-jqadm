<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2017
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


/** admin/extjs/url/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.04
 * @category Developer
 * @see admin/extjs/url/controller
 * @see admin/extjs/url/action
 * @see admin/extjs/url/config
 */
$extTarget = $this->config( 'admin/extjs/url/target' );

/** admin/extjs/url/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @category Developer
 * @see admin/extjs/url/target
 * @see admin/extjs/url/action
 * @see admin/extjs/url/config
 */
$extCntl = $this->config( 'admin/extjs/url/controller', 'Extadm' );

/** admin/extjs/url/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @category Developer
 * @see admin/extjs/url/target
 * @see admin/extjs/url/controller
 * @see admin/extjs/url/config
 */
$extAction = $this->config( 'admin/extjs/url/action', 'index' );

/** admin/extjs/url/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  admin/extjs/url/config = array( 'absoluteUri' => true )
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2016.04
 * @category Developer
 * @see admin/extjs/url/target
 * @see admin/extjs/url/controller
 * @see admin/extjs/url/action
 */
$extConfig = $this->config( 'admin/extjs/url/config', [] );


/** admin/jqadm/resources-types
 * List of type resource clients in the JQAdm interface
 *
 * The JQAdm admin interface consists of several clients for different resources.
 * This configuration setting lists the names of the type resources and their order.
 *
 * @param array List of resource client names
 * @since 2017.10
 * @category Developer
 */
$typesList = $this->config( 'admin/jqadm/resources-types', [] );


$resource = $this->param( 'resource', 'dashboard' );
$site = $this->param( 'site', 'default' );
$lang = $this->param( 'lang' );

$params = ['resource' => $resource, 'site' => $site];
$extParams = ['site' => $site];

if( $lang ) {
	$params['lang'] = $extParams['lang'] = $lang;
}

$title = $this->translate( 'admin', '%1$s (Ctrl+Alt+%2$s)' );


/** admin/jqadm/partial/error
 * Relative path to the partial template for displaying errors
 *
 * The template file contains the HTML code and processing instructions
 * to generate the result shown in the administration interface. The
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
 * @param string Relative path to the partial creating the HTML code
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/partial/confirm
 * @see admin/jqadm/partial/filter
 * @see admin/jqadm/partial/pagination
 */

/** admin/jqadm/partial/confirm
 * Relative path to the partial template for displaying the confirmation dialog
 *
 * The template file contains the HTML code and processing instructions
 * to generate the result shown in the administration interface. The
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
 * @param string Relative path to the partial creating the HTML code
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/partial/error
 * @see admin/jqadm/partial/filter
 * @see admin/jqadm/partial/pagination
 */

?>
<div class="aimeos" lang="<?= $this->param( 'lang' ); ?>" data-url="<?= $enc->attr( $this->url( $jsonTarget, $jsonCntl, $jsonAction, array( 'site' => $site ), [], $jsonConfig ) ); ?>">

	<nav class="main-sidebar">
		<div class="sidebar-wrapper">

			<a class="logo" href="https://aimeos.org/update/?type={type}&version={version}">
				<img src="https://aimeos.org/check/?type={type}&version={version}&extensions={extensions}" alt="Aimeos update" title="Aimeos update">
			</a>

			<ul class="sidebar-menu basic">

				<?php if( $this->pageSiteTree->getChildren() !== [] && $this->access( $this->config( 'admin/jqadm/access/site/groups', [] ) ) ) : ?>
					<li class="site treeview">
						<a href="#">
							<i class="icon"></i>
							<span class="title"><?= $enc->html( $this->site()->label() ); ?></span>
						</a>
						<ul class="tree-menu">
							<li class="menu-header"><strong><?= $enc->html( $this->translate( 'admin', 'Site' ) ); ?></strong></li>

							<?php $siteFcn = function( \Aimeos\MShop\Locale\Item\Site\Iface $site ) use ( &$siteFcn, $enc, $searchTarget, $cntl, $action, $params, $config ) { ?>

								<li class="site-<?= $enc->attr( $site->getCode() ) ?>">
									<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'site' => $site->getCode() ) + $params, [], $config ) ); ?>">
										<span class="name"><?= $enc->html( $site->getLabel() ); ?></span>
									</a>

									<?php if( $site->getChildren() !== [] ) : ?>
										<ul class="menu-sub">
											<?php foreach( $site->getChildren() as $site ) { $siteFcn( $site ); } ?>
										</ul>
									<?php endif; ?>
								</li>

							<?php }; $siteFcn( $this->pageSiteTree ); ?>

						</ul>
					</li>
				<?php endif; ?>

				<?php if( $this->access( $this->config( 'admin/jqadm/access/dashboard/groups', [] ) ) ) : ?>
					<li class="dashboard <?= $this->param( 'resource', 'dashboard' ) === 'dashboard' ? 'active' : '' ?>">
						<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => 'dashboard' ) + $params, [], $config ) ); ?>"
							title="<?= $enc->attr( sprintf( $title, $this->translate( 'admin', 'Dashboard' ), 'D' ) ); ?>"
							data-ctrlkey="<?= $enc->attr( 'd' ); ?>">
							<i class="icon"></i>
							<span class="title"><?= $enc->html( $this->translate( 'admin', 'Dashboard' ) ); ?></span>
						</a>
					</li>
				<?php endif; ?>

				<?php if( $this->access( $this->config( 'admin/jqadm/access/order/groups', [] ) ) ) : ?>
					<li class="order <?= $this->param( 'resource' ) === 'order' ? 'active' : '' ?>">
						<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => 'order' ) + $params, [], $config ) ); ?>"
							title="<?= $enc->attr( sprintf( $title, $this->translate( 'admin', 'Orders' ), 'O' ) ); ?>"
							data-ctrlkey="<?= $enc->attr( 'o' ); ?>">
							<i class="icon"></i>
							<span class="title"><?= $enc->html( $this->translate( 'admin', 'Orders' ) ); ?></span>
						</a>
					</li>
				<?php endif; ?>

				<?php if( $this->access( $this->config( 'admin/jqadm/access/customer/groups', [] ) ) ) : ?>
					<li class="customer <?= $this->param( 'resource' ) === 'customer' ? 'active' : '' ?>">
						<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => 'customer' ) + $params, [], $config ) ); ?>"
							title="<?= $enc->attr( sprintf( $title, $this->translate( 'admin', 'Users' ), 'U' ) ); ?>"
							data-ctrlkey="<?= $enc->attr( 'u' ); ?>">
							<i class="icon"></i>
							<span class="title"><?= $enc->html( $this->translate( 'admin', 'Users' ) ); ?></span>
						</a>
					</li>
				<?php endif; ?>

				<?php if( $this->access( $this->config( 'admin/jqadm/access/product/groups', [] ) ) ) : ?>
					<li class="product <?= $this->param( 'resource' ) === 'product' ? 'active' : '' ?>">
						<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => 'product' ) + $params, [], $config ) ); ?>"
							title="<?= $enc->attr( sprintf( $title, $this->translate( 'admin', 'Products' ), 'P' ) ); ?>"
							data-ctrlkey="<?= $enc->attr( 'p' ); ?>">
							<i class="icon"></i>
							<span class="title"><?= $enc->html( $this->translate( 'admin', 'Products' ) ); ?></span>
						</a>
					</li>
				<?php endif; ?>

				<?php if( $this->access( $this->config( 'admin/jqadm/access/catalog/groups', [] ) ) ) : ?>
					<li class="catalog <?= $this->param( 'resource' ) === 'catalog' ? 'active' : '' ?>">
						<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => 'catalog' ) + $params, [], $config ) ); ?>"
							title="<?= $enc->attr( sprintf( $title, $this->translate( 'admin', 'Categories' ), 'C' ) ); ?>"
							data-ctrlkey="<?= $enc->attr( 'c' ); ?>">
							<i class="icon"></i>
							<span class="title"><?= $enc->html( $this->translate( 'admin', 'Categories' ) ); ?></span>
						</a>
					</li>
				<?php endif; ?>

				<?php if( $this->access( $this->config( 'admin/jqadm/access/attribute/groups', [] ) ) ) : ?>
					<li class="attribute <?= $this->param( 'resource' ) === 'attribute' ? 'active' : '' ?>">
						<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => 'attribute' ) + $params, [], $config ) ); ?>"
							title="<?= $enc->attr( sprintf( $title, $this->translate( 'admin', 'Attributes' ), 'T' ) ); ?>"
							data-ctrlkey="<?= $enc->attr( 't' ); ?>">
							<i class="icon"></i>
							<span class="title"><?= $enc->html( $this->translate( 'admin', 'Attributes' ) ); ?></span>
						</a>
					</li>
				<?php endif; ?>

				<?php if( $this->access( $this->config( 'admin/jqadm/access/coupon/groups', [] ) ) ) : ?>
					<li class="coupon <?= $this->param( 'resource' ) === 'coupon' ? 'active' : '' ?>">
						<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => 'coupon' ) + $params, [], $config ) ); ?>"
							title="<?= $enc->attr( sprintf( $title, $this->translate( 'admin', 'Vouchers' ), 'V' ) ); ?>"
							data-ctrlkey="<?= $enc->attr( 'v' ); ?>">
							<i class="icon"></i>
							<span class="title"><?= $enc->html( $this->translate( 'admin', 'Vouchers' ) ); ?></span>
						</a>
					</li>
				<?php endif; ?>
			</ul>

			<div class="separator"><i class="icon more"></i></div>

			<ul class="sidebar-menu advanced">
				<?php if( $this->access( $this->config( 'admin/jqadm/access/supplier/groups', [] ) ) ) : ?>
					<li class="supplier <?= $this->param( 'resource' ) === 'supplier' ? 'active' : '' ?>">
						<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => 'supplier' ) + $params, [], $config ) ); ?>"
							title="<?= $enc->attr( sprintf( $title, $this->translate( 'admin', 'Suppliers' ), 'I' ) ); ?>"
							data-ctrlkey="<?= $enc->attr( 'i' ); ?>">
							<i class="icon"></i>
							<span class="title"><?= $enc->html( $this->translate( 'admin', 'Suppliers' ) ); ?></span>
						</a>
					</li>
				<?php endif; ?>

				<?php if( $this->access( $this->config( 'admin/jqadm/access/service/groups', [] ) ) ) : ?>
					<li class="service <?= $this->param( 'resource' ) === 'service' ? 'active' : '' ?>">
						<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => 'service' ) + $params, [], $config ) ); ?>"
							title="<?= $enc->attr( sprintf( $title, $this->translate( 'admin', 'Services' ), 'S' ) ); ?>"
							data-ctrlkey="<?= $enc->attr( 's' ); ?>">
							<i class="icon"></i>
							<span class="title"><?= $enc->html( $this->translate( 'admin', 'Services' ) ); ?></span>
						</a>
					</li>
				<?php endif; ?>

				<?php if( $this->access( $this->config( 'admin/jqadm/access/plugin/groups', [] ) ) ) : ?>
					<li class="plugin <?= $this->param( 'resource' ) === 'plugin' ? 'active' : '' ?>">
						<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => 'plugin' ) + $params, [], $config ) ); ?>"
							title="<?= $enc->attr( sprintf( $title, $this->translate( 'admin', 'Plugins' ), 'G' ) ); ?>"
							data-ctrlkey="<?= $enc->attr( 'g' ); ?>">
							<i class="icon"></i>
							<span class="title"><?= $enc->html( $this->translate( 'admin', 'Plugins' ) ); ?></span>
						</a>
					</li>
				<?php endif; ?>

				<?php if( $this->access( $this->config( 'admin/jqadm/access/type/groups', [] ) ) ) : ?>
					<li class="type treeview <?= substr_compare( $this->param( 'resource', '<none>' ), '/type', -5 ) ? '' : 'active' ?>">
						<span>
							<i class="icon"></i>
							<span class="title"><?= $enc->attr( $this->translate( 'admin', 'Types' ) ); ?></span>
						</span>
						<ul class="tree-menu">
							<li class="menu-header"><strong><?= $enc->html( $this->translate( 'admin', 'Types' ) ); ?></strong></li>

							<?php foreach( $typesList as $type ) : ?>
								<?php if( $this->access( $this->config( 'admin/jqadm/access/' . $type . '/groups', [] ) ) ) : ?>
									<li class="<?= $enc->attr( str_replace( '/', '-', $type ) ) . ' ' . ( $this->param( 'resource' ) === $type ? 'active' : '' ) ?>">
										<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, ['resource' => $type] + $params, [], $config ) ); ?>">
											<span class="name"><?= $enc->html( $this->translate( 'admin', $type ) ); ?></span>
										</a>
									</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php if( $this->access( $this->config( 'admin/jqadm/access/locale/groups', [] ) ) ) : ?>
					<li class="locale treeview <?= strncmp( $this->param( 'resource' ), 'locale', 6 ) ? '' : 'active' ?>">
						<span>
							<i class="icon"></i>
							<span class="title"><?= $enc->attr( $this->translate( 'admin', 'Locale' ) ); ?></span>
						</span>
						<ul class="tree-menu">
							<li class="menu-header"><strong><?= $enc->html( $this->translate( 'admin', 'Locale' ) ); ?></strong></li>
							<li class="locale-list">
								<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, ['resource' => 'locale'] + $params, [], $config ) ); ?>">
									<span class="name"><?= $enc->html( $this->translate( 'admin', 'List' ) ); ?></span>
								</a>
							</li>
							<?php if( $this->access( $this->config( 'admin/jqadm/access/locale/site/groups', [] ) ) ) : ?>
								<li class="locale-site">
									<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, ['resource' => 'locale/site'] + $params, [], $config ) ); ?>">
										<span class="name"><?= $enc->html( $this->translate( 'admin', 'Sites' ) ); ?></span>
									</a>
								</li>
							<?php endif; ?>
							<?php if( $this->access( $this->config( 'admin/jqadm/access/locale/language/groups', [] ) ) ) : ?>
								<li class="locale-language">
									<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, ['resource' => 'locale/language'] + $params, [], $config ) ); ?>">
										<span class="name"><?= $enc->html( $this->translate( 'admin', 'Languages' ) ); ?></span>
									</a>
								</li>
							<?php endif; ?>
							<?php if( $this->access( $this->config( 'admin/jqadm/access/locale/currency/groups', [] ) ) ) : ?>
								<li class="locale-currency">
									<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, ['resource' => 'locale/currency'] + $params, [], $config ) ); ?>">
										<span class="name"><?= $enc->html( $this->translate( 'admin', 'Currencies' ) ); ?></span>
									</a>
								</li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php if( $this->access( $this->config( 'admin/jqadm/access/expert/groups', [] ) ) ) : ?>
					<li class="expert">
						<a href="<?= $enc->attr( $this->url( $extTarget, $extCntl, $extAction, $extParams, [], $extConfig ) ); ?>"
							title="<?= $enc->attr( sprintf( $this->translate( 'admin', '%1$s (Ctrl+Alt+%2$s)' ), 'Expert', 'E' ) ); ?>"
							data-ctrlkey="e">
							<i class="icon"></i>
							<span class="title"><?= $enc->html( $this->translate( 'admin', 'Expert' ) ); ?></span>
						</a>
					</li>
				<?php endif; ?>

				<?php if( $this->access( $this->config( 'admin/jqadm/access/language/groups', [] ) ) ) : ?>
					<li class="language treeview">
						<span>
							<i class="icon"></i>
							<span class="title"><?= $enc->attr( $this->translate( 'client/language', $this->param( 'lang', $this->translate( 'admin', 'Language' ) ) ) ); ?></span>
						</span>
						<ul class="tree-menu">
							<li class="menu-header"><strong><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></strong></li>
							<?php foreach( $this->get( 'pageI18nList', [] ) as $langid ) : ?>
								<li class="lang-<?= $enc->attr( $langid ) ?>">
									<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'lang' => $langid ) + $params, [], $config ) ); ?>">
										<span class="name"><?= $enc->html( $this->translate( 'client/language', $langid ) ); ?> (<?= $langid ?>)</span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endif; ?>
			</ul>

		</div>
	</nav>

	<main class="main-content">

		<?= $this->partial( $this->config( 'admin/jqadm/partial/error', 'common/partials/error-default.php' ), array( 'errors' => $this->get( 'errors', [] ) ) ); ?>
		<?= $this->partial( $this->config( 'admin/jqadm/partial/info', 'common/partials/info-default.php' ), array( 'info' => $this->get( 'info', [] ) ) ); ?>

		<?= $this->block()->get( 'jqadm_content' ); ?>

	</main>

	<footer class="main-footer">
		<a href="https://github.com/aimeos/ai-admin-jqadm/issues" target="_blank">
			<?= $enc->html( $this->translate( 'admin', 'Bug or suggestion?' ) ); ?>
		</a>
	</footer>

	<?= $this->partial( $this->config( 'admin/jqadm/partial/confirm', 'common/partials/confirm-default.php' ) ); ?>

</div>
