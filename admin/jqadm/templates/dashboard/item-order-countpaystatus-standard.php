<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */

$enc = $this->encoder();
$trans = array(
	'-1' => $this->translate( 'mshop/code', 'pay:-1' ),
	'0' => $this->translate( 'mshop/code', 'pay:0' ),
	'1' => $this->translate( 'mshop/code', 'pay:1' ),
	'2' => $this->translate( 'mshop/code', 'pay:2' ),
	'3' => $this->translate( 'mshop/code', 'pay:3' ),
	'4' => $this->translate( 'mshop/code', 'pay:4' ),
	'5' => $this->translate( 'mshop/code', 'pay:5' ),
	'6' => $this->translate( 'mshop/code', 'pay:6' ),
);

?>
<div class="chart order-countpaystatus col-xl-6">
	<div class="box">
		<div class="header"
			data-bs-toggle="collapse" data-bs-target="#order-countpaystatus-data"
			aria-expanded="true" aria-controls="order-countpaystatus-data">
			<div class="card-tools-left">
				<div class="btn act-show fa"></div>
			</div>
			<span class="header-label">
				<?= $enc->html( $this->translate( 'admin', 'Orders by payment status' ) ); ?>
			</span>
		</div>
		<div id="order-countpaystatus-data" class="collapse show content" data-translation="<?= $enc->attr( $trans ); ?>">
			<div class="chart loading"></div>
		</div>
	</div>
</div>
<?= $this->get( 'orderpaymentstatusBody' ); ?>
