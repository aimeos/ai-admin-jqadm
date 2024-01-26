<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2024
 */


$enc = $this->encoder();


?>
<div id="property" class="item-property tab-pane fade" role="tabpanel" aria-labelledby="property">

	<div class="box vue" data-data="<?= $enc->attr( $this->get( 'propertyData', [] ) ) ?>">

		<property-table
			v-bind:domain="'customer'" v-bind:siteid="`<?= $enc->js( $this->site()->siteid() ) ?>`" v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
			v-bind:types="<?= $enc->attr( $this->get( 'propertyTypes', map() )->col( 'customer.property.type.label', 'customer.property.type.code' )->all() ) ?>"
			v-bind:languages="<?= $enc->attr( $this->get( 'pageLangItems', map() )->col( 'locale.language.label', 'locale.language.id' )->unshift( $this->translate( 'admin', '__hidden__' ), 'xx' )->all() ) ?>"
			v-bind:name="`<?= $enc->js( $this->formparam( ['property', '_propidx_', '_key_'] ) ) ?>`"
			v-bind:items="dataset" v-on:update:property="dataset = $event"
			v-bind:i18n="{
				all: `<?= $enc->js( $this->translate( 'admin', 'All' ) ) ?>`,
				delete: `<?= $enc->js( $this->translate( 'admin', 'Delete this entry' ) ) ?>`,
				header: `<?= $enc->js( $this->translate( 'admin', 'Properties' ) ) ?>`,
				help: `<?= $enc->js( $this->translate( 'admin', 'Customer characteristics that are not shared with other customers' ) ) ?>`,
				insert: `<?= $enc->js( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>`,
				placeholder: `<?= $enc->js( $this->translate( 'admin', 'Property value (required)' ) ) ?>`,
				select: `<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`
			}">
		</property-table>

	</div>

	<?= $this->get( 'propertyBody' ) ?>

</div>
