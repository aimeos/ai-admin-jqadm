<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021
 */

$enc = $this->encoder();


?>
<div class="quick order-quick-countorder col-md-6 col-xl-3">
	<div class="box">
		<h2 class="quick-header">
			<?= $enc->html( $this->translate( 'admin', 'Orders' ) ); ?>
		</h2>
		<div class="quick-body row">
			<div class="col quick-number"></div>
			<div class="col quick-difference"></div>
		</div>
		<div class="quick-progress"></div>
	</div>
</div>
<?= $this->get( 'orderQuickCountorderBody' ); ?>
