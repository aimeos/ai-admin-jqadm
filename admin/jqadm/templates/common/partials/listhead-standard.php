<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */

/**
 * Renders the table header row in the list view
 *
 * Available data:
 * - data: Associative list of keys (e.g. "product.id") and translated names (e.g. "ID")
 * - fields: List of columns that are currently shown
 * - params: Associative list of current parameters
 * - sort: Current sort code
 * - action: Action to use for generating URLs
 * - fragment: Name of the subpanel that should be shown by default
 * - tabindex: Numerical index for tabbing through the fields and buttons
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


if( $this->get( 'action' ) === 'get' )
{
	$target = $this->config( 'admin/jqadm/url/get/target' );
	$controller = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
	$action = $this->config( 'admin/jqadm/url/get/action', 'get' );
	$config = $this->config( 'admin/jqadm/url/get/config', [] );
}
else
{
	$target = $this->config( 'admin/jqadm/url/search/target' );
	$controller = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
	$action = $this->config( 'admin/jqadm/url/search/action', 'search' );
	$config = $this->config( 'admin/jqadm/url/search/config', [] );
}

$fields = $this->get( 'fields', [] );
$params = $this->get( 'params', [] );
$fragment = (array) $this->get( 'fragment', [] );
$sortcode = $this->get( 'sort' );

$enc = $this->encoder();


?>
<?php foreach( $this->get( 'data', [] ) as $key => $name ) : ?>
	<?php if( in_array( $key, $fields ) ) : ?>
		<th class="<?= $enc->attr( str_replace( '.', '-', $key ) ); ?>">
			<a class="<?= $sortclass( $sortcode, $key ); ?>" tabindex="<?= $this->get( 'tabindex', 1 ); ?>"
				href="<?= $enc->attr( $this->url( $target, $controller, $action, ['sort' => $sort( $sortcode, $key )] + $params, $fragment, $config ) ); ?>">
				<?= $enc->html( $name ); ?>
			</a>
		</th>
	<?php endif; ?>
<?php endforeach; ?>
