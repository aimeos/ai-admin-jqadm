<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2024
 */

$enc = $this->encoder();

?>

<div class="chart pie order-servicedelivery col-xl-6" data-title="<?= $enc->attr( $this->translate( 'admin', 'Delivery' ) ) ?>">
	<div class="box">
		<div class="header"
			data-bs-toggle="collapse" data-bs-target="#order-servicedelivery-data"
			aria-expanded="true" aria-controls="order-servicedelivery-data">
			<div class="card-tools-start">
				<div class="btn act-show icon"></div>
			</div>
			<h2 class="header-label">
				<?= $enc->html( $this->translate( 'admin', 'Delivery types' ) ) ?>
			</h2>
		</div>
		<div id="order-servicedelivery-data" class="collapse show loading">
			<div class="row">
				<div class="content col-md-7">
					<div class="chart"><canvas></canvas></div>
				</div>
				<div class="content col-md-5">
					<div class="chart-legend"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->get( 'orderdeliverytypeBody' ) ?>
