<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();


?>
<div id="selection" class="item-selection content-block tab-pane fade" role="tabpanel" aria-labelledby="selection">

	<?php foreach( (array) $this->get( 'selectionData', [] ) as $code => $map ) : ?>

		<div class="group-item card">
			<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.lists.id', '' ) ) ); ?>"
				value="<?= $enc->attr( $this->value( $map, 'product.lists.id' ) ); ?>" />

			<div id="product-item-selection-group-item-<?= $enc->attr( $code ); ?>" class="header card-header collapsed" role="tab"
				data-toggle="collapse" data-target="#product-item-selection-group-data-<?= $enc->attr( $code ); ?>"
				aria-expanded="true" aria-controls="product-item-selection-group-data-<?= $enc->attr( $code ); ?>">
				<div class="card-tools-left">
					<div class="btn btn-card-header act-show fa fa-chevron-down"></div>
				</div>
				<span class="item-code header-label">
					<?= $enc->html( $this->value( $map, 'product.id' ) ); ?> - <?= $enc->attr( $this->value( $map, 'product.label' ) ); ?>
				</span>
				&nbsp;
				<div class="card-tools-right">
					<div class="btn btn-card-header act-copy fa"></div>
					<div class="btn btn-card-header act-delete fa"></i></div>
				</div>
			</div>

			<div id="product-item-selection-group-data-<?= $enc->attr( $code ); ?>" class="card-block collapse row">
				<div class="col-lg-6">
					<input class="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.id', '' ) ) ); ?>"
						value="<?= $enc->attr( $this->value( $map, 'product.id' ) ); ?>" />
					<div class="form-group row mandatory">
						<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'SKU' ) ); ?></label>
						<div class="col-lg-8">
							<input class="form-control item-code" type="text" name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.code', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'EAN, SKU or article number (required)' ) ); ?>"
								value="<?= $enc->attr( $code ); ?>">
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Label' ) ); ?></label>
						<div class="col-lg-8">
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
								<th class="actions"><div class="btn act-add fa"></div></th>
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
									<td class="actions"><div class="btn act-delete fa"></div></td>
								</tr>
							<?php endforeach; ?>

							<tr class="prototype">
								<td>
									<input class="item-attr-ref" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'ref', '' ) ) ); ?>" value="" disabled="disabled" />
									<input class="item-attr-label" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'label', '' ) ) ); ?>" value="" disabled="disabled" />
									<select class="combobox-prototype item-attr-id" name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'id', '' ) ) ); ?>" disabled="disabled"></select>
								</td>
								<td class="actions"><div class="btn act-delete fa"></div></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	<?php endforeach; ?>

	<div class="group-item card prototype">

		<div id="item-selection-group-item-" class="header card-header" role="tab"
			data-toggle="collapse" data-target="#item-selection-group-data-">
			<div class="card-tools-left">
				<div class="btn btn-card-header act-show fa"></div>
			</div>
			<span class="item-code header-label"></span>
			&nbsp;
			<div class="card-tools-right">
				<div class="btn btn-card-header act-copy fa"></div>
				<div class="btn btn-card-header act-delete fa"></div>
			</div>
		</div>

		<div id="item-selection-group-data-" class="card-block collapse show row">
			<div class="col-lg-6">
				<div class="form-group row mandatory">
					<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'SKU' ) ); ?></label>
					<div class="col-lg-8">
						<input class="form-control item-code" type="text" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.code', '' ) ) ); ?>"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'EAN, SKU or article number (required)' ) ); ?>">
					</div>
				</div>
				<div class="form-group row mandatory">
					<label class="col-lg-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Label' ) ); ?></label>
					<div class="col-lg-8">
						<input class="form-control item-label" type="text" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.label', '' ) ) ); ?>"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ); ?>">
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<table class="selection-item-attributes table table-default">
					<thead>
						<tr>
							<th><?= $enc->html( $this->translate( 'admin', 'Variant attributes' ) ); ?></th>
							<th class="actions"><div class="btn act-add fa"></div></th>
						</tr>
					</thead>
					<tbody>
						<tr class="prototype">
							<td>
								<input class="item-attr-ref" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'ref', '' ) ) ); ?>" value="" disabled="disabled" />
								<input class="item-attr-label" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'label', '' ) ) ); ?>" value="" disabled="disabled" />
								<select class="combobox-prototype item-attr-id" name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'id', '' ) ) ); ?>" disabled="disabled"></select>
							</td>
							<td class="actions"><div class="btn act-delete fa"></div></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="card-tools-more">
		<div class="btn btn-card-more act-add fa"></div>
	</div>

	<?= $this->get( 'selectionBody' ); ?>
</div>
