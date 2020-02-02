<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2020
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
$list = $this->get( 'info', [] );

?>
<?php if( !empty( $list ) ) : ?>
	<ul class="info-list alert alert-info" role="alert">
		<?php	foreach( $list as $key => $entry ) : ?>
			<li class="info-item" data-key="<?= $enc->attr( $key ); ?>">
				<span class="fa fa-exclamation-circle" aria-hidden="true"></span>
				<span class="sr-only"><?= $enc->html( $this->translate( 'admin', 'Info' ) ); ?></span>
				<?= $enc->html( $entry ); ?>
			</li>
		<?php	endforeach; ?>
	</ul>
<?php endif; ?>