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
<div class="order-paymentstatus card panel col-lg-6">
	<div id="order-paymentstatus-head" class="header card-header">
		<?php echo $enc->html( $this->translate( 'admin', 'Orders by payment status' ) ); ?>
	</div>
	<div id="order-paymentstatus-data" class="content card-block loading" data-translation="<?php echo $enc->attr( json_encode( $trans ) ); ?>">
	</div>
</div>
<?php echo $this->get( 'orderpaymentstatusBody' ); ?>
