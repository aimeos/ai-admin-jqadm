<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */

?>
<?php $this->block()->start( 'jqadm_content' ); ?>
<div class="dashboard">
<?php echo $this->get( 'listBody' ); ?>
</div>
<?php $this->block()->stop(); ?>

<?php echo $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-default.php' ) ); ?>
