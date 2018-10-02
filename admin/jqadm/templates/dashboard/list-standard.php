<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */

$enc = $this->encoder();


?>
<?php $this->block()->start( 'jqadm_content' ); ?>
<nav class="main-navbar">
	<span class="navbar-brand">
		<?= $enc->html( $this->translate( 'admin', 'Dashboard' ) ); ?>
		<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ); ?>)</span>
	</span>
	<div class="btn act-show fa" style="visibility: hidden"></div>
</nav>

<div class="dashboard">
	<?= $this->get( 'listBody' ); ?>
</div>
<?php $this->block()->stop(); ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard.php' ) ); ?>
