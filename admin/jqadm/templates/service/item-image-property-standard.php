<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018
 */


$enc = $this->encoder();


?>
<div v-show="advanced[idx]" class="item-image-property col-xl-12 content-block secondary" v-bind:class="items['service.lists.siteid'][idx] != '<?= $this->site()->siteid() ?>' ? 'readonly' : ''">

	<table class="table table-default" >

		<thead>
			<tr>
				<th colspan="3">
					<span class="help"><?= $enc->html( $this->translate( 'admin', 'Media properties' ) ); ?></span>
					<div class="form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'Non-shared properties for the media item' ) ); ?>
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

			<tr v-for="(id, propidx) in getPropertyData(idx)" v-bind:key="idx"
				v-bind:class="items['property'][idx]['media.property.siteid'][idx] != '<?= $this->site()->siteid() ?>' ? 'readonly' : ''">
				<td class="property-type">
					<input class="item-propertyid" type="hidden" v-bind:value="items['property'][idx]['media.property.id'][idx]"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'image', 'property', 'idx', 'media.property.id', '' ) ) ); ?>'.replace( 'idx', idx )" />
					<select class="form-control custom-select item-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'image', 'property', 'idx', 'media.property.typeid', '' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:readonly="checkSite('media.siteid', idx)"
						v-model="items['property'][idx]['media.property.typeid'][idx]" >

						<?php foreach( $this->get( 'propertyTypes', [] ) as $id => $item ) : ?>
							<option value="<?= $enc->attr( $id ); ?>" v-bind:selected="items['property'][idx]['media.property.typeid'][idx] == '<?= $enc->attr( $id ) ?>'" >
								<?= $enc->html( $item->getLabel() ); ?>
							</option>
						<?php endforeach; ?>

					</select>
				</td>
				<td class="property-language">
					<select class="form-control custom-select item-languageid" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'image', 'property', 'idx', 'media.property.languageid', '' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:readonly="checkSite('media.siteid', idx)"
						v-model="items['property'][idx]['media.property.languageid'][propidx]" >

						<option v-bind:value="null">
							<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>
						</option>

						<?php foreach( $this->get( 'pageLangItems', [] ) as $langId => $langItem ) : ?>
							<option value="<?= $enc->attr( $langId ); ?>" v-bind:selected="items['property'][idx]['media.property.languageid'][propidx] == '<?= $enc->attr( $langId ) ?>'" >
								<?= $enc->html( $langItem->getLabel() ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
				<td class="property-value">
					<input class="form-control item-value" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'image', 'property', 'idx', 'media.property.value', '' ) ) ); ?>'.replace( 'idx', idx )"
						placeholder="<?= $enc->attr( $this->translate( 'admin', 'Property value (required)' ) ); ?>"
						v-bind:readonly="checkSite('media.siteid', idx)"
						v-model="items['property'][idx]['media.property.value'][propidx]" >
				</td>
				<td class="actions">
					<div v-if="!checkSite('media.siteid', idx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
						v-on:click.stop="removePropertyItem(idx, propidx)">
					</div>
				</td>
			</tr>

		</tbody>
	</table>

	<?= $this->get( 'propertyBody' ); ?>

</div>
