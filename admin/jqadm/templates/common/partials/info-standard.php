<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
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