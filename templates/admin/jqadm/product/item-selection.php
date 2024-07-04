<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2024
 */

$enc = $this->encoder();

$keys = ['product.lists.siteid', 'product.lists.id', 'product.lists.refid', 'product.label', 'product.code', 'product.status', 'stock.id', 'stock.stocklevel'];


?>
<div id="selection" class="item-selection tab-pane fade" role="tablist" aria-labelledby="selection">
	<div id="item-selection-group" role="tablist" aria-multiselectable="true"
		data-data="<?= $enc->attr( $this->get( 'selectionData', [] ) ) ?>"
		data-keys="<?= $enc->attr( $keys ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>" >

		<div class="group-list" role="tablist" aria-multiselectable="true">
			<div is="vue:draggable" item-key="product.id" group="selection" :list="items" handle=".act-move">
				<template #item="{element, index}">

					<div class="group-item card box" v-bind:class="{mismatch: !can('match', index)}">
						<div v-bind:id="'item-selection-group-item-' + index" class="card-header header">
							<div class="card-tools-start">
								<div class="btn btn-card-header act-show icon" v-bind:class="css(index)" v-on:click="toggle(index)"
									v-bind:aria-controls="'item-selection-group-data-' + index" aria-expanded="false"
									data-bs-toggle="collapse" v-bind:data-bs-target="'#item-selection-group-data-' + index"
									tabindex="<?= $this->get( 'tabindex' ) ?>" title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry' ) ) ?>">
								</div>
							</div>
							<div class="item-label header-label" v-bind:class="'status-' + element['product.status']" v-on:click="toggle(index)">
								<span>{{ element['product.label'] }}</span>
							</div>
							<div class="card-tools-end">
								<a v-if="element['product.id']" class="btn btn-card-header act-view icon" target="_blank" tabindex="<?= $this->get( 'tabindex' ) ?>"
									v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['id' => '_ID_'] + $this->get( 'pageParams', [] ) ) ) ?>`.replace('_ID_', element['product.id'])"
									title="<?= $enc->attr( $this->translate( 'admin', 'View details' ) ) ?>"></a>

								<div class="btn btn-card-header act-copy icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Duplicate entry (Ctrl+D)' ) ) ?>"
									v-on:click.stop="copy(index)">
								</div>

								<div v-if="can('move', index)"
									class="btn btn-card-header act-move icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down' ) ) ?>">
								</div>

								<div v-if="can('delete', index)"
									class="btn btn-card-header act-delete icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
									v-on:click.stop="remove(index)">
								</div>
							</div>
						</div>

						<div v-bind:id="'item-selection-group-data-' + index" v-bind:class="css(index)"
							v-bind:aria-labelledby="'item-selection-group-item-' + index" role="tabpanel" class="card-block collapse row">

							<input type="hidden" v-model="element['product.id']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'product.id'] ) ) ?>`.replace('_idx_', index)">
							<input type="hidden" v-model="element['product.lists.id']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'product.lists.id'] ) ) ?>`.replace('_idx_', index)">
							<input type="hidden" v-model="element['quantity']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'quantity'] ) ) ?>`.replace('_idx_', index)">

							<div class="col-xl-6">

								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></label>
									<div class="col-sm-8">
										<select class="form-select item-status" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'selection', '_idx_', 'product.status' ) ) ) ?>`.replace('_idx_', index)"
											v-bind:readonly="!can('change', index)"
											v-model="element['product.status']" >
											<option value="1" v-bind:selected="element['product.status'] == 1" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?>
											</option>
											<option value="2" v-bind:selected="element['product.status'] == 2" >
													<?= $enc->html( $this->translate( 'mshop/code', 'status:2' ) ) ?>
											</option>
											<option value="0" v-bind:selected="element['product.status'] == 0" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?>
											</option>
											<option value="-1" v-bind:selected="element['product.status'] == -1" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?>
											</option>
											<option value="-2" v-bind:selected="element['product.status'] == -2" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?>
											</option>
										</select>
									</div>
								</div>
								<?php if( ( $types = $this->get( 'itemTypes', map() )->col( null, 'product.type.code' )->only( ['default', 'event', 'voucher'] ) )->count() !== 1 ) : ?>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?></label>
										<div class="col-sm-8">
											<select is="vue:select-component" class="form-select item-type" required
												v-bind:readonly="!can('change', index)"
												v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
												v-bind:name="`<?= $enc->js( $this->formparam( array( 'selection', '_idx_', 'product.type' ) ) ) ?>`.replace('_idx_', index)"
												v-bind:items="<?= $enc->attr( $types->getName()->toArray() ) ?>"
												v-model="element['product.type']" >
												<option value="<?= $enc->attr( $this->get( 'itemData/product.type' ) ) ?>">
													<?= $enc->html( $types[$this->get( 'itemData/product.type', '' )] ?? $this->translate( 'admin', 'Please select' ) ) ?>
												</option>
											</select>
										</div>
									</div>
								<?php else : ?>
									<input class="item-type" type="hidden"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'selection', '_idx_', 'product.type' ) ) ) ?>`.replace('_idx_', index)"
										value="<?= $enc->attr( $types->firstKey() ) ?>">
								<?php endif ?>
								<div class="form-group row mandatory">
									<label class="col-lg-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'SKU' ) ) ?></label>
									<div class="col-lg-8">
										<input class="item-attr-refid" type="hidden" v-bind:value="element['product.code']"
											v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'product.code'] ) ) ?>`.replace('_idx_', index)">

										<Multiselect class="item-id form-control"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'EAN, SKU or article number (required)' ) ) ?>"
											value-prop="product.code"
											track-by="product.code"
											label="product.code"
											@open="function(select) {return select.refreshOptions()}"
											@input="use(index, $event)"
											:value="element"
											:disabled="!can('change', index)"
											:options="async function(query) {return await fetch(query, element['product.type'])}"
											:on-create="function(option, select$) {return create(index, option, select$)}"
											:resolve-on-load="false"
											:filter-results="false"
											:create-option="true"
											:can-deselect="false"
											:allow-absent="true"
											:searchable="true"
											:can-clear="false"
											:required="true"
											:min-chars="1"
											:object="true"
											:delay="300"
										></Multiselect>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Unique article code related to stock levels, e.g. from the ERP system, an EAN/GTIN number or self invented' ) ) ?>
									</div>
								</div>
								<div class="form-group row mandatory">
									<label class="col-lg-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ) ?></label>
									<div class="col-lg-8">
										<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'selection', '_idx_', 'product.label' ) ) ) ?>`.replace('_idx_', index)"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ) ?>"
											v-bind:readonly="!can('change', index)"
											v-model="element['product.label']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Internal article name, will be used on the web site if no product name for the language is available' ) ) ?>
									</div>
								</div>
								<div class="form-group row optional">
									<label class="col-lg-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Stock level' ) ) ?></label>
									<div class="col-lg-8">
										<input type="hidden" v-bind:disabled="element['stock'] === false" v-model="element['stock.id']"
											v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'stock.id'] ) ) ?>`.replace('_idx_', index)">
										<input class="form-control item-stocklevel" type="number" step="1" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'selection', '_idx_', 'stock.stocklevel' ) ) ) ?>`.replace('_idx_', index)"
											v-bind:readonly="!can('change', index)"
											v-bind:disabled="element['stock'] === false"
											v-model="element['stock.stocklevel']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Number of articles currently in stock, leave empty for an unlimited quantity' ) ) ?>
									</div>
								</div>

							</div>
							<div class="col-xl-6">

								<table class="selection-item-attributes table table-default">
									<thead>
										<tr>
											<th>
												<span class="help"><?= $enc->html( $this->translate( 'admin', 'Variant attributes' ) ) ?></span>
												<div class="form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'All attributes that uniquely define an article, e.g. width, length and color for jeans' ) ) ?>
												</div>
											</th>
											<th class="actions">
												<a class="btn act-list icon" tabindex="<?= $this->get( 'tabindex' ) ?>" target="_blank"
													title="<?= $enc->attr( $this->translate( 'admin', 'Go to attribute panel' ) ) ?>"
													href="<?= $enc->attr( $this->link( 'admin/jqadm/url/search', ['resource' => 'attribute'] + $this->get( 'pageParams', [] ) ) ) ?>">
												</a>
												<div class="btn act-add icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
													title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
													v-on:click.stop="addAttribute(index)">
												</div>
											</th>
										</tr>
									</thead>

									<tbody is="vue:draggable" item-key="attribute.id" group="vattributes" :list="element['attr']" handle=".act-move" tag="tbody">
										<template #item="{element: attr, index: attridx}">
											<tr>
												<td>
													<input class="item-attr-listid" type="hidden" v-model="attr['product.lists.id']"
														v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'attr', '_attridx_', 'product.lists.id'] ) ) ?>`.replace('_idx_', index).replace( '_attridx_', attridx)">

													<input class="item-attr-refid" type="hidden" v-model="attr['attribute.id']"
														v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'attr', '_attridx_', 'attribute.id'] ) ) ?>`.replace('_idx_', index).replace( '_attridx_', attridx)">

													<input class="item-attr-label" type="hidden" v-model="attr['attribute.label']"
														v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'attr', '_attridx_', 'attribute.label'] ) ) ?>`.replace('_idx_', index).replace( '_attridx_', attridx)">

													<Multiselect class="item-id form-control"
														placeholder="Enter attribute ID, code or label"
														value-prop="attribute.id"
														track-by="attribute.id"
														label="attribute.label"
														@open="function(select) {return select.refreshOptions()}"
														@input="useAttribute(index, attridx, $event)"
														:value="attr"
														:title="title(index, attridx)"
														:disabled="!can('change', index, attridx)"
														:options="async function(query) {return await fetchAttribute(query)}"
														:resolve-on-load="false"
														:filter-results="false"
														:can-deselect="false"
														:allow-absent="true"
														:searchable="true"
														:can-clear="false"
														:required="true"
														:min-chars="1"
														:object="true"
														:delay="300"
													></Multiselect>
												</td>
												<td class="actions">
													<div v-if="can('move', index, attridx)"
														class="btn act-move icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
														title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down' ) ) ?>">
													</div>
													<div v-if="can('delete', index, attridx)"
														class="btn act-delete icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
														title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
														v-on:click.stop="removeAttribute(index, attridx)">
													</div>
												</td>
											</tr>
										</template>
									</tbody>
								</table>
							</div>

							<?= $this->get( 'selectionBody' ) ?>

						</div>
					</div>
				</template>
			</div>

			<div slot="footer" class="card-tools-more">
				<div class="btn btn-primary btn-card-more act-add icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
					title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
					v-on:click="add()" >
				</div>
			</div>
		</div>

	</div>
</div>
