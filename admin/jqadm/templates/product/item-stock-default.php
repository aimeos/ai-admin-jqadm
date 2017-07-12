<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();
$stockTypes = $this->get( 'stockTypes', [] );


?>
<div id="stock" class="item-stock content-block tab-pane fade" role="tabpanel" aria-labelledby="stock">
	<table class="stock-list table table-default">
		<thead>
			<tr>
				<?php if( count( $stockTypes ) > 1 ) : ?>
					<th class="stock-type">
						<span class="help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></span>
						<div class="form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Warehouse or local store if your articles are available at several locations' ) ); ?>
						</div>
					</th>
				<?php endif; ?>
				<th class="stock-stocklevel">
					<span class="help"><?= $enc->html( $this->translate( 'admin', 'Stock level' ) ); ?></span>
					<div class="form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'Number of articles currently in stock, leave empty for an unlimited quantity' ) ); ?>
					</div>
				</th>
				<th class="stock-databack">
					<span class="help"><?= $enc->html( $this->translate( 'admin', 'Back in stock' ) ); ?></span>
					<div class="form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'Shown if the article reached a stock level of zero' ) ); ?>
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

			<?php foreach( $this->get( 'stockData/stock.id', [] ) as $idx => $id ) : ?>
				<tr class="<?= $this->site()->readonly( $this->get( 'stockData/stock.siteid/' . $idx ) ); ?>">
					<?php if( count( $stockTypes ) > 1 ) : ?>
						<td class="stock-type mandatory">
							<select class="form-control custom-select item-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.typeid', '' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'stockData/stock.siteid/' . $idx ) ); ?> >
								<option value="">
									<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
								</option>

								<?php foreach( $this->get( 'stockTypes', [] ) as $typeId => $typeItem ) : ?>
									<?php if( $typeId == $this->get( 'stockData/stock.typeid/' . $idx ) ) : ?>
										<option value="<?= $enc->attr( $typeId ); ?>" selected="selected"><?= $enc->html( $typeItem->getLabel() ) ?></option>
									<?php else : ?>
										<option value="<?= $enc->attr( $typeId ); ?>"><?= $enc->html( $typeItem->getLabel() ) ?></option>
									<?php endif; ?>
								<?php endforeach; ?>

							</select>
						</td>
					<?php else : $stockType = reset( $stockTypes ); ?>
						<input class="item-typeid" type="hidden"
							name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.typeid', '' ) ) ); ?>"
							value="<?= $enc->attr( $stockType ? $stockType->getId() : '' ); ?>" />
					<?php endif; ?>
					<td class="stock-stocklevel optional">
						<input class="form-control item-stocklevel" type="number" step="1" min="0" tabindex="<?= $this->get( 'tabindex' ); ?>"
							name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.stocklevel', '' ) ) ); ?>"
							value="<?= $enc->attr( $this->get( 'stockData/stock.stocklevel/' . $idx ) ); ?>"
							<?= $this->site()->readonly( $this->get( 'stockData/stock.siteid/' . $idx ) ); ?> />
					</td>
					<td class="stock-databack optional">
						<input class="form-control item-dateback date" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
							name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.dateback', '' ) ) ); ?>"
							value="<?= $enc->attr( $this->get( 'stockData/stock.dateback/' . $idx ) ); ?>"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
							data-format="<?= $this->translate( 'admin', 'yy-mm-dd' ); ?>"
							<?= $this->site()->readonly( $this->get( 'stockData/stock.siteid/' . $idx ) ); ?> />
					</td>
					<td class="actions">
						<input class="item-id" type="hidden" value="<?= $enc->attr( $id ); ?>"
							name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.id', '' ) ) ); ?>" />
						<?php if( !$this->site()->readonly( $this->get( 'stockData/stock.siteid/' . $idx ) ) ) : ?>
							<div class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
							</div>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>

			<tr class="prototype">
				<?php if( count( $stockTypes ) > 1 ) : ?>
					<td class="stock-type">
						<select class="form-control custom-select item-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.typeid', '' ) ) ); ?>">
							<option value="">
								<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
							</option>

							<?php foreach( $stockTypes as $typeId => $typeItem ) : ?>
								<option value="<?= $enc->attr( $typeId ); ?>"><?= $enc->html( $typeItem->getLabel() ) ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				<?php else : $stockType = reset( $stockTypes ); ?>
					<input class="item-typeid" type="hidden"
						name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.typeid', '' ) ) ); ?>"
						value="<?= $enc->attr( $stockType ? $stockType->getId() : '' ); ?>" />
				<?php endif; ?>
				<td class="stock-stocklevel optional">
					<input class="form-control item-stocklevel" type="number" step="1" min="0" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
					name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.stocklevel', '' ) ) ); ?>" />
				</td>
				<td class="stock-databack optional">
					<input class="form-control date-prototype item-dateback" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
						name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.dateback', '' ) ) ); ?>"
						placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
						data-format="<?= $this->translate( 'admin', 'yy-mm-dd' ); ?>" />
				</td>
				<td class="actions">
					<input class="item-id" type="hidden" value="" disabled="disabled"
						name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.id', '' ) ) ); ?>" />
					<div class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
					</div>
				</td>
			</tr>
		</tbody>
	</table>

	<?= $this->get( 'stockBody' ); ?>
</div>
