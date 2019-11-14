<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */


$enc = $this->encoder();


?>
<div id="property" class="item-property tab-pane fade" role="tabpanel" aria-labelledby="property">

	<div class="vue-block" data-data="<?= $enc->attr( $this->get( 'propertyData', [] ) ) ?>">

		<property-table inline-template
			v-bind:domain="'attribute'" v-bind:siteid="'<?= $this->site()->siteid() ?>'"
			v-bind:items="data" v-on:update:property="data = $event">

			<table class="item-attribute-property table table-default" >
				<thead>
					<tr>
						<th colspan="3">
							<span class="help"><?= $enc->html( $this->translate( 'admin', 'Properties' ) ); ?></span>
							<div class="form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Attribute properties that are not shared with other attributes' ) ); ?>
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
							<input class="item-propertyid" type="hidden" v-model="propdata['attribute.property.id']"
								v-bind:name="'<?= $enc->attr( $this->formparam( ['property', '_propidx_', 'attribute.property.id'] ) ); ?>'.replace('_idx_', index).replace('_propidx_', propidx)" />

							<select is="select-component" required class="form-control custom-select item-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
								v-bind:items="JSON.parse('<?= $enc->attr( $this->map( $this->get( 'propertyTypes', [] ), 'attribute.property.type.code', 'attribute.property.type.label' )->toArray() ) ?>')"
								v-bind:name="'<?= $enc->attr( $this->formparam( ['property', '_propidx_', 'attribute.property.type'] ) ); ?>'.replace('_idx_', index).replace('_propidx_', propidx)"
								v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
								v-bind:readonly="readonly(propidx)"
								v-model="propdata['attribute.property.type']" >
							</select>
						</td>
						<td class="property-language">
							<select is="select-component" class="form-control custom-select item-languageid" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
								v-bind:items="JSON.parse('<?= $enc->attr( $this->map( $this->get( 'pageLangItems', [] ), 'locale.language.code', 'locale.language.label' )->toArray() ) ?>')"
								v-bind:name="'<?= $enc->attr( $this->formparam( ['property', '_propidx_', 'attribute.property.languageid'] ) ); ?>'.replace('_idx_', index).replace('_propidx_', propidx)"
								v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>'"
								v-bind:readonly="readonly(propidx)"
								v-model="propdata['attribute.property.languageid']" >
							</select>
						</td>
						<td class="property-value">
							<input class="form-control item-value" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								v-bind:name="'<?= $enc->attr( $this->formparam( ['property', '_propidx_', 'attribute.property.value'] ) ); ?>'.replace('_idx_', index).replace('_propidx_', propidx)"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Property value (required)' ) ); ?>"
								v-bind:readonly="readonly(propidx)"
								v-model="propdata['attribute.property.value']" >
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
