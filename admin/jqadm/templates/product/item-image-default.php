<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

?>
<div id="image" class="item-image tab-pane fade" role="tabpanel" aria-labelledby="image">
	<table class="image-list table table-default">
		<thead>
			<tr>
				<th class="image-preview"><?= $enc->html( $this->translate( 'admin', 'Preview' ) ); ?></th>
				<th class="image-language"><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></th>
				<th class="image-label"><?= $enc->html( $this->translate( 'admin', 'Title' ) ); ?></th>
				<th class="actions"><div class="btn act-add fa"><input class="fileupload" type="file" name="image[files][]" multiple /></div></th>
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

						<select class="custom-select item-languageid" name="<?= $enc->attr( $this->formparam( array( 'image', 'media.languageid', '' ) ) ); ?>">
							<?php $lang = $this->get( 'imageData/media.languageid/' . $idx ); ?>

							<option value="" <?= ( $lang == '' ? 'selected="selected"' : '' ) ?> >
								<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>
							</option>

							<?php foreach( $this->get( 'itemLanguages', [] ) as $langId => $langItem ) : ?>
								<option value="<?= $enc->attr( $langId ); ?>" <?= ( $lang == $langId ? 'selected="selected"' : '' ) ?> >
									<?= $enc->html( $langId ); ?>
								</option>
							<?php endforeach; ?>
						</select>

					</td>
					<td class="image-label">
						<input class="form-control item-label" type="text" name="<?= $enc->attr( $this->formparam( array( 'image', 'media.label', '' ) ) ); ?>" required="required" value="<?= $enc->attr( $this->get( 'imageData/media.label/' . $idx ) ); ?>" />
					</td>
					<td class="actions"><div class="btn act-delete fa"></div></td>
				</tr>
			<?php endforeach; ?>

			<tr class="prototype">
				<td class="image-preview"></td>
				<td class="image-language">
					<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'image', 'product.lists.id', '' ) ) ); ?>" value="" disabled="disabled" />
					<select class="custom-select item-languageid" name="<?= $enc->attr( $this->formparam( array( 'image', 'media.languageid', '' ) ) ); ?>" disabled="disabled">
						<option value="" selected="selected"><?= $enc->html( $this->translate( 'admin', 'All' ) ); ?></option>
						<?php foreach( $this->get( 'itemLanguages', [] ) as $langId => $langItem ) : ?>
							<option value="<?= $enc->attr( $langId ); ?>"><?= $enc->html( $langId ); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td class="image-label"><input class="form-control item-label" type="text" name="<?= $enc->attr( $this->formparam( array( 'image', 'media.label', '' ) ) ); ?>" value="" disabled="disabled" /></td>
				<td class="actions"><div class="btn act-delete fa"></div></td>
			</tr>
		</tbody>
	</table>

	<?= $this->get( 'imageBody' ); ?>
</div>
