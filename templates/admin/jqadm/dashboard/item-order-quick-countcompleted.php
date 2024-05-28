<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2024
 */

$enc = $this->encoder();


?>
<div class="quick order-quick-countcompleted col-sm-6 col-xl-3" title="<?= $enc->attr( $this->translate( 'admin', 'Completed orders within the last seven days compared to the period before' ) ) ?>">
	<div class="box row" v-bind:class="state">
		<div class="col quick-start">
			<div class="quick-number" v-html="current"></div>
		</div>
		<div class="col quick-end">
			<div class="quick-percent" v-bind:class="mood" v-html="percent"></div>
		</div>
		<div class="col-xs-12">
			<h2 class="quick-header"><?= $enc->html( $this->translate( 'admin', 'Completed orders' ) ) ?></h2>
			<div class="quick-progress"><div class="quick-length" v-bind:style="'width: ' + width +'%'"></div></div>
		</div>
	</div>
</div>
<?= $this->get( 'quickCountcompletedBody' ) ?>
