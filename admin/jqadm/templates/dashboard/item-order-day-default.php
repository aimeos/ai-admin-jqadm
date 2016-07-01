<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */

$enc = $this->encoder();

?>

<div class="order-day card panel col-lg-12">
	<div id="order-day-head" class="header card-header">
		<?php echo $enc->html( $this->translate( 'admin', 'Orders by day' ) ); ?>
	</div>
	<div id="order-day-data" class="content card-block loading">
	</div>
</div>
<?php echo $this->get( 'orderdayBody' ); ?>
