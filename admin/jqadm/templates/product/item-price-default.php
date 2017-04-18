<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();


?>
<div class="product-item-price card panel">
	<div id="product-item-price" class="header card-header collapsed" role="tab"
		data-toggle="collapse" data-parent="#accordion" data-target="#product-item-price-data"
		aria-expanded="false" aria-controls="product-item-price-data">
		<?= $enc->html( $this->translate( 'admin', 'Prices' ) ); ?>
	</div>
	<div id="product-item-price-data" class="item-price card-block panel-collapse collapse" role="tabpanel" aria-labelledby="product-item-price">
		<div id="product-item-price-group" role="tablist" aria-multiselectable="true">

			<?php foreach( (array) $this->get( 'priceData/price.currencyid', [] ) as $idx => $currencyid ) : ?>

				<div class="group-item card panel">
					<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'price', 'product.lists.id', '' ) ) ); ?>"
						value="<?= $enc->attr( $this->get( 'priceData/product.lists.id/' . $idx ) ); ?>" />
					<div id="product-item-price-group-item-<?= $enc->attr( $idx ); ?>" class="card-header header collapsed" role="tab"
						data-toggle="collapse" data-target="#product-item-price-group-data-<?= $enc->attr( $idx ); ?>"
						aria-expanded="false" aria-controls="product-item-price-group-data-<?= $enc->attr( $idx ); ?>">
						<select class="combobox item-currencyid" name="<?= $enc->attr( $this->formparam( array( 'price', 'price.currencyid', '' ) ) ); ?>">
							<option value="<?= $enc->attr( $currencyid ); ?>"><?= $enc->html( $currencyid ); ?></option>
						</select>
						<div class="btn btn-secondary fa fa-files-o"></div>
						<div class="btn btn-danger fa fa-trash"></div>
						<span class="item-label header-label"><?= $enc->html( $this->get( 'priceData/price.label/' . $idx ) ); ?></span>
					</div>
					<div id="product-item-price-group-data-<?= $enc->attr( $idx ); ?>" class="card-block panel-collapse collapse"
						role="tabpanel" aria-labelledby="product-item-price-group-item-<?= $enc->attr( $idx ); ?>">
						<div class="col-lg-6">
							<div class="form-group row mandatory">
								<label class="col-lg-3 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
								<div class="col-lg-9">
									<select class="form-control c-select item-typeid" name="<?= $enc->attr( $this->formparam( array( 'price', 'price.typeid', '' ) ) ); ?>">

										<?php foreach( (array) $this->get( 'priceTypes', [] ) as $typeId => $typeItem ) : ?>
											<?php if( $typeId == $this->get( 'priceData/price.typeid/' . $idx ) ) : ?>
												<option value="<?= $enc->attr( $typeId ); ?>" selected="selected"><?= $enc->html( $typeItem->getLabel() ); ?></option>
											<?php else : ?>
												<option value="<?= $enc->attr( $typeId ); ?>"><?= $enc->html( $typeItem->getLabel() ); ?></option>
											<?php endif; ?>
										<?php endforeach; ?>

									</select>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-lg-3 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Label' ) ); ?></label>
								<div class="col-lg-9">
								<input class="form-control item-label" type="text" name="<?= $enc->attr( $this->formparam( array( 'price', 'price.label', '' ) ) ); ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Label' ) ); ?>"
										value="<?= $enc->attr( $this->get( 'priceData/price.label/' . $idx ) ); ?>" />
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-lg-3 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Minimum quantity' ) ); ?></label>
								<div class="col-lg-9">
									<input class="form-control item-quantity" type="number" step="1" min="1" max="2147483647"
										name="<?= $enc->attr( $this->formparam( array( 'price', 'price.quantity', '' ) ) ); ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Minimum quantity' ) ); ?>"
										value="<?= $enc->attr( $this->get( 'priceData/price.quantity/' . $idx, 1 ) ); ?>" />
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group row mandatory">
								<label class="col-lg-3 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Tax rate in %' ) ); ?></label>
								<div class="col-lg-9">
									<input class="form-control item-taxrate" type="text" data-pattern="^[0-9]+(\.[0-9]+)?$"
										name="<?= $enc->attr( $this->formparam( array( 'price', 'price.taxrate', '' ) ) ); ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Tax rate in %' ) ); ?>"
										value="<?= $enc->attr( $this->get( 'priceData/price.taxrate/' . $idx, 0 ) ); ?>" />
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-lg-3 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Actual current price' ) ); ?></label>
								<div class="col-lg-9">
									<input class="form-control item-value" type="text" data-pattern="^[0-9]+(\.[0-9]+)?$"
										name="<?= $enc->attr( $this->formparam( array( 'price', 'price.value', '' ) ) ); ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Actual current price' ) ); ?>"
										value="<?= $enc->attr( $this->get( 'priceData/price.value/' . $idx, '0.00' ) ); ?>" />
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-lg-3 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Substracted rebate amount' ) ); ?></label>
								<div class="col-lg-9">
									<input class="form-control item-rebate" type="text" data-pattern="^([0-9]+(\.[0-9]+)?)?$"
										name="<?= $enc->attr( $this->formparam( array( 'price', 'price.rebate', '' ) ) ); ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Substracted rebate amount' ) ); ?>"
										value="<?= $enc->attr( $this->get( 'priceData/price.rebate/' . $idx, '0.00' ) ); ?>" />
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-lg-3 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Costs per item' ) ); ?></label>
								<div class="col-lg-9">
									<input class="form-control item-costs" type="text" data-pattern="^([0-9]+(\.[0-9]+)?)?$"
										name="<?= $enc->attr( $this->formparam( array( 'price', 'price.costs', '' ) ) ); ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Costs per item' ) ); ?>"
										value="<?= $enc->attr( $this->get( 'priceData/price.costs/' . $idx, '0.00' ) ); ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>

	<?php endforeach; ?>

		</div>

		<?= $this->get( 'priceBody' ); ?>
	</div>
</div>
