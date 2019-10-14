<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2019
 */


$enc = $this->encoder();


?>
<div v-show="advanced[idx]" class="item-price-property col-xl-12 content-block secondary">

	<table class="table table-default" >

		<thead>
			<tr>
				<th colspan="3">
					<span class="help"><?= $enc->html( $this->translate( 'admin', 'Price properties' ) ); ?></span>
					<div class="form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'Non-shared properties for the price item' ) ); ?>
					</div>
				</th>
				<th class="actions">
					<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
						v-on:click="addPropertyItem(idx)">
					</div>
				</th>
			</tr>
		</thead>
		<tbody>

			<tr v-for="(propdata, propidx) in getPropertyData(idx)" v-bind:key="propidx" v-bind:class="checkSite('price.siteid', idx) ? 'readonly' : ''">

				<td class="property-type">
					<input class="item-propertyid" type="hidden" v-bind:value="propdata['price.property.id']"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'property', 'propidx', 'price.property.id' ) ) ); ?>'.replace( 'idx', idx ).replace( 'propidx', propidx )" />
					<select class="form-control custom-select item-type" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'property', 'propidx', 'price.property.type' ) ) ); ?>'.replace( 'idx', idx ).replace( 'propidx', propidx )"
						v-bind:readonly="checkSite('price.siteid', idx)"
						v-model="items[idx]['property'][propidx]['price.property.type']" >

						<option value="" disabled="disabled">
							<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
						</option>

						<?php foreach( $this->get( 'propertyTypes', [] ) as $type => $item ) : ?>
							<option value="<?= $enc->attr( $type ); ?>" v-bind:selected="items[idx]['property'][propidx]['price.property.type'] == '<?= $enc->attr( $type ) ?>'" >
								<?= $enc->html( $item->getLabel() ); ?>
							</option>
						<?php endforeach; ?>

					</select>
				</td>
				<td class="property-language">
					<select class="form-control custom-select item-languageid" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'property', 'propidx', 'price.property.languageid' ) ) ); ?>'.replace( 'idx', idx ).replace( 'propidx', propidx )"
						v-bind:readonly="checkSite('price.siteid', idx)"
						v-model="items[idx]['property'][propidx]['price.property.languageid']" >

						<option v-bind:value="null">
							<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>
						</option>

						<?php foreach( $this->get( 'pageLangItems', [] ) as $langId => $langItem ) : ?>
							<option value="<?= $enc->attr( $langId ); ?>" v-bind:selected="items[idx]['property'][propidx]['price.property.languageid'] == '<?= $enc->attr( $langId ) ?>'" >
								<?= $enc->html( $langItem->getLabel() ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
				<td class="property-value">
					<input class="form-control item-value" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'property', 'propidx', 'price.property.value' ) ) ); ?>'.replace( 'idx', idx ).replace( 'propidx', propidx )"
						placeholder="<?= $enc->attr( $this->translate( 'admin', 'Property value (required)' ) ); ?>"
						v-bind:readonly="checkSite('price.siteid', idx)"
						v-model="items[idx]['property'][propidx]['price.property.value']" >
				</td>

				<td class="actions">
					<div v-if="!checkSite('price.siteid', idx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
						v-on:click.stop="removePropertyItem(idx, propidx)">
					</div>
				</td>
			</tr>

		</tbody>
	</table>

	<?= $this->get( 'propertyBody' ); ?>

</div>
