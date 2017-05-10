<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();


?>
<div id="price" class="item-price tab-pane fade" role="tabpanel" aria-labelledby="price">
	<div id="product-item-price-group" role="tablist" aria-multiselectable="true">

		<?php foreach( (array) $this->get( 'priceData/price.currencyid', [] ) as $idx => $currencyid ) : ?>

			<div class="group-item card">
				<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'price', 'product.lists.id', '' ) ) ); ?>"
					value="<?= $enc->attr( $this->get( 'priceData/product.lists.id/' . $idx ) ); ?>" />

				<div id="product-item-price-group-item-<?= $enc->attr( $idx ); ?>" class="card-header header collapsed" role="tab"
					data-toggle="collapse" data-target="#product-item-price-group-data-<?= $enc->attr( $idx ); ?>"
					aria-expanded="false" aria-controls="product-item-price-group-data-<?= $enc->attr( $idx ); ?>">
					<div class="card-tools-left">
						<div class="btn btn-card-header act-show fa"></div>
					</div>
					<span class="item-label header-label"><?= $enc->html( $this->get( 'priceData/price.label/' . $idx ) ); ?></span>
					&nbsp;
					<div class="card-tools-right">
						<div class="btn btn-card-header act-delete fa"></div>
					</div>
				</div>

				<div id="product-item-price-group-data-<?= $enc->attr( $idx ); ?>" class="card-block collapse row"
					role="tabpanel" aria-labelledby="product-item-price-group-item-<?= $enc->attr( $idx ); ?>">

					<div class="col-xl-6">
						<div class="form-group row mandatory">
							<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Tax rate in %' ) ); ?></label>
							<div class="col-lg-8">
								<input class="form-control item-taxrate" type="text" data-pattern="^[0-9]+(\.[0-9]+)?$"
									name="<?= $enc->attr( $this->formparam( array( 'price', 'price.taxrate', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Tax rate in %' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'priceData/price.taxrate/' . $idx, 0 ) ); ?>" />
							</div>
						</div>
						<div class="form-group row mandatory">
							<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Actual current price' ) ); ?></label>
							<div class="col-lg-8">
								<input class="form-control item-value" type="text" data-pattern="^[0-9]+(\.[0-9]+)?$"
									name="<?= $enc->attr( $this->formparam( array( 'price', 'price.value', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Actual current price' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'priceData/price.value/' . $idx, '0.00' ) ); ?>" />
							</div>
						</div>
						<div class="form-group row optional">
							<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Substracted rebate amount' ) ); ?></label>
							<div class="col-lg-8">
								<input class="form-control item-rebate" type="text" data-pattern="^([0-9]+(\.[0-9]+)?)?$"
									name="<?= $enc->attr( $this->formparam( array( 'price', 'price.rebate', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Substracted rebate amount' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'priceData/price.rebate/' . $idx, '0.00' ) ); ?>" />
							</div>
						</div>
						<div class="form-group row optional">
							<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Shipping costs per item' ) ); ?></label>
							<div class="col-lg-8">
								<input class="form-control item-costs" type="text" data-pattern="^([0-9]+(\.[0-9]+)?)?$"
									name="<?= $enc->attr( $this->formparam( array( 'price', 'price.costs', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Shipping costs per item' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'priceData/price.costs/' . $idx, '0.00' ) ); ?>" />
							</div>
						</div>
					</div>

					<div class="col-xl-6">

						<?php if( count( $this->get( 'priceCurrencies', [] ) ) > 1 ) : ?>
							<div class="form-group row mandatory">
								<label class="col-xl-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Currency' ) ); ?></label>
								<div class="col-xl-8">
									<select class="form-control custom-select item-currencyid" name="<?= $enc->attr( $this->formparam( array( 'price', 'price.currencyid', '' ) ) ); ?>">
										<?php foreach( $this->get( 'priceCurrencies', [] ) as $currencyItem ) : ?>
											<option value="<?= $enc->attr( $currencyItem->getCode() ); ?>" <?= ( $currencyid == $currencyItem->getCode() ? 'selected="selected"' : '' ) ?> >
												<?= $enc->html( $currencyItem->getCode() ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						<?php else : ?>
							<input class="item-currencyid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'price', 'price.currencyid', '' ) ) ); ?>" value="<?= $enc->attr( $currencyid ); ?>" />
						<?php endif; ?>

						<?php if( count( $this->get( 'priceTypes', [] ) ) > 1 ) : ?>
							<div class="form-group row mandatory">
								<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
								<div class="col-lg-8">
									<select class="form-control c-select item-typeid" name="<?= $enc->attr( $this->formparam( array( 'price', 'price.typeid', '' ) ) ); ?>">

										<?php foreach( (array) $this->get( 'priceTypes', [] ) as $typeId => $typeItem ) : ?>
											<option value="<?= $enc->attr( $typeId ); ?>" <?= ( $typeId == $this->get( 'priceData/price.typeid/' . $idx ) ? 'selected="selected"' : '' ) ?> >
												<?= $enc->html( $typeItem->getLabel() ); ?>
											</option>
										<?php endforeach; ?>

									</select>
								</div>
							</div>
						<?php else : ?>
							<?php $priceTypes = $this->get( 'priceTypes', [] ); $priceType = ( ( $item = reset( $priceTypes ) ) !== false ? $item->getId() : null ); ?>
							<input class="item-typeid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'price', 'price.typeid', '' ) ) ); ?>" value="<?= $enc->attr( $priceType ); ?>" />
						<?php endif; ?>

						<div class="form-group row mandatory">
							<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Minimum quantity' ) ); ?></label>
							<div class="col-lg-8">
								<input class="form-control item-quantity" type="number" step="1" min="1" max="2147483647"
									name="<?= $enc->attr( $this->formparam( array( 'price', 'price.quantity', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Minimum quantity' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'priceData/price.quantity/' . $idx, 1 ) ); ?>" />
							</div>
						</div>
					</div>

				</div>

			</div>

		<?php endforeach; ?>

		<div class="group-item card prototype">
			<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'price', 'product.lists.id', '' ) ) ); ?>" disabled="disabled" />

			<div id="item-price-group-item-" class="card-header header collapsed" role="tab"
				data-toggle="collapse" data-target="#item-price-group-data-">
				<div class="card-tools-left">
					<div class="btn btn-card-header act-show fa"></div>
				</div>
				<span class="item-label header-label"></span>
				&nbsp;
				<div class="card-tools-right">
					<div class="btn btn-card-header act-delete fa"></div>
				</div>
			</div>

			<div id="item-price-group-data-" class="card-block collapse row" role="tabpanel">

				<div class="col-xl-6">
					<div class="form-group row mandatory">
						<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Tax rate in %' ) ); ?></label>
						<div class="col-lg-8">
							<input class="form-control item-taxrate" type="text" data-pattern="^[0-9]+(\.[0-9]+)?$" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.taxrate', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Tax rate in %' ) ); ?>" />
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Actual current price' ) ); ?></label>
						<div class="col-lg-8">
							<input class="form-control item-value" type="text" data-pattern="^[0-9]+(\.[0-9]+)?$" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.value', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Actual current price' ) ); ?>" />
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Substracted rebate amount' ) ); ?></label>
						<div class="col-lg-8">
							<input class="form-control item-rebate" type="text" data-pattern="^([0-9]+(\.[0-9]+)?)?$" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.rebate', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Substracted rebate amount' ) ); ?>" />
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Shipping costs per item' ) ); ?></label>
						<div class="col-lg-8">
							<input class="form-control item-costs" type="text" data-pattern="^([0-9]+(\.[0-9]+)?)?$" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.costs', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Shipping costs per item' ) ); ?>" />
						</div>
					</div>
				</div>

				<div class="col-xl-6">

					<?php if( count( $this->get( 'priceCurrencies', [] ) ) > 1 ) : ?>
						<div class="form-group row mandatory">
							<label class="col-xl-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Currency' ) ); ?></label>
							<div class="col-xl-8">
								<select class="form-control custom-select item-currencyid" name="<?= $enc->attr( $this->formparam( array( 'price', 'price.currencyid', '' ) ) ); ?>" disabled="disabled">
									<?php foreach( $this->get( 'priceCurrencies', [] ) as $currencyItem ) : ?>
										<option value="<?= $enc->attr( $currencyItem->getCode() ); ?>" >
											<?= $enc->html( $currencyItem->getCode() ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					<?php else : ?>
						<input class="item-currencyid" type="hidden" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'price', 'price.currencyid', '' ) ) ); ?>"
							value="<?= $enc->attr( $this->get( 'priceCurrencyDefault' ) ); ?>" />
					<?php endif; ?>

					<?php if( count( $this->get( 'priceTypes', [] ) ) > 1 ) : ?>
						<div class="form-group row mandatory">
							<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
							<div class="col-lg-8">
								<select class="form-control c-select item-typeid" name="<?= $enc->attr( $this->formparam( array( 'price', 'price.typeid', '' ) ) ); ?>" disabled="disabled">

									<?php foreach( (array) $this->get( 'priceTypes', [] ) as $typeId => $typeItem ) : ?>
										<option value="<?= $enc->attr( $typeId ); ?>" <?= ( $typeId == $this->get( 'priceData/price.typeid/' . $idx ) ? 'selected="selected"' : '' ) ?> >
											<?= $enc->html( $typeItem->getLabel() ); ?>
										</option>
									<?php endforeach; ?>

								</select>
							</div>
						</div>
					<?php else : ?>
						<?php $priceTypes = $this->get( 'priceTypes', [] ); $priceType = ( ( $item = reset( $priceTypes ) ) !== false ? $item->getId() : null ); ?>
						<input class="item-typeid" type="hidden" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'price', 'price.typeid', '' ) ) ); ?>"
							value="<?= $enc->attr( $priceType ); ?>" />
					<?php endif; ?>

					<div class="form-group row mandatory">
						<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Minimum quantity' ) ); ?></label>
						<div class="col-lg-8">
							<input class="form-control item-quantity" type="number" step="1" min="1" max="2147483647" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'price', 'price.quantity', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Minimum quantity' ) ); ?>"
								value="1" />
						</div>
					</div>
				</div>

			</div>

		</div>

		<div class="card-tools-more">
			<div class="btn btn-card-more act-add fa"></i></div>
		</div>

	</div>

	<?= $this->get( 'priceBody' ); ?>
</div>
