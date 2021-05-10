<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */

$enc = $this->encoder();

$starget = $this->config( 'admin/jqadm/url/search/target' );
$scntl = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$saction = $this->config( 'admin/jqadm/url/search/action', 'search' );
$sconfig = $this->config( 'admin/jqadm/url/search/config', [] );

$keys = [
	'product.lists.id', 'product.lists.siteid', 'product.lists.refid',
	'attribute.label', 'attribute.type'
];


?>
<div class="col-xl-12 item-option-custom">

	<div class="box">
		<table class="attribute-list table table-default"
			data-items="<?= $enc->attr( $this->get( 'customData', [] ) ) ?>"
			data-keys="<?= $enc->attr( $keys ) ?>"
			data-prefix="product.lists."
			data-siteid="<?= $this->site()->siteid() ?>" >

			<thead>
				<tr>
					<th>
						<span class="help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?></span>
						<div class="form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Attribute type that limits the list of available attributes' ) ) ?>
						</div>
					</th>
					<th>
						<span class="help"><?= $enc->html( $this->translate( 'admin', 'Custom' ) ) ?></span>
						<div class="form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Product attributes stored in the basket where customers can enter their own texts or values' ) ) ?>
						</div>
					</th>
					<th class="actions">
						<a class="btn act-list fa" tabindex="<?= $this->get( 'tabindex' ) ?>" target="_blank"
							title="<?= $enc->attr( $this->translate( 'admin', 'Go to attribute panel' ) ) ?>"
							href="<?= $enc->attr( $this->url( $starget, $scntl, $saction, ['resource' => 'attribute'] + $this->get( 'pageParams', [] ), [], $sconfig ) ) ?>">
						</a>
						<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
							v-on:click="add()">
					</th>
				</tr>
			</thead>

			<tbody is="draggable" v-model="items" group="option-custom" handle=".act-move" tag="tbody">

				<tr v-for="(item, idx) in items" v-bind:key="idx"
					v-bind:class="item['product.lists.siteid'] != `<?= $enc->js( $this->site()->siteid() ) ?>` ? 'readonly' : ''">
					<td v-bind:class="item['css'] || ''">
						<select is="combo-box" class="form-select item-type"
							v-bind:name="`<?= $enc->js( $this->formparam( ['option', 'custom', 'idx', 'attribute.type'] ) ) ?>`.replace( 'idx', idx )"
							v-bind:readonly="checkSite('product.lists.siteid', idx) || item['product.lists.id'] != ''"
							v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
							v-bind:label="item['attribute.type']"
							v-bind:required="'required'"
							v-bind:getfcn="getTypeItems"
							v-bind:index="idx"
							v-on:select="updateType"
							v-model="item['attribute.type']" >
						</select>
					</td>
					<td v-bind:class="item['css'] || ''">
						<input class="item-listid" type="hidden" v-model="item['product.lists.id']"
							v-bind:name="`<?= $enc->js( $this->formparam( ['option', 'custom', 'idx', 'product.lists.id'] ) ) ?>`.replace( 'idx', idx )" />

						<input class="item-label" type="hidden" v-model="item['attribute.label']"
							v-bind:name="`<?= $enc->js( $this->formparam( ['option', 'custom', 'idx', 'attribute.label'] ) ) ?>`.replace( 'idx', idx )" />

						<select is="combo-box" class="form-select item-refid"
							v-bind:name="`<?= $enc->js( $this->formparam( ['option', 'custom', 'idx', 'product.lists.refid'] ) ) ?>`.replace( 'idx', idx )"
							v-bind:readonly="checkSite('product.lists.siteid', idx) || item['product.lists.id'] != ''"
							v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
							v-bind:label="item['attribute.label']"
							v-bind:required="'required'"
							v-bind:getfcn="getItems"
							v-bind:index="idx"
							v-on:select="update"
							v-model="item['product.lists.refid']" >
						</select>
					</td>
					<td class="actions">
						<div v-if="!checkSite('product.lists.siteid', idx) && item['product.lists.id'] != ''"
							class="btn btn-card-header act-move fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down' ) ) ?>">
						</div>
						<div v-if="!checkSite('product.lists.siteid', idx)"
							class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
							v-on:click.stop="remove(idx)">
						</div>
					</td>
				</tr>

			</tbody>

		</table>
	</div>

	<?= $this->get( 'customBody' ) ?>

</div>
