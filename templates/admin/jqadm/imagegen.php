<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2024
 */

/** admin/jqadm/partial/imagegen
 * Relative path to the partial template for image generation dialog
 *
 * The template file contains the HTML code and processing instructions
 * to generate images in the administration interface. The
 * configuration string is the path to the template file relative
 * to the templates directory (usually in templates/admin/jqadm).
 *
 * You can overwrite the template file configuration in extensions and
 * provide alternative templates.
 *
 * @param string Relative path to the partial creating the HTML code
 * @since 2024.10
 */

$enc = $this->encoder();

?>
<div id="imagegen">
	<div class="modal modal-lg fade" v-bind:class="{loading: loading, show: show}">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><?= $enc->html( $this->translate( 'admin', 'Generate image' ) ) ?></h4>
					<button type="button" class="btn-close" v-on:click="$emit('close')"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Close' ) ) ?>"></button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="form-control-label"><?= $enc->html( $this->translate( 'admin', 'Image description' ) ) ?></label>
						<textarea class="form-control" :class="{'is-valid': prompt, 'is-invalid': !prompt && missing}" rows="5" v-model="prompt"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'Describe your image' ) ) ?>">
						</textarea>
					</div>
					<div class="form-group">
						<label class="form-control-label"><?= $enc->html( $this->translate( 'admin', 'Size' ) ) ?></label>
						<select class="form-select" v-model="size">
							<option value="1792x1024">1792x1024</option>
							<option value="1024x1024">1024x1024</option>
							<option value="1024x1792">1024x1792</option>
						</select>
					</div>
					<div class="form-group">
						<label class="form-control-label"><?= $enc->html( $this->translate( 'admin', 'Style' ) ) ?></label>
						<select class="form-select" v-model="style">
							<option value="natural"><?= $enc->html( $this->translate( 'admin', 'Natural' ) ) ?></option>
							<option value="vivid"><?= $enc->html( $this->translate( 'admin', 'Hyper-real and dramatic' ) ) ?></option>
						</select>
					</div>
					<div class="form-group generate">
						<button type="button" class="btn btn-primary btn-generate icon" :disabled="loading" @click.prevent="generate()">
							<?= $enc->html( $this->translate( 'admin', 'Generate' ) ) ?>
						</button>
					</div>
					<div v-if="images.length > 1" class="form-group selector">
						<span v-for="(data, idx) in images" class="image-select" @click="selected = idx">{{ idx + 1 }}</span>
					</div>
					<div class="form-group">
						<img v-if="images[selected]" class="img-fluid" :src="images[selected]?.url" />
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" v-on:click="$emit('close')">
						<?= $enc->html( $this->translate( 'admin', 'Close' ) ) ?>
					</button>
					<button v-if="images[selected]" type="button" class="btn btn-primary" v-on:click="$emit('confirm', images[selected].file)">
						<?= $enc->html( $this->translate( 'admin', 'Use' ) ) ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
