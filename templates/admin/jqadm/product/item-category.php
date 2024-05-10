<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2024
 */


$enc = $this->encoder();
$data = map( $this->get( 'categoryData', [] ) )->groupBy( 'product.lists.type' );

$keys = [
	'product.lists.id', 'product.lists.siteid', 'product.lists.type',
	'catalog.label', 'catalog.code', 'catalog.id'
];


?>
<div id="category" class="item-category tab-pane fade" role="tabpanel" aria-labelledby="category">
	<div class="row">

		<?php foreach( $this->get( 'categoryTypes', [] ) as $type => $typeLabel ) : ?>

			<div id="catalog-<?= $enc->attr( $type ) ?>" class="col-xl-6 catalog"
				data-data="<?= $enc->attr( array_values( $data->get( $type, [] ) ) ) ?>"
				data-keys="<?= $enc->attr( $keys ) ?>"
				data-siteid="<?= $this->site()->siteid() ?>"
				data-listtype="<?= $enc->attr( $type ) ?>">

				<div class="box">
					<table class="category-list table table-default">

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

						<tbody>

							<tr v-for="(item, idx) in items" v-bind:key="idx" v-bind:class="{'mismatch': !can('match', idx)}">
								<td v-bind:class="item['css'] || ''">
									<input class="item-listtype" type="hidden" v-model="item['product.lists.type']"
										v-bind:name="`<?= $enc->js( $this->formparam( ['category', $type . '-idx', 'product.lists.type'] ) ) ?>`.replace( 'idx', idx )">

									<input class="item-listid" type="hidden" v-model="item['product.lists.id']"
										v-bind:name="`<?= $enc->js( $this->formparam( ['category', $type . '-idx', 'product.lists.id'] ) ) ?>`.replace( 'idx', idx )">

									<input class="item-id" type="hidden" v-model="item['catalog.id']"
										v-bind:name="`<?= $enc->js( $this->formparam( ['category', $type . '-idx', 'catalog.id'] ) ) ?>`.replace( 'idx', idx )">

									<input class="item-label" type="hidden" v-model="item['catalog.label']"
										v-bind:name="`<?= $enc->js( $this->formparam( ['category', $type . '-idx', 'catalog.label'] ) ) ?>`.replace( 'idx', idx )">

									<Multiselect class="item-id form-control"
										placeholder="Enter catalog ID, code or label"
										value-prop="catalog.id"
										track-by="catalog.id"
										label="catalog.label"
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
									<div v-if="can('delete', idx)" class="btn act-delete icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
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

	<?= $this->get( 'categoryBody' ) ?>

</div>
