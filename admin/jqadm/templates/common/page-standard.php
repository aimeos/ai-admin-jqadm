<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */

$enc = $this->encoder();

$target = $this->request()->getTarget();
$searchTarget = $this->config( 'admin/jqadm/url/search/target' );
$cntl = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/search/action', 'search' );
$config = $this->config( 'admin/jqadm/url/search/config', [] ) + ['absoluteUri' => true];


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
$navlist = map( $this->config( 'admin/jqadm/navbar', [] ) )->ksort();

foreach( $navlist as $key => $navitem )
{
	$name = is_array( $navitem ) ? ( $navitem['_'] ?? current( $nav ) ) : $navitem;

	if( !$this->access( $this->config( 'admin/jqadm/resource/' . $name . '/groups', [] ) ) ) {
		$navlist->remove( $key );
	}
}


$resource = $this->param( 'resource', 'dashboard' );
$site = $this->param( 'site', 'default' );
$lang = $this->param( 'locale' );

$params = ['resource' => $resource, 'site' => $site];
$extParams = ['site' => $site];

if( $lang ) {
	$params['locale'] = $extParams['locale'] = $lang;
}


$pos = $navlist->pos( function( $item, $key ) use ( $resource ) {
	return is_array( $item ) ? in_array( $resource, $item ) : !strncmp( $resource, $item, strlen( $item ) );
} );
$before = $pos > 0 ? $navlist->slice( $pos - 1, 1 )->first() : null;
$before = is_array( $before ) ? $before['_'] ?? reset( $before ) : $before;
$after = $pos < count( $navlist ) ? $navlist->slice( $pos + 1, 1 )->first() : null;
$after = is_array( $after ) ? $after['_'] ?? reset( $after ) : $after;


