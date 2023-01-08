<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2022-2023
 */


$enc = $this->encoder();
$params = $this->get( 'pageParams', [] );

$paymentStatusList = [
	'-1' => $this->translate( 'mshop/code', 'pay:-1' ),
	'0' => $this->translate( 'mshop/code', 'pay:0' ),
	'1' => $this->translate( 'mshop/code', 'pay:1' ),
	'2' => $this->translate( 'mshop/code', 'pay:2' ),
	'3' => $this->translate( 'mshop/code', 'pay:3' ),
	'4' => $this->translate( 'mshop/code', 'pay:4' ),
	'5' => $this->translate( 'mshop/code', 'pay:5' ),
	'6' => $this->translate( 'mshop/code', 'pay:6' ),
	'7' => $this->translate( 'mshop/code', 'pay:7' ),
];


?>
<div id="transaction" class="item-transaction tab-pane fade" role="tabpanel" aria-labelledby="transaction">

	<?php foreach( $this->item->getService( 'payment' ) as $service ) : ?>

		<div class="item-transaction-list box">
			<h2 class="item-header"><?= $enc->html( $this->translate( 'admin', 'Payment' ) ) ?>: <?= $enc->html( $service->getName() ) ?></h2>

			<?php foreach( $service->getTransactions() as $txItem ) : ?>

				<div class="list-item">
					<div class="row">
						<div class="col-sm-9">

							<div class="row">
								<div class="col-sm-4 item-column transaction-id">
									<div class="row">
										<label class="col-5 col-sm-12 form-control-label"><?= $enc->html( $this->translate( 'admin', 'ID' ) ) ?></label>
										<div class="col-7 col-sm-12"><?= $enc->html( $txItem->getId() ) ?></div>
									</div>
								</div>
								<div class="col-sm-4 item-column transaction-type">
									<div class="row">
										<label class="col-5 col-sm-12 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?></label>
										<div class="col-7 col-sm-12"><?= $enc->html( $txItem->getType() ) ?></div>
									</div>
								</div>
								<div class="col-sm-4 item-column transaction-status">
									<div class="row">
										<label class="col-5 col-sm-12 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></label>
										<div class="col-7 col-sm-12"><?= $enc->html( $paymentStatusList[$txItem->getStatus()] ?? $txItem->getStatus() ) ?></div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-4 item-column transaction-currencyid">
									<div class="row">
										<label class="col-5 col-sm-12 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Currency' ) ) ?></label>
										<div class="col-7 col-sm-12"><?= $enc->html( $txItem->getPrice()->getCurrencyId() ) ?></div>
									</div>
								</div>
								<div class="col-sm-4 item-column transaction-price">
									<div class="row">
										<label class="col-5 col-sm-12 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Price' ) ) ?></label>
										<div class="col-7 col-sm-12"><?= $enc->html( $txItem->getPrice()->getValue() ) ?></div>
									</div>
								</div>
								<div class="col-sm-4 item-column transaction-costs">
									<div class="row">
										<label class="col-5 col-sm-12 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Costs' ) ) ?></label>
										<div class="col-7 col-sm-12"><?= $enc->html( $txItem->getPrice()->getCosts() ) ?></div>
									</div>
								</div>
							</div>

						</div>
						<div class="col-sm-3">

							<div class="row">
								<div class="col-sm-12 item-column transaction-config">
									<div class="row">
										<label class="col-5 col-sm-12 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Data' ) ) ?></label>
										<div class="col-7 col-sm-12"><?= str_replace( [',', '{', '}'], '<br>', $enc->html( str_replace( [':', '"'], [': ', ''], trim( json_encode( $txItem->getConfig() ), '{}' ) ) ) ) ?></div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

			<?php endforeach ?>

			<div class="list-item">
				<div class="row">
					<div class="col-sm-3 item-column transaction-currencyid">
						<div class="row">
							<label class="col-5 col-sm-12 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Currency' ) ) ?></label>
							<div class="col-7 col-sm-12">
								<?= $enc->html( $service->getPrice()->getCurrencyId() ) ?>
							</div>
						</div>
					</div>
					<div class="col-sm-3 item-column transaction-price">
						<div class="row">
							<label class="col-5 col-sm-12 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Price' ) ) ?></label>
							<div class="col-7 col-sm-12">
								<input type="number" min="0" step="0.01" class="form-control"
									name="<?= $enc->attr( $this->formparam( array( 'transaction', $service->getId(), 'order.service.transaction.value' ) ) ) ?>">
							</div>
						</div>
					</div>
					<div class="col-sm-3 item-column transaction-costs">
						<div class="row">
							<label class="col-5 col-sm-12 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Costs' ) ) ?></label>
							<div class="col-7 col-sm-12">
								<input type="number" step="0.01" class="form-control"
									name="<?= $enc->attr( $this->formparam( array( 'transaction', $service->getId(), 'order.service.transaction.costs' ) ) ) ?>">
							</div>
						</div>
					</div>
					<div class="col-sm-3 item-column transaction-create">
						<div class="row">
							<div class="col-sm-12">
								<button class="btn btn-primary"><?= $enc->html( $this->translate( 'admin', 'Refund' ) ) ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php endforeach ?>

</div>
<?= $this->get( 'transactionIBody' ) ?>
