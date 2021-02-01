<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */

$enc = $this->encoder();

?>

<div class="chart order-servicepayment col-xl-6">
	<div class="box">
		<div class="header"
			data-bs-toggle="collapse" data-bs-target="#order-servicepayment-data"
			aria-expanded="true" aria-controls="order-servicepayment-data">
			<div class="card-tools-left">
				<div class="btn act-show fa"></div>
			</div>
			<span class="header-label">
				<?= $enc->html( $this->translate( 'admin', 'Payment types' ) ); ?>
			</span>
		</div>
		<div id="order-servicepayment-data" class="collapse show content">
			<div class="chart loading"></div>
		</div>
	</div>
</div>
<?= $this->get( 'orderpaymenttypeBody' ); ?>
