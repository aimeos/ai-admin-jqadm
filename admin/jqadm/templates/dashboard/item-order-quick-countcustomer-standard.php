<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021
 */

$enc = $this->encoder();


?>
<div class="quick order-quick-countcustomer col-sm-6 col-xl-3"
	title="<?= $enc->attr( $this->translate( 'admin', 'New customers within the last seven days compared to the period before' ) ) ?>">
	<dashboard-order-quick-countcustomer inline-template>
		<div class="box row" v-bind:class="state">
			<div class="col quick-start">
				<div class="quick-number" v-html="current">0</div>
			</div>
			<div class="col quick-end">
				<div class="quick-percent" v-bind:class="mood" v-html="percent"></div>
			</div>
			<div class="col-xs-12">
				<h2 class="quick-header"><?= $enc->html( $this->translate( 'admin', 'New customers' ) ) ?></h2>
				<div class="quick-progress"><div class="quick-length" v-bind:style="'width: ' + width +'%'"></div></div>
			</div>
		</div>
	</dashboard-order-quick-countcustomer>
</div>
<?= $this->get( 'quickCountcustomerBody' ) ?>
