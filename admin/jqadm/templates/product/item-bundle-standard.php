<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
 */

$enc = $this->encoder();

$keys = [
	'product.lists.id', 'product.lists.siteid', 'product.lists.refid', 'product.label', 'product.code'
];


?>
<div id="bundle" class="row item-bundle tab-pane fade" role="tabpanel" aria-labelledby="bundle">

	<div class="col-xl-6 content-block product-default">

		<table class="product-list table table-default"
			data-items="<?= $enc->attr( json_encode( $this->get( 'bundleData', [] ) ) ); ?>"
			data-keys="<?= $enc->attr( json_encode( $keys ) ) ?>"
			data-prefix="product.lists."
			data-siteid="<?= $this->site()->siteid() ?>" >

			<thead>
				<tr>
					<th>
						<span class="help"><?= $enc->html( $this->translate( 'admin', 'Products' ) ); ?></span>
						<div class="form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'List of articles that should be sold as one product, often at a reduced price' ) ); ?>
						</div>
					</th>
					<th class="actions">
						<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
							v-on:click="addItem()">
						</div>
					</th>
				</tr>
			</thead>

			<tbody>

				<tr v-for="(id, idx) in items['product.lists.id']" v-bind:key="idx"
					v-bind:class="items['product.lists.siteid'][idx] != '<?= $this->site()->siteid() ?>' ? 'readonly' : ''">

					<td>
						<input class="item-listid" type="hidden" v-model="items['product.lists.id'][idx]"
							name="<?= $enc->attr( $this->formparam( array( 'bundle', 'product.lists.id', '' ) ) ); ?>" />

						<input class="item-label" type="hidden" v-model="items['product.label'][idx]"
							name="<?= $enc->attr( $this->formparam( array( 'bundle', 'product.label', '' ) ) ); ?>" />

						<input class="item-code" type="hidden" v-model="items['product.code'][idx]"
							name="<?= $enc->attr( $this->formparam( array( 'bundle', 'product.code', '' ) ) ); ?>" />

						<select is="combo-box" class="form-control custom-select item-refid"
							v-bind:name="'<?= $enc->attr( $this->formparam( array( 'bundle', 'product.lists.refid', '' ) ) ); ?>'"
							v-bind:readonly="checkSite('product.lists.siteid', idx) || id != ''"
							v-bind:tabindex="'<?= $this->get( 'tabindex' ); ?>'"
							v-bind:label="getLabel(idx)"
							v-bind:required="'required'"
							v-bind:getfcn="getItems"
							v-bind:index="idx"
							v-on:select="update"
							v-model="items['product.lists.refid'][idx]" >
						</select>
					</td>
					<td class="actions">
						<div v-if="!checkSite('product.lists.siteid', idx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
							v-on:click.stop="removeItem(idx)">
					</td>
				</tr>

			</tbody>
		</table>

		<?= $this->get( 'bundleBody' ); ?>

	</div>
</div>
