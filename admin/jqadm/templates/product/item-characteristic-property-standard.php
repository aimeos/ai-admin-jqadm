<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */


$enc = $this->encoder();

$starget = $this->config( 'admin/jqadm/url/search/target' );
$scntl = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$saction = $this->config( 'admin/jqadm/url/search/action', 'search' );
$sconfig = $this->config( 'admin/jqadm/url/search/config', [] );


?>
<div class="col-xl-12 vue" data-key="characteristic/property"
	data-data="<?= $enc->attr( $this->get( 'propertyData', [] ) ) ?>">

	<div class="box">
		<property-table
			v-bind:domain="'product'" v-bind:siteid="`<?= $enc->js( $this->site()->siteid() ) ?>`" v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
			v-bind:types="<?= $enc->attr( $this->get( 'propertyTypes', map() )->col( 'product.property.type.label', 'product.property.type.code' )->toArray() ) ?>"
			v-bind:languages="<?= $enc->attr( $this->get( 'pageLangItems', map() )->col( 'locale.language.label', 'locale.language.id' )->toArray() ) ?>"
			v-bind:name="`<?= $enc->js( $this->formparam( ['characteristic', 'property', '_propidx_', '_key_'] ) ) ?>`"
			v-bind:items="data" v-on:update:property="data = $event"
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
