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
			<h2 class="item-header">OpenAI settings > <a href="https://openai.com/chatgpt/pricing/">OpenAI.com</a></h2>

			<div class="form-group row">
				<label class="col-sm-4 form-control-label"><?= $this->translate( 'admin', 'API key' ) ?></label>
				<div class="col-sm-8">
					<input class="form-control" tabindex="<?= $this->get( 'tabindex' ) ?>"
						name="<?= $enc->attr( $this->formparam( ['api', 'openai', 'key'] ) ) ?>"
						value="<?= $enc->attr( $this->get( 'openaiData/key' ) ) ?>"
					>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 form-control-label"><?= $this->translate( 'admin', 'Model' ) ?></label>
				<div class="col-sm-8">
					<input class="form-control" tabindex="<?= $this->get( 'tabindex' ) ?>"
						name="<?= $enc->attr( $this->formparam( ['api', 'openai', 'model'] ) ) ?>"
						value="<?= $enc->attr( $this->get( 'openaiData/model', 'gpt-4o-mini' ) ) ?>"
					>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 form-control-label"><?= $this->translate( 'admin', 'Context' ) ?></label>
				<div class="col-sm-8">
					<text class="form-control" tabindex="<?= $this->get( 'tabindex' ) ?>"
						name="<?= $enc->attr( $this->formparam( ['api', 'openai', 'context'] ) ) ?>">
						<?= $enc->html( $this->get( 'openaiData/context', 'You are a professional writer for product texts and blog articles and create descriptions and articles in the language of the input without markup' ) ) ?>
					</text>
				</div>
			</div>

		</div>
	</div>

</div>
