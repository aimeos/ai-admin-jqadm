<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2024
 */


/** admin/jqadm/product/item/price/config/suggest
 * List of suggested configuration keys in product price panel
 *
 * Item references can store arbitrary key value pairs. This setting gives
 * editors a hint which config keys are available and are used in the templates.
 *
 * @param string List of suggested config keys
 * @since 2020.01
 */


$enc = $this->encoder();


?>
<div id="price" class="item-price tab-pane fade" role="tablist" aria-labelledby="price">

	<div class="card pricecustom">
		<div class="row optional">
			<div class="col-1">
				<input class="form-check-input item-pricecustom" type="checkbox" value="1" tabindex="<?= $this->get( 'tabindex' ) ?>"
					name="<?= $enc->attr( $this->formparam( array( 'pricecustom' ) ) ) ?>"
					data-attr="<?= $enc->attr( $this->priceCustomItem ) ?>"
					<?= $this->get( 'priceCustom' ) ? 'checked="checked"' : '' ?>
				>
			</div>
			<label class="col-11 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Allow custom price' ) ) ?></label>
			<div class="col-12 form-text text-muted help-text">
				<?= $enc->html( $this->translate( 'admin', 'Allow customers to choose themselves how much they want to pay' ) ) ?>
			</div>
		</div>
	</div>

	<div id="item-price-group"
		data-data="<?= $enc->attr( $this->get( 'priceData', [] ) ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>"
		data-domain="product" >

		<div class="group-list" role="tablist" aria-multiselectable="true">
			<div is="vue:draggable" item-key="price.id" group="price" :list="items" handle=".act-move">
				<template #item="{element, index}">

					<div class="group-item card box" v-bind:class="{mismatch: !can('match', index)}">
						<div v-bind:id="'item-price-group-item-' + index" class="card-header header">
							<div class="card-tools-start">
								<div class="btn btn-card-header act-show icon" v-bind:class="element['_show'] ? 'show' : 'collapsed'" v-on:click="toggle('_show', index)"
									v-bind:data-bs-target="'#item-price-group-data-' + index" data-bs-toggle="collapse"
									v-bind:aria-controls="'item-price-group-data-' + index" aria-expanded="false"
									title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry' ) ) ?>" tabindex="<?= $this->get( 'tabindex' ) ?>">
								</div>
							</div>
							<div class="item-label header-label" v-bind:class="{disabled: !active(index)}" v-on:click="toggle('_show', index)">{{ label(index) }}</div>
							<div class="card-tools-end">
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

						<div v-bind:id="'item-price-group-data-' + index" v-bind:class="element['_show'] ? 'show' : 'collapsed'"
							v-bind:aria-labelledby="'item-price-group-item-' + index" role="tabpanel" class="card-block collapse row">

							<input type="hidden" v-model="element['price.id']"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.id' ) ) ) ?>`.replace('idx', index)">

							<div class="col-xl-6">

								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Tax rate in %' ) ) ?></label>
									<div class="col-sm-8">
										<div is="vue:taxrates" v-bind:key="index" class="item-taxrate"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.taxrates' ) ) ) ?>`.replace('idx', index)"
											v-bind:types="<?= $enc->attr( $this->config( 'admin/tax', [] ) ) ?>"
											v-bind:placeholder="`<?= $enc->js( $this->translate( 'admin', 'Tax rate in %' ) ) ?>`"
											v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
											v-bind:readonly="!can('change', index)"
											v-bind:taxrates="element['price.taxrates']"
										></div>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Country specific tax rate to calculate and display the included tax (B2C) or add the tax if required (B2B)' ) ) ?>
									</div>
								</div>
								<div class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Actual price' ) ) ?></label>
									<div class="col-sm-8">
										<input class="form-control item-value" type="number" step="<?= $this->pageNumberStep ?>" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.value' ) ) ) ?>`.replace('idx', index)"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'Actual price' ) ) ?>"
											v-bind:readonly="!can('change', index)"
											v-model="element['price.value']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Actual price customers can buy the article for on the web site' ) ) ?>
									</div>
								</div>
								<div class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Granted rebate' ) ) ?></label>
									<div class="col-sm-8">
										<input class="form-control item-rebate" type="number" step="<?= $this->pageNumberStep ?>" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.rebate' ) ) ) ?>`.replace('idx', index)"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'Granted rebate' ) ) ?>"
											v-bind:readonly="!can('change', index)"
											v-model="element['price.rebate']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Reduction from the original price, used to calculate the rebate in % and the cross price' ) ) ?>
									</div>
								</div>
								<div class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Shipping/item' ) ) ?></label>
									<div class="col-sm-8">
										<input class="form-control item-costs" type="number" step="<?= $this->pageNumberStep ?>" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.costs' ) ) ) ?>`.replace('idx', index)"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'Shipping/item' ) ) ?>"
											v-bind:readonly="!can('change', index)"
											v-model="element['price.costs']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Additional delivery costs for each item, e.g. $20 for one heavy item will be $100 for five items it total' ) ) ?>
									</div>
								</div>

							</div>

							<div class="col-xl-6">

								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></label>
									<div class="col-sm-8">
										<select class="form-select item-status" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.status' ) ) ) ?>`.replace('idx', index)"
											v-bind:readonly="!can('change', index)"
											v-model="element['price.status']" >
											<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?></option>
											<option value="1" v-bind:selected="element['price.status'] == 1" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?>
											</option>
											<option value="0" v-bind:selected="element['price.status'] == 0" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?>
											</option>
											<option value="-1" v-bind:selected="element['price.status'] == -1" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?>
											</option>
											<option value="-2" v-bind:selected="element['price.status'] == -2" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?>
											</option>
										</select>
									</div>
								</div>

								<?php if( ( $currencies = $this->get( 'priceCurrencies', map() ) )->count() !== 1 ) : ?>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Currency' ) ) ?></label>
										<div class="col-sm-8">
											<select is="vue:select-component" required class="form-select item-currencyid" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"
												v-bind:items="<?= $enc->attr( $currencies->col( 'locale.currency.label', 'locale.currency.id' )->toArray() ) ?>"
												v-bind:name="`<?= $enc->js( $this->formparam( ['price', 'idx', 'price.currencyid'] ) ) ?>`.replace('idx', index)"
												v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`"
												v-bind:readonly="!can('change', index)"
												v-model="element['price.currencyid']" >
											</select>
										</div>
									</div>
								<?php else : ?>
									<input class="item-currencyid" type="hidden"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.currencyid' ) ) ) ?>`.replace('idx', index)"
										value="<?= $enc->attr( $currencies->getCode()->first() ) ?>">
								<?php endif ?>

								<?php if( ( $priceTypes = $this->get( 'priceTypes', map() ) )->count() !== 1 ) : ?>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?></label>
										<div class="col-sm-8">
											<select is="vue:select-component" required class="form-select item-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"
												v-bind:items="<?= $enc->attr( $priceTypes->col( null, 'price.type.code' )->getName()->toArray() ) ?>"
												v-bind:name="`<?= $enc->js( $this->formparam( ['price', 'idx', 'price.type'] ) ) ?>`.replace('idx', index)"
												v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`"
												v-bind:readonly="!can('change', index)"
												v-model="element['price.type']" >
											</select>
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Types for additional prices like per one lb/kg or per month' ) ) ?>
										</div>
									</div>
								<?php else : ?>
									<input class="item-type" type="hidden"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.type' ) ) ) ?>`.replace('idx', index)"
										value="<?= $enc->attr( $priceTypes->getCode()->first() ) ?>">
								<?php endif ?>

								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Min. quantity' ) ) ?></label>
									<div class="col-sm-8">
										<input class="form-control item-quantity" type="number" step="0.001" min="0.001" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.quantity' ) ) ) ?>`.replace('idx', index)"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'Min. quantity' ) ) ?>"
											v-bind:readonly="!can('change', index)"
											v-model="element['price.quantity']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Required quantity of articles for block pricing, e.g. one article for $5.00, ten articles for $45.00' ) ) ?>
									</div>
								</div>

							</div>

							<div class="col-12 secondary item-meta text-muted">
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Site' ) ) ?>:
									<span class="meta-value">{{ element['price.siteid'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
									<span class="meta-value">{{ element['price.editor'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
									<span class="meta-value">{{ element['price.ctime'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Modified' ) ) ?>:
									<span class="meta-value">{{ element['price.mtime'] }}</span>
								</small>
							</div>


							<div v-on:click="toggle('_ext', index)" class="col-xl-12 advanced" v-bind:class="{'collapsed': !element['_ext']}">
								<div class="card-tools-start">
									<div class="btn act-show icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide advanced data' ) ) ?>">
									</div>
								</div>
								<span class="header-label"><?= $enc->html( $this->translate( 'admin', 'Advanced' ) ) ?></span>
							</div>

							<div v-show="element['_ext']" class="col-xl-6 secondary">

								<?php if( ( $listTypes = $this->get( 'priceListTypes', map() ) )->count() !== 1 ) : ?>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'List type' ) ) ?></label>
										<div class="col-sm-8">
											<select is="vue:select-component" required class="form-select listitem-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"
												v-bind:items="<?= $enc->attr( $listTypes->col( null, 'product.lists.type.code' )->getName()->toArray() ) ?>"
												v-bind:name="`<?= $enc->js( $this->formparam( ['price', 'idx', 'product.lists.type'] ) ) ?>`.replace('idx', index)"
												v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`"
												v-bind:readonly="!can('change', index)"
												v-model="element['product.lists.type']" >
											</select>
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Second level type for grouping entries' ) ) ?>
										</div>
									</div>
								<?php else : ?>
									<input class="listitem-type" type="hidden"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'product.lists.type' ) ) ) ?>`.replace('idx', index)"
										value="<?= $enc->attr( $listTypes->getCode()->first() ) ?>">
								<?php endif ?>

								<div class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ) ?></label>
									<div class="col-sm-8">
										<input is="vue:flat-pickr" class="form-control listitem-datestart select" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'product.lists.datestart' ) ) ) ?>`.replace('idx', index)"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
											v-bind:disabled="!can('change', index)"
											v-bind:config="Aimeos.flatpickr.datetime"
											v-model="element['product.lists.datestart']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'The entry is only shown on the web site after that date and time' ) ) ?>
									</div>
								</div>
								<div class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?></label>
									<div class="col-sm-8">
										<input is="vue:flat-pickr" class="form-control listitem-dateend select" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'product.lists.dateend' ) ) ) ?>`.replace('idx', index)"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
											v-bind:disabled="!can('change', index)"
											v-bind:config="Aimeos.flatpickr.datetime"
											v-model="element['product.lists.dateend']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'The entry is only shown on the web site until that date and time' ) ) ?>
									</div>
								</div>
							</div>

							<div v-show="element['_ext']" class="col-xl-6 secondary">
								<config-table v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
									v-bind:keys="<?= $enc->attr( $this->config( 'admin/jqadm/product/item/price/config/suggest', [] ) ) ?>"
									v-bind:name="`<?= $enc->js( $this->formparam( ['price', 'idx', 'config', '_pos_', '_key_'] ) ) ?>`.replace('idx', index)"
									v-bind:index="index" v-bind:readonly="!can('change', index)"
									v-bind:items="element['config']" v-on:update:items="element['config'] = $event"
									v-bind:i18n="{
										value: `<?= $enc->js( $this->translate( 'admin', 'Value' ) ) ?>`,
										option: `<?= $enc->js( $this->translate( 'admin', 'Option' ) ) ?>`,
										help: `<?= $enc->js( $this->translate( 'admin', 'Entry specific configuration options, will be available as key/value pairs in the templates' ) ) ?>`,
										insert: `<?= $enc->js( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>`,
										delete: `<?= $enc->js( $this->translate( 'admin', 'Delete this entry' ) ) ?>`,
									}">
								</config-table>
							</div>

							<div v-show="element['_ext']" class="col-12 secondary item-meta text-muted">
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Site' ) ) ?>:
									<span class="meta-value">{{ element['product.lists.siteid'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
									<span class="meta-value">{{ element['product.lists.editor'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
									<span class="meta-value">{{ element['product.lists.ctime'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Modified' ) ) ?>:
									<span class="meta-value">{{ element['product.lists.mtime'] }}</span>
								</small>
							</div>

							<?= $this->get( 'priceBody' ) ?>

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
