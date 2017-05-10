<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */

$enc = $this->encoder();

?>

<div class="order-hour card col-lg-6">
	<div id="order-hour-head" class="card-header header" role="tab"
		data-toggle="collapse" data-target="#order-hour-data"
		aria-expanded="true" aria-controls="order-hour-data">
		<div class="card-tools-left">
			<div class="btn btn-card-header act-show fa"></div>
		</div>
		<span class="item-label header-label">
			<?= $enc->html( $this->translate( 'admin', 'Orders by hour' ) ); ?>
		</span>
	</div>
	<div id="order-hour-data" class="card-block collapse show content loading" role="tabpanel">
	</div>
</div>
<?= $this->get( 'orderhourBody' ); ?>
