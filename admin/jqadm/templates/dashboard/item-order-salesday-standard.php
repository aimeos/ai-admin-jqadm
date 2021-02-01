<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */

$enc = $this->encoder();

?>

<div class="chart order-salesday col-xl-12">
	<div class="box">
		<div class="header"
			data-bs-toggle="collapse" data-bs-target="#order-salesday-data"
			aria-expanded="true" aria-controls="order-salesday-data">
			<div class="card-tools-left">
				<div class="btn act-show fa"></div>
			</div>
			<span class="header-label">
				<?= $enc->html( $this->translate( 'admin', 'Sales of the last 30 days' ) ); ?>
			</span>
		</div>
		<div id="order-salesday-data" class="collapse show content loading"></div>
	</div>
</div>
