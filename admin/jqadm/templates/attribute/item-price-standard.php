<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
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

$keys = [
	'attribute.lists.siteid', 'attribute.lists.type', 'attribute.lists.datestart', 'attribute.lists.dateend', 'config',
	'price.siteid', 'price.type', 'price.currencyid', 'price.status', 'price.quantity', 'price.taxrates', 'price.value', 'price.rebate', 'price.costs'
];


?>
<div id="price" class="item-price content-block tab-pane fade" role="tablist" aria-labelledby="price">
	<div id="item-price-group" role="tablist" aria-multiselectable="true"
		data-items="<?= $enc->attr( $this->get( 'priceData', [] ) ); ?>"
		data-listtype="<?= key( $this->get( 'priceListTypes', [] ) ) ?>"
		data-keys="<?= $enc->attr( $keys ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>" >

		<div class="group-list">
			<div is="draggable" v-model="items" group="price" handle=".act-move">
				<div v-for="(entry, idx) in items" v-bind:key="idx" class="group-item card">

					<div v-bind:id="'item-price-group-item-' + idx" v-bind:class="getCss(idx)"
						v-bind:data-target="'#item-price-group-data-' + idx" data-toggle="collapse" role="tab" class="card-header header"
						v-bind:aria-controls="'item-price-group-data-' + idx" aria-expanded="false">
						<div class="card-tools-left">
							<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry' ) ); ?>">
							</div>
						</div>
						<span class="item-label header-label" v-html="getLabel(idx)"></span>
						&nbsp;
						<div class="card-tools-right">
							<div v-if="!checkSite('attribute.lists.siteid', idx)"
								class="btn btn-card-header act-move fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down' ) ); ?>">
							</div>
							<div v-if="!checkSite('attribute.lists.siteid', idx)"
								class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ); ?>"
								v-on:click.stop="removeItem(idx)">
							</div>
						</div>
					</div>

					<div v-bind:id="'item-price-group-data-' + idx" v-bind:class="getCss(idx)"
						v-bind:aria-labelledby="'item-price-group-item-' + idx" role="tabpanel" class="card-block collapse row">

						<input type="hidden" v-model="items[idx]['price.id']"
							v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'price.id' ) ) ); ?>'.replace('idx', idx)" />

						<div class="col-xl-6">

							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Tax rate in %' ) ); ?></label>
								<div class="col-sm-8">
									<div is="taxrates" v-bind:key="idx"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'price.taxrates' ) ) ); ?>'.replace('idx', idx)"
										v-bind:placeholder="'<?= $enc->attr( $this->translate( 'admin', 'Tax rate in %' ) ); ?>'"
										v-bind:types="JSON.parse('<?= $enc->attr($this->config( 'admin/tax', [] ) ) ?>')"
										v-bind:tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:readonly="checkSite('price.siteid', idx)"
										v-bind:taxrates="items[idx]['price.taxrates']"
									></div>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Country specific tax rate to calculate and display the included tax (B2C) or add the tax if required (B2B)' ) ); ?>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Actual current price' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control item-value" type="number" step="0.01" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'price.value' ) ) ); ?>'.replace('idx', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Actual current price' ) ); ?>"
										v-bind:readonly="checkSite('price.siteid', idx)"
										v-model="items[idx]['price.value']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Actual price customers can buy the article for on the web site' ) ); ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Substracted rebate amount' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control item-rebate" type="number" step="0.01" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'price.rebate' ) ) ); ?>'.replace('idx', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Substracted rebate amount' ) ); ?>"
										v-bind:readonly="checkSite('price.siteid', idx)"
										v-model="items[idx]['price.rebate']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Reduction from the original price, used to calculate the rebate in % and the cross price' ) ); ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Shipping costs per item' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control item-costs" type="number" step="0.01" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'price.costs' ) ) ); ?>'.replace('idx', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Shipping costs per item' ) ); ?>"
										v-bind:readonly="checkSite('price.siteid', idx)"
										v-model="items[idx]['price.costs']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Additional delivery costs for each item, e.g. $20 for one heavy item will be $100 for five items it total' ) ); ?>
								</div>
							</div>

						</div>

						<div class="col-xl-6">

							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ); ?></label>
								<div class="col-sm-8">
									<select class="form-control custom-select item-status" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'price.status' ) ) ); ?>'.replace('idx', idx)"
										v-bind:readonly="checkSite('price.siteid', idx)"
										v-model="items[idx]['price.status']" >
										<option value="1" v-bind:selected="items[idx]['price.status'] == 1" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ); ?>
										</option>
										<option value="0" v-bind:selected="items[idx]['price.status'] == 0" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ); ?>
										</option>
										<option value="-1" v-bind:selected="items[idx]['price.status'] == -1" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ); ?>
										</option>
										<option value="-2" v-bind:selected="items[idx]['price.status'] == -2" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ); ?>
										</option>
									</select>
								</div>
							</div>

							<?php if( ( $currencies = $this->get( 'priceCurrencies', [] ) ) !== [] ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Currency' ) ); ?></label>
									<div class="col-sm-8">
										<select is="select-component" required class="form-control custom-select item-currencyid" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
											v-bind:items="JSON.parse('<?= $enc->attr( $this->map( $currencies, 'locale.currency.code', 'locale.currency.label' )->toArray() ) ?>')"
											v-bind:name="'<?= $enc->attr( $this->formparam( ['price', 'idx', 'price.currencyid'] ) ); ?>'.replace('idx', idx)"
											v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
											v-bind:readonly="checkSite('price.siteid', idx)"
											v-model="entry['price.currencyid']" >
										</select>
									</div>
								</div>
							<?php else : ?>
								<input class="item-currencyid" type="hidden"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'price.currencyid' ) ) ); ?>'.replace('idx', idx)"
									value="<?= $enc->attr( key( $currencies ) ) ?>" />
							<?php endif; ?>

							<?php if( ( $priceTypes = $this->get( 'priceTypes', [] ) ) !== [] ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
									<div class="col-sm-8">
										<select is="select-component" required class="form-control custom-select item-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
											v-bind:items="JSON.parse('<?= $enc->attr( $this->map( $priceTypes, 'price.type.code', 'price.type.label' )->toArray() ) ?>')"
											v-bind:name="'<?= $enc->attr( $this->formparam( ['price', 'idx', 'price.type'] ) ); ?>'.replace('idx', idx)"
											v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
											v-bind:readonly="checkSite('price.siteid', idx)"
											v-model="entry['price.type']" >
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Types for additional prices like per one lb/kg or per month' ) ); ?>
									</div>
								</div>
							<?php else : ?>
								<input class="item-type" type="hidden"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'price.type' ) ) ); ?>'.replace('idx', idx)"
									value="<?= $enc->attr( key( $priceTypes ) ) ?>" />
							<?php endif; ?>

							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Minimum quantity' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control item-quantity" type="number" step="1" min="1" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'price.quantity' ) ) ); ?>'.replace('idx', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Minimum quantity' ) ); ?>"
										v-bind:readonly="checkSite('price.siteid', idx)"
										v-model="items[idx]['price.quantity']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Required quantity of articles for block pricing, e.g. one article for $5.00, ten articles for $45.00' ) ); ?>
								</div>
							</div>

						</div>


						<div v-on:click="toggle(idx)" class="col-xl-12 advanced" v-bind:class="{ 'collapsed': !advanced[idx] }">
							<div class="card-tools-left">
								<div class="btn act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide advanced data' ) ); ?>">
								</div>
							</div>
							<span class="header-label"><?= $enc->html( $this->translate( 'admin', 'Advanced' ) ); ?></span>
						</div>

						<div v-show="advanced[idx]" class="col-xl-6 content-block secondary">

							<?php if( ( $listTypes = $this->get( 'priceListTypes', [] ) ) !== [] ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'List type' ) ); ?></label>
									<div class="col-sm-8">
										<select is="select-component" required class="form-control custom-select listitem-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
											v-bind:items="JSON.parse('<?= $enc->attr( $this->map( $listTypes, 'attribute.lists.type.code', 'attribute.lists.type.label' )->toArray() ) ?>')"
											v-bind:name="'<?= $enc->attr( $this->formparam( ['price', 'idx', 'attribute.lists.type'] ) ); ?>'.replace('idx', idx)"
											v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
											v-bind:readonly="checkSite('attribute.lists.siteid', idx)"
											v-model="entry['attribute.lists.type']" >
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Second level type for grouping items' ) ); ?>
									</div>
								</div>
							<?php else : ?>
								<input class="listitem-type" type="hidden"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'attribute.lists.type' ) ) ); ?>'.replace('idx', idx)"
									value="<?= $enc->attr( key( $listTypes ) ); ?>" />
							<?php endif; ?>

							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control listitem-datestart" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'attribute.lists.datestart' ) ) ); ?>'.replace('idx', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
										v-bind:readonly="checkSite('attribute.lists.siteid', idx)"
										v-model="items[idx]['attribute.lists.datestart']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The item is only shown on the web site after that date and time' ) ); ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control listitem-dateend" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'attribute.lists.dateend' ) ) ); ?>'.replace('idx', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
										v-bind:readonly="checkSite('attribute.lists.siteid', idx)"
										v-model="items[idx]['attribute.lists.dateend']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The item is only shown on the web site until that date and time' ) ); ?>
								</div>
							</div>
						</div>

						<div v-show="advanced[idx]" class="col-xl-6 content-block secondary" v-bind:class="checkSite('attribute.lists.siteid', idx) ? 'readonly' : ''">
							<config-table inline-template v-bind:idx="idx" v-bind:items="getConfig(idx)" v-bind:readonly="checkSite('attribute.lists.siteid', idx)">
								<table class="item-config table table-striped">
									<thead>
										<tr>
											<th class="config-row-key">
												<span class="help"><?= $enc->html( $this->translate( 'admin', 'Option' ) ); ?></span>
												<div class="form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Configuration options, will be available as key/value pairs in the list item' ) ); ?>
												</div>
											</th class="config-row-value">
											<th><?= $enc->html( $this->translate( 'admin', 'Value' ) ); ?></th>
											<th class="actions">
												<div v-if="!readonly" class="btn act-add fa" tabindex="1" v-on:click="add()"
													title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"></div>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(entry, pos) in list" v-bind:key="pos" class="config-item">
											<td class="config-row-key">
												<input is="auto-complete" class="form-control" v-bind:tabindex="1" v-bind:readonly="readonly" required
													v-bind:name="'<?= $enc->attr( $this->formparam( ['price', '_idx_', 'config', '_pos_', 'key'] ) ); ?>'.replace('_idx_', idx).replace('_pos_', pos)"
													v-bind:keys="JSON.parse('<?= $enc->attr( $this->config( 'admin/jqadm/attribute/item/price/config/suggest', [] ) ) ?>')"
													v-bind:value="entry.key" />
											</td>
											<td class="config-row-value">
												<input class="form-control" v-bind:tabindex="1" v-bind:readonly="readonly"
													v-bind:name="'<?= $enc->attr( $this->formparam( ['price', '_idx_', 'config', '_pos_', 'val'] ) ); ?>'.replace('_idx_', idx).replace('_pos_', pos)"
													v-bind:value="entry.val" />
											</td>
											<td class="actions">
												<div v-if="!readonly" class="btn act-delete fa" tabindex="1" v-on:click="remove(pos)"
													title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"></div>
											</td>
										</tr>
									</tbody>
								</table>
							</config-table>
						</div>

						<?= $this->get( 'priceBody' ); ?>

					</div>
				</div>
			</div>

			<div slot="footer" class="card-tools-more">
				<div class="btn btn-primary btn-card-more act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
					title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ); ?>"
					v-on:click="addItem('attribute.lists.')" >
				</div>
			</div>
		</div>
	</div>
</div>
