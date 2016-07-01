<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */

$enc = $this->encoder();

?>

<div class="order-deliverytype card panel col-lg-6">
	<div id="order-deliverytype-head" class="header card-header">
		<?php echo $enc->html( $this->translate( 'admin', 'Delivery types' ) ); ?>
	</div>
	<div id="order-deliverytype-data" class="content card-block loading">
	</div>
</div>
<?php echo $this->get( 'orderdeliverytypeBody' ); ?>
