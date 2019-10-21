<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */


$enc = $this->encoder();


?>
<div class="col-xl-12 content-block vue-block"
	data-data="<?= $enc->attr( $this->get( 'propertyData', [] ) ) ?>">

	<property-table inline-template
		v-bind:domain="'product'" v-bind:siteid="'<?= $this->site()->siteid() ?>'"
		v-bind:items="data" v-on:update:property="data = $event">

		<table class="item-characteristic-property table table-default">
			<thead>
				<tr>
					<th colspan="3">
						<span class="help"><?= $enc->html( $this->translate( 'admin', 'Properties' ) ); ?></span>
						<div class="form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Product characteristics that are not shared with other products' ) ); ?>
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
						<input class="item-id" type="hidden" v-model="propdata['product.property.id']"
							v-bind:name="'<?= $enc->attr( $this->formparam( ['characteristic', 'property', '_idx_', 'product.property.id'] ) ); ?>'.replace('_idx_', propidx)" />

						<select is="select-component" required class="form-control custom-select item-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
							v-bind:items="JSON.parse('<?= $enc->attr( $this->map( $this->get( 'propertyTypes', [] ), 'product.property.type.code', 'product.property.type.label' )->toArray() ) ?>')"
							v-bind:name="'<?= $enc->attr( $this->formparam( ['characteristic', 'property', '_idx_', 'product.property.type'] ) ); ?>'.replace('_idx_', propidx)"
							v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
							v-bind:readonly="readonly(propidx)"
							v-model="propdata['product.property.type']" >
						</select>
					</td>
					<td class="property-language">
						<select is="select-component" class="form-control custom-select item-languageid" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
							v-bind:items="JSON.parse('<?= $enc->attr( $this->map( $this->get( 'pageLangItems', [] ), 'locale.language.code', 'locale.language.label' )->toArray() ) ?>')"
							v-bind:name="'<?= $enc->attr( $this->formparam( ['characteristic', 'property', '_idx_', 'product.property.languageid'] ) ); ?>'.replace('_idx_', propidx)"
							v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>'"
							v-bind:readonly="readonly(propidx)"
							v-model="propdata['product.property.languageid']" >
						</select>
					</td>
					<td class="property-value">
						<input class="form-control item-value" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
							v-bind:name="'<?= $enc->attr( $this->formparam( ['characteristic', 'property', '_idx_', 'product.property.value'] ) ); ?>'.replace('_idx_', propidx)"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'Property value (required)' ) ); ?>"
							v-bind:readonly="readonly(propidx)"
							v-model="propdata['product.property.value']" >
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

	<?= $this->get( 'propertyBody' ); ?>

</div>
