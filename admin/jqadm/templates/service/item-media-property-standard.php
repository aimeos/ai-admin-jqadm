<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2021
 */


$enc = $this->encoder();


?>
<div v-show="item['_ext']" class="col-xl-12 secondary">

	<property-table v-if="item['property'] && item['property'].length"
		v-bind:index="idx" v-bind:domain="'media'"
		v-bind:siteid="`<?= $this->site()->siteid() ?>`" v-bind:tabindex="<?= $this->get( 'tabindex' ) ?>"
		v-bind:types="<?= $enc->attr( $this->get( 'propertyTypes', map() )->col( 'media.property.type.label', 'media.property.type.code' )->toArray() ) ?>"
		v-bind:languages="<?= $enc->attr( $this->get( 'pageLangItems', map() )->col( 'locale.language.label', 'locale.language.id' )->toArray() ) ?>"
		v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'property', '_propidx_', '_key_'] ) ) ?>`"
		v-bind:items="item['property']" v-on:update:property="item['property'] = $event"
		v-bind:i18n="{
			all: `<?= $enc->js( $this->translate( 'admin', 'All' ) ) ?>`,
			delete: `<?= $enc->js( $this->translate( 'admin', 'Delete this entry' ) ) ?>`,
			header: `<?= $enc->js( $this->translate( 'admin', 'Media properties' ) ) ?>`,
			help: `<?= $enc->js( $this->translate( 'admin', 'Non-shared properties for the media item' ) ) ?>`,
			insert: `<?= $enc->js( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>`,
			placeholder: `<?= $enc->js( $this->translate( 'admin', 'Property value (required)' ) ) ?>`,
			select: `<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`
		}">
	</property-table>

	<?= $this->get( 'propertyBody' ) ?>

</div>
