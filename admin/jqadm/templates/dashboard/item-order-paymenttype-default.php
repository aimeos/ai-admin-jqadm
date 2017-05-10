<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */

$enc = $this->encoder();

?>

<div class="order-paymenttype card col-lg-6">
	<div id="order-paymenttype-head" class="card-header header" role="tab"
		data-toggle="collapse" data-target="#order-paymenttype-data"
		aria-expanded="true" aria-controls="order-paymenttype-data">
		<div class="card-tools-left">
			<div class="btn btn-card-header act-show fa"></div>
		</div>
		<span class="item-label header-label">
			<?= $enc->html( $this->translate( 'admin', 'Payment types' ) ); ?>
		</span>
	</div>
	<div id="order-paymenttype-data" class="card-block collapse show content loading" role="tabpanel">
	</div>
</div>
<?= $this->get( 'orderpaymenttypeBody' ); ?>
