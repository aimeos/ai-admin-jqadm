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
			<table id="characteristics-<?= $type ?>" class="attribute-list table table-default"
				data-items="<?= $enc->attr( array_values( $map[$type] ?? [] ) ) ?>"
				data-listtype="<?= $enc->attr( $type ) ?>"
				data-keys="<?= $enc->attr( $keys ) ?>"
				data-prefix="product.lists."
				data-siteid="<?= $this->site()->siteid() ?>" >

				<thead>
					<tr>
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
							/>
						</td>
						<td v-bind:class="item['css'] || ''">
							<input class="item-listid" type="hidden" v-model="item['product.lists.id']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', 'idx', 'product.lists.id'] ) ) ?>`.replace( 'idx', listtype + '-' + idx )">

							<input class="item-listtype" type="hidden" v-model="item['product.lists.type']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', 'idx', 'product.lists.type'] ) ) ?>`.replace( 'idx', listtype + '-' + idx )">

							<input class="item-refid" type="hidden" v-model="item['product.lists.refid']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'attribute', 'idx', 'product.lists.refid'] ) ) ?>`.replace( 'idx', listtype + '-' + idx )">

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
							/>
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
