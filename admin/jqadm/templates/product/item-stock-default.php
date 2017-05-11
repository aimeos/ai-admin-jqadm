<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

?>
<div id="stock" class="item-stock content-block tab-pane fade" role="tabpanel" aria-labelledby="stock">
	<table class="stock-list table table-default">
		<thead>
			<tr>
				<th class="stock-type"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></th>
				<th class="stock-stocklevel"><?= $enc->html( $this->translate( 'admin', 'Stock level' ) ); ?></th>
				<th class="stock-databack"><?= $enc->html( $this->translate( 'admin', 'Back in stock' ) ); ?></th>
				<th class="actions"><div class="btn act-add fa"></div></th>
			</tr>
		</thead>
		<tbody>

			<?php foreach( $this->get( 'stockData/stock.id', [] ) as $idx => $id ) : ?>
				<tr>
					<td class="stock-type">
						<input class="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.id', '' ) ) ); ?>" value="<?= $enc->attr( $id ); ?>" />
						<select class="form-control c-select item-typeid" name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.typeid', '' ) ) ); ?>">

							<?php foreach( $this->get( 'stockTypes', [] ) as $typeId => $typeItem ) : ?>
								<?php if( $typeId == $this->get( 'stockData/stock.typeid/' . $idx ) ) : ?>
									<option value="<?= $enc->attr( $typeId ); ?>" selected="selected"><?= $enc->html( $typeItem->getLabel() ) ?></option>
								<?php else : ?>
									<option value="<?= $enc->attr( $typeId ); ?>"><?= $enc->html( $typeItem->getLabel() ) ?></option>
								<?php endif; ?>
							<?php endforeach; ?>

						</select>
					</td>
					<td class="stock-stocklevel">
						<input class="form-control item-stocklevel" type="text" name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.stocklevel', '' ) ) ); ?>"
							value="<?= $enc->attr( $this->get( 'stockData/stock.stocklevel/' . $idx ) ); ?>" />
					</td>
					<td class="stock-databack">
						<input class="form-control item-dateback date" type="text" name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.dateback', '' ) ) ); ?>"
							value="<?= $enc->attr( $this->get( 'stockData/stock.dateback/' . $idx ) ); ?>"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
							data-format="<?= $this->translate( 'admin', 'yy-mm-dd' ); ?>" />
					</td>
					<td class="actions"><div class="btn act-delete fa"></div></td>
				</tr>
			<?php endforeach; ?>

			<tr class="prototype">
				<td class="stock-type">
					<input class="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.id', '' ) ) ); ?>" value="" disabled="disabled" />
					<select class="form-control c-select item-typeid" name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.typeid', '' ) ) ); ?>" disabled="disabled">
						<?php foreach( $this->get( 'stockTypes', [] ) as $typeId => $typeItem ) : ?>
							<option value="<?= $enc->attr( $typeId ); ?>"><?= $enc->html( $typeItem->getLabel() ) ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td class="stock-stocklevel">
					<input class="form-control item-stocklevel" type="text" name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.stocklevel', '' ) ) ); ?>" disabled="disabled" />
				</td>
				<td class="stock-databack">
					<input class="form-control date-prototype item-dateback" type="text" name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.dateback', '' ) ) ); ?>" disabled="disabled"
						placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
						data-format="<?= $this->translate( 'admin', 'yy-mm-dd' ); ?>" />
				</td>
				<td class="actions"><div class="btn act-delete fa"></div></td>
			</tr>
		</tbody>
	</table>

	<?= $this->get( 'stockBody' ); ?>
</div>
