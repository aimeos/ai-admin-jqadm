<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2024
 */


/** admin/jqadm/product/item/characteristic/attribute/config/suggest
 * Suggested keys for attribute configuration in product characteristics
 *
 * The names of the keys that are suggested in the product characteristics
 * sub-panel for the configuration key/value pairs.
 *
 * @param array List of key names
 * @since 2023.10
 * @see admin/jqadm/product/item/config/suggest
 * @see admin/jqadm/catalog/item/config/suggest
 */

$enc = $this->encoder();

$keys = [
	'product.lists.id', 'product.lists.siteid', 'product.lists.type',
	'attribute.id', 'attribute.type', 'attribute.code', 'attribute.label'
];

$map = map( $this->get( 'attributeData', [] ) )->groupBy( 'product.lists.type' );


?>
<div class="col-xl-12 item-characteristic-attribute">

	<?php foreach( $this->get( 'attributeTypes', [] ) as $type ) : ?>

		<div id="characteristic-<?= $type ?>" class="attribute-list box"
			data-data="<?= $enc->attr( array_values( $map[$type] ?? [] ) ) ?>"
			data-listtype="<?= $enc->attr( $type ) ?>"
			data-keys="<?= $enc->attr( $keys ) ?>"
			data-prefix="product.lists."
			data-siteid="<?= $this->site()->siteid() ?>" >

			<table class="table table-striped">
				<thead>
					<tr>
						<th class="actions"></th>
						<th>
							<span class="help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?></span>
							<div class="form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Attribute type that limits the list of available attributes' ) ) ?>
							</div>
						</th>
						<th>
							<span class="help"><?= $enc->html( $this->translate( 'admin', 'Attributes' ) . ' (' . $type . ')' ) ?></span>
							<div class="form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Product attributes that are used by other products too' ) ) ?>
							</div>
						</th>
						<th class="actions">
							<a class="btn act-list icon" tabindex="<?= $this->get( 'tabindex' ) ?>" target="_blank"
								title="<?= $enc->attr( $this->translate( 'admin', 'Go to attribute panel' ) ) ?>"
								href="<?= $enc->attr( $this->link( 'admin/jqadm/url/search', ['resource' => 'attribute'] + $this->get( 'pageParams', [] ) ) ) ?>">
							</a>
							<div class="btn act-add icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
								v-on:click="add()">
							</div>
						</th>
					</tr>
				</thead>

				<tbody is="vue:draggable" item-key="attribute.id" group="characteristic-attribute" :list="items" handle=".act-move" tag="tbody">
					<template #item="{element, index}">

						<tr v-bind:class="{mismatch: !can('match', index)}">
							<td class="actions" v-bind:class="{advanced: element['_ext']}">
								<div class="btn btn-card-header act-show icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide details' ) ) ?>"
									v-on:click="toggle('_ext', index)">
								</div>
							</td>
							<td v-bind:class="element['css'] || ''">
								<Multiselect class="item-type form-control"
									placeholder="Enter attribute type ID, code or label"
									value-prop="attribute.type"
									track-by="attribute.type"
									label="attribute.type"
									@open="function(select) {return select.refreshOptions()}"
									@input="useType(index, $event)"
									:value="element"
									:options="async function(query) {return await attrTypes(query)}"
									:disabled="!can('change', index)"
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
								<div v-show="element['_ext']" class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ) ?></label>
									<div class="col-sm-8">
										<input is="vue:flat-pickr" class="form-control listitem-datestart select" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', '_idx_', 'product.lists.datestart'] ) ) ?>`.replace( '_idx_', listtype + '-' + index )"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
											v-bind:disabled="!can('change', index)"
											v-bind:config="Aimeos.flatpickr.datetime"
											v-model="element['product.lists.datestart']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'The entry is only shown on the web site after that date and time' ) ) ?>
									</div>
								</div>
								<div v-show="element['_ext']" class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?></label>
									<div class="col-sm-8">
										<input is="vue:flat-pickr" class="form-control listitem-dateend select" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', '_idx_', 'product.lists.dateend'] ) ) ?>`.replace( '_idx_', listtype + '-' + index )"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
											v-bind:disabled="!can('change', index)"
											v-bind:config="Aimeos.flatpickr.datetime"
											v-model="element['product.lists.dateend']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'The entry is only shown on the web site until that date and time' ) ) ?>
									</div>
								</div>
							</td>
							<td v-bind:class="element['css'] || ''">
								<input class="item-listid" type="hidden" v-model="element['product.lists.id']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', '_idx_', 'product.lists.id'] ) ) ?>`.replace( '_idx_', listtype + '-' + index )">

								<input class="item-listtype" type="hidden" v-model="element['product.lists.type']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', '_idx_', 'product.lists.type'] ) ) ?>`.replace( '_idx_', listtype + '-' + index )">

								<input class="item-id" type="hidden" v-model="element['attribute.id']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', '_idx_', 'attribute.id'] ) ) ?>`.replace( '_idx_', listtype + '-' + index )">

								<input class="item-label" type="hidden" v-model="element['attribute.type']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', '_idx_', 'attribute.type'] ) ) ?>`.replace( '_idx_', listtype + '-' + index )">

								<input class="item-label" type="hidden" v-model="element['attribute.label']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', '_idx_', 'attribute.label'] ) ) ?>`.replace( '_idx_', listtype + '-' + index )">

								<Multiselect v-if="element['attribute.type']" class="item-refid form-control"
									placeholder="Enter attribute ID, code or label"
									value-prop="attribute.id"
									track-by="attribute.id"
									label="attribute.label"
									@open="function(select) {return select.refreshOptions()}"
									@input="use(index, $event)"
									:value="element"
									:options="async function(query) {return await attr(query, index)}"
									:disabled="!element['attribute.type'] || !can('change', index)"
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
								<config-table v-show="element['_ext']" v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
									v-bind:keys="<?= $enc->attr( $this->config( 'admin/jqadm/product/item/characteristic/attribute/config/suggest', [] ) ) ?>"
									v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', '_idx_', 'config', '_pos_', '_key_'] ) ) ?>`.replace( '_idx_', listtype + '-' + index )"
									v-bind:index="index"
									v-bind:items="element['config']"
									v-bind:readonly="!can('change', index)"
									v-on:update:items="element['config'] = $event"
									v-bind:i18n="{
										value: `<?= $enc->js( $this->translate( 'admin', 'Value' ) ) ?>`,
										option: `<?= $enc->js( $this->translate( 'admin', 'Option' ) ) ?>`,
										help: `<?= $enc->js( $this->translate( 'admin', 'Entry specific configuration options, will be available as key/value pairs in the templates' ) ) ?>`,
										insert: `<?= $enc->js( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>`,
										delete: `<?= $enc->js( $this->translate( 'admin', 'Delete this entry' ) ) ?>`,
									}">
								</config-table>
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

	<?php endforeach ?>

	<?= $this->get( 'attributeBody' ) ?>

</div>
