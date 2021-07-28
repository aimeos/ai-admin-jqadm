<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */

/**
 * Renders the table row with the search fields in the list view
 *
 * Available data:
 * - data: Associative list of keys (e.g. "product.id") and translated names (e.g. "ID")
 * - fields: List of columns that are currently shown
 * - filter: List of filter parameters
 * - group: Parameter group if several lists are on one page
 * - tabindex: Numerical index for tabbing through the fields and buttons
 */


$selected = function( $key, $code ) {
	return ( (string) $key === (string) $code ? 'selected="selected"' : '' );
};


$group = (array) $this->get( 'group', [] );
$filter = $this->get( 'filter', [] );
$fields = $this->get( 'fields', [] );
$idx = 0;

$enc = $this->encoder();


?>
<tr class="list-search">
	<?php if( in_array( 'select', $fields ) ) : ?>
		<td class="select">
			<input v-on:click="toggleAll()" v-model="all" class="form-check-input" type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
		</td>
	<?php endif ?>
	<?php foreach( $this->get( 'data', [] ) as $key => $list ) : $idx++ ?>
		<?php if( in_array( $key, $fields ) ) : ?>
			<td class="<?= str_replace( '.', '-', $key ) ?>">
				<?php if( $list !== null ) : $type = $this->value( $list, 'type', 'text' ) ?>
					<input type="hidden" value="<?= $enc->attr( $key ) ?>"
						name="<?= $enc->attr( $this->formparam( array_merge( $group, ['filter', 'key', $idx] ) ) ) ?>" />
					<input type="hidden" value="<?= $enc->attr( $this->value( $list, 'op', '=~' ) ) ?>"
						name="<?= $enc->attr( $this->formparam( array_merge( $group, ['filter', 'op', $idx] ) ) ) ?>" />

					<?php if( $type === 'select' ) : ?>
						<select class="form-select" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
							name="<?= $enc->attr( $this->formparam( array_merge( $group, ['filter', 'val', $idx] ) ) ) ?>"
							v-bind:value="value(`<?= $enc->js( $idx ) ?>`)">
							<option value=""><?= $enc->attr( $this->translate( 'admin', 'All' ) ) ?></option>

							<?php foreach( (array) $this->value( $list, 'val', [] ) as $val => $name ) : ?>
								<option value="<?= $enc->attr( $val ) ?>" <?= $selected( $this->value( $filter, 'val/' . $idx ), $val ) ?> >
									<?= $enc->html( $name ) ?>
								</option>
							<?php endforeach ?>
						</select>
					<?php elseif( $this->value( $list, 'op', '==' ) === '-' && $type === 'datetime-local' ) : ?>
						<input is="flat-pickr" class="form-control" type="text" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
							name="<?= $enc->attr( $this->formparam( array_merge( $group, ['filter', 'val', $idx] ) ) ) ?>"
							v-model="value(`<?= $enc->js( $idx ) ?>`)"
							v-bind:config="Aimeos.flatpickr.datetimerange" />
					<?php elseif( $this->value( $list, 'op', '==' ) === '-' && $type === 'date' ) : ?>
						<input is="flat-pickr" class="form-control" type="text" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
							name="<?= $enc->attr( $this->formparam( array_merge( $group, ['filter', 'val', $idx] ) ) ) ?>"
							v-model="value(`<?= $enc->js( $idx ) ?>`)"
							v-bind:config="Aimeos.flatpickr.daterange" />
					<?php else : ?>
						<input class="form-control" type="<?= $enc->attr( $type ) ?>" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
							name="<?= $enc->attr( $this->formparam( array_merge( $group, ['filter', 'val', $idx] ) ) ) ?>"
							v-bind:value="value(`<?= $enc->js( $idx ) ?>`)" />
					<?php endif ?>
				<?php endif ?>
			</td>
		<?php endif ?>
	<?php endforeach ?>

	<td class="actions">
		<button type="submit" class="btn act-search fa" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
			title="<?= $enc->attr( $this->translate( 'admin', 'Search' ) ) ?>"
			aria-label="<?= $enc->attr( $this->translate( 'admin', 'Search' ) ) ?>">
		</button>
		<button v-on:click="reset()"  type="submit" class="btn act-reset fa" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
			title="<?= $enc->attr( $this->translate( 'admin', 'Reset' ) ) ?>"
			aria-label="<?= $enc->attr( $this->translate( 'admin', 'Reset' ) ) ?>">
		</button>
	</td>
</tr>
