<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$enc = $this->encoder();

?>
<div id="image" class="item-image tab-pane fade" role="tabpanel" aria-labelledby="image">
	<table class="image-list table table-default content-block">
		<thead>
			<tr>
				<th class="image-preview">
					<?= $enc->html( $this->translate( 'admin', 'Preview' ) ); ?>
				</th>
				<th class="image-language">
					<span class="help"><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></span>
					<div class="form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'Images will only be shown for that language, useful if the image contains text or is language sepecific' ) ); ?>
					</div>
				</th>
				<th class="image-label">
					<span class="help"><?= $enc->html( $this->translate( 'admin', 'Title' ) ); ?></span>
					<div class="form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'The image title is used for the title tag of the image on the web site' ) ); ?>
					</div>
				</th>
				<th class="actions">
					<div class="btn fa fa-plus"
						title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+A)') ); ?>">
						<input class="fileupload act-add" type="file" name="image[files][]" tabindex="<?= $this->get( 'tabindex' ); ?>" multiple />
					</div>
				</th>
			</tr>
		</thead>
		<tbody>

			<?php foreach( $this->get( 'imageData/attribute.lists.id', [] ) as $idx => $id ) : ?>
				<tr class="<?= $this->site()->readonly( $this->get( 'imageData/attribute.lists.siteid/' . $idx ) ); ?>">
					<td class="image-preview">
						<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'image', 'attribute.lists.id', '' ) ) ); ?>" value="<?= $enc->attr( $id ); ?>" />
						<input class="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'image', 'media.id', '' ) ) ); ?>" value="<?= $enc->attr( $this->get( 'imageData/media.id/' . $idx ) ); ?>" />
						<input class="item-preview" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'image', 'media.preview', '' ) ) ); ?>" value="<?= $enc->attr( $this->get( 'imageData/media.preview/' . $idx ) ); ?>" />
						<img class="item-preview" src="<?= $this->content( $this->get( 'imageData/media.preview/' . $idx ) ); ?>" alt="<?= $enc->html( $this->translate( 'admin', 'Preview' ) ); ?>" />
					</td>
					<td class="image-language optional">

						<select class="custom-select item-languageid" tabindex="<?= $this->get( 'tabindex' ); ?>"
							name="<?= $enc->attr( $this->formparam( array( 'image', 'media.languageid', '' ) ) ); ?>"
							<?= $this->site()->readonly( $this->get( 'imageData/attribute.lists.siteid/' . $idx ) ); ?> >
							<?php $lang = $this->get( 'imageData/media.languageid/' . $idx ); ?>

							<option value="" <?= ( $lang == '' ? 'selected="selected"' : '' ) ?> >
								<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>
							</option>

							<?php foreach( $this->get( 'pageLanguages', [] ) as $langId => $langItem ) : ?>
								<option value="<?= $enc->attr( $langId ); ?>" <?= ( $lang == $langId ? 'selected="selected"' : '' ) ?> >
									<?= $enc->html( $langId ); ?>
								</option>
							<?php endforeach; ?>
						</select>

					</td>
					<td class="image-label mandatory">
						<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
							name="<?= $enc->attr( $this->formparam( array( 'image', 'media.label', '' ) ) ); ?>"
							value="<?= $enc->attr( $this->get( 'imageData/media.label/' . $idx ) ); ?>"
							<?= $this->site()->readonly( $this->get( 'imageData/attribute.lists.siteid/' . $idx ) ); ?> />
					</td>
					<td class="actions">
						<?php if( !$this->site()->readonly( $this->get( 'imageData/attribute.lists.siteid/' . $idx ) ) ) : ?>
							<div class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
							</div>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>

			<tr class="prototype">
				<td class="image-preview"></td>
				<td class="image-language optional">
					<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'image', 'attribute.lists.id', '' ) ) ); ?>" value="" disabled="disabled" />
					<select class="custom-select item-languageid" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
						name="<?= $enc->attr( $this->formparam( array( 'image', 'media.languageid', '' ) ) ); ?>">
						<option value=""><?= $enc->html( $this->translate( 'admin', 'All' ) ); ?></option>

						<?php foreach( $this->get( 'pageLanguages', [] ) as $langId => $langItem ) : ?>
							<option value="<?= $enc->attr( $langId ); ?>"><?= $enc->html( $langId ); ?></option>
						<?php endforeach; ?>

					</select>
				</td>
				<td class="image-label mandatory">
					<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
						name="<?= $enc->attr( $this->formparam( array( 'image', 'media.label', '' ) ) ); ?>" />
				</td>
				<td class="actions">
					<div class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
					</div>
				</td>
			</tr>
		</tbody>
	</table>

	<?= $this->get( 'imageBody' ); ?>
</div>
