<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */

$enc = $this->encoder();

?>

<div class="chart order-servicedelivery col-xl-6">
	<div class="card">
		<div id="order-servicedelivery-head" class="card-header header" role="tab"
			data-bs-toggle="collapse" data-target="#order-servicedelivery-data"
			aria-expanded="true" aria-controls="order-servicedelivery-data">
			<div class="card-tools-left">
				<div class="btn btn-card-header act-show fa"></div>
			</div>
			<span class="item-label header-label">
				<?= $enc->html( $this->translate( 'admin', 'Delivery types' ) ); ?>
			</span>
		</div>
		<div id="order-servicedelivery-data" class="card-block collapse show content loading" role="tabpanel"
			aria-labelledby="order-servicedelivery-head">
		</div>
	</div>
</div>
<?= $this->get( 'orderdeliverytypeBody' ); ?>
