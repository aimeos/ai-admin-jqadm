<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$selected = function( $key, $code ) {
	return ( (string) $key === (string) $code ? 'selected="selected"' : '' );
};

/**
 * Renders the table row with the search fields in the list view
 *
 * Available data:
 * - data: Associative list of keys (e.g. "product.id") and translated names (e.g. "ID")
 * - fields: List of columns that are currently shown
 * - params: Associative list of current parameters
 */

$target = $this->config( 'admin/jqadm/url/search/target' );
$controller = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/search/action', 'search' );
$config = $this->config( 'admin/jqadm/url/search/config', [] );

$fields = $this->get( 'fields', [] );
$idx = -1;

$enc = $this->encoder();


?>
<tr class="list-search">
	<?php foreach( $this->get( 'data', [] ) as $key => $list ) : $idx++ ?>
		<?php if( in_array( $key, $fields ) ) : ?>
			<td class="<?= str_replace( '.', '-', $key ); ?>">
				<input type="hidden" value="<?= $enc->attr( $key ); ?>"
					name="<?= $enc->attr( $this->formparam( ['filter', 'key', $idx] ) ); ?>" />
				<input type="hidden" value="<?= $enc->attr( $this->value( $list, 'op', '=~' ) ); ?>"
					name="<?= $enc->attr( $this->formparam( ['filter', 'op', $idx] ) ); ?>" />

				<?php if( ( $type = $this->value( $list, 'type', 'text' ) ) === 'select' ) : ?>
					<select class="form-control custom-select" tabindex="2"
						name="<?= $enc->attr( $this->formparam( ['filter', 'val', $idx] ) ); ?>">
						<option value=""><?= $enc->attr( $this->translate( 'admin', 'All' ) ); ?></option>

						<?php foreach( (array) $this->value( $list, 'val', [] ) as $val => $name ) : ?>
							<option value="<?= $enc->attr( $val ); ?>" <?= $selected( $this->param( 'filter/val/' . $idx ), $val ); ?> >
								<?= $enc->html( $name ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				<?php else : ?>
					<input class="form-control" type="<?= $enc->attr( $type ); ?>" tabindex="2"
						name="<?= $enc->attr( $this->formparam( ['filter', 'val', $idx] ) ); ?>"
						value="<?= $enc->attr( $this->param( 'filter/val/' . $idx ) ); ?>" />
				<?php endif; ?>
			</td>
		<?php endif; ?>
	<?php endforeach; ?>

	<td class="actions">
		<a class="btn act-reset fa"
			href="<?= $enc->attr( $this->url( $target, $controller, $action, $this->get( 'params', [] ), [], $config ) ); ?>"
			title="<?= $enc->attr( $this->translate( 'admin', 'Reset search') ); ?>"
			aria-label="<?= $enc->attr( $this->translate( 'admin', 'Reset' ) ); ?>"></a>
		<button class="btn act-search fa"
			title="<?= $enc->attr( $this->translate( 'admin', 'Search entries') ); ?>"
			aria-label="<?= $enc->attr( $this->translate( 'admin', 'Search' ) ); ?>">
		</button>
	</td>
</tr>
