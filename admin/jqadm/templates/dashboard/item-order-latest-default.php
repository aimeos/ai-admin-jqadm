<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
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
$baskets = $this->get( 'orderlatestBaskets', [] );
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
<div class="order-latest card panel col-lg-12">
	<div id="order-latest-head" class="header card-header">
		<?php echo $enc->html( $this->translate( 'admin', 'Latest orders' ) ); ?>
	</div>
	<div id="order-latest-data" class="content card-block">
		<div class="table-responsive">
			<table class="list-items table table-hover">
				<tbody>
		<?php foreach( $this->get( 'orderlatestItems', [] ) as $id => $item ) : ?>
					<tr>
						<td class="order-id"><?php echo $enc->html( $item->getId() ); ?></td>
						<td class="order-base-address-name"><?php echo $enc->html( $name( $baskets, $item ) ); ?></td>
						<td class="order-base-price"><?php echo $enc->html( $price( $baskets, $item, $priceFormat ) ); ?></td>
						<td class="order-datepayment"><?php echo $enc->html( $item->getDatePayment() ); ?></td>
						<td class="order-statuspayment"><?php echo $enc->html( $status( $statuslist, $item->getPaymentStatus() ) ); ?></td>
						<td class="order-base-service-payment"><?php echo $enc->html( $payment( $baskets, $item ) ); ?></td>
					</tr>
		<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo $this->get( 'orderlatestBody' ); ?>
