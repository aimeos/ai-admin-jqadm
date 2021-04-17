<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2021
 */

/** admin/jqadm/partial/confirm
 * Relative path to the partial template for displaying the confirmation dialog
 *
 * The template file contains the HTML code and processing instructions
 * to generate the result shown in the administration interface. The
 * configuration string is the path to the template file relative
 * to the templates directory (usually in admin/jqadm/templates).
 *
 * You can overwrite the template file configuration in extensions and
 * provide alternative templates. These alternative templates should be
 * named like the default one but with the string "default" replaced by
 * an unique name. You may use the name of your project for this. If
 * you've implemented an alternative client class as well, "default"
 * should be replaced by the name of the new class.
 *
 * @param string Relative path to the partial creating the HTML code
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/partial/error
 * @see admin/jqadm/partial/info
 * @see admin/jqadm/partial/problem
 */

$enc = $this->encoder();

?>
<div id="confirm-delete">
	<div class="modal fade" v-bind:class="{show: show}">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><?= $enc->html( $this->translate( 'admin', 'Delete items?' ) ) ?></h4>
					<button type="button" class="btn-close" v-on:click="$emit('close')"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Close' ) ) ?>"></button>
				</div>
				<div class="modal-body">
					<p><?= $enc->html( $this->translate( 'admin', 'You are going to delete one or more items. Would you like to proceed?' ) ) ?></p>
					<ul class="items">
						<li class="item" v-for="entry in items">{{ entry }}</li>
					</ul>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" v-on:click="$emit('close')">
						<?= $enc->html( $this->translate( 'admin', 'Close' ) ) ?>
					</button>
					<button type="button" class="btn btn-danger" v-on:click="$emit('confirm')">
						<?= $enc->html( $this->translate( 'admin', 'Delete' ) ) ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
