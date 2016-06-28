<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */

$price = function( array $orders, \Aimeos\MShop\Order\Item\Iface $item, $priceFormat )
{
	if( isset( $orders[$item->getBaseId()] ) )
	{
		$price = $orders[$item->getBaseId()]->getPrice();
		return sprintf( $priceFormat, $price->getValue(), $price->getCurrencyId() );
	}
};

$status = function( $list, $key )
{
	return ( isset( $list[$key] ) ? $list[$key] : '' );
};


$enc = $this->encoder();
$orders = $this->get( 'orderlatestOrders', array() );
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
<div class="order-latest card panel">
	<div id="order-latest-head" class="header card-header">
		<?php echo $enc->html( $this->translate( 'admin', 'Latest orders' ) ); ?>
	</div>
	<div id="order-latest-data" class="content card-block">
		<div class="table-responsive">
			<table class="list-items table table-hover">
				<thead class="header">
					<tr>
						<th class="order.id"><?php echo $enc->html( $this->translate( 'admin', 'ID' ) ); ?></th>
						<th class="order.type"><?php echo $enc->html( $this->translate( 'admin', 'Type' ) ); ?></th>
						<th class="order.datepayment"><?php echo $enc->html( $this->translate( 'admin', 'Payment date' ) ); ?></th>
						<th class="order.statuspayment"><?php echo $enc->html( $this->translate( 'admin', 'Payment status' ) ); ?></th>
						<th class="order.base.price"><?php echo $enc->html( $this->translate( 'admin', 'Price' ) ); ?></th>
					</tr>
				</thead>
				<tbody>
		<?php foreach( $this->get( 'orderlatestItems', array() ) as $id => $item ) : ?>
					<tr>
						<td class="order.id"><?php echo $enc->html( $item->getId() ); ?></td>
						<td class="order.type"><?php echo $enc->html( $item->getType() ); ?></td>
						<td class="order.datepayment"><?php echo $enc->html( $item->getDatePayment() ); ?></td>
						<td class="order.statuspayment"><?php echo $enc->html( $status( $statuslist, $item->getPaymentStatus() ) ); ?></td>
						<td class="order.base.price"><?php echo $enc->html( $price( $orders, $item, $priceFormat ) ); ?></td>
					</tr>
		<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo $this->get( 'orderlatestBody' ); ?>
