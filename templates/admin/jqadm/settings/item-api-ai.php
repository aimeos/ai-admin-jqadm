<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2026
 */

$enc = $this->encoder();

/** admin/ai
 * Consolidated AI provider settings by capability
 *
 * Each capability contains the Prisma provider name and its flat provider
 * configuration. The model is only used by providers supporting model selection.
 * The URL and all other provider-specific keys are optional.
 *
 *  [
 *    'write' => ['provider' => 'openai', 'model' => 'gpt-4o-mini', 'api_key' => '...'],
 *    'translate' => ['provider' => 'deepl', 'api_key' => '...'],
 *    'imagine' => ['provider' => 'openai', 'model' => 'dall-e-3', 'api_key' => '...'],
 *    'isolate' => ['provider' => 'removebg', 'api_key' => '...'],
 *  ]
 *
 * @param array Associative list of AI capability settings
 * @since 2026.10
 */

$sections = [
	'write' => ['label' => 'Write', 'provider' => 'openai', 'model' => 'gpt-4o-mini'],
	'translate' => ['label' => 'Translate', 'provider' => 'deepl'],
	'imagine' => ['label' => 'Imagine', 'provider' => 'openai', 'model' => 'dall-e-3'],
	'isolate' => ['label' => 'Remove background', 'provider' => 'removebg'],
];

?>
<div class="row">
	<?php foreach( $sections as $action => $defaults ) : ?>
		<?php $extra = array_diff_key( (array) $this->get( 'aiData/' . $action, [] ), array_flip( ['provider', 'model', 'api_key', 'url'] ) ) ?>
		<div class="col-xl-6">
			<div class="box">
				<h2 class="item-header"><?= $enc->html( $this->translate( 'admin', $defaults['label'] ) ) ?></h2>

				<div class="form-group row mandatory">
					<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Provider' ) ) ?></label>
					<div class="col-sm-8">
						<input class="form-control" required tabindex="<?= $this->get( 'tabindex' ) ?>"
							name="<?= $enc->attr( $this->formparam( ['api', 'ai', $action, 'provider'] ) ) ?>"
							value="<?= $enc->attr( $this->get( 'aiData/' . $action . '/provider', $defaults['provider'] ) ) ?>"
						>
					</div>
				</div>

				<?php if( isset( $defaults['model'] ) ) : ?>
					<div class="form-group row">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Model' ) ) ?></label>
						<div class="col-sm-8">
							<input class="form-control" tabindex="<?= $this->get( 'tabindex' ) ?>"
								name="<?= $enc->attr( $this->formparam( ['api', 'ai', $action, 'model'] ) ) ?>"
								value="<?= $enc->attr( $this->get( 'aiData/' . $action . '/model', $defaults['model'] ) ) ?>"
							>
						</div>
					</div>
				<?php endif ?>

				<div class="form-group row">
					<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'API key' ) ) ?></label>
					<div class="col-sm-8">
						<input class="form-control" type="password" autocomplete="new-password" tabindex="<?= $this->get( 'tabindex' ) ?>"
							name="<?= $enc->attr( $this->formparam( ['api', 'ai', $action, 'api_key'] ) ) ?>"
							placeholder="<?= $enc->attr( $this->get( 'aiData/' . $action . '/api_key' ) ? '••••••••' : '' ) ?>"
						>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'URL' ) ) ?></label>
					<div class="col-sm-8">
						<input class="form-control" type="url" tabindex="<?= $this->get( 'tabindex' ) ?>"
							name="<?= $enc->attr( $this->formparam( ['api', 'ai', $action, 'url'] ) ) ?>"
							value="<?= $enc->attr( $this->get( 'aiData/' . $action . '/url' ) ) ?>"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'Optional' ) ) ?>"
						>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Provider settings' ) ) ?></label>
					<div class="col-sm-8">
						<textarea class="form-control" rows="3" tabindex="<?= $this->get( 'tabindex' ) ?>"
							name="<?= $enc->attr( $this->formparam( ['api', 'ai', $action, 'extra'] ) ) ?>"
							placeholder="<?= $enc->attr( '{"resource":"example"}' ) ?>"
						><?= $enc->html( $extra ? json_encode( $extra, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) : '' ) ?></textarea>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach ?>
</div>
