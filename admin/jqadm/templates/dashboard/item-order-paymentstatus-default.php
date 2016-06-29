<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */

$enc = $this->encoder();

?>

<div class="order-paymentstatus card panel col-lg-6">
	<div id="order-paymentstatus-head" class="header card-header">
		<?php echo $enc->html( $this->translate( 'admin', 'Orders by payment status' ) ); ?>
	</div>
	<div id="order-paymentstatus-data" class="content card-block">
	</div>
</div>
<?php echo $this->get( 'orderpaymentstatusBody' ); ?>
