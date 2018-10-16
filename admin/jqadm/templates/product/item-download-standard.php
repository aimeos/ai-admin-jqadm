<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$enc = $this->encoder();


?>
<div id="download" class="row item-download tab-pane fade" role="tabpanel" aria-labelledby="download">
	<div class="col-lg-6 content-block <?= $this->site()->readonly( $this->get( 'downloadData/product.lists.siteid', $this->pageSiteItem->getId() ) ); ?>">
		<div class="form-group row optional">
			<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'File' ) ); ?></label>
			<div class="input-group col-sm-8">
				<input class="item-siteid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'download', 'product.lists.siteid' ) ) ); ?>"
					value="<?= $enc->attr( $this->get( 'downloadData/product.lists.siteid' ) ); ?>" />
				<input class="item-listid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'download', 'product.lists.id' ) ) ); ?>"
					value="<?= $enc->attr( $this->get( 'downloadData/product.lists.id' ) ); ?>" />
				<input class="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'download', 'attribute.id' ) ) ); ?>"
					value="<?= $enc->attr( $this->get( 'downloadData/attribute.id' ) ); ?>" />
				<div class="custom-file">
					<input id="download-file" class="custom-file-input fileupload" type="file" name="download[file]" tabindex="<?= $this->get( 'tabindex' ); ?>" />
					<label for="download-file" class="custom-file-label"><?= $enc->html( $this->translate( 'admin', 'Choose file' ) ); ?></label>
				</div>
			</div>
		</div>
		<div class="form-group row optional">
			<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ); ?></label>
			<div class="col-sm-8">
				<select class="form-control custom-select item-status" tabindex="<?= $this->get( 'tabindex' ); ?>"
					name="<?= $enc->attr( $this->formparam( array( 'download', 'attribute.status' ) ) ); ?>"
					<?= $this->site()->readonly( $this->get( 'downloadData/attribute.siteid', $this->pageSiteItem->getId() ) ); ?> >
					<option value="1" <?= $selected( $this->get( 'downloadData/attribute.status', 1 ), 1 ); ?> >
						<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ); ?>
					</option>
					<option value="0" <?= $selected( $this->get( 'downloadData/attribute.status', 1 ), 0 ); ?> >
						<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ); ?>
					</option>
					<option value="-1" <?= $selected( $this->get( 'downloadData/attribute.status', 1 ), -1 ); ?> >
						<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ); ?>
					</option>
					<option value="-2" <?= $selected( $this->get( 'downloadData/attribute.status', 1 ), -2 ); ?> >
						<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ); ?>
					</option>
				</select>
			</div>
		</div>
		<div class="form-group row optional">
			<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Name' ) ); ?></label>
			<div class="col-sm-8">
				<input class="form-control item-label" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
					name="<?= $enc->attr( $this->formparam( array( 'download', 'attribute.label' ) ) ); ?>"
					value="<?= $enc->attr( $this->get( 'downloadData/attribute.label' ) ); ?>"
					<?= $this->site()->readonly( $this->get( 'downloadData/product.lists.siteid', $this->pageSiteItem->getId() ) ); ?> />
			</div>
			<div class="col-sm-12 form-text text-muted help-text">
				<?= $enc->html( $this->translate( 'admin', 'Name of the downloaded file when customers saves the file on their computers' ) ); ?>
			</div>
		</div>
		<div class="form-group row optional">
			<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Replace file' ) ); ?></label>
			<div class="col-sm-8">
				<input class="form-control item-overwrite" type="checkbox" tabindex="<?= $this->get( 'tabindex' ); ?>"
					name="<?= $enc->attr( $this->formparam( array( 'download', 'overwrite' ) ) ); ?>" value="1"
					<?= $this->site()->readonly( $this->get( 'downloadData/product.lists.siteid', $this->pageSiteItem->getId() ) ); ?>
					<?= $selected( $this->get( 'downloadData/overwrite' ), 1 ); ?> />
			</div>
			<div class="col-sm-12 form-text text-muted help-text">
				<?= $enc->html( $this->translate( 'admin', 'Overwrite the existing file and customers bought it in the past can then download the new content too' ) ); ?>
			</div>
		</div>
	</div>
	<div class="col-lg-6 content-block ">
		<?php if( $this->get( 'downloadData/attribute.code' ) != '' ) : ?>
			<div class="form-group row">
				<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Path' ) ); ?></label>
				<div class="col-sm-8">
					<p class="form-control-plaintext item-file"><?= $enc->html( $this->get( 'downloadData/attribute.code' ) ); ?></p>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Size' ) ); ?></label>
				<div class="col-sm-8">
					<p class="form-control-plaintext item-file"><?= $enc->html( number_format( $this->get( 'downloadData/size' ) / 1024, 0, '.', ' ' ) ); ?> KB</p>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Uploaded' ) ); ?></label>
				<div class="col-sm-8">
					<p class="form-control-plaintext item-file"><?= $enc->html( date( 'Y-m-d H:i:s', $this->get( 'downloadData/time' ) ) ); ?></p>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<?= $this->get( 'downloadBody' ); ?>
</div>
