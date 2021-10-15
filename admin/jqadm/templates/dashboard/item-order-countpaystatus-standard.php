<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */

$enc = $this->encoder();
$trans = array(
	null => '',
	'-1' => $this->translate( 'mshop/code', 'pay:-1' ),
	'0' => $this->translate( 'mshop/code', 'pay:0' ),
	'1' => $this->translate( 'mshop/code', 'pay:1' ),
	'2' => $this->translate( 'mshop/code', 'pay:2' ),
	'3' => $this->translate( 'mshop/code', 'pay:3' ),
	'4' => $this->translate( 'mshop/code', 'pay:4' ),
	'5' => $this->translate( 'mshop/code', 'pay:5' ),
	'6' => $this->translate( 'mshop/code', 'pay:6' ),
	'7' => $this->translate( 'mshop/code', 'pay:7' ),
);

?>
<div class="chart bar order-countpaystatus col-xl-6" data-labels="<?= $enc->attr( $trans ) ?>">
	<div class="box">
		<div class="header"
			data-bs-toggle="collapse" data-bs-target="#order-countpaystatus-data"
			aria-expanded="true" aria-controls="order-countpaystatus-data">
			<div class="card-tools-start">
				<div class="btn act-show fa"></div>
			</div>
			<h2 class="header-label">
				<?= $enc->html( $this->translate( 'admin', 'Orders by payment status' ) ) ?>
			</h2>
		</div>
		<div id="order-salesday-data" class="collapse show content loading">
			<div class="chart-legend"></div>
			<div class="chart"><canvas></canvas></div>
		</div>
	</div>
</div>
<?= $this->get( 'orderpaymentstatusBody' ) ?>
