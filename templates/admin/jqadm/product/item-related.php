<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


$enc = $this->encoder();
$data = map( $this->get( 'relatedData', [] ) )->groupBy( 'product.lists.type' );

$keys = [
	'product.lists.id', 'product.lists.siteid', 'product.lists.type', 'product.lists.refid',
	'product.label', 'product.code', 'product.id'
];


?>
<div id="related" class="item-related tab-pane fade" role="tabpanel" aria-labelledby="related">
	<div class="row">

		<?php foreach( $this->get( 'relatedTypes', [] ) as $type => $typeLabel ) : ?>

			<div id="related-<?= $enc->attr( $type ) ?>" class="col-xl-6 product"
				data-items="<?= $enc->attr( array_values( $data->get( $type, [] ) ) ) ?>"
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
									<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
										v-on:click="add()">
									</div>
								</th>
							</tr>
						</thead>

						<tbody>

							<tr v-for="(item, idx) in items" v-bind:key="idx" v-bind:class="{'mismatch': !can('match', idx)}">
								<td v-bind:class="item['css'] || ''">
									<input class="item-listtype" type="hidden" v-model="item['product.lists.type']"
										v-bind:name="`<?= $enc->js( $this->formparam( ['related', $type . '-idx', 'product.lists.type'] ) ) ?>`.replace( 'idx', idx )">

									<input class="item-listid" type="hidden" v-model="item['product.lists.id']"
										v-bind:name="`<?= $enc->js( $this->formparam( ['related', $type . '-idx', 'product.lists.id'] ) ) ?>`.replace( 'idx', idx )">

									<input class="item-refid" type="hidden" v-model="item['product.lists.refid']"
										v-bind:name="`<?= $enc->js( $this->formparam( ['related', $type . '-idx', 'product.lists.refid'] ) ) ?>`.replace( 'idx', idx )">

									<Multiselect class="item-id form-control"
										placeholder="Enter product ID, code or label"
										value-prop="product.id"
										track-by="product.id"
										label="product.label"
										@open="function(select) {return select.refreshOptions()}"
										@input="use(idx, $event)"
										:value="item"
										:title="title(idx)"
										:disabled="!can('change', idx)"
										:options="async function(query) {return await fetch(query, idx)}"
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
									<div v-if="can('delete', idx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
										v-on:click.stop="remove(idx)">
									</div>
								</td>
							</tr>

						</tbody>

					</table>
				</div>
			</div>

		<?php endforeach ?>

	</div>

	<?= $this->get( 'relatedBody' ) ?>

</div>
