<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */


$enc = $this->encoder();

$keys = [
	'attribute.property.id', 'attribute.property.siteid', 'attribute.property.typeid',
	'attribute.property.languageid', 'attribute.property.value'
];


?>
<div id="property" class="item-property tab-pane fade" role="tabpanel" aria-labelledby="property">

	<table class="property-list table table-default"
		data-items="<?= $enc->attr( json_encode( $this->get( 'propertyData', [] ) ) ); ?>"
		data-keys="<?= $enc->attr( json_encode( $keys ) ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>" >

		<thead>
			<tr>
				<th colspan="3">
					<span class="help"><?= $enc->html( $this->translate( 'admin', 'Properties' ) ); ?></span>
					<div class="form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'Attribute properties that are not shared with other attributes' ) ); ?>
					</div>
				</th>
				<th class="actions">
					<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
						v-on:click="addItem('attribute.property.')">
					</div>
				</th>
			</tr>
		</thead>

		<tbody>

			<tr v-for="(id, idx) in items['attribute.property.id']" v-bind:key="idx"
				v-bind:class="items['attribute.property.siteid'][idx] != '<?= $this->site()->siteid() ?>' ? 'readonly' : ''">
				<td class="property-type">
					<input class="item-id" type="hidden" v-bind:value="items['attribute.property.id'][idx]"
						name="<?= $enc->attr( $this->formparam( array( 'property', 'attribute.property.id', '' ) ) ); ?>" />
					<select class="form-control custom-select item-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						name="<?= $enc->attr( $this->formparam( array( 'property', 'attribute.property.typeid', '' ) ) ); ?>"
						v-bind:readonly="checkSite('attribute.property.siteid', idx)"
						v-model="items['attribute.property.typeid'][idx]" >

						<?php foreach( $this->get( 'propertyTypes', [] ) as $id => $item ) : ?>
							<option value="<?= $enc->attr( $id ); ?>" v-bind:selected="items['attribute.property.typeid'][idx] == '<?= $enc->attr( $id ) ?>'" >
								<?= $enc->html( $item->getLabel() ); ?>
							</option>
						<?php endforeach; ?>

					</select>
				</td>
				<td class="property-language">
					<select class="form-control custom-select item-languageid" tabindex="<?= $this->get( 'tabindex' ); ?>"
						name="<?= $enc->attr( $this->formparam( array( 'property', 'attribute.property.languageid', '' ) ) ); ?>"
						v-bind:readonly="checkSite('attribute.property.siteid', idx)"
						v-model="items['attribute.property.languageid'][idx]" >

						<option v-bind:value="null">
							<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>
						</option>

						<?php foreach( $this->get( 'pageLangItems', [] ) as $langId => $langItem ) : ?>
							<option value="<?= $enc->attr( $langId ); ?>" v-bind:selected="items['attribute.property.languageid'][idx] == '<?= $enc->attr( $langId ) ?>'" >
								<?= $enc->html( $langItem->getLabel() ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
				<td class="property-value">
					<input class="form-control item-value" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						name="<?= $enc->attr( $this->formparam( array( 'property', 'attribute.property.value', '' ) ) ); ?>"
						placeholder="<?= $enc->attr( $this->translate( 'admin', 'Property value (required)' ) ); ?>"
						v-bind:readonly="checkSite('attribute.property.siteid', idx)"
						v-model="items['attribute.property.value'][idx]" >
				</td>
				<td class="actions">
					<div v-if="!checkSite('attribute.property.siteid', idx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
						v-on:click.stop="removeItem(idx)">
					</div>
				</td>
			</tr>

		</tbody>
	</table>

	<?= $this->get( 'propertyBody' ); ?>
</div>
