<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$enc = $this->encoder();

?>
<div class="col-lg-6 product-item-option-custom">
	<table class="attribute-list table table-default">
		<thead>
			<tr>
				<th><?php echo $enc->html( $this->translate( 'admin', 'Custom' ) ); ?></th>
				<th class="actions"><div class="btn btn-primary fa fa-plus"></div></th>
			</tr>
		</thead>
		<tbody>
<?php foreach( $this->get( 'customData/product.lists.id', [] ) as $idx => $id ) : ?>
			<tr>
				<td>
					<input class="item-listid" type="hidden" name="<?php echo $enc->attr( $this->formparam( array( 'option', 'custom', 'product.lists.id', '' ) ) ); ?>" value="<?php echo $enc->attr( $id ); ?>" />
					<input class="item-label" type="hidden" name="<?php echo $enc->attr( $this->formparam( array( 'option', 'custom', 'attribute.label', '' ) ) ); ?>" value="<?php echo $enc->attr( $this->get( 'customData/attribute.label/' . $idx ) ); ?>" />
					<select class="combobox item-refid" name="<?php echo $enc->attr( $this->formparam( array( 'option', 'custom', 'product.lists.refid', '' ) ) ); ?>">
						<option value="<?php echo $enc->attr( $this->get( 'customData/product.lists.refid/' . $idx ) ); ?>" ><?php echo $enc->html( $this->get( 'customData/attribute.label/' . $idx ) ); ?></option>
					</select>
				</td>
				<td class="actions"><div class="btn btn-danger fa fa-trash"></div></td>
			</tr>
<?php endforeach; ?>
			<tr class="prototype">
				<td>
					<input class="item-listid" type="hidden" name="<?php echo $enc->attr( $this->formparam( array( 'option', 'custom', 'product.lists.id', '' ) ) ); ?>" value="" disabled="disabled" />
					<input class="item-label" type="hidden" name="<?php echo $enc->attr( $this->formparam( array( 'option', 'custom', 'attribute.label', '' ) ) ); ?>" value="" disabled="disabled" />
					<select class="combobox-prototype item-refid" name="<?php echo $enc->attr( $this->formparam( array( 'option', 'custom', 'product.lists.refid', '' ) ) ); ?>" disabled="disabled">
					</select>
				</td>
				<td class="actions"><div class="btn btn-danger fa fa-trash"></div></td>
			</tr>
		</tbody>
	</table>
<?php echo $this->get( 'attributeBody' ); ?>
</div>
