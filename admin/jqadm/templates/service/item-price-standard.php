<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2017
 */


$enc = $this->encoder();

$keys = [
	'service.lists.id', 'service.lists.siteid', 'service.lists.typeid', 'service.lists.datestart', 'service.lists.dateend',
	'price.id', 'price.siteid', 'price.taxrate', 'price.value', 'price.rebate', 'price.costs',
	'price.status', 'price.currencyid', 'price.typeid', 'price.quantity'
];

$currencies = $this->get( 'priceCurrencies', [] );


?>
<div id="price" class="item-price content-block tab-pane fade" role="tablist" aria-labelledby="price">
	<div id="item-price-group" role="tablist" aria-multiselectable="true"
		data-items="<?= $enc->attr( json_encode( $this->get( 'priceData', [] ) ) ); ?>"
		data-listtypeid="<?= key( $this->get( 'priceListTypes', [] ) ) ?>"
		data-currencyid="<?= key( $currencies ) ?>"
		data-keys="<?= $enc->attr( json_encode( $keys ) ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>" >

		<div v-for="(id, idx) in items['service.lists.id']" class="group-item card">
			<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'price', 'service.lists.id', '' ) ) ); ?>"
				v-bind:value="items['service.lists.id'][idx]" />

			<div v-bind:id="'item-price-group-item-' + idx" v-bind:class="getCss(idx)"
				v-bind:data-target="'#item-price-group-data-' + idx" data-toggle="collapse" role="tab" class="card-header header"
				v-bind:aria-controls="'item-price-group-data-' + idx" aria-expanded="false">
				<div class="card-tools-left">
					<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry') ); ?>">
					</div>
				</div>
				<span class="item-label header-label">{{ getLabel(idx) }}</span>
				&nbsp;
				<div class="card-tools-right">
					<div v-if="!checkSite('service.lists.siteid', idx)"
						class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
						v-on:click.stop="removeItem(idx)">
					</div>
				</div>
			</div>

			<div v-bind:id="'item-price-group-data-' + idx" v-bind:class="getCss(idx)"
				v-bind:aria-labelledby="'item-price-group-item-' + idx" role="tabpanel" class="card-block collapse row">

				<div class="col-xl-6">

					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Tax rate in %' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-taxrate" type="number" step="0.01" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.taxrate', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Tax rate in %' ) ); ?>"
								v-bind:readonly="checkSite('price.siteid', idx)"
								v-model="items['price.taxrate'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Country specific tax rate to calculate and display the included tax (B2C) or add the tax if required (B2B)' ) ); ?>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Actual current price' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-value" type="number" step="0.01" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.value', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Actual current price' ) ); ?>"
								v-bind:readonly="checkSite('price.siteid', idx)"
								v-model="items['price.value'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Actual price customers can buy the article for on the web site' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Substracted rebate amount' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-rebate" type="number" step="0.01"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.rebate', '' ) ) ); ?>" tabindex="<?= $this->get( 'tabindex' ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Substracted rebate amount' ) ); ?>"
								v-bind:readonly="checkSite('price.siteid', idx)"
								v-model="items['price.rebate'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Reduction from the original price, used to calculate the rebate in % and the cross price' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Shipping costs per item' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-costs" type="number" step="0.01"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.costs', '' ) ) ); ?>" tabindex="<?= $this->get( 'tabindex' ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Shipping costs per item' ) ); ?>"
								v-bind:readonly="checkSite('price.siteid', idx)"
								v-model="items['price.costs'][idx]" />
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
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.status', '' ) ) ); ?>"
								v-bind:readonly="checkSite('price.siteid', idx)"
								v-model="items['price.status'][idx]" >
								<option value="1" v-bind:selected="items['price.status'][idx] == 1" >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ); ?>
								</option>
								<option value="0" v-bind:selected="items['price.status'][idx] == 0" >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ); ?>
								</option>
								<option value="-1" v-bind:selected="items['price.status'][idx] == -1" >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ); ?>
								</option>
								<option value="-2" v-bind:selected="items['price.status'][idx] == -2" >
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
									name="<?= $enc->attr( $this->formparam( array( 'price', 'price.currencyid', '' ) ) ); ?>"
									v-bind:readonly="checkSite('price.siteid', idx)"
									v-model="items['price.currencyid'][idx]" >
									<option value="" disabled>
										<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
									</option>

									<?php foreach( $currencies as $currencyId => $currencyItem ) : ?>
										<option value="<?= $enc->attr( $currencyItem->getCode() ); ?>" v-bind:selected="items['price.currencyid'][idx] == '<?= $enc->attr( $currencyId ) ?>'" >
											<?= $enc->html( $currencyItem->getCode() ); ?>
										</option>
									<?php endforeach; ?>

								</select>
							</div>
						</div>
					<?php else : ?>
						<input class="item-currencyid" type="hidden"
							name="<?= $enc->attr( $this->formparam( array( 'price', 'price.currencyid', '' ) ) ); ?>"
							value="<?= $enc->attr( key( $currencies ) ); ?>" />
					<?php endif; ?>

					<?php $priceTypes = $this->get( 'priceTypes', [] ); ?>
					<?php if( count( $priceTypes ) > 1 ) : ?>
						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
							<div class="col-sm-8">
								<select class="form-control custom-select item-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'price', 'price.typeid', '' ) ) ); ?>"
									v-bind:readonly="checkSite('price.siteid', idx)"
									v-model="items['price.typeid'][idx]" >
									<option value="">
										<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
									</option>

									<?php foreach( (array) $priceTypes as $typeId => $typeItem ) : ?>
										<option value="<?= $enc->attr( $typeId ); ?>" v-bind:selected="items['price.typeid'][idx] == '<?= $enc->attr( $typeId ) ?>'" >
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
							name="<?= $enc->attr( $this->formparam( array( 'price', 'price.typeid', '' ) ) ); ?>"
							value="<?= $enc->attr( key( $priceTypes ) ); ?>" />
					<?php endif; ?>

					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Minimum quantity' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-quantity" type="number" step="1" min="1" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.quantity', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Minimum quantity' ) ); ?>"
								v-bind:readonly="checkSite('price.siteid', idx)"
								v-model="items['price.quantity'][idx]" />
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
					<?php $listTypes = $this->get( 'priceListTypes', [] ); ?>
					<?php if( count( $listTypes ) > 1 ) : ?>
						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'List type' ) ); ?></label>
							<div class="col-sm-8">
								<select class="form-control custom-select listitem-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'price', 'service.lists.typeid', '' ) ) ); ?>"
									v-bind:readonly="checkSite('service.lists.siteid', idx)"
									v-model="items['service.lists.typeid'][idx]" >

									<?php foreach( $this->get( 'priceListTypes', [] ) as $id => $typeItem ) : ?>
										<option value="<?= $enc->attr( $id ); ?>" v-bind:selected="items['service.lists.typeid'][idx] == '<?= $enc->attr( $id ) ?>'" >
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
							name="<?= $enc->attr( $this->formparam( array( 'price', 'service.lists.typeid', '' ) ) ); ?>"
							value="<?= $enc->attr( key( $listTypes ) ); ?>"
							v-model="items['service.lists.typeid'][idx]" />
					<?php endif; ?>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control listitem-datestart" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'service.lists.datestart', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
								v-bind:readonly="checkSite('service.lists.siteid', idx)"
								v-model="items['service.lists.datestart'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'The item is only shown on the web site after that date and time' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control listitem-dateend" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'service.lists.dateend', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
								v-bind:readonly="checkSite('service.lists.siteid', idx)"
								v-model="items['service.lists.dateend'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'The item is only shown on the web site until that date and time' ) ); ?>
						</div>
					</div>
				</div>

				<div v-show="advanced[idx]" class="col-xl-6 content-block secondary" v-bind:class="items['service.lists.siteid'][idx] != '<?= $this->site()->siteid() ?>' ? 'readonly' : ''">
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
										v-bind:readonly="checkSite('service.lists.siteid', idx)"
										v-on:click="addConfig(idx)" >
									</div>
								</th>
							</tr>
						</thead>
						<tbody>

							<tr v-for="(key, pos) in getConfig(idx)" v-bind:key="pos">
								<td>
									<input is="auto-complete" v-once
										v-model="items['config'][idx]['key'][pos]"
										v-bind:value="items['config'][idx]['key'][pos]"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'config', 'idx', 'key', '' ) ) ); ?>'.replace('idx', idx)"
										v-bind:readonly="checkSite('service.lists.siteid', idx)"
										v-bind:tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:keys="[]" />
								</td>
								<td>
									<input type="text" class="form-control" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'price', 'config', 'idx', 'val', '' ) ) ); ?>'.replace('idx', idx)"
										v-bind:readonly="checkSite('service.lists.siteid', idx)"
										v-model="items['config'][idx]['val'][pos]" />
								</td>
								<td class="actions">
									<div v-if="!checkSite('service.lists.siteid', idx)" v-on:click="removeConfig(idx, pos)"
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
	</div>

	<div class="card-tools-more">
		<div class="btn btn-primary btn-card-more act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
			title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
			v-on:click="addItem('service.lists.')" >
		</div>
	</div>
</div>
