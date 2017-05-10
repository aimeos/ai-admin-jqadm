<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */

$enc = $this->encoder();
$trans = array(
	'-1' => $this->translate( 'admin', 'pay:unfinished' ),
	'0' => $this->translate( 'admin', 'pay:deleted' ),
	'1' => $this->translate( 'admin', 'pay:canceled' ),
	'2' => $this->translate( 'admin', 'pay:refused' ),
	'3' => $this->translate( 'admin', 'pay:refund' ),
	'4' => $this->translate( 'admin', 'pay:pending' ),
	'5' => $this->translate( 'admin', 'pay:authorized' ),
	'6' => $this->translate( 'admin', 'pay:received' ),
);

?>
<div class="order-paymentstatus card col-lg-6">
	<div id="order-paymentstatus-head" class="card-header header" role="tab"
		data-toggle="collapse" data-target="#order-paymentstatus-data"
		aria-expanded="true" aria-controls="order-paymentstatus-data">
		<div class="card-tools-left">
			<div class="btn btn-card-header act-show fa"></div>
		</div>
		<span class="item-label header-label">
			<?= $enc->html( $this->translate( 'admin', 'Orders by payment status' ) ); ?>
		</span>
	</div>
	<div id="order-paymentstatus-data" class="card-block collapse show content loading" role="tabpanel"
		aria-labelledby="order-paymentstatus-head" data-translation="<?= $enc->attr( json_encode( $trans ) ); ?>">
	</div>
</div>
<?= $this->get( 'orderpaymentstatusBody' ); ?>
