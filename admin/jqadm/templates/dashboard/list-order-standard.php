<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2020
 */

$enc = $this->encoder();
$currencies = $this->get( 'orderCurrencyItems', map() )->keys()->toArray();


?>
<div class="dashboard-order row" data-currencies="<?= $enc->attr( $currencies ) ?>">
	<?= $this->get( 'orderBody' ); ?>
</div>
