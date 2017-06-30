<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$checked = function( array $list, $code ) {
	return ( in_array( $code, $list ) ? 'checked="checked"' : '' );
};

/**
 * Renders the drop down for the available columns in the list views
 *
 * Available data:
 * - data: Associative list of keys (e.g. "product.id") and translated names (e.g. "ID")
 * - group: Field group name to distinguish between several field lists
 * - fields: List of columns that are currently shown
 * - tabindex: Numerical index for tabbing through the fields and buttons
 */

$fields = $this->get( 'fields', [] );

$enc = $this->encoder();


?>
<div class="dropdown filter-columns">
	<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
		data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" tabindex="<?= $this->get( 'tabindex' ); ?>">
	</button>
	<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
		<?php foreach( $this->get( 'data', [] ) as $key => $name ) : ?>
			<li class="dropdown-item">
				<label>
					<input type="checkbox" tabindex="<?= $this->get( 'tabindex' ); ?>"
						name="<?= $enc->attr( $this->formparam( ['fields', $this->get( 'group', '0' ), ''] ) ); ?>"
						value="<?= $enc->attr( $key ); ?>" <?= $checked( $fields, $key ); ?> />
					<?= $enc->html( $name ); ?>
				</label>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
