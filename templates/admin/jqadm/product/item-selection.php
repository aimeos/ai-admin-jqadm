<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */

$enc = $this->encoder();

$keys = ['product.lists.siteid', 'product.lists.id', 'product.lists.refid', 'product.label', 'product.code', 'product.status', 'stock.id', 'stock.stocklevel'];


?>
<div id="selection" class="item-selection tab-pane fade" role="tablist" aria-labelledby="selection">
	<div id="item-selection-group" role="tablist" aria-multiselectable="true"
		data-items="<?= $enc->attr( $this->get( 'selectionData', [] ) ) ?>"
		data-keys="<?= $enc->attr( $keys ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>" >

		<div class="group-list">
			<div is="draggable" v-model="items" group="selection" handle=".act-move">
				<div v-for="(item, idx) in items" v-bind:key="idx" class="group-item card">

					<div v-bind:id="'item-selection-group-item-' + idx" class="card-header header">
						<div class="card-tools-start">
							<div class="btn btn-card-header act-show fa" v-bind:class="css(idx)"
								v-bind:aria-controls="'item-selection-group-data-' + idx" aria-expanded="false"
								data-bs-toggle="collapse" v-bind:data-bs-target="'#item-selection-group-data-' + idx"
								tabindex="<?= $this->get( 'tabindex' ) ?>" title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry' ) ) ?>">
							</div>
						</div>
						<div class="item-label header-label" v-bind:class="'status-' + item['product.status']">
							<span>{{ item['product.label'] }}</span>
						</div>
						<div class="card-tools-end">
							<a v-if="item['product.id']" class="btn btn-card-header act-view fa" target="_blank" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['id' => '_ID_'] + $this->get( 'pageParams', [] ) ) ) ?>`.replace('_ID_', item['product.id'])"
								title="<?= $enc->attr( $this->translate( 'admin', 'View details' ) ) ?>"></a>

							<div class="btn btn-card-header act-copy fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Duplicate entry (Ctrl+D)' ) ) ?>"
								v-on:click.stop="copy(idx)">
							</div>

							<div v-if="can('move', idx)"
								class="btn btn-card-header act-move fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down' ) ) ?>">
							</div>

							<div v-if="can('delete', idx)"
								class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
								v-on:click.stop="remove(idx)">
							</div>
						</div>
					</div>

					<div v-bind:id="'item-selection-group-data-' + idx" v-bind:class="css(idx)"
						v-bind:aria-labelledby="'item-selection-group-item-' + idx" role="tabpanel" class="card-block collapse row">

						<input type="hidden" v-model="item['product.id']"
							v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'product.id'] ) ) ?>`.replace('_idx_', idx)">
						<input type="hidden" v-model="item['product.lists.id']"
							v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'product.lists.id'] ) ) ?>`.replace('_idx_', idx)">

						<div class="col-xl-6">

							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></label>
								<div class="col-sm-8">
									<select class="form-select item-status" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'selection', '_idx_', 'product.status' ) ) ) ?>`.replace('_idx_', idx)"
										v-bind:readonly="!editable(idx)"
										v-model="item['product.status']" >
										<option value="1" v-bind:selected="item['product.status'] == 1" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?>
										</option>
                                        					<option value="2" v-bind:selected="item['product.status'] == 2" >
                                            						<?= $enc->html( $this->translate( 'mshop/code', 'status:2' ) ) ?>
                                        					</option>
										<option value="0" v-bind:selected="item['product.status'] == 0" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?>
										</option>
										<option value="-1" v-bind:selected="item['product.status'] == -1" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?>
										</option>
										<option value="-2" v-bind:selected="item['product.status'] == -2" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?>
										</option>
									</select>
								</div>
							</div>
							<?php if( ( $types = $this->get( 'itemTypes', map() )->col( 'product.type.label', 'product.type.code' )->only( ['default', 'event', 'voucher'] ) )->count() !== 1 ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?></label>
									<div class="col-sm-8">
										<select is="select-component" class="form-select item-type" required
											v-bind:readonly="!editable(idx)"
											v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'selection', '_idx_', 'product.type' ) ) ) ?>`.replace('_idx_', idx)"
											v-bind:items="<?= $enc->attr( $types->toArray() ) ?>"
											v-model="item['product.type']" >
											<option value="<?= $enc->attr( $this->get( 'itemData/product.type' ) ) ?>">
												<?= $enc->html( $types[$this->get( 'itemData/product.type', '' )] ?? $this->translate( 'admin', 'Please select' ) ) ?>
											</option>
										</select>
									</div>
								</div>
							<?php else : ?>
								<input class="item-type" type="hidden"
									v-bind:name="`<?= $enc->js( $this->formparam( array( 'selection', '_idx_', 'product.type' ) ) ) ?>`.replace('_idx_', idx)"
									value="<?= $enc->attr( $types->firstKey() ) ?>">
							<?php endif ?>
							<div class="form-group row mandatory">
								<label class="col-lg-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'SKU' ) ) ?></label>
								<div class="col-lg-8">
									<input is="auto-complete"
										v-model="item['product.code']"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'EAN, SKU or article number (required)' ) ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'selection', '_idx_', 'product.code' ) ) ) ?>`.replace('_idx_', idx)"
										v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
										v-bind:readonly="!editable(idx) || item['product.lists.id'] != ''"
										v-bind:keys="get"
										v-bind:required="'required'"
										v-on:input="update(idx, ...arguments)">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Unique article code related to stock levels, e.g. from the ERP system, an EAN/GTIN number or self invented' ) ) ?>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-lg-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ) ?></label>
								<div class="col-lg-8">
									<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'selection', '_idx_', 'product.label' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ) ?>"
										v-bind:readonly="!editable(idx)"
										v-model="item['product.label']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Internal article name, will be used on the web site if no product name for the language is available' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-lg-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Stock level' ) ) ?></label>
								<div class="col-lg-8">
									<input type="hidden" v-bind:disabled="item['stock'] === false" v-model="item['stock.id']"
										v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'stock.id'] ) ) ?>`.replace('_idx_', idx)">
									<input class="form-control item-stocklevel" type="number" step="1" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'selection', '_idx_', 'stock.stocklevel' ) ) ) ?>`.replace('_idx_', idx)"
										v-bind:readonly="!editable(idx)"
										v-bind:disabled="item['stock'] === false"
										v-model="item['stock.stocklevel']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Internal article name, will be used on the web site if no product name for the language is available' ) ) ?>
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
											<a class="btn act-list fa" tabindex="<?= $this->get( 'tabindex' ) ?>" target="_blank"
												title="<?= $enc->attr( $this->translate( 'admin', 'Go to attribute panel' ) ) ?>"
												href="<?= $enc->attr( $this->link( 'admin/jqadm/url/search', ['resource' => 'attribute'] + $this->get( 'pageParams', [] ) ) ) ?>">
											</a>
											<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
												title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
												v-on:click.stop="addAttribute(idx)">
											</div>
										</th>
									</tr>
								</thead>

								<tbody is="draggable" handle=".act-move" tag="tbody">

									<tr v-for="(attr, attridx) in (item['attr'] || [])">
										<td>
											<input class="item-attr-listid" type="hidden" v-model="attr['product.lists.id']"
												v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'attr', 'attridx', 'product.lists.id'] ) ) ?>`.replace('_idx_', idx).replace('attridx', attridx)">

											<input class="item-attr-siteid" type="hidden" v-model="attr['product.lists.siteid']"
												v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'attr', 'attridx', 'product.lists.siteid'] ) ) ?>`.replace('_idx_', idx).replace('attridx', attridx)">

											<input class="item-attr-type" type="hidden" v-model="attr['attribute.type']"
												v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'attr', 'attridx', 'attribute.type'] ) ) ?>`.replace('_idx_', idx).replace('attridx', attridx)">

											<input class="item-attr-label" type="hidden" v-model="attr['attribute.label']"
												v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'attr', 'attridx', 'attribute.label'] ) ) ?>`.replace('_idx_', idx).replace('attridx', attridx)">

											<select is="combo-box" class="form-select item-attr-refid"
												v-bind:name="`<?= $enc->js( $this->formparam( ['selection', '_idx_', 'attr', 'attridx', 'product.lists.refid'] ) ) ?>`.replace('_idx_', idx).replace('attridx', attridx)"
												v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
												v-bind:readonly="!can('change', idx, attridx)"
												v-bind:label="label(idx, attridx)"
												v-bind:title="title(idx, attridx)"
												v-bind:required="'required'"
												v-bind:getfcn="getAttributes"
												v-on:select="updateAttribute($event, idx, attridx)"
												v-model="attr['product.lists.refid']" >
											</select>
										</td>
										<td class="actions">
											<div v-if="can('move', idx, attridx)"
												class="btn act-move fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
												title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down' ) ) ?>">
											</div>
											<div v-if="can('delete', idx, attridx)"
												class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
												title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
												v-on:click.stop="removeAttribute(idx, attridx)">
											</div>
										</td>
									</tr>

								</tbody>
							</table>
						</div>

						<?= $this->get( 'selectionBody' ) ?>

					</div>
				</div>
			</div>

			<div slot="footer" class="card-tools-more">
				<div class="btn btn-primary btn-card-more act-add fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
					title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
					v-on:click="add()" >
				</div>
			</div>
		</div>

	</div>
</div>
