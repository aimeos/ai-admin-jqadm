<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

?>
<div class="product-item-selection card panel">
	<div id="product-item-selection-head" class="header card-header collapsed" role="tab"
		data-toggle="collapse" data-parent="#accordion" data-target="#product-item-selection-data"
		aria-expanded="true" aria-controls="product-item-selection-data">
		<?= $enc->html( $this->translate( 'admin', 'Variants' ) ); ?>
	</div>
	<div id="product-item-selection-data" class="item-selection card-block panel-collapse collapse"
		role="tabpanel" aria-labelledby="product-item-selection-head">
		<div id="product-item-selection-group" role="tablist" aria-multiselectable="true">

	<?php foreach( (array) $this->get( 'selectionData', [] ) as $code => $map ) : ?>

				<div class="group-item card">
					<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.lists.id', '' ) ) ); ?>"
						value="<?= $enc->attr( $this->value( $map, 'product.lists.id' ) ); ?>" />
					<div id="product-item-selection-group-item-<?= $enc->attr( $code ); ?>" class="header card-header collapsed" role="tab"
						data-toggle="collapse" data-target="#product-item-selection-group-data-<?= $enc->attr( $code ); ?>"
						aria-expanded="true" aria-controls="product-item-selection-group-data-<?= $enc->attr( $code ); ?>">
						<div class="btn btn-secondary fa fa-files-o"></div>
						<div class="btn btn-danger fa fa-trash"></div>
						<span class="item-code header-label"><?= $enc->html( $code ); ?></span>
					</div>
					<div id="product-item-selection-group-data-<?= $enc->attr( $code ); ?>" class="card-block panel-collapse collapse">
						<div class="col-lg-6">
							<div class="form-group row">
								<label class="col-sm-3 form-control-label"><?= $enc->html( $this->translate( 'admin', 'ID' ) ); ?></label>
								<div class="col-sm-9">
									<input class="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.id', '' ) ) ); ?>"
										value="<?= $enc->attr( $this->value( $map, 'product.id' ) ); ?>" />
									<p class="form-control-static group-item-id"><?= $enc->html( $this->value( $map, 'product.id' ) ); ?></p>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-3 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Code' ) ); ?></label>
								<div class="col-sm-9">
									<input class="form-control item-code" type="text" name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.code', '' ) ) ); ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'EAN, SKU or article number (required)' ) ); ?>"
										value="<?= $enc->attr( $code ); ?>">
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-3 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Label' ) ); ?></label>
								<div class="col-sm-9">
									<input class="form-control item-label" type="text" name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.label', '' ) ) ); ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ); ?>"
										value="<?= $enc->attr( $this->value( $map, 'product.label' ) ); ?>">
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<table class="selection-item-attributes table table-default">
								<thead>
									<tr>
										<th><?= $enc->html( $this->translate( 'admin', 'Variant attributes' ) ); ?></th>
										<th class="actions"><div class="btn btn-primary fa fa-plus"></div></th>
									</tr>
								</thead>
								<tbody>

									<?php foreach( (array) $this->value( $map, 'attr', [] ) as $attrid => $list ) : ?>
										<tr>
											<td>
												<input class="item-attr-ref" type="hidden" value="<?= $enc->attr( $code ); ?>"
													name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'ref', '' ) ) ); ?>" />
												<input class="item-attr-label" type="hidden" value="<?= $enc->attr( $this->value( $list, 'label' ) ); ?>"
													name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'label', '' ) ) ); ?>" />
												<select class="combobox item-attr-id" name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'id', '' ) ) ); ?>">
													<option value="<?= $enc->attr( $attrid ); ?>" ><?= $enc->html( $this->value( $list, 'label' ) ); ?></option>
												</select>
											</td>
											<td class="actions"><div class="btn btn-danger fa fa-trash"></div></td>
										</tr>
									<?php endforeach; ?>

									<tr class="prototype">
										<td>
											<input class="item-attr-ref" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'ref', '' ) ) ); ?>" value="" disabled="disabled" />
											<input class="item-attr-label" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'label', '' ) ) ); ?>" value="" disabled="disabled" />
											<select class="combobox-prototype item-attr-id" name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'id', '' ) ) ); ?>" disabled="disabled"></select>
										</td>
										<td class="actions"><div class="btn btn-danger fa fa-trash"></div></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

			<?php endforeach; ?>

		</div>

		<?= $this->get( 'selectionBody' ); ?>
	</div>
</div>
