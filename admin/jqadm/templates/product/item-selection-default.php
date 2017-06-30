<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

$target = $this->config( 'admin/jqadm/url/get/target' );
$cntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/get/action', 'get' );
$config = $this->config( 'admin/jqadm/url/get/config', [] );


?>
<div id="selection" class="item-selection content-block tab-pane fade" role="tablist" aria-labelledby="selection">

	<?php foreach( (array) $this->get( 'selectionData', [] ) as $code => $map ) : ?>

		<div class="group-item card <?= $this->site()->readonly( $this->value( $map, 'product.lists.siteid' ) ); ?>">
			<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.lists.id', '' ) ) ); ?>"
				value="<?= $enc->attr( $this->value( $map, 'product.lists.id' ) ); ?>" />

			<div id="product-item-selection-group-item-<?= $enc->attr( $code ); ?>" class="header card-header collapsed" role="tab"
				data-toggle="collapse" data-target="#product-item-selection-group-data-<?= $enc->attr( $code ); ?>"
				aria-expanded="true" aria-controls="product-item-selection-group-data-<?= $enc->attr( $code ); ?>">
				<div class="card-tools-left">
					<div class="btn btn-card-header act-show fa"
						title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry') ); ?>">
					</div>
				</div>
				<span class="item-code header-label">
					<?= $enc->html( $this->value( $map, 'product.id' ) ); ?> - <?= $enc->attr( $this->value( $map, 'product.label' ) ); ?>
				</span>
				&nbsp;
				<div class="card-tools-right">
					<?php if( $this->value( $map, 'product.id' ) ) : ?>
						<a class="btn btn-card-header act-view fa" target="_blank"
							href="<?= $enc->attr( $this->url( $target, $cntl, $action, ['id' => $this->value( $map, 'product.id' )] + $this->get( 'pageParams', [] ), [], $config ) ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'View details') ); ?>"></a>
					<?php endif; ?>
					<div class="btn btn-card-header act-copy fa"
						title="<?= $enc->attr( $this->translate( 'admin', 'Duplicate entry (Ctrl+D)') ); ?>">
					</div>
					<?php if( !$this->site()->readonly( $this->value( $map, 'product.lists.siteid' ) ) ) : ?>
						<div class="btn btn-card-header act-delete fa"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div id="product-item-selection-group-data-<?= $enc->attr( $code ); ?>" class="card-block collapse row">
				<div class="col-lg-6">
					<input class="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.id', '' ) ) ); ?>"
						value="<?= $enc->attr( $this->value( $map, 'product.id' ) ); ?>" />
					<div class="form-group row mandatory">
						<label class="col-lg-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'SKU' ) ); ?></label>
						<div class="col-lg-8">
							<input class="form-control item-code" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.code', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'EAN, SKU or article number (required)' ) ); ?>"
								value="<?= $enc->attr( $code ); ?>"
								<?= $this->site()->readonly( $this->value( $map, 'product.lists.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Unique article code related to stock levels, e.g. from the ERP system, an EAN/GTIN number or self invented' ) ); ?>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-lg-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ); ?></label>
						<div class="col-lg-8">
							<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.label', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ); ?>"
								value="<?= $enc->attr( $this->value( $map, 'product.label' ) ); ?>"
								<?= $this->site()->readonly( $this->value( $map, 'product.lists.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Internal article name, will be used on the web site if no product name for the language is available' ) ); ?>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<table class="selection-item-attributes table table-default">
						<thead>
							<tr>
								<th>
									<span class="help"><?= $enc->html( $this->translate( 'admin', 'Variant attributes' ) ); ?></span>
									<div class="form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'All attributes that uniquely define an article, e.g. width, length and color for jeans' ) ); ?>
									</div>
								</th>
								<th class="actions">
									<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"></div>
								</th>
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
										<select class="combobox item-attr-id" tabindex="<?= $this->get( 'tabindex' ); ?>"
											name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'id', '' ) ) ); ?>"
											<?= $this->site()->readonly( $this->value( $list, 'siteid' ) ); ?> >
											<option value="<?= $enc->attr( $attrid ); ?>" ><?= $enc->html( $this->value( $list, 'label' ) ); ?></option>
										</select>
									</td>
									<td class="actions">
										<?php if( !$this->site()->readonly( $this->value( $list, 'siteid' ) ) ) : ?>
											<div class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
												title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
											</div>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>

							<tr class="prototype">
								<td>
									<input class="item-attr-ref" type="hidden" disabled="disabled"
										name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'ref', '' ) ) ); ?>" />
									<input class="item-attr-label" type="hidden" disabled="disabled"
										name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'label', '' ) ) ); ?>" />
									<select class="combobox-prototype item-attr-id" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
										name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'id', '' ) ) ); ?>">
									</select>
								</td>
								<td class="actions">
									<div class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
									</div>
								</td>
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
				<div class="btn btn-card-header act-show fa"
					title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry') ); ?>">
				</div>
			</div>
			<span class="item-code header-label"></span>
			&nbsp;
			<div class="card-tools-right">
				<div class="btn btn-card-header act-copy fa"
					title="<?= $enc->attr( $this->translate( 'admin', 'Duplicate entry (Ctrl+D)') ); ?>"></div>
				<div class="btn btn-card-header act-delete fa"
					title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"></div>
			</div>
		</div>

		<div id="item-selection-group-data-" class="card-block collapse show row">
			<div class="col-lg-6">
				<div class="form-group row mandatory">
					<label class="col-lg-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'SKU' ) ); ?></label>
					<div class="col-lg-8">
						<input class="form-control item-code" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.code', '' ) ) ); ?>"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'EAN, SKU or article number (required)' ) ); ?>">
					</div>
					<div class="col-sm-12 form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'Unique article code related to stock levels, e.g. from the ERP system, an EAN/GTIN number or self invented' ) ); ?>
					</div>
				</div>
				<div class="form-group row mandatory">
					<label class="col-lg-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ); ?></label>
					<div class="col-lg-8">
						<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'selection', 'product.label', '' ) ) ); ?>"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ); ?>">
					</div>
					<div class="col-sm-12 form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'Internal article name, will be used on the web site if no product name for the language is available' ) ); ?>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<table class="selection-item-attributes table table-default">
					<thead>
						<tr>
							<th>
								<span class="help"><?= $enc->html( $this->translate( 'admin', 'Variant attributes' ) ); ?></span>
								<div class="form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'All attributes that uniquely define an article, e.g. width, length and color for jeans' ) ); ?>
								</div>
							</th>
							<th class="actions">
								<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+A)') ); ?>">
								</div>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr class="prototype">
							<td>
								<input class="item-attr-ref" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'ref', '' ) ) ); ?>" value="" disabled="disabled" />
								<input class="item-attr-label" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'label', '' ) ) ); ?>" value="" disabled="disabled" />
								<select class="combobox-prototype item-attr-id" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
									name="<?= $enc->attr( $this->formparam( array( 'selection', 'attr', 'id', '' ) ) ); ?>">
								</select>
							</td>
							<td class="actions">
								<div class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="card-tools-more">
		<div class="btn btn-primary btn-card-more act-add fa"
			title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+A)') ); ?>">
		</div>
	</div>

	<?= $this->get( 'selectionBody' ); ?>
</div>
