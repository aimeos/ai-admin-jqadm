<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
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

			<tr v-for="(entry, idx) in items" v-bind:key="idx" v-bind:class="checkSite('attribute.property.siteid', idx) ? 'readonly' : ''">
				<td class="property-type">
					<input class="item-id" type="hidden" v-bind:value="entry['attribute.property.id']"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'property', 'idx', 'attribute.property.id' ) ) ); ?>'.replace('idx', idx)" />

					<select class="form-control custom-select item-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'property', 'idx', 'attribute.property.typeid' ) ) ); ?>'.replace('idx', idx)"
						v-bind:readonly="checkSite('attribute.property.siteid', idx)"
						v-model="items[idx]['attribute.property.typeid']" >

						<?php foreach( $this->get( 'propertyTypes', [] ) as $id => $item ) : ?>
							<option value="<?= $enc->attr( $id ); ?>" v-bind:selected="entry['attribute.property.typeid'] == '<?= $enc->attr( $id ) ?>'" >
								<?= $enc->html( $item->getLabel() ); ?>
							</option>
						<?php endforeach; ?>

					</select>
				</td>

				<td class="property-language">
					<select class="form-control custom-select item-languageid" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'property', 'idx', 'attribute.property.languageid' ) ) ); ?>'.replace('idx', idx)"
						v-bind:readonly="checkSite('attribute.property.siteid', idx)"
						v-model="items[idx]['attribute.property.languageid']" >

						<option v-bind:value="null">
							<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>
						</option>

						<?php foreach( $this->get( 'pageLangItems', [] ) as $langId => $langItem ) : ?>
							<option value="<?= $enc->attr( $langId ); ?>" v-bind:selected="entry['attribute.property.languageid'] == '<?= $enc->attr( $langId ) ?>'" >
								<?= $enc->html( $langItem->getLabel() ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>

				<td class="property-value">
					<input class="form-control item-value" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'property', 'idx', 'attribute.property.value' ) ) ); ?>'.replace('idx', idx)"
						placeholder="<?= $enc->attr( $this->translate( 'admin', 'Property value (required)' ) ); ?>"
						v-bind:readonly="checkSite('attribute.property.siteid', idx)"
						v-model="items[idx]['attribute.property.value']" >
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
