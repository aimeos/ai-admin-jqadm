<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2024
 */

$enc = $this->encoder();

$keys = [
	'product.lists.id', 'product.lists.siteid', 'product.id', 'product.label', 'product.code'
];


?>
<div id="bundle" class="item-bundle tab-pane fade" role="tabpanel" aria-labelledby="bundle">

	<div class="row">
		<div class="col-xl-6 product-default">

			<div class="box">
				<table class="product-list table table-default"
					data-data="<?= $enc->attr( $this->get( 'bundleData', [] ) ) ?>"
					data-keys="<?= $enc->attr( $keys ) ?>"
					data-siteid="<?= $this->site()->siteid() ?>"
					data-listtype="default">

					<thead>
						<tr>
							<th>
								<span class="help"><?= $enc->html( $this->translate( 'admin', 'Products' ) ) ?></span>
								<div class="form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'List of articles that should be sold as one product, often at a reduced price' ) ) ?>
								</div>
							</th>
							<th class="actions">
								<div class="btn act-add icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
									v-on:click="add()">
								</div>
							</th>
						</tr>
					</thead>

					<tbody is="vue:draggable" item-key="product.id" group="bundle" :list="items" handle=".act-move" tag="tbody">
						<template #item="{element, index}">

							<tr v-bind:class="{readonly: !can('change', index)}">
								<td v-bind:class="element['css'] ||''">
									<input class="item-listid" type="hidden" v-model="element['product.lists.id']"
										v-bind:name="`<?= $enc->js( $this->formparam( ['bundle', '_idx_', 'product.lists.id'] ) ) ?>`.replace( '_idx_', index )">

									<input class="item-id" type="hidden" v-model="element['product.id']"
										v-bind:name="`<?= $enc->js( $this->formparam( ['bundle', '_idx_', 'product.id'] ) ) ?>`.replace( '_idx_', index )">

									<input class="item-label" type="hidden" v-model="element['product.label']"
										v-bind:name="`<?= $enc->js( $this->formparam( ['bundle', '_idx_', 'product.label'] ) ) ?>`.replace( '_idx_', index )">

									<Multiselect class="item-id form-control"
										placeholder="Enter product ID, code or label"
										value-prop="product.id"
										track-by="product.id"
										label="product.label"
										@open="function(select) {return select.refreshOptions()}"
										@input="use(index, $event)"
										:value="element"
										:title="title(index)"
										:disabled="!can('change', index)"
										:options="async function(query) {return await fetch(query, index)}"
										:resolve-on-load="false"
										:filter-results="false"
										:can-deselect="false"
										:allow-absent="true"
										:searchable="true"
										:can-clear="false"
										:required="true"
										:min-chars="1"
										:object="true"
										:delay="300"
									></Multiselect>
								</td>
								<td class="actions">
									<div v-if="can('move', index)"
										class="btn btn-card-header act-move icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down' ) ) ?>">
									</div>
									<div v-if="can('delete', index)"
										class="btn act-delete icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
										v-on:click.stop="remove(index)">
									</div>
								</td>
							</tr>

						</template>
					</tbody>
				</table>
			</div>
		</div>

		<?= $this->get( 'bundleBody' ) ?>

	</div>
</div>
