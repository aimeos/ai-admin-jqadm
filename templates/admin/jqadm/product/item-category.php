<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


$enc = $this->encoder();

$keys = [
	'product.lists.id', 'product.lists.siteid', 'product.lists.type', 'product.lists.refid',
	'catalog.label', 'catalog.code', 'catalog.id'
];


?>
<div id="category" class="item-category tab-pane fade" role="tabpanel" aria-labelledby="category">

	<div class="row">
		<div class="col-xl-6 catalog-default">
			<div class="box">
				<table class="category-list table table-default"
					data-items="<?= $enc->attr( $this->get( 'categoryData', [] ) ) ?>"
					data-keys="<?= $enc->attr( $keys ) ?>"
					data-siteid="<?= $this->site()->siteid() ?>"
					data-listtype="default">

					<thead>
						<tr>
							<th>
								<span class="help"><?= $enc->html( $this->translate( 'admin', 'Default' ) ) ?></span>
								<div class="form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Categories the product can be found in by the user on the web site' ) ) ?>
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

					<tbody>

						<tr v-for="(item, idx) in items" v-if="item['product.lists.type'] == listtype" v-bind:key="idx"
							v-bind:class="{'readonly': !can('change', idx)}">
							<td v-bind:class="item['css'] || ''">
								<input class="item-listtype" type="hidden" v-model="item['product.lists.type']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'default-idx', 'product.lists.type'] ) ) ?>`.replace( 'idx', idx )">

								<input class="item-listid" type="hidden" v-model="item['product.lists.id']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'default-idx', 'product.lists.id'] ) ) ?>`.replace( 'idx', idx )">

								<input class="item-label" type="hidden" v-model="item['catalog.code']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'default-idx', 'catalog.code'] ) ) ?>`.replace( 'idx', idx )">

								<input class="item-label" type="hidden" v-model="item['catalog.label']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'default-idx', 'catalog.label'] ) ) ?>`.replace( 'idx', idx )">

								<select is="combo-box" class="form-select item-id"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'default-idx', 'catalog.id'] ) ) ?>`.replace( 'idx', idx )"
									v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
									v-bind:readonly="!can('change', idx)"
									v-bind:label="label(idx)"
									v-bind:title="title(idx)"
									v-bind:required="'required'"
									v-bind:getfcn="itemFcn"
									v-bind:index="idx"
									v-on:select="update"
									v-model="item['catalog.id']" >
								</select>
							</td>
							<td class="actions">
								<div v-if="can('delete', idx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
									v-on:click.stop="remove(idx)">
								</div>
							</td>
						</tr>

					</tbody>

				</table>
			</div>
		</div>

		<div class="col-xl-6 catalog-promotion">

			<div class="box">
				<table class="category-list table table-default"
					data-items="<?= $enc->attr( $this->get( 'categoryData', [] ) ) ?>"
					data-keys="<?= $enc->attr( $keys ) ?>"
					data-siteid="<?= $this->site()->siteid() ?>"
					data-listtype="promotion">

					<thead>
						<tr>
							<th>
								<span class="help"><?= $enc->html( $this->translate( 'admin', 'Promotion' ) ) ?></span>
								<div class="form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Categories the product will be shown for in the promotional section' ) ) ?>
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

					<tbody>

					<tr v-for="(item, idx) in items" v-if="item['product.lists.type'] == listtype" v-bind:key="idx"
							v-bind:class="{'readonly': !can('change', idx)}">
							<td v-bind:class="item['css'] || ''">
								<input class="item-listtype" type="hidden" v-model="item['product.lists.type']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'promotion-idx', 'product.lists.type'] ) ) ?>`.replace( 'idx', idx )">

								<input class="item-listid" type="hidden" v-model="item['product.lists.id']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'promotion-idx', 'product.lists.id'] ) ) ?>`.replace( 'idx', idx )">

								<input class="item-label" type="hidden" v-model="item['catalog.code']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'promotion-idx', 'catalog.code'] ) ) ?>`.replace( 'idx', idx )">

								<input class="item-label" type="hidden" v-model="item['catalog.label']"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'promotion-idx', 'catalog.label'] ) ) ?>`.replace( 'idx', idx )">

								<select is="combo-box" class="form-select item-id"
									v-bind:name="`<?= $enc->js( $this->formparam( ['category', 'promotion-idx', 'catalog.id'] ) ) ?>`.replace( 'idx', idx )"
									v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
									v-bind:readonly="!can('change', idx)"
									v-bind:label="label(idx)"
									v-bind:title="title(idx)"
									v-bind:required="'required'"
									v-bind:getfcn="itemFcn"
									v-bind:index="idx"
									v-on:select="update"
									v-model="item['catalog.id']" >
								</select>
							</td>
							<td class="actions">
								<div v-if="can('delete', idx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
									v-on:click.stop="remove(idx)">
								</div>
							</td>
						</tr>

					</tbody>

				</table>
			</div>
		</div>
	</div>

	<?= $this->get( 'categoryBody' ) ?>

</div>
