<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */

$enc = $this->encoder();


?>
<div id="special-price" class="col-xl-6 content-block item-special-price">

	<div class="form-group row optional warning">
		<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Allow custom price' ) ); ?></label>
		<div class="col-sm-8">
			<input class="form-control item-special-price-custom" type="checkbox" value="1" tabindex="<?= $this->get( 'tabindex' ); ?>"
				name="<?= $enc->attr( $this->formparam( array( 'specialprice', 'custom' ) ) ); ?>"
				<?= $this->get( 'specialpriceData/custom', 0 ) ? 'checked="checked"' : ''; ?>
			/>
		</div>
		<div class="col-sm-12 form-text text-muted help-text">
			<?= $enc->html( $this->translate( 'admin', 'Allow customers to choose themselves how much they want to pay' ) ); ?>
		</div>
	</div>

	<?= $this->get( 'specialpriceBody' ); ?>
</div>
