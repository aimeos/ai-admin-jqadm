<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2022
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

	<?php foreach( $this->item->getService( 'payment') as $service ) : ?>

		<div class="box">
			<h2 class="item-header"><?= $enc->html( $service->getName() ) ?></h2>

			<div class="table-responsive">
				<table class="list-items table table-striped">
					<thead class="list-header">
						<tr>
							<th class="transaction-id"><?= $enc->html( $this->translate( 'admin', 'ID' ) ) ?></th>
							<th class="transaction-type"><?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?></th>
							<th class="transaction-currencyid"><?= $enc->html( $this->translate( 'admin', 'Currency' ) ) ?></th>
							<th class="transaction-price"><?= $enc->html( $this->translate( 'admin', 'Price' ) ) ?></th>
							<th class="transaction-costs"><?= $enc->html( $this->translate( 'admin', 'Costs' ) ) ?></th>
							<th class="transaction-status"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></th>
							<th class="transaction-data"><?= $enc->html( $this->translate( 'admin', 'Data' ) ) ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach( $service->getTransactions() as $txItem ) : ?>
							<tr>
								<td class="transaction-id"><?= $enc->html( $txItem->getId() ) ?></td>
								<td class="transaction-type"><?= $enc->html( $txItem->getType() ) ?></td>
								<td class="transaction-currencyid"><?= $enc->html( $txItem->getPrice()->getCurrencyId() ) ?></td>
								<td class="transaction-price"><?= $enc->html( $txItem->getPrice()->getValue() ) ?></td>
								<td class="transaction-costs"><?= $enc->html( $txItem->getPrice()->getCosts() ) ?></td>
								<td class="transaction-status"><?= $enc->html( $paymentStatusList[$txItem->getStatus()] ?? $txItem->getStatus() ) ?></td>
								<td class="transaction-data"><?= str_replace( [',', '{', '}'], '<br>', $enc->html( str_replace( [':', '"'], [': ', ''], trim( json_encode( $txItem->getConfig() ), '{}' ) ) ) ) ?></td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>

	<?php endforeach ?>

</div>
<?= $this->get( 'transactionIBody' ) ?>
