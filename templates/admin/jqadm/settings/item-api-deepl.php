<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2024
 */

$enc = $this->encoder();

/** admin/jqadm/api/translate
 * Configuration for realtime online translation service
 *
 * Contains the settings for configuring the online translation service.
 * Currently, only DeepL is supported and a DeepL API account is required to
 * use the service. You have to configure at least the API "key", all other
 * settings are optional:
 *
 *  [
 *    'key' => '<your-DeepL-API-key>',
 *    'url' => 'https://api.deepl.com/v2',
 *  ]
 *
 * @param array Associative list of key/value pairs
 * @since 2019.10
 */


?>
<div class="row">

	<div class="col-xl-12">
		<div class="box">
			<h2 class="item-header">DeepL settings > <a href="https://www.deepl.com/en/pro">DeepL.com</a></h2>

			<div class="form-group row">
				<label class="col-sm-4 form-control-label"><?= $this->translate( 'admin', 'API key' ) ?></label>
				<div class="col-sm-8">
					<input class="form-control" tabindex="<?= $this->get( 'tabindex' ) ?>"
						name="<?= $enc->attr( $this->formparam( ['api', 'deepl', 'key'] ) ) ?>"
						value="<?= $enc->attr( $this->get( 'deeplData/key' ) ) ?>"
					>
				</div>
			</div>

		</div>
	</div>

</div>
