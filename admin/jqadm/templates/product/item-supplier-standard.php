<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2021
 */


$enc = $this->encoder();

$keys = [
	'supplier.lists.id', 'supplier.lists.siteid', 'supplier.lists.type', 'supplier.lists.refid',
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
						<tr v-for="(item, idx) in items" v-if="item['supplier.lists.type'] == listtype" v-bind:key="idx"
							v-bind:class="checkSite(idx) ? 'readonly' : ''">
							<td v-bind:class="item['css'] || ''">
								<input class="item-listtype" type="hidden" v-model="item['supplier.lists.type']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'default-idx', 'supplier.lists.type'] ) ) ?>`.replace( 'idx', idx )" />

								<input class="item-listid" type="hidden" v-model="item['supplier.lists.id']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'default-idx', 'supplier.lists.id'] ) ) ?>`.replace( 'idx', idx )" />

								<input class="item-label" type="hidden" v-model="item['supplier.code']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'default-idx', 'supplier.code'] ) ) ?>`.replace( 'idx', idx )" />

								<input class="item-label" type="hidden" v-model="item['supplier.label']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'default-idx', 'supplier.label'] ) ) ?>`.replace( 'idx', idx )" />

								<select is="combo-box" class="form-select item-id"
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'default-idx', 'supplier.id'] ) ) ?>`.replace( 'idx', idx )"
									v-bind:readonly="checkSite(idx) || item['supplier.lists.id'] != ''"
									v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
									v-bind:label="getLabel(idx)"
									v-bind:required="'required'"
									v-bind:getfcn="getItems"
									v-bind:index="idx"
									v-on:select="update"
									v-model="item['supplier.id']" >
								</select>
							</td>
							<td class="actions">
								<div v-if="!checkSite(idx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
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

					<tr v-for="(item, idx) in items" v-if="item['supplier.lists.type'] == listtype" v-bind:key="idx"
							v-bind:class="checkSite(idx) ? 'readonly' : ''">
							<td v-bind:class="item['css'] || ''">
								<input class="item-listtype" type="hidden" v-model="item['supplier.lists.type']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'promotion-idx', 'supplier.lists.type'] ) ) ?>`.replace( 'idx', idx )" />

								<input class="item-listid" type="hidden" v-model="item['supplier.lists.id']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'promotion-idx', 'supplier.lists.id'] ) ) ?>`.replace( 'idx', idx )" />

								<input class="item-label" type="hidden" v-model="item['supplier.code']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'promotion-idx', 'supplier.code'] ) ) ?>`.replace( 'idx', idx )" />

								<input class="item-label" type="hidden" v-model="item['supplier.label']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'promotion-idx', 'supplier.label'] ) ) ?>`.replace( 'idx', idx )" />

								<select is="combo-box" class="form-select item-id"
									v-bind:name="`<?= $enc->js( $this->formparam( ['supplier', 'promotion-idx', 'supplier.id'] ) ) ?>`.replace( 'idx', idx )"
									v-bind:readonly="checkSite(idx) || item['supplier.lists.id'] != ''"
									v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
									v-bind:label="getLabel(idx)"
									v-bind:required="'required'"
									v-bind:getfcn="getItems"
									v-bind:index="idx"
									v-on:select="update"
									v-model="item['supplier.id']" >
								</select>
							</td>
							<td class="actions">
								<div v-if="!checkSite(idx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
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
