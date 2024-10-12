<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2024
 */

$enc = $this->encoder();


?>
<div class="row">

	<div class="col-xl-12">
		<div class="box">
			<h2 class="item-header">DeepL settings</h2>

			<div class="form-group row mandatory">
				<label class="col-sm-8 form-control-label"><?= $this->translate( 'admin', 'API key' ) ?></label>
				<div class="col-sm-4">
					<input class="form-control" tabindex="<?= $this->get( 'tabindex' ) ?>"
						name="<?= $enc->attr( $this->formparam( ['api', 'deepl', 'key'] ) ) ?>"
						value="<?= $enc->attr( $this->get( 'deeplData/key') ) ?>"
					>
				</div>
			</div>

		</div>
	</div>

</div>
