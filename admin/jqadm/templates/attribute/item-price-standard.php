<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2021
 */


/** admin/jqadm/attribute/item/price/config/suggest
 * List of suggested configuration keys in attribute price panel
 *
 * Item references can store arbitrary key value pairs. This setting gives
 * editors a hint which config keys are available and are used in the templates.
 *
 * @param string List of suggested config keys
 * @since 2020.01
 * @category Developer
 */


$enc = $this->encoder();


?>
<div id="price" class="item-price tab-pane fade" role="tablist" aria-labelledby="price">

	<div id="item-price-group"
		data-items="<?= $enc->attr( $this->get( 'priceData', [] ) ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>"
		data-domain="attribute" >

		<div class="group-list" role="tablist" aria-multiselectable="true">
			<div is="draggable" v-model="items" group="price" handle=".act-move">
				<div v-for="(item, idx) in items" v-bind:key="idx" class="group-item card">

					<div v-bind:id="'item-price-group-item-' + idx" class="card-header header">
						<div class="card-tools-start">
							<div class="btn btn-card-header act-show fa" v-bind:class="item['_show'] ? 'show' : 'collapsed'" v-on:click="toggle('_show', idx)"
								v-bind:data-bs-target="'#item-price-group-data-' + idx" data-bs-toggle="collapse"
								v-bind:aria-controls="'item-price-group-data-' + idx" aria-expanded="false"
								title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry' ) ) ?>" tabindex="<?= $this->get( 'tabindex' ) ?>">
							</div>
						</div>
						<span class="item-label header-label" v-bind:class="{disabled: !active(idx)}">{{ label(idx) }}</span>
						<div class="card-tools-end">
							<div v-if="item['attribute.lists.siteid'] == siteid && !item['_nosort']"
								class="btn btn-card-header act-move fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down' ) ) ?>">
							</div>
							<div v-if="item['attribute.lists.siteid'] == siteid"
								class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
								v-on:click.stop="remove(idx)">
							</div>
						</div>
					</div>

					<div v-bind:id="'item-price-group-data-' + idx" v-bind:class="item['_show'] ? 'show' : 'collapsed'"
						v-bind:aria-labelledby="'item-price-group-item-' + idx" role="tabpanel" class="card-block collapse row">

						<input type="hidden" v-model="item['price.id']"
							v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.id' ) ) ) ?>`.replace('idx', idx)" />

						<div class="col-xl-6">

							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Tax rate in %' ) ) ?></label>
								<div class="col-sm-8">
									<div is="taxrates" v-bind:key="idx" class="item-taxrate"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.taxrates' ) ) ) ?>`.replace('idx', idx)"
										v-bind:types="<?= $enc->attr( $this->config( 'admin/tax', [] ) ) ?>"
										v-bind:placeholder="`<?= $enc->js( $this->translate( 'admin', 'Tax rate in %' ) ) ?>`"
										v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
										v-bind:readonly="item['price.siteid'] != siteid"
										v-bind:taxrates="item['price.taxrates']"
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
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.value' ) ) ) ?>`.replace('idx', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Actual price' ) ) ?>"
										v-bind:readonly="item['price.siteid'] != siteid"
										v-model="item['price.value']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Actual price customers can buy the article for on the web site' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Granted rebate' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-rebate" type="number" step="<?= $this->pageNumberStep ?>" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.rebate' ) ) ) ?>`.replace('idx', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Granted rebate' ) ) ?>"
										v-bind:readonly="item['price.siteid'] != siteid"
										v-model="item['price.rebate']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Reduction from the original price, used to calculate the rebate in % and the cross price' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Shipping/item' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-costs" type="number" step="<?= $this->pageNumberStep ?>" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.costs' ) ) ) ?>`.replace('idx', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Shipping/item' ) ) ?>"
										v-bind:readonly="item['price.siteid'] != siteid"
										v-model="item['price.costs']" />
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
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.status' ) ) ) ?>`.replace('idx', idx)"
										v-bind:readonly="item['price.siteid'] != siteid"
										v-model="item['price.status']" >
										<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?></option>
										<option value="1" v-bind:selected="item['price.status'] == 1" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?>
										</option>
										<option value="0" v-bind:selected="item['price.status'] == 0" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?>
										</option>
										<option value="-1" v-bind:selected="item['price.status'] == -1" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?>
										</option>
										<option value="-2" v-bind:selected="item['price.status'] == -2" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?>
										</option>
									</select>
								</div>
							</div>

							<?php if( ( $currencies = $this->get( 'priceCurrencies', map() ) )->count() !== 1 ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Currency' ) ) ?></label>
									<div class="col-sm-8">
										<select is="select-component" required class="form-select item-currencyid" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"
											v-bind:items="<?= $enc->attr( $currencies->col( 'locale.currency.label', 'locale.currency.id' )->toArray() ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( ['price', 'idx', 'price.currencyid'] ) ) ?>`.replace('idx', idx)"
											v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`"
											v-bind:readonly="item['price.siteid'] != siteid"
											v-model="item['price.currencyid']" >
										</select>
									</div>
								</div>
							<?php else : ?>
								<input class="item-currencyid" type="hidden"
									v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.currencyid' ) ) ) ?>`.replace('idx', idx)"
									value="<?= $enc->attr( $currencies->getCode()->first() ) ?>" />
							<?php endif ?>

							<?php if( ( $priceTypes = $this->get( 'priceTypes', map() ) )->count() !== 1 ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?></label>
									<div class="col-sm-8">
										<select is="select-component" required class="form-select item-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"
											v-bind:items="<?= $enc->attr( $priceTypes->col( 'price.type.label', 'price.type.code' )->toArray() ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( ['price', 'idx', 'price.type'] ) ) ?>`.replace('idx', idx)"
											v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`"
											v-bind:readonly="item['price.siteid'] != siteid"
											v-model="item['price.type']" >
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Types for additional prices like per one lb/kg or per month' ) ) ?>
									</div>
								</div>
							<?php else : ?>
								<input class="item-type" type="hidden"
									v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.type' ) ) ) ?>`.replace('idx', idx)"
									value="<?= $enc->attr( $priceTypes->getCode()->first() ) ?>" />
							<?php endif ?>

							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Min. quantity' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-quantity" type="number" step="0.001" min="0.001" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'price.quantity' ) ) ) ?>`.replace('idx', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Min. quantity' ) ) ?>"
										v-bind:readonly="item['price.siteid'] != siteid"
										v-model="item['price.quantity']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Required quantity of articles for block pricing, e.g. one article for $5.00, ten articles for $45.00' ) ) ?>
								</div>
							</div>

						</div>


						<div v-on:click="toggle('_ext', idx)" class="col-xl-12 advanced" v-bind:class="{'collapsed': !item['_ext']}">
							<div class="card-tools-start">
								<div class="btn act-show fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide advanced data' ) ) ?>">
								</div>
							</div>
							<span class="header-label"><?= $enc->html( $this->translate( 'admin', 'Advanced' ) ) ?></span>
						</div>

						<div v-show="item['_ext']" class="col-xl-6 secondary">

							<?php if( ( $listTypes = $this->get( 'priceListTypes', map() ) )->count() !== 1 ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'List type' ) ) ?></label>
									<div class="col-sm-8">
										<select is="select-component" required class="form-select listitem-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"
											v-bind:items="<?= $enc->attr( $listTypes->col( 'attribute.lists.type.label', 'attribute.lists.type.code' )->toArray() ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( ['price', 'idx', 'attribute.lists.type'] ) ) ?>`.replace('idx', idx)"
											v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`"
											v-bind:readonly="item['attribute.lists.siteid'] != siteid"
											v-model="item['attribute.lists.type']" >
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Second level type for grouping items' ) ) ?>
									</div>
								</div>
							<?php else : ?>
								<input class="listitem-type" type="hidden"
									v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'attribute.lists.type' ) ) ) ?>`.replace('idx', idx)"
									value="<?= $enc->attr( $listTypes->getCode()->first() ) ?>" />
							<?php endif ?>

							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ) ?></label>
								<div class="col-sm-8">
									<input is="flat-pickr" class="form-control listitem-datestart select" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'attribute.lists.datestart' ) ) ) ?>`.replace('idx', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
										v-bind:disabled="item['attribute.lists.siteid'] != siteid"
										v-bind:config="Aimeos.flatpickr.datetime"
										v-model="item['attribute.lists.datestart']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The item is only shown on the web site after that date and time' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?></label>
								<div class="col-sm-8">
									<input is="flat-pickr" class="form-control listitem-dateend select" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'price', 'idx', 'attribute.lists.dateend' ) ) ) ?>`.replace('idx', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
										v-bind:disabled="item['attribute.lists.siteid'] != siteid"
										v-bind:config="Aimeos.flatpickr.datetime"
										v-model="item['attribute.lists.dateend']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The item is only shown on the web site until that date and time' ) ) ?>
								</div>
							</div>
						</div>

						<div v-show="item['_ext']" class="col-xl-6 secondary" v-bind:class="{readonly: item['attribute.lists.siteid'] != siteid}">
							<config-table v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
								v-bind:keys="<?= $enc->attr( $this->config( 'admin/jqadm/attribute/item/price/config/suggest', [] ) ) ?>"
								v-bind:name="`<?= $enc->js( $this->formparam( ['price', '_idx_', 'config', '_pos_', '_key_'] ) ) ?>`.replace('_idx_', idx)"
								v-bind:index="idx" v-bind:readonly="item['attribute.lists.siteid'] != siteid"
								v-bind:items="item['config']" v-on:update:config="item['config'] = $event"
								v-bind:i18n="{
									value: `<?= $enc->js( $this->translate( 'admin', 'Value' ) ) ?>`,
									option: `<?= $enc->js( $this->translate( 'admin', 'Option' ) ) ?>`,
									help: `<?= $enc->js( $this->translate( 'admin', 'Item specific configuration options, will be available as key/value pairs in the templates' ) ) ?>`,
									insert: `<?= $enc->js( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>`,
									delete: `<?= $enc->js( $this->translate( 'admin', 'Delete this entry' ) ) ?>`,
								}">
							</config-table>
						</div>

						<?= $this->get( 'priceBody' ) ?>

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
