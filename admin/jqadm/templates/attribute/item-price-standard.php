<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
 */


$enc = $this->encoder();

$keys = [
	'attribute.lists.siteid', 'attribute.lists.typeid', 'attribute.lists.datestart', 'attribute.lists.dateend', 'config',
	'price.siteid', 'price.typeid', 'price.currencyid', 'price.status', 'price.quantity', 'price.taxrate', 'price.value', 'price.rebate', 'price.costs'
];

$currencies = $this->get( 'priceCurrencies', [] );


?>
<div id="price" class="item-price content-block tab-pane fade" role="tablist" aria-labelledby="price">
	<div id="item-price-group" role="tablist" aria-multiselectable="true"
		data-items="<?= $enc->attr( json_encode( $this->get( 'priceData', [] ) ) ); ?>"
		data-listtypeid="<?= key( $this->get( 'priceListTypes', [] ) ) ?>"
		data-keys="<?= $enc->attr( json_encode( $keys ) ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>" >

		<div v-for="(entry, idx) in items" class="group-item card">

			<div v-bind:id="'item-price-group-item-' + idx" v-bind:class="getCss(idx)"
				v-bind:data-target="'#item-price-group-data-' + idx" data-toggle="collapse" role="tab" class="card-header header"
				v-bind:aria-controls="'item-price-group-data-' + idx" aria-expanded="false">
				<div class="card-tools-left">
					<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry') ); ?>">
					</div>
				</div>
				<span class="item-label header-label" v-html="getLabel(idx)"></span>
				&nbsp;
				<div class="card-tools-right">
					<div v-if="!checkSite('attribute.lists.siteid', idx)"
						class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
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
							<input class="form-control item-taxrate" type="number" step="0.01" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'price.taxrate' ) ) ); ?>'.replace('idx', idx)"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Tax rate in %' ) ); ?>"
								v-bind:readonly="checkSite('price.siteid', idx)"
								v-model="items[idx]['price.taxrate']" />
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

					<?php if( count( $currencies ) > 1 ) : ?>
						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Currency' ) ); ?></label>
							<div class="col-sm-8">
								<select class="form-control custom-select item-currencyid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'price.currencyid' ) ) ); ?>'.replace('idx', idx)"
									v-bind:readonly="checkSite('price.siteid', idx)"
									v-model="items[idx]['price.currencyid']" >
									<option value="" disabled>
										<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
									</option>

									<?php foreach( $currencies as $currencyId => $currencyItem ) : ?>
										<option value="<?= $enc->attr( $currencyItem->getCode() ); ?>" v-bind:selected="items[idx]['price.currencyid'] == '<?= $enc->attr( $currencyId ) ?>'" >
											<?= $enc->html( $currencyItem->getCode() ); ?>
										</option>
									<?php endforeach; ?>

								</select>
							</div>
						</div>
					<?php else : ?>
						<input class="item-currencyid" type="hidden"
							v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'price.currencyid' ) ) ); ?>'.replace('idx', idx)"
							value="<?= $enc->attr( key( $currencies ) ); ?>" />
					<?php endif; ?>

					<?php $priceTypes = $this->get( 'priceTypes', [] ); ?>
					<?php if( count( $priceTypes ) > 1 ) : ?>
						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
							<div class="col-sm-8">
								<select class="form-control custom-select item-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'price.typeid' ) ) ); ?>'.replace('idx', idx)"
									v-bind:readonly="checkSite('price.siteid', idx)"
									v-model="items[idx]['price.typeid']" >
									<option value="">
										<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
									</option>

									<?php foreach( (array) $priceTypes as $typeId => $typeItem ) : ?>
										<option value="<?= $enc->attr( $typeId ); ?>" v-bind:selected="items[idx]['price.typeid'] == '<?= $enc->attr( $typeId ) ?>'" >
											<?= $enc->html( $typeItem->getLabel() ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Types for additional prices like per one lb/kg or per month' ) ); ?>
							</div>
						</div>
					<?php else : ?>
						<input class="item-typeid" type="hidden"
							v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'price.typeid' ) ) ); ?>'.replace('idx', idx)"
							value="<?= $enc->attr( key( $priceTypes ) ); ?>" />
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
							title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide advanced data') ); ?>">
						</div>
					</div>
					<span class="header-label"><?= $enc->html( $this->translate( 'admin', 'Advanced' ) ); ?></span>
				</div>

				<div v-show="advanced[idx]" class="col-xl-6 content-block secondary">

					<input type="hidden" v-model="items[idx]['attribute.lists.type']"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'attribute.lists.type' ) ) ); ?>'.replace( 'idx', idx )" />

					<?php $listTypes = $this->get( 'priceListTypes', [] ); ?>
					<?php if( count( $listTypes ) > 1 ) : ?>
						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'List type' ) ); ?></label>
							<div class="col-sm-8">
								<select class="form-control custom-select listitem-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'attribute.lists.typeid' ) ) ); ?>'.replace('idx', idx)"
									v-bind:readonly="checkSite('attribute.lists.siteid', idx)"
									v-model="items[idx]['attribute.lists.typeid']" >

									<?php foreach( $this->get( 'priceListTypes', [] ) as $id => $typeItem ) : ?>
										<option value="<?= $enc->attr( $id ); ?>" v-bind:selected="entry['attribute.lists.typeid'] == '<?= $enc->attr( $id ) ?>'" >
											<?= $enc->html( $typeItem->getLabel() ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Second level type for grouping items' ) ); ?>
							</div>
						</div>
					<?php else : ?>
						<input class="listitem-typeid" type="hidden"
							v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'attribute.lists.typeid' ) ) ); ?>'.replace('idx', idx)"
							value="<?= $enc->attr( key( $listTypes ) ); ?>"
							v-model="items[idx]['attribute.lists.typeid']" />
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
					<table class="item-config table table-striped">
						<thead>
							<tr>
								<th>
									<span class="help"><?= $enc->html( $this->translate( 'admin', 'Option' ) ); ?></span>
									<div class="form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Configuration options, will be available as key/value pairs in the list item' ) ); ?>
									</div>
								</th>
								<th>
									<?= $enc->html( $this->translate( 'admin', 'Value' ) ); ?>
								</th>
								<th class="actions">
									<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
										v-bind:readonly="checkSite('attribute.lists.siteid', idx)"
										v-on:click="addConfig(idx)" >
									</div>
								</th>
							</tr>
						</thead>
						<tbody>

							<tr v-for="(key, pos) in getConfig(idx)" v-bind:key="pos">
								<td>
									<input is="auto-complete"
										v-model="items[idx]['config']['key'][pos]"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'config', 'key', '' ) ) ); ?>'.replace('idx', idx)"
										v-bind:readonly="checkSite('attribute.lists.siteid', idx)"
										v-bind:tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:keys="[]" />
								</td>
								<td>
									<input type="text" class="form-control" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'idx', 'config', 'val', '' ) ) ); ?>'.replace('idx', idx)"
										v-bind:readonly="checkSite('attribute.lists.siteid', idx)"
										v-model="items[idx]['config']['val'][pos]" />
								</td>
								<td class="actions">
									<div v-if="!checkSite('attribute.lists.siteid', idx)" v-on:click="removeConfig(idx, pos)"
										class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
									</div>
								</td>
							</tr>

						</tbody>
					</table>
				</div>

				<?= $this->get( 'priceBody' ); ?>

			</div>
		</div>

		<div class="card-tools-more">
			<div class="btn btn-primary btn-card-more act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
				title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
				v-on:click="addItem('attribute.lists.')" >
			</div>
		</div>
	</div>
</div>
