<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$enc = $this->encoder();

?>

<div class="order-weekday card col-lg-6">
	<div id="order-weekday-head" class="card-header header" role="tab"
		data-toggle="collapse" data-target="#order-weekday-data"
		aria-expanded="true" aria-controls="order-weekday-data">
		<div class="card-tools-left">
			<div class="btn btn-card-header act-show fa"></div>
		</div>
		<span class="item-label header-label">
			<?= $enc->html( $this->translate( 'admin', 'Sales by weekday' ) ); ?>
		</span>
	</div>
	<div id="order-weekday-data" class="card-block collapse show content loading" role="tabpanel">
	</div>
</div>
<?= $this->get( 'orderweekdayBody' ); ?>
