<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */

$enc = $this->encoder();

?>

<div class="chart order-servicedelivery col-xl-6">
	<div class="box">
		<div class="header"
			data-bs-toggle="collapse" data-bs-target="#order-servicedelivery-data"
			aria-expanded="true" aria-controls="order-servicedelivery-data">
			<div class="card-tools-left">
				<div class="btn act-show fa"></div>
			</div>
			<span class="header-label">
				<?= $enc->html( $this->translate( 'admin', 'Delivery types' ) ); ?>
			</span>
		</div>
		<div class="collapse show content loading"></div>
	</div>
</div>
<?= $this->get( 'orderdeliverytypeBody' ); ?>
