<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2022
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$enc = $this->encoder();


?>
<div id="theme" class="item-theme tab-pane fade" role="tabpanel" aria-labelledby="theme">

	<div class="box">
		<div class="row">
			<div class="col-lg-6">

				<?php foreach( $this->get( 'themeData', [] ) as $key => $value ) : ?>
					
					<div class="form-group row mandatory">
						<label class="col-sm-8 form-control-label"><?= $enc->html( $key ) ?></label>
						<div class="col-sm-4">
							<input class="form-control" type="<?= !strncmp( $value, '#', 1 ) ? 'color' : 'text' ?>"
								required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
								name="<?= $enc->attr( $this->formparam( ['theme', $key] ) ) ?>"
								value="<?= $enc->attr( $value ) ?>"
							/>
						</div>
					</div>

				<?php endforeach ?>

				<?php if( empty( $this->get( 'themeData', [] ) ) ) : ?>
					<?= $enc->html( $this->translate( 'admin', 'No theme options available' ) ) ?>
				<?php endif ?>

			</div>
			<div class="col-lg-6"></div>
		</div>
	</div>

</div>