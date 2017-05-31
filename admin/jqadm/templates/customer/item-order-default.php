<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$price = function( array $orders, \Aimeos\MShop\Order\Item\Iface $item, $priceFormat )
{
	if( isset( $orders[$item->getBaseId()] ) )
	{
		$price = $orders[$item->getBaseId()]->getPrice();
		return sprintf( $priceFormat, $price->getValue(), $price->getCurrencyId() );
	}
};


$name = function( array $orders, \Aimeos\MShop\Order\Item\Iface $item )
{
	if( isset( $orders[$item->getBaseId()] ) )
	{
		$addresses = $orders[$item->getBaseId()]->getAddresses();

		if( !isset( $addresses[\Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT] ) ) {
			return;
		}

		$address = $addresses[\Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT];

		if( $address->getSalutation() !== \Aimeos\MShop\Common\Item\Address\Base::SALUTATION_COMPANY ) {
			return $address->getFirstName() . ' ' . $address->getLastName();
		} else {
			return $address->getCompany();
		}
	}
};


$payment = function( array $orders, \Aimeos\MShop\Order\Item\Iface $item )
{
	if( isset( $orders[$item->getBaseId()] ) )
	{
		$services = $orders[$item->getBaseId()]->getServices();

		if( isset( $services[\Aimeos\MShop\Order\Item\Base\Service\Base::TYPE_PAYMENT] ) ) {
			return $services[\Aimeos\MShop\Order\Item\Base\Service\Base::TYPE_PAYMENT]->getCode();
		}
	}
};


$status = function( $list, $key )
{
	return ( isset( $list[$key] ) ? $list[$key] : '' );
};


$enc = $this->encoder();
$baskets = $this->get( 'orderBaskets', [] );

/// price format with value (%1$s) and currency (%2$s)
$priceFormat = $this->translate( 'admin', '%1$s %2$s' );

$statuslist = array(
	Aimeos\MShop\Order\Item\Base::PAY_UNFINISHED => $this->translate( 'admin', 'pay:unfinished' ),
	Aimeos\MShop\Order\Item\Base::PAY_DELETED => $this->translate( 'admin', 'pay:deleted' ),
	Aimeos\MShop\Order\Item\Base::PAY_CANCELED => $this->translate( 'admin', 'pay:canceled' ),
	Aimeos\MShop\Order\Item\Base::PAY_REFUSED => $this->translate( 'admin', 'pay:refused' ),
	Aimeos\MShop\Order\Item\Base::PAY_REFUND => $this->translate( 'admin', 'pay:refund' ),
	Aimeos\MShop\Order\Item\Base::PAY_PENDING => $this->translate( 'admin', 'pay:pending' ),
	Aimeos\MShop\Order\Item\Base::PAY_AUTHORIZED => $this->translate( 'admin', 'pay:authorized' ),
	Aimeos\MShop\Order\Item\Base::PAY_RECEIVED => $this->translate( 'admin', 'pay:received' ),
);


?>
<div id="order" class="item-order content-block tab-pane fade" role="tabpanel" aria-labelledby="order">
	<div class="col-xl-6 content-block">
		<div class="table-responsive">
			<table class="list-items table table-hover">
				<thead class="list-header">
					<th class="order-id">
						<?= $enc->html( $this->translate( 'admin', 'ID' ) ); ?>
					</th>
					<th class="order-base-address-name">
						<?= $enc->html( $this->translate( 'admin', 'Name' ) ); ?>
					</th>
					<th class="order-base-price">
						<?= $enc->html( $this->translate( 'admin', 'Price' ) ); ?>
					</th>
					<th class="order-datepayment">
						<?= $enc->html( $this->translate( 'admin', 'Purchased' ) ); ?>
					</th>
					<th class="order-statuspayment">
						<?= $enc->html( $this->translate( 'admin', 'Payment status' ) ); ?>
					</th>
					<th class="order-base-service-payment">
						<?= $enc->html( $this->translate( 'admin', 'Payment type' ) ); ?>
					</th>
				</thead>
				<tbody>
					<?php foreach( $this->get( 'orderItems', [] ) as $id => $item ) : ?>
						<tr>
							<td class="order-id"><?= $enc->html( $item->getId() ); ?></td>
							<td class="order-base-address-name"><?= $enc->html( $name( $baskets, $item ) ); ?></td>
							<td class="order-base-price"><?= $enc->html( $price( $baskets, $item, $priceFormat ) ); ?></td>
							<td class="order-datepayment"><?= $enc->html( $item->getDatePayment() ); ?></td>
							<td class="order-statuspayment"><?= $enc->html( $status( $statuslist, $item->getPaymentStatus() ) ); ?></td>
							<td class="order-base-service-payment"><?= $enc->html( $payment( $baskets, $item ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?= $this->get( 'orderBody' ); ?>
