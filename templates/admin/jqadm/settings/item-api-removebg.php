<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2024
 */

$enc = $this->encoder();

/** admin/jqadm/api/removebg
 * Configuration for image background removal service
 *
 * Contains the settings for configuring the image background removal service.
 * Currently, only RemoveBG is supported and a RemoveBG API account is required to
 * use the service. You have to configure at least the API "key":
 *
 *  [
 *    'key' => '<your-RemoveBG-API-key>',
 *  ]
 *
 * @param array Associative list of key/value pairs
 * @since 2024.10
 */


?>
<div class="row">

	<div class="col-xl-12">
		<div class="box">
			<h2 class="item-header">RemoveBG settings > <a href="https://www.remove.bg/api">Remove.BG</a></h2>

			<div class="form-group row">
				<label class="col-sm-4 form-control-label"><?= $this->translate( 'admin', 'API key' ) ?></label>
				<div class="col-sm-8">
					<input class="form-control" tabindex="<?= $this->get( 'tabindex' ) ?>"
						name="<?= $enc->attr( $this->formparam( ['api', 'removebg', 'key'] ) ) ?>"
						value="<?= $enc->attr( $this->get( 'removebgData/key' ) ) ?>"
					>
				</div>
			</div>

		</div>
	</div>

</div>
