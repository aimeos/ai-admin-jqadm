<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
 */

$enc = $this->encoder();

$target = $this->config( 'admin/jqadm/url/get/target' );
$cntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/get/action', 'get' );
$config = $this->config( 'admin/jqadm/url/get/config', [] );

$keys = ['product.lists.siteid', 'product.lists.id', 'product.lists.refid', 'product.label', 'product.code', 'product.status'];


?>
<div id="selection" class="item-selection content-block tab-pane fade" role="tablist" aria-labelledby="selection">
	<div id="item-selection-group" role="tablist" aria-multiselectable="true"
		data-items="<?= $enc->attr( json_encode( $this->get( 'selectionData', [] ) ) ); ?>"
		data-keys="<?= $enc->attr( json_encode( $keys ) ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>" >

		<div v-for="(entry, idx) in items" class="group-item card">

			<div v-bind:id="'item-selection-group-item-' + idx" v-bind:class="getCss(idx)"
				v-bind:data-target="'#item-selection-group-data-' + idx" data-toggle="collapse" role="tab" class="card-header header"
				v-bind:aria-controls="'item-selection-group-data-' + idx" aria-expanded="false">
				<div class="card-tools-left">
					<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry') ); ?>">
					</div>
				</div>
				<span class="item-label header-label" v-html="getLabel(idx)"></span>
				&nbsp;
				<div class="card-tools-right">
					<a v-if="entry['product.id']" class="btn btn-card-header act-view fa" target="_blank" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:href="'<?= $enc->attr( $this->url( $target, $cntl, $action, ['id' => '_ID_'] + $this->get( 'pageParams', [] ), [], $config ) ); ?>'.replace('_ID_', entry['product.id'])"
						title="<?= $enc->attr( $this->translate( 'admin', 'View details') ); ?>"></a>

					<div class="btn btn-card-header act-copy fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Duplicate entry (Ctrl+D)') ); ?>"
						v-on:click.stop="copyItem(idx)">
					</div>

					<div v-if="!checkSite('product.lists.siteid', idx)"
						class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
						v-on:click.stop="removeItem(idx)">
					</div>
				</div>
			</div>

			<div v-bind:id="'item-selection-group-data-' + idx" v-bind:class="getCss(idx)"
				v-bind:aria-labelledby="'item-selection-group-item-' + idx" role="tabpanel" class="card-block collapse row">

				<input class="item-id" type="hidden" v-model="items[idx]['product.lists.id']"
					v-bind:name="'<?= $enc->attr( $this->formparam( ['selection', 'idx', 'product.lists.id'] ) ); ?>'.replace('idx', idx)" />
				<input class="item-listid" type="hidden" v-model="items[idx]['product.id']"
					v-bind:name="'<?= $enc->attr( $this->formparam( ['selection', 'idx', 'product.id'] ) ); ?>'.replace('idx', idx)" />

				<div class="col-xl-6">

					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ); ?></label>
						<div class="col-sm-8">
							<select class="form-control custom-select item-status" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								v-bind:name="'<?= $enc->attr( $this->formparam( array( 'selection', 'idx', 'product.status' ) ) ); ?>'.replace('idx', idx)"
								v-bind:readonly="checkSite('product.siteid', idx)"
								v-model="items[idx]['product.status']" >
								<option value="1" v-bind:selected="items[idx]['product.status'] == 1" >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ); ?>
								</option>
								<option value="0" v-bind:selected="items[idx]['product.status'] == 0" >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ); ?>
								</option>
								<option value="-1" v-bind:selected="items[idx]['product.status'] == -1" >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ); ?>
								</option>
								<option value="-2" v-bind:selected="items[idx]['product.status'] == -2" >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ); ?>
								</option>
							</select>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-lg-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'SKU' ) ); ?></label>
						<div class="col-lg-8">
							<input is="auto-complete"
								v-model="items[idx]['product.code']"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'EAN, SKU or article number (required)' ) ); ?>"
								v-bind:name="'<?= $enc->attr( $this->formparam( array( 'selection', 'idx', 'product.code' ) ) ); ?>'.replace('idx', idx)"
								v-bind:readonly="checkSite('product.lists.siteid', idx) || entry['product.lists.id'] != ''"
								v-bind:tabindex="<?= $this->get( 'tabindex' ); ?>"
								v-bind:keys="getArticles"
								v-bind:required="'required'"
								v-on:input="updateProductItem(idx, ...arguments)" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Unique article code related to stock levels, e.g. from the ERP system, an EAN/GTIN number or self invented' ) ); ?>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-lg-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ); ?></label>
						<div class="col-lg-8">
							<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								v-bind:name="'<?= $enc->attr( $this->formparam( array( 'selection', 'idx', 'product.label' ) ) ); ?>'.replace('idx', idx)"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ); ?>"
								v-bind:readonly="checkSite('product.siteid', idx)"
								v-model="items[idx]['product.label']" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Internal article name, will be used on the web site if no product name for the language is available' ) ); ?>
						</div>
					</div>

				</div>
				<div class="col-lg-6">

					<table class="selection-item-attributes table table-default">
						<thead>
							<tr>
								<th>
									<span class="help"><?= $enc->html( $this->translate( 'admin', 'Variant attributes' ) ); ?></span>
									<div class="form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'All attributes that uniquely define an article, e.g. width, length and color for jeans' ) ); ?>
									</div>
								</th>
								<th class="actions">
									<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
										v-on:click="addAttributeItem(idx)">
									</div>
								</th>
							</tr>
						</thead>
						<tbody>

							<tr v-for="(attr, attridx) in (entry['attr'] || [])">
								<td>
									<input class="item-attr-listid" type="hidden" v-model="items[idx]['attr'][attridx]['product.lists.id']"
										v-bind:name="'<?= $enc->attr( $this->formparam( ['selection', 'idx', 'attr', 'attridx', 'product.lists.id'] ) ); ?>'.replace('idx', idx).replace('attridx', attridx)" />

									<input class="item-attr-siteid" type="hidden" v-model="items[idx]['attr'][attridx]['product.lists.siteid']"
										v-bind:name="'<?= $enc->attr( $this->formparam( ['selection', 'idx', 'attr', 'attridx', 'product.lists.siteid'] ) ); ?>'.replace('idx', idx).replace('attridx', attridx)" />

									<input class="item-attr-type" type="hidden" v-model="items[idx]['attr'][attridx]['attribute.type']"
										v-bind:name="'<?= $enc->attr( $this->formparam( ['selection', 'idx', 'attr', 'attridx', 'attribute.type'] ) ); ?>'.replace('idx', idx).replace('attridx', attridx)" />

									<input class="item-attr-label" type="hidden" v-model="items[idx]['attr'][attridx]['attribute.label']"
										v-bind:name="'<?= $enc->attr( $this->formparam( ['selection', 'idx', 'attr', 'attridx', 'attribute.label'] ) ); ?>'.replace('idx', idx).replace('attridx', attridx)" />

									<select is="combo-box" class="form-control custom-select item-attr-refid"
										v-bind:name="'<?= $enc->attr( $this->formparam( ['selection', 'idx', 'attr', 'attridx', 'product.lists.refid'] ) ); ?>'.replace('idx', idx).replace('attridx', attridx)"
										v-bind:readonly="checkSite('product.lists.siteid', idx, attridx) || attr['product.lists.id'] != ''"
										v-bind:tabindex="'<?= $this->get( 'tabindex' ); ?>'"
										v-bind:label="getAttributeLabel(idx, attridx)"
										v-bind:required="'required'"
										v-bind:getfcn="getAttributeItems"
										v-on:select="updateAttributeItem($event, idx, attridx)"
										v-model="items[idx]['attr'][attridx]['product.lists.refid']" >
									</select>
								</td>
								<td class="actions">
									<div v-if="!checkSite('product.lists.siteid', idx, attridx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
										v-on:click.stop="removeAttributeItem(idx, attridx)">
									</div>
								</td>
							</tr>

						</tbody>
					</table>
				</div>

				<?= $this->get( 'selectionBody' ); ?>

			</div>
		</div>

		<div class="card-tools-more">
			<div class="btn btn-primary btn-card-more act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
				title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
				v-on:click="addItem()" >
			</div>
		</div>
	</div>
</div>
