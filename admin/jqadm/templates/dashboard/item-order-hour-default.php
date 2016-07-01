<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */

$enc = $this->encoder();

?>

<div class="order-hour card panel col-lg-6">
	<div id="order-hour-head" class="header card-header">
		<?php echo $enc->html( $this->translate( 'admin', 'Orders by hour' ) ); ?>
	</div>
	<div id="order-hour-data" class="content card-block loading">
	</div>
</div>
<?php echo $this->get( 'orderhourBody' ); ?>
