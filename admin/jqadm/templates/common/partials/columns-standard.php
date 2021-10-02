<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
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

$enc = $this->encoder();
$names = array_merge( (array) $this->get( 'group', [] ), ['fields', ''] );


?>
<div id="column-select">
	<div class="modal fade" v-bind:class="{show: show}">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">

				<div class="modal-header">
					<h4 class="modal-title"><?= $enc->html( $this->translate( 'admin', 'Columns' ) ) ?></h4>
					<button type="button" class="btn-close" v-on:click="$emit('close')"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Close' ) ) ?>">
					</button>
				</div>

				<div class="modal-body">
					<ul class="column-list">
						<li v-for="(title, key) in titles" class="column-item">
							<label tabindex="tabindex">
								<input class="form-check-input" type="checkbox"
									v-bind:checked="checked(key)"
									v-bind:name="name"
									v-bind:value="key"
									v-on:click="toggle(key)"
								/>
								{{ title }}
							</label>
						</li>
					</ul>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" v-on:click="$emit('close')" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
						<?= $enc->html( $this->translate( 'admin', 'Close' ) ) ?>
					</button>
					<button type="submit" class="btn btn-primary" v-on:click="update($event); $emit('close')" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
						<?= $enc->html( $this->translate( 'admin', 'Apply' ) ) ?>
					</button>
				</div>

			</div>
		</div>
	</div>
</div>
