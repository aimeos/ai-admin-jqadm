<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2021
 */


$enc = $this->encoder();


?>
<div id="property" class="item-property tab-pane fade" role="tabpanel" aria-labelledby="property">

	<div class="vue-block" data-data="<?= $enc->attr( $this->get( 'propertyData', [] ) ) ?>">

		<property-table
			v-bind:domain="'customer'" v-bind:siteid="'<?= $this->site()->siteid() ?>'" v-bind:tabindex="<?= $this->get( 'tabindex' ); ?>"
			v-bind:types="JSON.parse('<?= $enc->attr( $this->get( 'propertyTypes', map() )->col( 'customer.property.type.label', 'customer.property.type.code' )->toJson( JSON_FORCE_OBJECT ) ) ?>')"
			v-bind:languages="JSON.parse('<?= $enc->attr( $this->get( 'pageLangItems', map() )->col( 'locale.language.label', 'locale.language.id' )->toJson( JSON_FORCE_OBJECT ) ) ?>')"
			v-bind:name="'<?= $enc->attr( $this->formparam( ['property', '_propidx_', '_key_'] ) ) ?>'"
			v-bind:items="data" v-on:update:property="data = $event"
			v-bind:i18n="{
				all: '<?= $enc->attr( $this->translate( 'admin', 'All' ) ) ?>',
				delete: '<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>',
				header: '<?= $enc->attr( $this->translate( 'admin', 'Properties' ) ) ?>',
				help: '<?= $enc->attr( $this->translate( 'admin', 'Customer characteristics that are not shared with other customers' ) ) ?>',
				insert: '<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>',
				placeholder: '<?= $enc->attr( $this->translate( 'admin', 'Property value (required)' ) ) ?>',
				select: '<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ) ?>'
			}">
		</property-table>

	</div>

	<?= $this->get( 'propertyBody' ); ?>

</div>
