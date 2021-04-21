<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */


$enc = $this->encoder();


?>
<div id="property" class="item-property tab-pane fade" role="tabpanel" aria-labelledby="property">

	<div class="box vue" data-data="<?= $enc->attr( $this->get( 'propertyData', [] ) ) ?>">

		<property-table
			v-bind:domain="'attribute'" v-bind:siteid="`<?= $enc->js( $this->site()->siteid() ) ?>`" v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
			v-bind:types="<?= $enc->attr( $this->get( 'propertyTypes', map() )->col( 'attribute.property.type.label', 'attribute.property.type.code' )->toArray() ) ?>"
			v-bind:languages="<?= $enc->attr( $this->get( 'pageLangItems', map() )->col( 'locale.language.label', 'locale.language.id' )->toArray() ) ?>"
			v-bind:name="`<?= $enc->js( $this->formparam( ['property', '_propidx_', '_key_'] ) ) ?>`"
			v-bind:items="data" v-on:update:property="data = $event"
			v-bind:i18n="{
				all: `<?= $enc->js( $this->translate( 'admin', 'All' ) ) ?>`,
				delete: `<?= $enc->js( $this->translate( 'admin', 'Delete this entry' ) ) ?>`,
				header: `<?= $enc->js( $this->translate( 'admin', 'Properties' ) ) ?>`,
				help: `<?= $enc->js( $this->translate( 'admin', 'Attribute properties that are not shared with other attributes' ) ) ?>`,
				insert: `<?= $enc->js( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>`,
				placeholder: `<?= $enc->js( $this->translate( 'admin', 'Property value (required)' ) ) ?>`,
				select: `<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`
			}">
		</property-table>

	</div>

	<?= $this->get( 'propertyBody' ) ?>
</div>
