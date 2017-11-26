<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$enc = $this->encoder();

?>
<div id="image" class="item-image tab-pane fade" role="tabpanel" aria-labelledby="image">
	<div id="item-image-group" role="tablist" aria-multiselectable="true">

		<?php foreach( $this->get( 'imageData/catalog.lists.id', [] ) as $idx => $id ) : ?>

			<div class="group-item card <?= $this->site()->readonly( $this->get( 'imageData/catalog.lists.siteid/' . $idx ) ); ?>">
				<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'image', 'catalog.lists.id', '' ) ) ); ?>" value="<?= $enc->attr( $id ); ?>" />

				<div id="item-image-group-item-<?= $enc->attr( $idx ); ?>" class="card-header header  <?= ( $idx !== 0 ? 'collapsed' : '' ); ?>" role="tab"
					data-toggle="collapse" data-target="#item-image-group-data-<?= $enc->attr( $idx ); ?>"
					aria-expanded="false" aria-controls="item-image-group-data-<?= $enc->attr( $idx ); ?>">
					<div class="card-tools-left">
						<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry') ); ?>">
						</div>
					</div>
					<span class="item-label header-label">
						<?php if( ( $lang = $this->get( 'imageData/media.languageid/' . $idx ) ) != '' ) : ?>
							<?= $enc->html( $lang ); ?>:
						<?php endif; ?>
						<?= $enc->html( $this->get( 'imageData/media.label/' . $idx ) ); ?>
					</span>
					&nbsp;
					<div class="card-tools-right">
						<?php if( !$this->site()->readonly( $this->get( 'imageData/catalog.lists.siteid/' . $idx ) ) ) : ?>
							<div class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div id="item-image-group-data-<?= $enc->attr( $idx ); ?>" class="card-block collapse row <?= ( $idx === 0 ? 'show' : '' ); ?>"
					role="tabpanel" aria-labelledby="item-image-group-item-<?= $enc->attr( $idx ); ?>">

					<div class="col-xl-6">
						<div class="form-group row image-preview">
							<input class="item-refid" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'image', 'catalog.lists.refid', '' ) ) ); ?>"
								value="<?= $enc->attr( $this->get( 'imageData/catalog.lists.refid/' . $idx ) ); ?>" />
							<input class="item-preview" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'image', 'media.preview', '' ) ) ); ?>"
								value="<?= $enc->attr( $this->get( 'imageData/media.preview/' . $idx ) ); ?>" />
							<input class="fileupload" type="file" name="image[files][]" tabindex="<?= $this->get( 'tabindex' ); ?>" />
							<img class="item-preview" src="<?= $this->content( $this->get( 'imageData/media.preview/' . $idx ) ); ?>"
								alt="<?= $enc->html( $this->translate( 'admin', 'Preview' ) ); ?>" />
						</div>
					</div>

					<div class="col-xl-6">
						<div class="form-group row optional">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></label>
							<div class="col-sm-8">
								<select class="form-control custom-select item-languageid" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'image', 'media.languageid', '' ) ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'imageData/catalog.lists.siteid/' . $idx ) ); ?> >
									<?php $lang = $this->get( 'imageData/media.languageid/' . $idx ); ?>

									<option value="" <?= ( $lang == '' ? 'selected="selected"' : '' ) ?> >
										<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>
									</option>

									<?php foreach( $this->get( 'pageLangItems', [] ) as $langId => $langItem ) : ?>
										<option value="<?= $enc->attr( $langId ); ?>" <?= ( $lang == $langId ? 'selected="selected"' : '' ) ?> >
											<?= $enc->html( $langItem->getLabel() ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Images will only be shown for that language, useful if the image contains text or is language sepecific' ) ); ?>
							</div>
						</div>

						<?php $mediaTypes = $this->get( 'imageTypes', [] ); ?>
						<?php if( count( $mediaTypes ) > 1 ) : ?>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
								<div class="col-sm-8">
									<select class="form-control custom-select item-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
										name="<?= $enc->attr( $this->formparam( array( 'image', 'media.typeid', '' ) ) ); ?>"
										<?= $this->site()->readonly( $this->get( 'imageData/catalog.lists.siteid/' . $idx ) ); ?> >
										<option value="">
											<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
										</option>

										<?php foreach( (array) $mediaTypes as $typeId => $typeItem ) : ?>
											<option value="<?= $enc->attr( $typeId ); ?>" <?= ( $typeId == $this->get( 'imageData/media.typeid/' . $idx ) ? 'selected="selected"' : '' ) ?> >
												<?= $enc->html( $typeItem->getLabel() ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Types for additional images like icons' ) ); ?>
								</div>
							</div>
						<?php else : $mediaType = reset( $mediaTypes ); ?>
							<input class="item-typeid" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'image', 'media.typeid', '' ) ) ); ?>"
								value="<?= $enc->attr( $mediaType ? $mediaType->getId() : '' ); ?>" />
						<?php endif; ?>

						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Title' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'image', 'media.label', '' ) ) ); ?>"
									value="<?= $enc->attr( $this->get( 'imageData/media.label/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'imageData/catalog.lists.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'The image title is used for the title tag of the image on the web site' ) ); ?>
							</div>
						</div>
					</div>

				</div>
			</div>

		<?php endforeach; ?>

		<div class="group-item card prototype">
			<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'image', 'catalog.lists.id', '' ) ) ); ?>" value="" disabled="disabled" />

			<div id="item-image-group-item-" class="card-header header" role="tab"
				data-toggle="collapse" data-target="#item-image-group-data-">
				<div class="card-tools-left">
					<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry') ); ?>">
					</div>
				</div>
				<span class="item-label header-label"></span>
				&nbsp;
				<div class="card-tools-right">
					<div class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
					</div>
				</div>
			</div>

			<div id="item-image-group-data-" class="card-block collapse show row" role="tabpanel">
				<div class="col-xl-6">
					<div class="form-group row image-preview">
						<input class="fileupload" type="file" name="image[files][]" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled" />
					</div>
				</div>

				<div class="col-xl-6">
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></label>
						<div class="col-sm-8">
							<select class="form-control custom-select item-languageid" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'image', 'media.languageid', '' ) ) ); ?>">
								<option value=""><?= $enc->html( $this->translate( 'admin', 'All' ) ); ?></option>

								<?php foreach( $this->get( 'pageLangItems', [] ) as $langId => $langItem ) : ?>
									<option value="<?= $enc->attr( $langId ); ?>"><?= $enc->html( $langItem->getLabel() ); ?></option>
								<?php endforeach; ?>

							</select>
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Images will only be shown for that language, useful if the image contains text or is language sepecific' ) ); ?>
						</div>
					</div>

					<?php $mediaTypes = $this->get( 'imageTypes', [] ); ?>
					<?php if( count( $mediaTypes ) > 1 ) : ?>
						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
							<div class="col-sm-8">
								<select class="form-control custom-select item-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
									name="<?= $enc->attr( $this->formparam( array( 'image', 'media.typeid', '' ) ) ); ?>">
									<option value="">
										<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
									</option>

									<?php foreach( (array) $mediaTypes as $typeId => $typeItem ) : ?>
										<option value="<?= $enc->attr( $typeId ); ?>" >
											<?= $enc->html( $typeItem->getLabel() ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Types for additional images like icons' ) ); ?>
							</div>
						</div>
					<?php else : $mediaType = reset( $mediaTypes ); ?>
						<input class="item-typeid" type="hidden" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'image', 'media.typeid', '' ) ) ); ?>"
							value="<?= $enc->attr( $mediaType ? $mediaType->getId() : '' ); ?>" />
					<?php endif; ?>

					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Title' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'image', 'media.label', '' ) ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'The image title is used for the title tag of the image on the web site' ) ); ?>
						</div>
					</div>
				</div>

			</div>
		</div>

		<div class="card-tools-more">
			<div class="btn btn-primary btn-card-more act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
				title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>">
			</div>
		</div>
	</div>

	<?= $this->get( 'imageBody' ); ?>
</div>
