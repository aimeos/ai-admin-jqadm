<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */

/**
 * Renders the table header row in the list view
 *
 * Available data:
 * - data: Associative list of keys (e.g. "product.id") and translated names (e.g. "ID")
 * - fields: List of columns that are currently shown
 * - params: Associative list of current parameters
 * - sort: Current sort code
 * - group: Filter group for subpanels
 * - action: Action to use for generating URLs
 * - fragment: Name of the subpanel that should be shown by default
 * - tabindex: Numerical index for tabbing through the fields and buttons
 */


$nest = function( $group, $params ) {
	return $group ? [$group => $params] : $params;
};

$sort = function( $sortcode, $code ) {
	return ( $sortcode === $code ? '-' . $code : $code );
};

$sortclass = function( $sortcode, $code ) {
	if( $sortcode === $code ) {
		return 'sort-asc';
	}
	if( $sortcode === '-' . $code ) {
		return 'sort-desc';
	}
};


$enc = $this->encoder();

$fields = $this->get( 'fields', [] );
$params = $this->get( 'params', [] );
$fragment = (array) $this->get( 'fragment', [] );
$sortcode = $this->get( 'sort' );
$group = $this->get( 'group' );


if( $this->get( 'action' ) === 'get' )
{
	if( isset( $params['id'] ) ) {
		$cfgkey = 'admin/jqadm/url/get';
	} else {
		$cfgkey = 'admin/jqadm/url/create';
	}
}
else
{
	$cfgkey = 'admin/jqadm/url/search';
}


?>
<?php foreach( $this->get( 'data', [] ) as $key => $name ) : ?>
	<?php if( in_array( $key, $fields ) ) : ?>
		<th class="<?= $enc->attr( str_replace( '.', '-', $key ) ) ?>">
			<?php if( strpos( $key, '.' ) !== false ) : ?>
				<a class="<?= $sortclass( $sortcode, $key ) ?>" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
					href="<?= $enc->attr( $this->link( $cfgkey, $nest( $group, ['sort' => $sort( $sortcode, $key )] ) + $params, [], $fragment ) ) ?>">
					<?= $enc->html( $name ) ?>
				</a>
			<?php else : ?>
				<?= $enc->html( $name ) ?>
			<?php endif ?>
		</th>
	<?php endif ?>
<?php endforeach ?>
