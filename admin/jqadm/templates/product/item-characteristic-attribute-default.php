<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */

$enc = $this->encoder();

?>
<div class="col-xl-6 content-block item-characteristic-attribute">
	<table class="attribute-list table table-default">
		<thead>
			<tr>
				<th><?= $enc->html( $this->translate( 'admin', 'Attributes' ) ); ?></th>
				<th class="actions">
					<div class="btn act-add fa" tabindex="<?= $this->get( "tabindex" ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+A)') ); ?>">
					</div>
				</th>
			</tr>
		</thead>
		<tbody>

			<?php foreach( $this->get( 'attributeData/product.lists.id', [] ) as $idx => $id ) : ?>
				<tr>
					<td>
						<input class="item-listid" type="hidden" value="<?= $enc->attr( $id ); ?>"
							name="<?= $enc->attr( $this->formparam( array( 'characteristic', 'attribute', 'product.lists.id', '' ) ) ); ?>" />
						<input class="item-label" type="hidden" value="<?= $enc->attr( $this->get( 'attributeData/attribute.label/' . $idx ) ); ?>"
							name="<?= $enc->attr( $this->formparam( array( 'characteristic', 'attribute', 'attribute.label', '' ) ) ); ?>" />
						<select class="combobox item-refid" tabindex="<?= $this->get( "tabindex" ); ?>"
							name="<?= $enc->attr( $this->formparam( array( 'characteristic', 'attribute', 'product.lists.refid', '' ) ) ); ?>">
							<option value="<?= $enc->attr( $this->get( 'attributeData/product.lists.refid/' . $idx ) ); ?>" >
								<?= $enc->html( $this->get( 'attributeData/attribute.label/' . $idx ) ); ?>
							</option>
						</select>
					</td>
					<td class="actions">
						<div class="btn act-delete fa"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
						</div>
					</td>
				</tr>
			<?php endforeach; ?>

			<tr class="prototype">
				<td>
					<input class="item-listid" type="hidden" disabled="disabled"
						name="<?= $enc->attr( $this->formparam( array( 'characteristic', 'attribute', 'product.lists.id', '' ) ) ); ?>" />
					<input class="item-label" type="hidden" disabled="disabled"
						name="<?= $enc->attr( $this->formparam( array( 'characteristic', 'attribute', 'attribute.label', '' ) ) ); ?>" />
					<select class="combobox-prototype item-refid" tabindex="<?= $this->get( "tabindex" ); ?>" disabled="disabled"
						name="<?= $enc->attr( $this->formparam( array( 'characteristic', 'attribute', 'product.lists.refid', '' ) ) ); ?>">
					</select>
				</td>
				<td class="actions">
					<div class="btn act-delete fa" tabindex="<?= $this->get( "tabindex" ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
					</div>
				</td>
			</tr>
		</tbody>
	</table>
<?= $this->get( 'attributeBody' ); ?>
</div>
