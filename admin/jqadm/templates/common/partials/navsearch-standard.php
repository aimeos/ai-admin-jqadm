<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */


/**
 * Renders the navbar search fields in the list views
 */

$enc = $this->encoder();


?>
<div id="nav-search">
	<div class="modal fade" v-bind:class="{show: show}">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content form-inline">
				<form method="POST" v-bind:action="url">
					<?= $this->csrf()->formfield() ?>

					<div class="modal-header">
						<h4 class="modal-title"><?= $enc->html( $this->translate( 'admin', 'Search' ) ) ?></h4>
						<button type="button" class="btn-close" v-on:click="$emit('close')"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Close' ) ) ?>">
						</button>
					</div>

					<div class="modal-body">
						<select class="form-group form-select filter-key"
							v-bind:name="name.replace('_key_', 'key' )" v-model="key">
							<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?></option>
							<option v-for="entry in attributes" v-bind:value="entry.code"
								v-bind:selected="filter['key'] && filter['key'][0] && filter['key'][0] === key">
								{{ entry.label }}
							</option>
						</select>

						<select class="form-group form-select filter-operator"
							v-bind:name="name.replace('_key_', 'op' )" v-model="op">
							<option v-for="(label, item) in oplist" v-bind:value="item"
								v-bind:selected="filter['op'] && filter['op'][0] && filter['op'][0] === op" >
								{{ item }}{{ item.length === 1 ? '&nbsp;' : '' }}&nbsp;&nbsp;{{ label }}
							</option>
						</select>

						<input v-bind:type="type" class="form-group form-control filter-value"
							v-bind:name="name.replace('_key_', 'val' )"
							v-bind:value="filter['val'] && filter['val'][0] || ''"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'Value' ) ) ?>" >
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" v-on:click="$emit('close')">
							<?= $enc->html( $this->translate( 'admin', 'Close' ) ) ?>
						</button>
						<button type="submit" class="btn btn-primary">
							<?= $enc->html( $this->translate( 'admin', 'Search' ) ) ?>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
