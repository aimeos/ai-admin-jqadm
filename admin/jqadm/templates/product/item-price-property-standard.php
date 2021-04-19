<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2019-2021
 */


$enc = $this->encoder();


?>
<div v-show="item['_ext']" class="col-xl-12 secondary">

	<property-table v-if="item['property'] && item['property'].length"
		v-bind:index="idx" v-bind:domain="'price'"
		v-bind:siteid="'<?= $this->site()->siteid() ?>'" v-bind:tabindex="<?= $this->get( 'tabindex' ) ?>"
		v-bind:types="<?= $enc->attr( $this->get( 'propertyTypes', map() )->col( 'price.property.type.label', 'price.property.type.code' )->toJson( JSON_FORCE_OBJECT ) ) ?>"
		v-bind:languages="<?= $enc->attr( $this->get( 'pageLangItems', map() )->col( 'locale.language.label', 'locale.language.id' )->toJson( JSON_FORCE_OBJECT ) ) ?>"
		v-bind:name="'<?= $enc->attr( $this->formparam( ['price', '_idx_', 'property', '_propidx_', '_key_'] ) ) ?>'"
		v-bind:items="item['property']" v-on:update:property="item['property'] = $event"
		v-bind:i18n="{
			all: '<?= $enc->attr( $this->translate( 'admin', 'All' ) ) ?>',
			delete: '<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>',
			header: '<?= $enc->attr( $this->translate( 'admin', 'Price properties' ) ) ?>',
			help: '<?= $enc->attr( $this->translate( 'admin', 'Non-shared properties for the price item' ) ) ?>',
			insert: '<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>',
			placeholder: '<?= $enc->attr( $this->translate( 'admin', 'Property value (required)' ) ) ?>',
			select: '<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ) ?>'
		}">
	</property-table>

	<?= $this->get( 'propertyBody' ) ?>

</div>
