<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2021
 */

$enc = $this->encoder();

$keys = [
	'product.lists.id', 'product.lists.siteid', 'product.lists.refid', 'product.label', 'product.code'
];


?>
<div id="bundle" class="item-bundle tab-pane fade" role="tabpanel" aria-labelledby="bundle">

	<div class="row">
		<div class="col-xl-6 product-default">

			<div class="box">
				<table class="product-list table table-default"
					data-items="<?= $enc->attr( $this->get( 'bundleData', [] ) ) ?>"
					data-keys="<?= $enc->attr( $keys ) ?>"
					data-prefix="product.lists."
					data-siteid="<?= $this->site()->siteid() ?>" >

					<thead>
						<tr>
							<th>
								<span class="help"><?= $enc->html( $this->translate( 'admin', 'Products' ) ) ?></span>
								<div class="form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'List of articles that should be sold as one product, often at a reduced price' ) ) ?>
								</div>
							</th>
							<th class="actions">
								<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
									v-on:click="add()">
								</div>
							</th>
						</tr>
					</thead>

					<tbody is="draggable" v-model="items" group="bundle" handle=".act-move" tag="tbody">

						<tr v-for="(item, idx) in items" v-bind:key="idx"
							v-bind:class="item['product.lists.siteid'] != `<?= $enc->js( $this->site()->siteid() ) ?>` ? 'readonly' : ''">
							<td v-bind:class="item['css'] ||''">
								<input class="item-listid" type="hidden" v-model="item['product.lists.id']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['bundle', 'idx', 'product.lists.id'] ) ) ?>`.replace( 'idx', idx )" />

								<input class="item-label" type="hidden" v-model="item['product.label']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['bundle', 'idx', 'product.label'] ) ) ?>`.replace( 'idx', idx )" />

								<input class="item-code" type="hidden" v-model="item['product.code']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['bundle', 'idx', 'product.code'] ) ) ?>`.replace( 'idx', idx )" />

								<select is="combo-box" class="form-select item-refid"
									v-bind:name="`<?= $enc->js( $this->formparam( ['bundle', 'idx', 'product.lists.refid'] ) ) ?>`.replace( 'idx', idx )"
									v-bind:readonly="checkSite('product.lists.siteid', idx) || item['product.lists.id'] != ''"
									v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
									v-bind:label="getLabel(idx)"
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
		</div>

		<?= $this->get( 'bundleBody' ) ?>

	</div>
</div>
