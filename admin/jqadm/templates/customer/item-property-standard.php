<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2020
 */


$enc = $this->encoder();


?>
<div id="property" class="item-property tab-pane fade" role="tabpanel" aria-labelledby="property">

	<div class="vue-block" data-data="<?= $enc->attr( $this->get( 'propertyData', [] ) ) ?>">

		<property-table inline-template
			v-bind:domain="'customer'" v-bind:siteid="'<?= $this->site()->siteid() ?>'"
			v-bind:types="JSON.parse('<?= $enc->attr( $this->get( 'propertyTypes', map() )->col( 'customer.property.type.label', 'customer.property.type.code' )->toJson( JSON_FORCE_OBJECT ) ) ?>')"
			v-bind:languages="JSON.parse('<?= $enc->attr( $this->get( 'pageLangItems', map() )->col( 'locale.language.label', 'locale.language.id' )->toJson( JSON_FORCE_OBJECT ) ) ?>')"
			v-bind:items="data" v-on:update:property="data = $event">

			<table class="item-characteristic-property table table-default">
				<thead>
					<tr>
						<th colspan="3">
							<span class="help"><?= $enc->html( $this->translate( 'admin', 'Properties' ) ); ?></span>
							<div class="form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Customer characteristics that are not shared with other customers' ) ); ?>
							</div>
						</th>
						<th class="actions">
							<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>" v-on:click="add()"
								title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ); ?>">
							</div>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(propdata, propidx) in items" v-bind:key="propidx" v-bind:class="{readonly: readonly(propidx)}">
						<td class="property-type">
							<input class="item-id" type="hidden" v-model="propdata['customer.property.id']"
								v-bind:name="'<?= $enc->attr( $this->formparam( ['property', '_idx_', 'customer.property.id'] ) ); ?>'.replace('_idx_', propidx)" />

							<select is="select-component" required class="form-control custom-select item-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
								v-bind:name="'<?= $enc->attr( $this->formparam( ['property', '_idx_', 'customer.property.type'] ) ); ?>'.replace('_idx_', propidx)"
								v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
								v-bind:readonly="readonly(propidx)"
								v-bind:items="types"
								v-model="propdata['customer.property.type']" >
							</select>
						</td>
						<td class="property-language">
							<select is="select-component" class="form-control custom-select item-languageid" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
								v-bind:name="'<?= $enc->attr( $this->formparam( ['property', '_idx_', 'customer.property.languageid'] ) ); ?>'.replace('_idx_', propidx)"
								v-bind:all="'<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>'"
								v-bind:readonly="readonly(propidx)"
								v-bind:items="languages"
								v-model="propdata['customer.property.languageid']" >
							</select>
						</td>
						<td class="property-value">
							<input class="form-control item-value" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								v-bind:name="'<?= $enc->attr( $this->formparam( ['property', '_idx_', 'customer.property.value'] ) ); ?>'.replace('_idx_', propidx)"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Property value (required)' ) ); ?>"
								v-bind:readonly="readonly(propidx)"
								v-model="propdata['customer.property.value']" >
						</td>
						<td class="actions">
							<div v-if="!readonly(propidx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ); ?>" v-on:click.stop="remove(propidx)">
							</div>
						</td>
					</tr>
				</tbody>
			</table>

		</property-table>

	</div>

	<?= $this->get( 'propertyBody' ); ?>

</div>
