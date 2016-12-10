<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

?>
<div class="product-item-stock card panel">
	<div id="product-item-stock" class="header card-header" role="tab"
		data-toggle="collapse" data-parent="#accordion" data-target="#product-item-stock-data"
		aria-expanded="true" aria-controls="product-item-stock-data">
		<?php echo $enc->html( $this->translate( 'admin', 'Stock level' ) ); ?>
	</div>
	<div id="product-item-stock-data" class="item-stock card-block panel-collapse collapse table-responsive" role="tabpanel" aria-labelledby="product-item-stock">
		<table class="stock-list table table-default">
			<thead>
				<tr>
			  		<th class="stock-type"><?php echo $enc->html( $this->translate( 'admin', 'Type' ) ); ?></th>
			  		<th class="stock-stocklevel"><?php echo $enc->html( $this->translate( 'admin', 'Stock level' ) ); ?></th>
			  		<th class="stock-databack"><?php echo $enc->html( $this->translate( 'admin', 'Back in stock' ) ); ?></th>
					<th class="actions"><div class="btn btn-primary fa fa-plus"></div></th>
				</tr>
			</thead>
			<tbody>
<?php foreach( $this->get( 'stockData/stock.id', array() ) as $idx => $id ) : ?>
				<tr>
			  		<td class="stock-type">
						<input class="item-id" type="hidden" name="<?php echo $enc->attr( $this->formparam( array( 'stock', 'stock.id', '' ) ) ); ?>" value="<?php echo $enc->attr( $id ); ?>" />
						<select class="form-control c-select item-typeid" name="<?php echo $enc->attr( $this->formparam( array( 'stock', 'stock.typeid', '' ) ) ); ?>">
<?php	foreach( $this->get( 'stockTypes', array() ) as $typeId => $typeItem ) : ?>
<?php		if( $typeId == $this->get( 'stockData/stock.typeid/' . $idx ) ) : ?>
							<option value="<?php echo $enc->attr( $typeId ); ?>" selected="selected"><?php echo $enc->html( $typeItem->getLabel() ) ?></option>
<?php		else : ?>
							<option value="<?php echo $enc->attr( $typeId ); ?>"><?php echo $enc->html( $typeItem->getLabel() ) ?></option>
<?php		endif; ?>
<?php	endforeach; ?>
						</select>
					</td>
					<td class="stock-stocklevel">
						<input class="form-control item-stocklevel" type="text" name="<?php echo $enc->attr( $this->formparam( array( 'stock', 'stock.stocklevel', '' ) ) ); ?>"
							value="<?php echo $enc->attr( $this->get( 'stockData/stock.stocklevel/' . $idx ) ); ?>" />
					</td>
					<td class="stock-databack">
						<input class="form-control item-dateback date" type="text" name="<?php echo $enc->attr( $this->formparam( array( 'stock', 'stock.dateback', '' ) ) ); ?>"
							value="<?php echo $enc->attr( $this->get( 'stockData/stock.dateback/' . $idx ) ); ?>"
							placeholder="<?php echo $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
							data-format="<?php echo $this->translate( 'admin', 'yy-mm-dd' ); ?>" />
					</td>
					<td class="actions"><div class="btn btn-danger fa fa-trash"></div></td>
				</tr>
<?php endforeach; ?>
				<tr class="prototype">
			  		<td class="stock-type">
						<input class="item-id" type="hidden" name="<?php echo $enc->attr( $this->formparam( array( 'stock', 'stock.id', '' ) ) ); ?>" value="" disabled="disabled" />
						<select class="form-control c-select item-typeid" name="<?php echo $enc->attr( $this->formparam( array( 'stock', 'stock.typeid', '' ) ) ); ?>" disabled="disabled">
<?php foreach( $this->get( 'stockTypes', array() ) as $typeId => $typeItem ) : ?>
							<option value="<?php echo $enc->attr( $typeId ); ?>"><?php echo $enc->html( $typeItem->getLabel() ) ?></option>
<?php endforeach; ?>
						</select>
					</td>
					<td class="stock-stocklevel">
						<input class="form-control item-stocklevel" type="text" name="<?php echo $enc->attr( $this->formparam( array( 'stock', 'stock.stocklevel', '' ) ) ); ?>" disabled="disabled" />
					</td>
					<td class="stock-databack">
						<input class="form-control date-prototype item-dateback" type="text" name="<?php echo $enc->attr( $this->formparam( array( 'stock', 'stock.dateback', '' ) ) ); ?>" disabled="disabled"
							placeholder="<?php echo $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
							data-format="<?php echo $this->translate( 'admin', 'yy-mm-dd' ); ?>" />
					</td>
					<td class="actions"><div class="btn btn-danger fa fa-trash"></div></td>
				</tr>
			</tbody>
		</table>
<?php echo $this->get( 'stockBody' ); ?>
	</div>
</div>
