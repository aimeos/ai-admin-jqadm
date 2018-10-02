<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */

$enc = $this->encoder();

?>

<div class="order-countday card col-lg-12">
	<div id="order-countday-head" class="card-header header" role="tab"
		data-toggle="collapse" data-target="#order-countday-data"
		aria-expanded="true" aria-controls="order-countday-data">
		<div class="card-tools-left">
			<div class="btn btn-card-header act-show fa"></div>
		</div>
		<span class="item-label header-label">
			<?= $enc->html( $this->translate( 'admin', 'Orders by day' ) ); ?>
		</span>
	</div>
	<div id="order-countday-data" class="card-block collapse show content loading" role="tabpanel"
		aria-labelledby="order-countday-head">
	</div>
</div>
<?= $this->get( 'orderdayBody' ); ?>
