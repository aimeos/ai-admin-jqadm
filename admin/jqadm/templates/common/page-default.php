<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
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


/** admin/jqadm/resources
 * List of available resource clients in the JQAdm interface
 *
 * The JQAdm admin interface consists of several clients for different resources.
 * This configuration setting lists the names of the basic resources and their order.
 *
 * @param array List of resource client names
 * @since 2016.07
 * @category Developer
 */
$resourceList = $this->config( 'admin/jqadm/resources', ['d' => 'dashboard', /*'o' => 'order', 'u' => 'customer',*/ 'p' => 'product', /*'c' => 'catalog', 'v' => 'voucher'*/] );

/** admin/jqadm/resources-more
 * List of available resource clients in the JQAdm interface
 *
 * The JQAdm admin interface consists of several clients for different resources.
 * This configuration setting lists the names of the advanced resources and their order.
 *
 * @param array List of resource client names
 * @since 2017.06
 * @category Developer
 */
$advancedList = $this->config( 'admin/jqadm/resources-more', [/*'r' => 'supplier', 's' => 'service', 'g' => 'plugin', 'l' => 'locale', 't' => 'type'*/] );


$resource = $this->param( 'resource', 'dashboard' );
$site = $this->param( 'site', 'default' );
$lang = $this->param( 'lang' );

$params = ['resource' => $resource, 'site' => $site];
$extParams = ['site' => $site];

if( $lang ) {
	$params['lang'] = $extParams['lang'] = $lang;
}


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

				<?php $sites = $this->get( 'sitesList', [] ); ?>
				<?php if( $this->access( 'admin' ) && count( $sites ) > 1 ) : ?>
					<li class="site treeview">
						<a href="#">
							<i class="icon"></i>
							<span class="name"><?= $enc->attr( $this->value( $sites, $site, $this->translate( 'admin', 'Site' ) ) ); ?></span>
						</a>
						<ul class="tree-menu">
							<?php foreach( $sites as $code => $label ) : ?>
								<li class="<?= $enc->attr( $code ) ?>">
									<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'site' => $code ) + $params, [], $config ) ); ?>">
										<?= $enc->html( $label ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endif; ?>


				<?php foreach( $resourceList as $ctrlkey => $code ) : ?>
					<?php $active = ( $this->param( 'resource', 'dashboard' ) === $code ? 'active' : '' ); ?>
					<li class="<?= $enc->attr( $code ) . ' ' . $active ?>">
						<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => $code ) + $params, [], $config ) ); ?>"
							title="<?= $enc->attr( sprintf( $this->translate( 'admin', '%1$s (Ctrl+Shift+%2$s)' ), $this->translate( 'admin', $code ), strtoupper( $ctrlkey ) ) ); ?>"
							data-ctrlkey="<?= $enc->attr( $ctrlkey ); ?>">
							<i class="icon"></i>
							<span class="name"><?= $enc->html( $this->translate( 'admin', $code ) ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<div class="separator"><i class="icon more"></i></div>

			<ul class="sidebar-menu advanced">
				<?php foreach( $advancedList as $ctrlkey => $code ) : ?>
					<?php $active = ( $this->param( 'resource' ) === $code ? 'active' : '' ); ?>
					<li class="<?= $enc->attr( $code ) . ' ' . $active ?>">
						<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => $code ) + $params, [], $config ) ); ?>"
							title="<?= $enc->attr( sprintf( $this->translate( 'admin', '%1$s (Ctrl+Shift+%2$s)' ), $this->translate( 'admin', $code ), strtoupper( $ctrlkey ) ) ); ?>"
							data-ctrlkey="<?= $enc->attr( $ctrlkey ); ?>">
							<i class="icon"></i>
							<span class="name"><?= $enc->html( $this->translate( 'admin', $code ) ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>

				<li class="config treeview">
					<span>
						<i class="icon"></i>
						<span class="name"><?= $enc->attr( $this->translate( 'client/language', $this->param( 'lang', $this->translate( 'admin', 'Language' ) ) ) ); ?></span>
					</span>
					<ul class="tree-menu">
						<?php foreach( $this->get( 'languagesList', [] ) as $langid ) : ?>
							<li class="<?= $enc->attr( $langid ) ?>">
								<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'lang' => $langid ) + $params, [], $config ) ); ?>"data-ctrlkey="e">
									<span class="name"><?= $enc->html( $this->translate( 'client/language', $langid ) ); ?> (<?= $langid ?>)</span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</li>

				<?php if( $this->access( 'admin' ) ) : ?>
					<li class="expert">
						<a href="<?= $enc->attr( $this->url( $extTarget, $extCntl, $extAction, $extParams, [], $extConfig ) ); ?>" data-ctrlkey="e">
							<i class="icon"></i>
							<span class="name"><?= $enc->html( $this->translate( 'admin', 'Expert' ) ); ?></span>
						</a>
					</li>
				<?php endif; ?>
			</ul>

		</div>
	</nav>

	<div class="main-content">

		<?= $this->partial( $this->config( 'admin/jqadm/partial/error', 'common/partials/error-default.php' ), array( 'errors' => $this->get( 'errors', [] ) ) ); ?>

		<?= $this->block()->get( 'jqadm_content' ); ?>

	</div>

	<?= $this->partial( $this->config( 'admin/jqadm/partial/confirm', 'common/partials/confirm-default.php' ) ); ?>

</div>
