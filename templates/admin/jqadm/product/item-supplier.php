<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2023
 */


$enc = $this->encoder();

$keys = [
	'product.lists.id', 'product.lists.siteid', 'product.lists.type', 'product.lists.refid',
	'supplier.label', 'supplier.code', 'supplier.id'
];


?>
<div id="supplier" class="item-supplier tab-pane fade" role="tabpanel" aria-labelledby="supplier">

	<div class="row">
		<div class="col-xl-6 supplier-default">
			<div class="box">
				<table class="supplier-list table table-default"
					data-items="<?= $enc->attr( $this->get( 'supplierData', [] ) ) ?>"
					data-keys="<?= $enc->attr( $keys ) ?>"
					data-siteid="<?= $this->site()->siteid() ?>"
					data-listtype="default">

					<thead>
						<tr>
							<th>
								<span class="help"><?= $enc->html( $this->translate( 'admin', 'Default' ) ) ?></span>
								<div class="form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Suppliers who manufacture the product' ) ) ?>
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
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'default-idx', 'product.lists.type'] ) ) ?>`.replace( 'idx', idx )">

								<input class="item-listid" type="hidden" v-model="item['product.lists.id']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'default-idx', 'product.lists.id'] ) ) ?>`.replace( 'idx', idx )">

								<input class="item-refid" type="hidden" v-model="item['product.lists.refid']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'default-idx', 'product.lists.refid'] ) ) ?>`.replace( 'idx', idx )">

								<Multiselect class="item-id"
									placeholder="Enter supplier ID, code or label"
									value-prop="supplier.id"
									track-by="supplier.id"
									label="supplier.label"
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

		<div class="col-xl-6 supplier-promotion">
			<div class="box">
				<table class="supplier-list table table-default"
					data-items="<?= $enc->attr( $this->get( 'supplierData', [] ) ) ?>"
					data-keys="<?= $enc->attr( $keys ) ?>"
					data-siteid="<?= $this->site()->siteid() ?>"
					data-listtype="promotion">

					<thead>
						<tr>
							<th>
								<span class="help"><?= $enc->html( $this->translate( 'admin', 'Promotion' ) ) ?></span>
								<div class="form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Highlighted suppliers of the product' ) ) ?>
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
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'promotion-idx', 'product.lists.type'] ) ) ?>`.replace( 'idx', idx )">

								<input class="item-listid" type="hidden" v-model="item['product.lists.id']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'promotion-idx', 'product.lists.id'] ) ) ?>`.replace( 'idx', idx )">

								<input class="item-refid" type="hidden" v-model="item['product.lists.refid']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'promotion-idx', 'product.lists.refid'] ) ) ?>`.replace( 'idx', idx )">

								<Multiselect class="item-id"
									placeholder="Enter supplier ID, code or label"
									value-prop="supplier.id"
									track-by="supplier.id"
									label="supplier.label"
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

	<?= $this->get( 'supplierBody' ) ?>

</div>
