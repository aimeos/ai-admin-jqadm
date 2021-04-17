<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */

$enc = $this->encoder();


?>
<div class="chart bar order-counthour col-xl-6">
	<div class="box">
		<div class="header"
			data-bs-toggle="collapse" data-bs-target="#order-counthour-data"
			aria-expanded="true" aria-controls="order-counthour-data">
			<div class="card-tools-start">
				<div class="btn act-show fa"></div>
			</div>
			<h2 class="header-label">
				<?= $enc->html( $this->translate( 'admin', 'Orders by hour' ) ) ?>
			</h2>
		</div>
		<div id="order-counthour-data" class="collapse show content loading">
			<div class="chart-legend"></div>
			<div class="chart"><canvas></canvas></div>
		</div>
	</div>
</div>
<?= $this->get( 'orderhourBody' ) ?>
