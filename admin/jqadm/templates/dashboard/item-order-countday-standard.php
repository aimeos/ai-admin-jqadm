<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */

$enc = $this->encoder();

?>
<div class="chart matrix order-countday col-xl-12">
	<div class="box">
		<div class="header"
			data-bs-toggle="collapse" data-bs-target="#order-countday-data"
			aria-expanded="true" aria-controls="order-countday-data">
			<div class="card-tools-start">
				<div class="btn act-show fa"></div>
			</div>
			<h2 class="header-label">
				<?= $enc->html( $this->translate( 'admin', 'Orders by day' ) ) ?>
			</h2>
		</div>
		<div id="order-countday-data" class="collapse show content">
			<div class="chart loading"><canvas></canvas></div>
		</div>
	</div>
</div>
<?= $this->get( 'orderdayBody' ) ?>
