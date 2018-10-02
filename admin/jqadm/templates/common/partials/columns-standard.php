<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */

/**
 * Renders the drop down for the available columns in the list views
 *
 * Available data:
 * - data: Associative list of keys (e.g. "product.id") and translated names (e.g. "ID")
 * - fields: List of columns that are currently shown
 * - group: Parameter group if several lists are on one page
 * - tabindex: Numerical index for tabbing through the fields and buttons
 */


$checked = function( array $list, $code ) {
	return ( in_array( $code, $list ) ? 'checked="checked"' : '' );
};


$enc = $this->encoder();
$fields = $this->get( 'fields', [] );
$names = array_merge( (array) $this->get( 'group', [] ), ['fields', ''] );


?>
<div class="dropdown filter-columns">
	<button class="btn act-columns fa" type="button" id="dropdownMenuButton-<?= $this->get( 'group' ) ?>"
		data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" tabindex="<?= $this->get( 'tabindex', 1 ); ?>"
		aria-label="<?= $enc->attr( $this->translate( 'admin', 'Columns' ) ); ?>"
		title="<?= $enc->attr( $this->translate( 'admin', 'Columns') ); ?>">
	</button>
	<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-<?= $this->get( 'group' ) ?>">
		<?php foreach( $this->get( 'data', [] ) as $key => $name ) : ?>
			<li class="dropdown-item">
				<a href="#"><label>
					<input type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ); ?>"
						name="<?= $enc->attr( $this->formparam( $names ) ); ?>"
						value="<?= $enc->attr( $key ); ?>" <?= $checked( $fields, $key ); ?> />
					<?= $enc->html( $name ); ?>
				</label></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
