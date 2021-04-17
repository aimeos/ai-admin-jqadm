<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2021
 */

/** admin/jqadm/partial/problem
 * Relative path to the partial template for displaying problems
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
 * @since 2019.04
 * @category Developer
 * @see admin/jqadm/partial/confirm
 * @see admin/jqadm/partial/error
 * @see admin/jqadm/partial/info
 */

$enc = $this->encoder();


$bytes = function( $value ) {

	$result = substr( $value, 0, -1 );

	switch( substr( $value, -1 ) )
	{
		case 'G': case 'g': $result *= 1024;
		case 'M': case 'm': $result *= 1024;
		case 'K': case 'k': $result *= 1024;
	}

	return $result;
};


$value = function( $val ) {
	return $val === '1' || strtolower( $val ) === 'on' ? 1 : 0;
};


?>
<div id="problem" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?= $enc->html( $this->translate( 'admin', 'Problem' ) ) ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $enc->attr( $this->translate( 'admin', 'Close' ) ) ?>"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<p class="file_uploads hidden" data-value="<?= $enc->attr( $value( ini_get( 'file_uploads' ) ) ) ?>">
					<?= $enc->html( sprintf( $this->translate( 'admin', 'PHP setting "%1$s" is disabled' ), "file_uploads" ) ) ?>
				</p>
				<p class="max_input_vars hidden" data-value="<?= $enc->attr( ini_get( 'max_input_vars' ) ) ?>">
					<?= $enc->html( sprintf( $this->translate( 'admin', 'PHP setting for "%1$s" is too low' ), "max_input_vars" ) ) ?>
				</p>
				<p class="max_file_uploads hidden" data-value="<?= $enc->attr( ini_get( 'max_file_uploads' ) ) ?>">
					<?= $enc->html( sprintf( $this->translate( 'admin', 'PHP setting for "%1$s" is too low' ), "max_file_uploads" ) ) ?>
				</p>
				<p class="post_max_size hidden" data-value="<?= $enc->attr( $bytes( ini_get( 'post_max_size' ) ) ) ?>">
					<?= $enc->html( sprintf( $this->translate( 'admin', 'PHP setting for "%1$s" is too low' ), "post_max_size" ) ) ?>
				</p>
				<p class="upload_max_filesize hidden" data-value="<?= $enc->attr( $bytes( ini_get( 'upload_max_filesize' ) ) ) ?>">
					<?= $enc->html( sprintf( $this->translate( 'admin', 'PHP setting for "%1$s" is too low' ), "upload_max_filesize" ) ) ?>
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $enc->html( $this->translate( 'admin', 'Close' ) ) ?></button>
			</div>
		</div>
	</div>
</div>
