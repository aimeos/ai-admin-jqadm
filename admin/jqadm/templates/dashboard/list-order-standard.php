<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */

$enc = $this->encoder();
$currencies = array_keys( $this->get( 'orderCurrencyItems', [] ) );


?>
<div class="dashboard-order row" data-currencies="<?= $enc->attr( json_encode( $currencies ) ) ?>">
	<?= $this->get( 'orderBody' ); ?>
</div>
