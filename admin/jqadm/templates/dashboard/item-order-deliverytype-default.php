<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */

$enc = $this->encoder();

?>

<div class="order-deliverytype card col-lg-6">
	<div id="order-deliverytype-head" class="card-header header" role="tab"
		data-toggle="collapse" data-target="#order-deliverytype-data"
		aria-expanded="true" aria-controls="order-deliverytype-data">
		<div class="card-tools-left">
			<div class="btn btn-card-header act-show fa"></div>
		</div>
		<span class="item-label header-label">
			<?= $enc->html( $this->translate( 'admin', 'Delivery types' ) ); ?>
		</span>
	</div>
	<div id="order-deliverytype-data" class="card-block collapse show content loading" role="tabpanel">
	</div>
</div>
<?= $this->get( 'orderdeliverytypeBody' ); ?>
