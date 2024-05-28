<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2024
 */


$enc = $this->encoder();
$data = map( $this->get( 'relatedData', [] ) )->groupBy( 'product.lists.type' );

$keys = [
	'product.lists.id', 'product.lists.siteid', 'product.lists.type',
	'product.label', 'product.code', 'product.id'
];


?>
<div id="related" class="item-related tab-pane fade" role="tabpanel" aria-labelledby="related">
	<div class="row">

		<?php foreach( $this->get( 'relatedTypes', [] ) as $type => $typeLabel ) : ?>

			<div id="related-<?= $enc->attr( $type ) ?>" class="col-xl-6 product"
				data-data="<?= $enc->attr( array_values( $data->get( $type, [] ) ) ) ?>"
				data-keys="<?= $enc->attr( $keys ) ?>"
				data-siteid="<?= $this->site()->siteid() ?>"
				data-listtype="<?= $enc->attr( $type ) ?>">

				<div class="box">
					<table class="product-list table table-default">

						<thead>
							<tr>
								<th>
									<?= $enc->html( $typeLabel ) ?>
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

								<tr v-bind:class="{'mismatch': !can('match', index)}">
									<td v-bind:class="element['css'] || ''">
										<input class="item-listid" type="hidden" v-model="element['product.lists.id']"
											v-bind:name="`<?= $enc->js( $this->formparam( ['related', $type, '_idx_', 'product.lists.id'] ) ) ?>`.replace( '_idx_', index )">

										<input class="item-id" type="hidden" v-model="element['product.id']"
											v-bind:name="`<?= $enc->js( $this->formparam( ['related', $type, '_idx_', 'product.id'] ) ) ?>`.replace( '_idx_', index )">

										<input class="item-label" type="hidden" v-model="element['product.label']"
											v-bind:name="`<?= $enc->js( $this->formparam( ['related', $type, '_idx_', 'product.label'] ) ) ?>`.replace( '_idx_', index )">

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
										<div v-if="can('delete', index)" class="btn act-delete icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
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

		<?php endforeach ?>

	</div>

	<?= $this->get( 'relatedBody' ) ?>

</div>
