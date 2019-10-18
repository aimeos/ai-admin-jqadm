<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */


$enc = $this->encoder();

$keys = [
	'product.property.id', 'product.property.siteid', 'product.property.type',
	'product.property.languageid', 'product.property.value'
];


?>
<div class="col-xl-12 content-block item-characteristic-property">

	<table class="property-list table table-default"
		data-items="<?= $enc->attr( $this->get( 'propertyData', [] ) ); ?>"
		data-keys="<?= $enc->attr( $keys ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>"
		data-domain="product" >

		<thead>
			<tr>
				<th colspan="3">
					<span class="help"><?= $enc->html( $this->translate( 'admin', 'Properties' ) ); ?></span>
					<div class="form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'Product characteristics that are not shared with other products' ) ); ?>
					</div>
				</th>
				<th class="actions">
					<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ); ?>"
						v-on:click="addItem('product.property.')">
					</div>
				</th>
			</tr>
		</thead>

		<tbody>

			<tr v-for="(entry, idx) in items" v-bind:key="idx" v-bind:class="checkSite('product.property.siteid', idx) ? 'readonly' : ''">
				<td class="property-type">
					<input class="item-id" type="hidden" v-bind:value="entry['product.property.id']"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'characteristic', 'property', 'idx', 'product.property.id' ) ) ); ?>'.replace('idx', idx)" />

					<select is="select-component" class="form-control custom-select item-type" required
						v-bind:items="JSON.parse('<?= $enc->attr( $this->map( $this->get( 'propertyTypes', [] ), 'product.property.type.code', 'product.property.type.label' )->toArray() ) ?>')"
						v-bind:name="'<?= $enc->attr( $this->formparam( ['characteristic', 'property', 'idx', 'product.property.type'] ) ); ?>'.replace('idx', idx)"
						v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
						v-bind:readonly="checkSite('product.property.siteid', idx)"
						v-bind:tabindex="'<?= $this->get( 'tabindex' ); ?>'"
						v-bind:value="entry['product.property.type']" >
					</select>
				</td>

				<td class="property-language">
					<select is="select-component" class="form-control custom-select item-type"
						v-bind:items="JSON.parse('<?= $enc->attr( $this->map( $this->get( 'pageLangItems', [] ), 'locale.language.code', 'locale.language.label' )->toArray() ) ?>')"
						v-bind:name="'<?= $enc->attr( $this->formparam( ['characteristic', 'property', 'idx', 'product.property.languageid'] ) ); ?>'.replace('idx', idx)"
						v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>'"
						v-bind:readonly="checkSite('product.property.siteid', idx)"
						v-bind:tabindex="'<?= $this->get( 'tabindex' ); ?>'"
						v-bind:value="entry['product.property.languageid']" >
					</select>
				</td>

				<td class="property-value">
					<input class="form-control item-value" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( ['characteristic', 'property', 'idx', 'product.property.value'] ) ); ?>'.replace('idx', idx)"
						placeholder="<?= $enc->attr( $this->translate( 'admin', 'Property value (required)' ) ); ?>"
						v-bind:readonly="checkSite('product.property.siteid', idx)"
						v-model="items[idx]['product.property.value']" >
				</td>

				<td class="actions">
					<div v-if="!checkSite('product.property.siteid', idx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ); ?>"
						v-on:click.stop="removeItem(idx)">
					</div>
				</td>
			</tr>

		</tbody>
	</table>

	<?= $this->get( 'propertyBody' ); ?>

</div>
