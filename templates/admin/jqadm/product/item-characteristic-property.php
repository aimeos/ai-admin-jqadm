<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2024
 */


$enc = $this->encoder();


?>
<div class="col-xl-12 vue" data-key="characteristic/property"
	data-data="<?= $enc->attr( $this->get( 'propertyData', [] ) ) ?>"
	data-domain="product/property"
	data-siteid="<?= $this->site()->siteid() ?>" >

	<div class="box">
		<property-table
			v-bind:domain="'product'" v-bind:siteid="`<?= $enc->js( $this->site()->siteid() ) ?>`" v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
			v-bind:types="<?= $enc->attr( (object) $this->get( 'propertyTypes', map() )->col( null, 'product.property.type.code' )->getName()->all() ) ?>"
			v-bind:languages="<?= $enc->attr( $this->get( 'pageLangItems', map() )->col( 'locale.language.label', 'locale.language.id' )->unshift( $this->translate( 'admin', '__hidden__' ), 'xx' )->all() ) ?>"
			v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'property', '_propidx_', '_key_'] ) ) ?>`"
			v-bind:items="dataset" v-on:update:property="dataset = $event"
			v-bind:i18n="{
				all: `<?= $enc->js( $this->translate( 'admin', 'All' ) ) ?>`,
				delete: `<?= $enc->js( $this->translate( 'admin', 'Delete this entry' ) ) ?>`,
				header: `<?= $enc->js( $this->translate( 'admin', 'Properties' ) ) ?>`,
				help: `<?= $enc->js( $this->translate( 'admin', 'Product characteristics that are not shared with other products' ) ) ?>`,
				insert: `<?= $enc->js( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>`,
				placeholder: `<?= $enc->js( $this->translate( 'admin', 'Property value (required)' ) ) ?>`,
				select: `<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`
			}">
		</property-table>
	</div>

	<?= $this->get( 'propertyBody' ) ?>

</div>
