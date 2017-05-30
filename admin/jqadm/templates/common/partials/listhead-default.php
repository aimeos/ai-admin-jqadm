<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$sort = function( $sortcode, $code ) {
	return ( $sortcode === $code ? '-' . $code : $code );
};

$sortclass = function( $sortcode, $code ) {
	if( $sortcode === $code ) {
		return 'sort-desc';
	}
	if( $sortcode === '-' . $code ) {
		return 'sort-asc';
	}
};

/**
 * Renders the table header row in the list view
 *
 * Available data:
 * - data: Associative list of keys (e.g. "product.id") and translated names (e.g. "ID")
 * - fields: List of columns that are currently shown
 * - params: Associative list of current parameters
 */


/** admin/jqadm/url/create/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/create/controller
 * @see admin/jqadm/url/create/action
 * @see admin/jqadm/url/create/config
 */
$newTarget = $this->config( 'admin/jqadm/url/create/target' );

/** admin/jqadm/url/create/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/create/target
 * @see admin/jqadm/url/create/action
 * @see admin/jqadm/url/create/config
 */
$newCntl = $this->config( 'admin/jqadm/url/create/controller', 'Jqadm' );

/** admin/jqadm/url/create/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/create/target
 * @see admin/jqadm/url/create/controller
 * @see admin/jqadm/url/create/config
 */
$newAction = $this->config( 'admin/jqadm/url/create/action', 'create' );

/** admin/jqadm/url/create/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  admin/jqadm/url/create/config = ['absoluteUri' => true]
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/create/target
 * @see admin/jqadm/url/create/controller
 * @see admin/jqadm/url/create/action
 */
$newConfig = $this->config( 'admin/jqadm/url/create/config', [] );

$target = $this->config( 'admin/jqadm/url/search/target' );
$controller = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/search/action', 'search' );
$config = $this->config( 'admin/jqadm/url/search/config', [] );

$fields = $this->get( 'fields', [] );
$params = $this->get( 'params', [] );
$sortcode = $this->get( 'params/sort' );

$enc = $this->encoder();


?>
<tr>
	<?php foreach( $this->get( 'data', [] ) as $key => $name ) : ?>
		<?php if( in_array( $key, $fields ) ) : ?>
			<th class="<?= $enc->attr( str_replace( '.', '-', $key ) ); ?>">
				<a class="<?= $sortclass( $sortcode, $key ); ?>"
					href="<?= $enc->attr( $this->url( $target, $controller, $action, ['sort' => $sort( $sortcode, $key )] + $params, [], $config ) ); ?>">
					<?= $enc->html( $name ); ?>
				</a>
			</th>
		<?php endif; ?>
	<?php endforeach; ?>

	<th class="actions">
		<a class="btn fa act-add"
			href="<?= $enc->attr( $this->url( $newTarget, $newCntl, $newAction, $params, [], $newConfig ) ); ?>"
			title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+a)') ); ?>"
			aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ); ?>">
		</a>
	</th>
</tr>
