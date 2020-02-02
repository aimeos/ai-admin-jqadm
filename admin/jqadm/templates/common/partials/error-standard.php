<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2020
 */

/** admin/jqadm/partial/error
 * Relative path to the partial template for displaying errors
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
 * @see admin/jqadm/partial/confirm
 * @see admin/jqadm/partial/info
 * @see admin/jqadm/partial/problem
 */

$enc = $this->encoder();
$errors = $this->get( 'errors', [] );

?>
<?php if( !empty( $errors ) ) : ?>
	<ul class="error-list alert alert-danger" role="alert">
		<?php	foreach( $errors as $key => $error ) : ?>
			<li class="error-item" data-key="<?= $enc->attr( $key ); ?>">
				<span class="fa fa-exclamation-circle" aria-hidden="true"></span>
				<span class="sr-only"><?= $enc->html( $this->translate( 'admin', 'Error' ) ); ?></span>
				<?= $enc->html( $error ); ?>
			</li>
		<?php	endforeach; ?>
	</ul>
<?php endif; ?>