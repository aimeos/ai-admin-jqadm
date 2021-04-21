<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2019-2021
 */


$enc = $this->encoder();
$types = $this->get( 'propertyTypes', map() )->col( 'price.property.type.label', 'price.property.type.code' )->toArray();


?>
<div v-show="item['_ext']" class="col-xl-12 secondary">

	<?php if( !empty( $types ) ) : ?>

		<property-table
			v-bind:index="idx" v-bind:domain="'price'"
			v-bind:types="<?= $enc->attr( $types ) ?>"
			v-bind:siteid="`<?= $enc->js( $this->site()->siteid() ) ?>`" v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
			v-bind:languages="<?= $enc->attr( $this->get( 'pageLangItems', map() )->col( 'locale.language.label', 'locale.language.id' )->toArray() ) ?>"
			v-bind:name="`<?= $enc->js( $this->formparam( ['price', '_idx_', 'property', '_propidx_', '_key_'] ) ) ?>`"
			v-bind:items="item['property']" v-on:update:property="item['property'] = $event"
			v-bind:i18n="{
				all: `<?= $enc->js( $this->translate( 'admin', 'All' ) ) ?>`,
				delete: `<?= $enc->js( $this->translate( 'admin', 'Delete this entry' ) ) ?>`,
				header: `<?= $enc->js( $this->translate( 'admin', 'Price properties' ) ) ?>`,
				help: `<?= $enc->js( $this->translate( 'admin', 'Non-shared properties for the price item' ) ) ?>`,
				insert: `<?= $enc->js( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>`,
				placeholder: `<?= $enc->js( $this->translate( 'admin', 'Property value (required)' ) ) ?>`,
				select: `<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`
			}">
		</property-table>

	<?php endif ?>

	<?= $this->get( 'propertyBody' ) ?>

</div>
