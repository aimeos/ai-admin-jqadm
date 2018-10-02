<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */

$enc = $this->encoder();

?>

<div class="order-salesmonth card col-lg-6">
	<div id="order-salesmonth-head" class="card-header header" role="tab"
		 data-toggle="collapse" data-target="#order-salesmonth-data"
		 aria-expanded="true" aria-controls="order-salesmonth-data">
		<div class="card-tools-left">
			<div class="btn btn-card-header act-show fa"></div>
		</div>
		<span class="item-label header-label">
			<?= $enc->html( $this->translate( 'admin', 'Sales per month' ) ); ?>
		</span>
	</div>
	<div id="order-salesmonth-data" class="card-block collapse show content loading" role="tabpanel"
		aria-labelledby="order-salesmonth-head">
	</div>
</div>
