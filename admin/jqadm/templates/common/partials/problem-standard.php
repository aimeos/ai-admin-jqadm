<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018
 */

$enc = $this->encoder();


$bytes = function( $value ) {

	$result = substr( trim( $value ), 0, -1 );

	switch( strtoupper( substr( trim( $value ), -1 ) ) )
	{
		case 'G': $result *= 1024;
		case 'M': $result *= 1024;
		case 'K': $result *= 1024;
	}

	return $result;
};


?>
<div id="problem" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?= $enc->html( $this->translate( 'admin', 'Problem' ) ); ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $enc->attr( $this->translate( 'admin', 'Close' ) ); ?>"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<p class="upload_max_filesize hidden" data-value="<?= $enc->attr( $bytes( ini_get( 'upload_max_filesize' ) ) ); ?>">
					<?= $enc->html( sprintf( $this->translate( 'admin', 'PHP setting for "%1$s" is too low' ), "upload_max_filesize" ) ); ?>
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $enc->html( $this->translate( 'admin', 'Close' ) ); ?></button>
			</div>
		</div>
	</div>
</div>
