<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


$enc = $this->encoder();

$keys = [
	'product.lists.id', 'product.lists.siteid', 'product.lists.type', 'product.lists.refid',
	'catalog.label', 'catalog.code', 'catalog.id'
];


?>
<div id="category" class="item-category tab-pane fade" role="tabpanel" aria-labelledby="category">

	<div class="row">
		<div class="col-xl-6 catalog-default">
			<div class="box">
				<table class="category-list table table-default"
					data-items="<?= $enc->attr( $this->get( 'categoryData', [] ) ) ?>"
					data-keys="<?= $enc->attr( $keys ) ?>"
					data-siteid="<?= $this->site()->siteid() ?>"
					data-listtype="default">

					<thead>
						<tr>
							<th>
								<span class="help"><?= $enc->html( $this->translate( 'admin', 'Default' ) ) ?></span>
								<div class="form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Categories the product can be found in by the user on the web site' ) ) ?>
								</div>
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

						<tr v-for="(item, idx) in items" v-if="item['product.lists.type'] == listtype" v-bind:key="idx"
							v-bind:class="{'readonly': !can('change', idx)}">
							<td v-bind:class="item['css'] || ''">
								<input class="item-listtype" type="hidden" v-model="item['product.lists.type']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'default-idx', 'product.lists.type'] ) ) ?>`.replace( 'idx', idx )">

								<input class="item-listid" type="hidden" v-model="item['product.lists.id']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'default-idx', 'product.lists.id'] ) ) ?>`.replace( 'idx', idx )">

								<input class="item-refid" type="hidden" v-model="item['product.lists.refid']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'default-idx', 'product.lists.refid'] ) ) ?>`.replace( 'idx', idx )">

								<Multiselect class="item-id"
									placeholder="Enter catalog ID, code or label"
									value-prop="catalog.id"
									track-by="catalog.id"
									label="catalog.label"
									@open="load"
									@input="use(idx, $event)"
									:value="item"
									:title="title(idx)"
									:readonly="!can('change', idx)"
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

		<div class="col-xl-6 catalog-promotion">

			<div class="box">
				<table class="category-list table table-default"
					data-items="<?= $enc->attr( $this->get( 'categoryData', [] ) ) ?>"
					data-keys="<?= $enc->attr( $keys ) ?>"
					data-siteid="<?= $this->site()->siteid() ?>"
					data-listtype="promotion">

					<thead>
						<tr>
							<th>
								<span class="help"><?= $enc->html( $this->translate( 'admin', 'Promotion' ) ) ?></span>
								<div class="form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Categories the product will be shown for in the promotional section' ) ) ?>
								</div>
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

					<tr v-for="(item, idx) in items" v-if="item['product.lists.type'] == listtype" v-bind:key="idx"
							v-bind:class="{'readonly': !can('change', idx)}">
							<td v-bind:class="item['css'] || ''">
								<input class="item-listtype" type="hidden" v-model="item['product.lists.type']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'promotion-idx', 'product.lists.type'] ) ) ?>`.replace( 'idx', idx )">

								<input class="item-listid" type="hidden" v-model="item['product.lists.id']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'promotion-idx', 'product.lists.id'] ) ) ?>`.replace( 'idx', idx )">

								<input class="item-refid" type="hidden" v-model="item['product.lists.refid']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'promotion-idx', 'product.lists.refid'] ) ) ?>`.replace( 'idx', idx )">

								<Multiselect class="item-id"
									placeholder="Enter catalog ID, code or label"
									value-prop="catalog.id"
									track-by="catalog.id"
									label="catalog.label"
									@open="load"
									@input="use(idx, $event)"
									:value="item"
									:title="title(idx)"
									:readonly="!can('change', idx)"
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
	</div>

	<?= $this->get( 'categoryBody' ) ?>

</div>
