<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018
 */


$enc = $this->encoder();


?>
<div class="col-xl-12 content-block property-table">

	<property-table inline-template
		v-bind:readonly="'<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?>' ? true : false"
		v-bind:items="JSON.parse('<?= $enc->attr( $this->get( 'propertyData', [] ) ) ?>')"
		v-bind:siteid="'<?= $this->site()->siteid() ?>'"
		v-bind:domain="'customer'" >

		<table class="item-characteristic-property table table-default">
			<thead>
				<tr>
					<th colspan="3">
						<span class="help"><?= $enc->html( $this->translate( 'admin', 'Properties' ) ); ?></span>
						<div class="form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Product characteristics that are not shared with other customers' ) ); ?>
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
				<tr v-for="(propdata, propidx) in list" v-bind:key="propidx" v-bind:class="readonly ? 'readonly' : ''">
					<td class="property-type">
						<input class="item-id" type="hidden" v-bind:value="propdata['customer.property.id']"
							v-bind:name="'<?= $enc->attr( $this->formparam( ['property', '_idx_', 'customer.property.id'] ) ); ?>'.replace('_idx_', propidx)" />

						<select is="select-component" class="form-control custom-select item-type" required
							v-bind:items="JSON.parse('<?= $enc->attr( $this->map( $this->get( 'propertyTypes', [] ), 'customer.property.type.code', 'customer.property.type.label' )->toArray() ) ?>')"
							v-bind:name="'<?= $enc->attr( $this->formparam( ['property', '_idx_', 'customer.property.type'] ) ); ?>'.replace('_idx_', propidx)"
							v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
							v-bind:tabindex="'<?= $this->get( 'tabindex' ); ?>'"
							v-bind:readonly="readonly"
							v-bind:value="propdata['customer.property.type']" >
						</select>
					</td>
					<td class="property-language">
						<select is="select-component" class="form-control custom-select item-type"
							v-bind:items="JSON.parse('<?= $enc->attr( $this->map( $this->get( 'pageLangItems', [] ), 'locale.language.code', 'locale.language.label' )->toArray() ) ?>')"
							v-bind:name="'<?= $enc->attr( $this->formparam( ['property', '_idx_', 'customer.property.languageid'] ) ); ?>'.replace('_idx_', propidx)"
							v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>'"
							v-bind:tabindex="'<?= $this->get( 'tabindex' ); ?>'"
							v-bind:readonly="readonly"
							v-bind:value="propdata['customer.property.languageid']" >
						</select>
					</td>
					<td class="property-value">
						<input class="form-control item-value" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
							v-bind:name="'<?= $enc->attr( $this->formparam( ['property', '_idx_', 'customer.property.value'] ) ); ?>'.replace('_idx_', propidx)"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'Property value (required)' ) ); ?>"
							v-bind:readonly="readonly"
							v-model="propdata['customer.property.value']" >
					</td>
					<td class="actions">
						<div v-if="!readonly" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ); ?>" v-on:click.stop="remove(propidx)">
						</div>
					</td>
				</tr>
			</tbody>
		</table>

	</property-table>

	<?= $this->get( 'propertyBody' ); ?>

</div>
