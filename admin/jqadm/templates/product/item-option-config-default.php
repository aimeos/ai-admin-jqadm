<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$enc = $this->encoder();

?>
<div class="col-xl-6 item-option-config">
	<table class="attribute-list table table-default">
		<thead>
			<tr>
				<th><?= $enc->html( $this->translate( 'admin', 'Configurable' ) ); ?></th>
				<th class="actions"><div class="btn act-add fa"></div></th>
			</tr>
		</thead>
		<tbody>

			<?php foreach( $this->get( 'configData/product.lists.id', [] ) as $idx => $id ) : ?>
				<tr>
					<td>
						<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'option', 'config', 'product.lists.id', '' ) ) ); ?>" value="<?= $enc->attr( $id ); ?>" />
						<input class="item-label" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'option', 'config', 'attribute.label', '' ) ) ); ?>" value="<?= $enc->attr( $this->get( 'configData/attribute.label/' . $idx ) ); ?>" />
						<select class="combobox item-refid" name="<?= $enc->attr( $this->formparam( array( 'option', 'config', 'product.lists.refid', '' ) ) ); ?>">
							<option value="<?= $enc->attr( $this->get( 'configData/product.lists.refid/' . $idx ) ); ?>" ><?= $enc->html( $this->get( 'configData/attribute.label/' . $idx ) ); ?></option>
						</select>
					</td>
					<td class="actions"><div class="btn act-delete fa"></div></td>
				</tr>
			<?php endforeach; ?>

			<tr class="prototype">
				<td>
					<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'option', 'config', 'product.lists.id', '' ) ) ); ?>" value="" disabled="disabled" />
					<input class="item-label" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'option', 'config', 'attribute.label', '' ) ) ); ?>" value="" disabled="disabled" />
					<select class="combobox-prototype item-refid" name="<?= $enc->attr( $this->formparam( array( 'option', 'config', 'product.lists.refid', '' ) ) ); ?>" disabled="disabled">
					</select>
				</td>
				<td class="actions"><div class="btn act-delete fa"></div></td>
			</tr>
		</tbody>
	</table>
<?= $this->get( 'attributeBody' ); ?>
</div>
