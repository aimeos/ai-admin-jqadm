<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2020
 */


$enc = $this->encoder();


?>
<div v-show="item['_ext']" class="col-xl-12 content-block secondary">

	<property-table inline-template
		v-bind:index="idx" v-bind:domain="'media'" v-bind:siteid="'<?= $this->site()->siteid() ?>'"
		v-bind:types="JSON.parse('<?= $enc->attr( $this->get( 'propertyTypes', map() )->col( 'media.property.type.label', 'media.property.type.code' )->toJson( JSON_FORCE_OBJECT ) ) ?>')"
		v-bind:languages="JSON.parse('<?= $enc->attr( $this->get( 'pageLangItems', map() )->col( 'locale.language.label', 'locale.language.id' )->toJson( JSON_FORCE_OBJECT ) ) ?>')"
		v-bind:items="item['property']" v-on:update:property="item['property'] = $event">

		<table v-if="Object.keys(types).length" class="item-media-property table table-default" >
			<thead>
				<tr>
					<th colspan="3">
						<span class="help"><?= $enc->html( $this->translate( 'admin', 'Media properties' ) ); ?></span>
						<div class="form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Non-shared properties for the media item' ) ); ?>
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
						<input class="item-propertyid" type="hidden" v-model="propdata['media.property.id']"
							v-bind:name="'<?= $enc->attr( $this->formparam( ['media', '_idx_', 'property', '_propidx_', 'media.property.id'] ) ); ?>'.replace('_idx_', index).replace('_propidx_', propidx)" />

						<select is="select-component" required class="form-control custom-select item-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
							v-bind:name="'<?= $enc->attr( $this->formparam( ['media', '_idx_', 'property', '_propidx_', 'media.property.type'] ) ); ?>'.replace('_idx_', index).replace('_propidx_', propidx)"
							v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
							v-bind:readonly="readonly(propidx)"
							v-bind:items="types"
							v-model="propdata['media.property.type']" >
						</select>
					</td>
					<td class="property-language">
						<select is="select-component" class="form-control custom-select item-languageid" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
							v-bind:name="'<?= $enc->attr( $this->formparam( ['media', '_idx_', 'property', '_propidx_', 'media.property.languageid'] ) ); ?>'.replace('_idx_', index).replace('_propidx_', propidx)"
							v-bind:all="'<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>'"
							v-bind:readonly="readonly(propidx)"
							v-bind:items="languages"
							v-model="propdata['media.property.languageid']" >
						</select>
					</td>
					<td class="property-value">
						<input class="form-control item-value" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
							v-bind:name="'<?= $enc->attr( $this->formparam( ['media', '_idx_', 'property', '_propidx_', 'media.property.value'] ) ); ?>'.replace('_idx_', index).replace('_propidx_', propidx)"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'Property value (required)' ) ); ?>"
							v-bind:readonly="readonly(propidx)"
							v-model="propdata['media.property.value']" >
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
