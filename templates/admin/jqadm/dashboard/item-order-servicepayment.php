<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2025
 */

$enc = $this->encoder();

?>

<div class="chart pie order-servicepayment col-xl-6" data-title="<?= $enc->attr( $this->translate( 'admin', 'Payment' ) ) ?>">
	<div class="box">
		<div class="header"
			data-bs-toggle="collapse" data-bs-target="#order-servicepayment-data"
			aria-expanded="true" aria-controls="order-servicepayment-data">
			<div class="card-tools-start">
				<div class="btn act-show icon"></div>
			</div>
			<h2 class="header-label">
				<?= $enc->html( $this->translate( 'admin', 'Payment types' ) ) ?>
			</h2>
		</div>
		<div id="order-servicepayment-data" class="collapse show loading">
			<div class="chart"><canvas></canvas></div>
		</div>
	</div>
</div>
<?= $this->get( 'orderpaymenttypeBody' ) ?>
