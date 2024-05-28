<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2024
 */

$enc = $this->encoder();


?>
<div id="physical" class="item-physical tab-pane fade" role="tabpanel" aria-labelledby="physical">
	<div class="row">
		<div class="col-xl-6 block">
			<div class="box <?= $this->site()->mismatch( $this->get( 'itemData/product.siteid' ) ) ?>">
				<div class="form-group row optional">
					<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Length' ) ) ?></label>
					<div class="col-sm-8">
						<input class="form-control item-package-length" type="number" step="any" min="0" tabindex="<?= $this->get( 'tabindex' ) ?>"
							name="<?= $enc->attr( $this->formparam( array( 'physical', 'package-length' ) ) ) ?>"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'Product length, e.g. 30.0 (in yard, inch, etc.)' ) ) ?>"
							value="<?= $enc->attr( $this->get( 'physicalData/package-length' ) ) ?>"
							<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ) ?> >
					</div>
				</div>
				<div class="form-group row optional">
					<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Width' ) ) ?></label>
					<div class="col-sm-8">
						<input class="form-control item-package-width" type="number" step="any" min="0" tabindex="<?= $this->get( 'tabindex' ) ?>"
							name="<?= $enc->attr( $this->formparam( array( 'physical', 'package-width' ) ) ) ?>"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'Product width, e.g. 17.5 (in yard, inch etc.)' ) ) ?>"
							value="<?= $enc->attr( $this->get( 'physicalData/package-width' ) ) ?>"
							<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ) ?> >
					</div>
				</div>
				<div class="form-group row optional">
					<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Height' ) ) ?></label>
					<div class="col-sm-8">
						<input class="form-control item-package-height" type="number" step="any" min="0" tabindex="<?= $this->get( 'tabindex' ) ?>"
							name="<?= $enc->attr( $this->formparam( array( 'physical', 'package-height' ) ) ) ?>"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'Product height, e.g. 20.0 (in yard, inch, etc.)' ) ) ?>"
							value="<?= $enc->attr( $this->get( 'physicalData/package-height' ) ) ?>"
							<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ) ?> >
					</div>
				</div>
				<div class="form-group row optional">
					<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Weight' ) ) ?></label>
					<div class="col-sm-8">
						<input class="form-control item-package-weight" type="number" step="any" min="0" tabindex="<?= $this->get( 'tabindex' ) ?>"
							name="<?= $enc->attr( $this->formparam( array( 'physical', 'package-weight' ) ) ) ?>"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'Product weight, e.g. 1.25 (in pound, ounce, etc.)' ) ) ?>"
							value="<?= $enc->attr( $this->get( 'physicalData/package-weight' ) ) ?>"
							<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ) ?> >
					</div>
				</div>
			</div>
		</div>
	</div>

	<?= $this->get( 'physicalBody' ) ?>
</div>
