<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */

/** admin/jqadm/partial/info
 * Relative path to the partial template for displaying notices
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
 * @since 2017.10
 * @category Developer
 * @see admin/jqadm/partial/confirm
 * @see admin/jqadm/partial/error
 * @see admin/jqadm/partial/problem
 */

$enc = $this->encoder();


?>
<div class="toast-list" aria-live="polite" aria-atomic="true">

	<?php foreach( $this->get( 'info', [] ) as $key => $entry ) : ?>
		<div class="row info toast hide" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="col-1 toast-icon">
				<span class="fa fa-exclamation-circle"></span>
			</div>
			<div class="col-9">
				<div class="toast-header">
					<strong><?= $enc->html( $this->translate( 'admin', 'Info' ) ) ?></strong>
				</div>
				<div class="toast-body">
					<?= str_replace( "\n", '<br>', $enc->html( $entry ) ) ?>
				</div>
			</div>
			<div class="col-2 toast-close">
				<button type="button" class="btn-close" data-bs-dismiss="toast"
					aria-label="<?= $enc->attr( $this->translate( 'admin', 'Close' ) ) ?>">
				</button>
			</div>
		</div>
	<?php endforeach ?>

	<?php foreach( $this->get( 'error', [] ) as $key => $entry ) : ?>
		<div class="row error toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
			<div class="col-1 toast-icon">
				<span class="fa fa-exclamation-circle"></span>
			</div>
			<div class="col-9">
				<div class="toast-header">
					<strong><?= $enc->html( $this->translate( 'admin', 'Error' ) ) ?></strong>
				</div>
				<div class="toast-body">
					<?= str_replace( "\n", '<br>', $enc->html( $entry ) ) ?>
				</div>
			</div>
			<div class="col-2 toast-close">
				<button type="button" class="btn-close" data-bs-dismiss="toast"
					aria-label="<?= $enc->attr( $this->translate( 'admin', 'Close' ) ) ?>">
				</button>
			</div>
		</div>
	<?php endforeach ?>

</div>