?>
<div class="aimeos" lang="<?= $this->param( 'locale' ) ?>" data-url="<?= $enc->attr( $this->url( $jsonTarget, $jsonCntl, $jsonAction, array( 'site' => $site ), [], $jsonConfig ) ) ?>">

	<nav class="main-sidebar">
		<div class="sidebar-wrapper">

			<a class="logo" target="_blank" href="https://aimeos.org/update/?type=<?= $this->get( 'aimeosType' ) ?>&version=<?= $this->get( 'aimeosVersion' ) ?>">
				<img src="https://aimeos.org/check/?type=<?= $this->get( 'aimeosType' ) ?>&version=<?= $this->get( 'aimeosVersion' ) ?>&extensions=<?= $this->get( 'aimeosExtensions' ) ?>" alt="Aimeos update" title="Aimeos update">
			</a>

			<ul class="sidebar-menu">

				<?php if( $this->access( $this->config( 'admin/jqadm/resource/site/groups', [] ) ) ) : ?>

					<li class="none"></li>
					<li class="treeview menuitem-site <?= $before === null ? 'before' : '' ?>">
						<a class="item-group" href="#">
							<i class="icon"></i>
							<span class="title"><?= $enc->html( $this->site()->label() ) ?></span>
						</a>
						<div class="tree-menu-wrapper">
							<div class="menu-header">
								<a href="#"><?= $enc->html( $this->translate( 'admin', 'Site' ) ) ?></a>
								<span class="close"></span>
							</div>
							<div class="menu-body vue" data-key="sidebar-sites">
								<site-tree
									v-bind:promise="Aimeos.options"
									current="<?= $enc->attr( $this->pageSiteItem->getId() ) ?>"
									parent="<?= $enc->attr( $this->pageSitePath->getParentId()->first( '0' ) ) ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Find site' ) ) ?>"
									url="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'site' => '_code_' ) + $params, [], $config ) ) ?>">
								</site-tree>
							</div>
						</div>
					</li>

				<?php else : ?>

					<li class="none <?= $before === null ? 'before' : '' ?>"></li>

				<?php endif ?>

				<?php foreach( $navlist as $nav => $navitem ) : ?>
					<?php if( is_array( $navitem ) ) : $nav = $navitem['_'] ?? current( $nav ) ?>

						<li class="treeview menuitem-<?= $enc->attr( $nav ) ?> <?= $nav === $before ? 'before' : '' ?> <?= in_array( $resource, $navitem ) !== false ? 'active' : '' ?> <?= $nav === $after ? 'after' : '' ?>">
							<span class="item-group">
								<i class="icon"></i>
								<span class="title"><?= $enc->attr( $this->translate( 'admin', $nav ) ) ?></span>
							</span>
							<div class="tree-menu-wrapper">
								<div class="menu-header">
									<a href="#"><?= $enc->html( $this->translate( 'admin', $nav ) ) ?></a>
									<span class="close"></span>
								</div>
								<ul class="tree-menu">

								<?php foreach( map( $navitem )->remove( '_' )->ksort() as $subresource ) : ?>
										<?php if( $this->access( $this->config( 'admin/jqadm/resource/' . $subresource . '/groups', [] ) ) ) : ?>
											<?php $key = $this->config( 'admin/jqadm/resource/' . $subresource . '/key' ) ?>

											<li class="menuitem-<?= str_replace( '/', '-', $subresource ) ?> <?= $subresource === $resource ? 'active' : '' ?>">
												<a class="item-group" href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, ['resource' => $subresource] + $params, [], $config ) ) ?>"
													title="<?= $enc->attr( sprintf( $this->translate( 'admin', '%1$s (Ctrl+Alt+%2$s)' ), $this->translate( 'admin', $subresource ), $key ) ) ?>"
													data-ctrlkey="<?= $enc->attr( strtolower( $key ) ) ?>">
													<i class="icon"></i>
													<span class="name"><?= $enc->html( $this->translate( 'admin', $subresource ) ) ?></span>
												</a>
											</li>

										<?php endif ?>
									<?php endforeach ?>
								</ul>
							</div>
						</li>

					<?php else : ?>
						<?php $key = $this->config( 'admin/jqadm/resource/' . $navitem . '/key' ) ?>

						<li class="menuitem-<?= $enc->attr( $navitem ) ?> <?= $navitem === $before ? 'before' : '' ?> <?= !strncmp( $resource, $navitem, strlen( $navitem ) ) ? 'active' : '' ?> <?= $navitem === $after ? 'after' : '' ?>">
							<a class="item-group" href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'resource' => $navitem ) + $params, [], $config ) ) ?>"
								title="<?= $enc->attr( sprintf( $this->translate( 'admin', '%1$s (Ctrl+Alt+%2$s)' ), $this->translate( 'admin', $navitem ), $key ) ) ?>"
								data-ctrlkey="<?= $enc->attr( strtolower( $key ) ) ?>">
								<i class="icon"></i>
								<span class="title"><?= $enc->html( $this->translate( 'admin', $navitem ) ) ?></span>
							</a>
						</li>

					<?php endif ?>
				<?php endforeach ?>

				<?php if( $this->access( $this->config( 'admin/jqadm/resource/language/groups', [] ) ) ) : ?>

					<li class="treeview menuitem-language <?= $after === null ? 'after' : '' ?>">
						<span class="item-group">
							<i class="icon"></i>
							<span class="title"><?= $enc->attr( $this->translate( 'language', $this->param( 'locale', $this->translate( 'admin', 'Language' ) ) ) ) ?></span>
						</span>
						<div class="tree-menu-wrapper">
							<div class="menu-header">
								<a href="#"><?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?></a>
								<span class="close"></span>
							</div>
							<ul class="tree-menu">
								<?php foreach( $this->get( 'pageI18nList', [] ) as $langid ) : ?>
									<li class="menuitem-language-<?= $enc->attr( $langid ) ?>">
										<a href="<?= $enc->attr( $this->url( $searchTarget, $cntl, $action, array( 'locale' => $langid ) + $params, [], $config ) ) ?>">
											<span class="name"><?= $enc->html( $this->translate( 'language', $langid ) ) ?> (<?= $langid ?>)</span>
										</a>
									</li>
								<?php endforeach ?>
							</ul>
						</div>
					</li>

				<?php endif ?>

				<li class="none"></li>
			</ul>

		</div>
	</nav>

	<main class="main-content">
		<?= $this->partial( $this->config( 'admin/jqadm/partial/info', 'common/partials/info-standard' ), [
			'info' => array_merge( $this->get( 'pageInfo', [] ), $this->get( 'info', [] ) ),
			'error' => $this->get( 'errors', [] )
		] ) ?>

		<?= $this->block()->get( 'jqadm_content' ) ?>
	</main>

	<footer class="main-footer">
		<a href="https://github.com/aimeos/ai-admin-jqadm/issues" target="_blank">
			<?= $enc->html( $this->translate( 'admin', 'Bug or suggestion?' ) ) ?>
		</a>
	</footer>

	<?= $this->partial( $this->config( 'admin/jqadm/partial/confirm', 'common/partials/confirm-standard' ) ) ?>
	<?= $this->partial( $this->config( 'admin/jqadm/partial/problem', 'common/partials/problem-standard' ) ) ?>

</div>
