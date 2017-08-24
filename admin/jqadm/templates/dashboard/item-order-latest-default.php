<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2017
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


$getTarget = $this->config( 'admin/jqadm/url/get/target' );
$getCntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$getAction = $this->config( 'admin/jqadm/url/get/action', 'get' );
$getConfig = $this->config( 'admin/jqadm/url/get/config', [] );


$enc = $this->encoder();
$params = $this->param();
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
<div class="order-latest card col-lg-12">
	<div id="order-latest-head" class="card-header header" role="tab"
		data-toggle="collapse" data-target="#order-latest-data"
		aria-expanded="true" aria-controls="order-latest-data">
		<div class="card-tools-left">
			<div class="btn btn-card-header act-show fa"></div>
		</div>
		<span class="item-label header-label">
			<?= $enc->html( $this->translate( 'admin', 'Latest orders' ) ); ?>
		</span>
	</div>
	<div id="order-latest-data" class="card-block content collapse show" role="tabpanel" aria-labelledby="order-latest-head">
		<div class="table-responsive">
			<table class="list-items table table-hover">
				<tbody>
					<?php foreach( $this->get( 'orderlatestItems', [] ) as $item ) : ?>
						<?php $url = $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['resource' => 'order', 'id' => $item->getBaseId()] + $params, [], $getConfig ) ); ?>
						<tr>
							<td class="order-id">
								<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getId() ); ?></a>
							</td>
							<td class="order-base-address-name">
								<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $name( $baskets, $item ) ); ?></a>
							</td>
							<td class="order-base-price">
								<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $price( $baskets, $item, $priceFormat ) ); ?></a>
							</td>
							<td class="order-datepayment">
								<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDatePayment() ); ?></a>
							</td>
							<td class="order-statuspayment">
								<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $status( $statuslist, $item->getPaymentStatus() ) ); ?></a>
							</td>
							<td class="order-base-service-payment">
								<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $payment( $baskets, $item ) ); ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?= $this->get( 'orderlatestBody' ); ?>
