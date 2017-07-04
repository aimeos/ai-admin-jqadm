<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();


?>
<div id="price" class="item-price content-block tab-pane fade" role="tablist" aria-labelledby="price">
	<div id="item-price-group" role="tablist" aria-multiselectable="true">

		<?php foreach( (array) $this->get( 'priceData/price.currencyid', [] ) as $idx => $currencyid ) : ?>

			<div class="group-item card <?= $this->site()->readonly( $this->get( 'priceData/product.lists.siteid/' . $idx ) ); ?>">
				<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'price', 'product.lists.id', '' ) ) ); ?>"
					value="<?= $enc->attr( $this->get( 'priceData/product.lists.id/' . $idx ) ); ?>" />

				<div id="item-price-group-item-<?= $enc->attr( $idx ); ?>" class="card-header header  <?= ( $idx !== 0 ? 'collapsed' : '' ); ?>" role="tab"
					data-toggle="collapse" data-target="#item-price-group-data-<?= $enc->attr( $idx ); ?>"
					aria-expanded="false" aria-controls="item-price-group-data-<?= $enc->attr( $idx ); ?>">
					<div class="card-tools-left">
						<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry') ); ?>">
						</div>
					</div>
					<span class="item-label header-label"><?= $enc->html( $this->get( 'priceData/price.label/' . $idx ) ); ?></span>
					&nbsp;
					<div class="card-tools-right">
						<?php if( !$this->site()->readonly( $this->get( 'priceData/product.lists.siteid/' . $idx ) ) ) : ?>
							<div class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div id="item-price-group-data-<?= $enc->attr( $idx ); ?>" class="card-block collapse row <?= ( $idx === 0 ? 'show' : '' ); ?>"
					role="tabpanel" aria-labelledby="item-price-group-item-<?= $enc->attr( $idx ); ?>">

					<div class="col-xl-6">
						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Tax rate in %' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-taxrate" type="number" step="0.01" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'price', 'price.taxrate', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Tax rate in %' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'priceData/price.taxrate/' . $idx, 0 ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'priceData/product.lists.siteid/' . $idx ) ); ?> />
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
									value="<?= $enc->attr( $this->get( 'priceData/price.value/' . $idx, '0.00' ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'priceData/product.lists.siteid/' . $idx ) ); ?> />
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
									value="<?= $enc->attr( $this->get( 'priceData/price.rebate/' . $idx, '0.00' ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'priceData/product.lists.siteid/' . $idx ) ); ?> />
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
									value="<?= $enc->attr( $this->get( 'priceData/price.costs/' . $idx, '0.00' ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'priceData/product.lists.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Additional delivery costs for each item, e.g. $20 for one heavy item will be $100 for five items it total' ) ); ?>
							</div>
						</div>
					</div>

					<div class="col-xl-6">

						<?php $currencies = $this->get( 'priceCurrencies', [] ); ?>
						<?php if( count( $currencies ) > 1 ) : ?>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Currency' ) ); ?></label>
								<div class="col-sm-8">
									<select class="form-control custom-select item-currencyid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
										name="<?= $enc->attr( $this->formparam( array( 'price', 'price.currencyid', '' ) ) ); ?>"
										<?= $this->site()->readonly( $this->get( 'priceData/product.lists.siteid/' . $idx ) ); ?> >
										<option value="">
											<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
										</option>

										<?php foreach( $currencies as $currencyItem ) : ?>
											<option value="<?= $enc->attr( $currencyItem->getCode() ); ?>" <?= ( $currencyid == $currencyItem->getCode() ? 'selected="selected"' : '' ) ?> >
												<?= $enc->html( $currencyItem->getCode() ); ?>
											</option>
										<?php endforeach; ?>

									</select>
								</div>
							</div>
						<?php else : ?>
							<input class="item-currencyid" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.currencyid', '' ) ) ); ?>"
								value="<?= $enc->attr( $currencyid ); ?>" />
						<?php endif; ?>

						<?php $priceTypes = $this->get( 'priceTypes', [] ); ?>
						<?php if( count( $priceTypes ) > 1 ) : ?>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
								<div class="col-sm-8">
									<select class="form-control custom-select item-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
										name="<?= $enc->attr( $this->formparam( array( 'price', 'price.typeid', '' ) ) ); ?>"
										<?= $this->site()->readonly( $this->get( 'priceData/product.lists.siteid/' . $idx ) ); ?> >
										<option value="">
											<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
										</option>

										<?php foreach( (array) $priceTypes as $typeId => $typeItem ) : ?>
											<option value="<?= $enc->attr( $typeId ); ?>" <?= ( $typeId == $this->get( 'priceData/price.typeid/' . $idx ) ? 'selected="selected"' : '' ) ?> >
												<?= $enc->html( $typeItem->getLabel() ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Types for additional prices like per one lb/kg or per month' ) ); ?>
								</div>
							</div>
						<?php else : $priceType = reset( $priceTypes ); ?>
							<input class="item-typeid" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.typeid', '' ) ) ); ?>"
								value="<?= $enc->attr( $priceType ? $priceType->getId() : '' ); ?>" />
						<?php endif; ?>

						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Minimum quantity' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-quantity" type="number" step="1" min="1" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'price', 'price.quantity', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Minimum quantity' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'priceData/price.quantity/' . $idx, 1 ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'priceData/product.lists.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Required quantity of articles for block pricing, e.g. one article for $5.00, ten articles for $45.00' ) ); ?>
							</div>
						</div>
					</div>

				</div>

			</div>

		<?php endforeach; ?>

		<div class="group-item card prototype">
			<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'price', 'product.lists.id', '' ) ) ); ?>" disabled="disabled" />

			<div id="item-price-group-item-" class="card-header header" role="tab"
				data-toggle="collapse" data-target="#item-price-group-data-">
				<div class="card-tools-left">
					<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry') ); ?>">
					</div>
				</div>
				<span class="item-label header-label"></span>
				&nbsp;
				<div class="card-tools-right">
					<div class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
					</div>
				</div>
			</div>

			<div id="item-price-group-data-" class="card-block collapse show row" role="tabpanel">

				<div class="col-xl-6">
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Tax rate in %' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-taxrate" type="number" step="0.01" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.taxrate', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Tax rate in %' ) ); ?>" />
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Actual current price' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-value" type="number" step="0.01" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.value', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Actual current price' ) ); ?>" />
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Substracted rebate amount' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-rebate" type="number" step="0.01" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.rebate', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Substracted rebate amount' ) ); ?>" />
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Shipping costs per item' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-costs" type="number" step="0.01" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.costs', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Shipping costs per item' ) ); ?>" />
						</div>
					</div>
				</div>

				<div class="col-xl-6">

					<?php $currencies = $this->get( 'priceCurrencies', [] ); ?>
					<?php if( count( $currencies ) > 1 ) : ?>
						<div class="form-group row">
							<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Currency' ) ); ?></label>
							<div class="col-sm-8">
								<select class="form-control custom-select item-currencyid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
									name="<?= $enc->attr( $this->formparam( array( 'price', 'price.currencyid', '' ) ) ); ?>">
									<option value="">
										<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
									</option>

									<?php foreach( $currencies as $currencyItem ) : ?>
										<option value="<?= $enc->attr( $currencyItem->getCode() ); ?>" >
											<?= $enc->html( $currencyItem->getCode() ); ?>
										</option>
									<?php endforeach; ?>

								</select>
							</div>
						</div>
					<?php else : $currencyItem = reset( $currencies ); ?>
						<input class="item-currencyid" type="hidden" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'price', 'price.currencyid', '' ) ) ); ?>"
							value="<?= $enc->attr( $currencyItem ? $currencyItem->getId() : '' ); ?>" />
					<?php endif; ?>

					<?php $priceTypes = $this->get( 'priceTypes', [] ); ?>
					<?php if( count( $priceTypes ) > 1 ) : ?>
						<div class="form-group row">
							<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
							<div class="col-sm-8">
								<select class="form-control custom-select item-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
									name="<?= $enc->attr( $this->formparam( array( 'price', 'price.typeid', '' ) ) ); ?>">
									<option value="">
										<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
									</option>

									<?php foreach( (array) $priceTypes as $typeId => $typeItem ) : ?>
										<option value="<?= $enc->attr( $typeId ); ?>" >
											<?= $enc->html( $typeItem->getLabel() ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					<?php else : $priceType = reset( $priceTypes ); ?>
						<input class="item-typeid" type="hidden" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'price', 'price.typeid', '' ) ) ); ?>"
							value="<?= $enc->attr( $priceType ? $priceType->getId() : '' ); ?>" />
					<?php endif; ?>

					<div class="form-group row">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Minimum quantity' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-quantity" type="number" step="1" min="1" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.quantity', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Minimum quantity' ) ); ?>"
								value="1" />
						</div>
					</div>
				</div>

			</div>

		</div>

		<div class="card-tools-more">
			<div class="btn btn-primary btn-card-more act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
				title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+A)') ); ?>">
			</div>
		</div>

	</div>

	<?= $this->get( 'priceBody' ); ?>
</div>
