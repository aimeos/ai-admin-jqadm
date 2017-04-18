<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

?>
<div class="product-item-image card panel">
	<div id="product-item-image" class="header card-header" role="tab" data-toggle="collapse" data-parent="#accordion" data-target="#product-item-image-data" aria-expanded="true" aria-controls="product-item-image-data">
		<?= $enc->html( $this->translate( 'admin', 'Images' ) ); ?>
	</div>
	<div id="product-item-image-data" class="item-image card-block panel-collapse collapse table-responsive" role="tabpanel" aria-labelledby="product-item-image">
	<table class="image-list table table-default">
			<thead>
				<tr>
					<th class="image-preview"><?= $enc->html( $this->translate( 'admin', 'Preview' ) ); ?></th>
					<th class="image-language"><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></th>
					<th class="image-label"><?= $enc->html( $this->translate( 'admin', 'Title' ) ); ?></th>
					<th class="actions"><div class="btn btn-primary fa fa-plus"><input class="fileupload" type="file" name="image[files][]" multiple /></div></th>
				</tr>
			</thead>
			<tbody>

				<?php foreach( $this->get( 'imageData/product.lists.id', [] ) as $idx => $id ) : ?>
					<tr>
						<td class="image-preview">
							<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'image', 'product.lists.id', '' ) ) ); ?>" value="<?= $enc->attr( $id ); ?>" />
							<input class="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'image', 'media.id', '' ) ) ); ?>" value="<?= $enc->attr( $this->get( 'imageData/media.id/' . $idx ) ); ?>" />
							<input class="item-preview" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'image', 'media.preview', '' ) ) ); ?>" value="<?= $enc->attr( $this->get( 'imageData/media.preview/' . $idx ) ); ?>" />
							<img class="item-preview" src="<?= $this->content( $this->get( 'imageData/media.preview/' . $idx ) ); ?>" alt="<?= $enc->html( $this->translate( 'admin', 'Preview' ) ); ?>" />
						</td>
						<td class="image-language">
							<select class="combobox item-languageid" name="<?= $enc->attr( $this->formparam( array( 'image', 'media.languageid', '' ) ) ); ?>">
								<option value="<?= $enc->attr( $this->get( 'imageData/media.languageid/' . $idx ) ); ?>" selected="selected"><?= $enc->html( $this->get( 'imageData/media.languageid/' . $idx, $this->translate( 'admin', 'All' ) ) ) ?></option>
							</select>
						</td>
						<td class="image-label">
							<input class="form-control item-label" type="text" name="<?= $enc->attr( $this->formparam( array( 'image', 'media.label', '' ) ) ); ?>" required="required" value="<?= $enc->attr( $this->get( 'imageData/media.label/' . $idx ) ); ?>" />
						</td>
						<td class="actions"><div class="btn btn-danger fa fa-trash"></div></td>
					</tr>
				<?php endforeach; ?>

				<tr class="prototype">
			  		<td class="image-preview"></td>
			  		<td class="image-language">
						<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'image', 'product.lists.id', '' ) ) ); ?>" value="" disabled="disabled" />
						<select class="combobox-prototype item-languageid" name="<?= $enc->attr( $this->formparam( array( 'image', 'media.languageid', '' ) ) ); ?>" disabled="disabled">
							<option value="" selected="selected"><?= $enc->html( $this->translate( 'admin', 'All' ) ); ?></option>
						</select>
					</td>
					<td class="image-label"><input class="form-control item-label" type="text" name="<?= $enc->attr( $this->formparam( array( 'image', 'media.label', '' ) ) ); ?>" value="" disabled="disabled" /></td>
					<td class="actions"><div class="btn btn-danger fa fa-trash"></div></td>
				</tr>
			</tbody>
		</table>

		<?= $this->get( 'imageBody' ); ?>
	</div>
</div>
