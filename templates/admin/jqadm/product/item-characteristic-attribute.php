<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 */


$enc = $this->encoder();

$keys = [
	'product.lists.id', 'product.lists.siteid', 'product.lists.refid', 'product.lists.type',
	'attribute.label'
];

$map = map( $this->get( 'attributeData', [] ) )->groupBy( 'product.lists.type' );


?>
<div class="col-xl-12 item-characteristic-attribute">

	<?php foreach( $this->get( 'attributeTypes', [] ) as $type ) : ?>

		<div class="box">
			<table id="characteristics-<?= $type ?>" class="attribute-list table table-striped"
				data-items="<?= $enc->attr( array_values( $map[$type] ?? [] ) ) ?>"
				data-listtype="<?= $enc->attr( $type ) ?>"
				data-keys="<?= $enc->attr( $keys ) ?>"
				data-prefix="product.lists."
				data-siteid="<?= $this->site()->siteid() ?>" >

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
							<a class="btn act-list fa" tabindex="<?= $this->get( 'tabindex' ) ?>" target="_blank"
								title="<?= $enc->attr( $this->translate( 'admin', 'Go to attribute panel' ) ) ?>"
								href="<?= $enc->attr( $this->link( 'admin/jqadm/url/search', ['resource' => 'attribute'] + $this->get( 'pageParams', [] ) ) ) ?>">
							</a>
							<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
								v-on:click="add()">
							</div>
						</th>
					</tr>
				</thead>

				<tbody is="draggable" v-model="items" group="characteristic-attribute" handle=".act-move" tag="tbody">

					<tr v-for="(item, idx) in items" v-bind:key="idx" v-bind:class="{readonly: !can('change', idx)}">
						<td class="actions" v-bind:class="{advanced: item['_ext']}">
							<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide details' ) ) ?>"
								v-on:click="toggle('_ext', idx)">
							</div>
						</td>
						<td v-bind:class="item['css'] || ''">
							<Multiselect class="item-type"
								placeholder="Enter attribute type ID, code or label"
								value-prop="attribute.type"
								track-by="attribute.type"
								label="attribute.type"
								@open="load"
								@input="useType(idx, $event)"
								:value="item"
								:options="async function(query) {return await attrTypes(query)}"
								:disabled="item['attribute.type'] || false"
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
							<div v-show="item['_ext']" class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ) ?></label>
								<div class="col-sm-8">
									<input is="flat-pickr" class="form-control listitem-datestart select" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', '_idx_', 'product.lists.datestart'] ) ) ?>`.replace( '_idx_', listtype + '-' + idx )"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
										v-bind:disabled="item['product.lists.siteid'] != siteid"
										v-bind:config="Aimeos.flatpickr.datetime"
										v-model="item['product.lists.datestart']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The item is only shown on the web site after that date and time' ) ) ?>
								</div>
							</div>
							<div v-show="item['_ext']" class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?></label>
								<div class="col-sm-8">
									<input is="flat-pickr" class="form-control listitem-dateend select" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', '_idx_', 'product.lists.dateend'] ) ) ?>`.replace( '_idx_', listtype + '-' + idx )"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
										v-bind:disabled="item['product.lists.siteid'] != siteid"
										v-bind:config="Aimeos.flatpickr.datetime"
										v-model="item['product.lists.dateend']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The item is only shown on the web site until that date and time' ) ) ?>
								</div>
							</div>
						</td>
						<td v-bind:class="item['css'] || ''">
							<input class="item-listid" type="hidden" v-model="item['product.lists.id']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', '_idx_', 'product.lists.id'] ) ) ?>`.replace( '_idx_', listtype + '-' + idx )">

							<input class="item-listtype" type="hidden" v-model="item['product.lists.type']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', '_idx_', 'product.lists.type'] ) ) ?>`.replace( '_idx_', listtype + '-' + idx )">

							<input class="item-refid" type="hidden" v-model="item['product.lists.refid']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', '_idx_', 'product.lists.refid'] ) ) ?>`.replace( '_idx_', listtype + '-' + idx )">

							<Multiselect v-if="item['attribute.type']" class="item-refid"
								placeholder="Enter attribute ID, code or label"
								value-prop="attribute.id"
								track-by="attribute.id"
								label="attribute.label"
								@open="load"
								@input="use(idx, $event)"
								:value="item"
								:options="async function(query) {return await attr(query, idx)}"
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
							<config-table v-show="item['_ext']" v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
								v-bind:keys="<?= $enc->attr( $this->config( 'admin/jqadm/product/item/characteristic/attribute/config/suggest', [] ) ) ?>"
								v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', '_idx_', 'config', '_pos_', '_key_'] ) ) ?>`.replace( '_idx_', listtype + '-' + idx )"
								v-bind:index="idx"
								v-bind:items="item['config']"
								v-bind:readonly="!can('change', idx)"
								v-on:update:config="item['config'] = $event"
								v-bind:i18n="{
									value: `<?= $enc->js( $this->translate( 'admin', 'Value' ) ) ?>`,
									option: `<?= $enc->js( $this->translate( 'admin', 'Option' ) ) ?>`,
									help: `<?= $enc->js( $this->translate( 'admin', 'Item specific configuration options, will be available as key/value pairs in the templates' ) ) ?>`,
									insert: `<?= $enc->js( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>`,
									delete: `<?= $enc->js( $this->translate( 'admin', 'Delete this entry' ) ) ?>`,
								}">
							</config-table>
						</td>
						<td class="actions">
							<div v-if="can('move', idx)"
								class="btn btn-card-header act-move fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down' ) ) ?>">
							</div>
							<div v-if="can('delete', idx)"
								class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
								v-on:click.stop="remove(idx)">
							</div>
						</td>
					</tr>

				</tbody>

			</table>
		</div>

	<?php endforeach ?>

	<?= $this->get( 'attributeBody' ) ?>

</div>
