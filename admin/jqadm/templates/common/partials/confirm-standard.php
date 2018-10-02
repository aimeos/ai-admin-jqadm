<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
 */

$enc = $this->encoder();

?>
<div id="confirm-delete" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?= $enc->html( $this->translate( 'admin', 'Delete items?' ) ); ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $enc->attr( $this->translate( 'admin', 'Close' ) ); ?>"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<p><?= $enc->html( $this->translate( 'admin', 'You are going to delete one or more items. Would you like to proceed?' ) ); ?></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><?= $enc->html( $this->translate( 'admin', 'Delete' ) ); ?></button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $enc->html( $this->translate( 'admin', 'Close' ) ); ?></button>
			</div>
		</div>
	</div>
</div>
