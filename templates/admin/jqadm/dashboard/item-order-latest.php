<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 */

$price = function( \Aimeos\MShop\Order\Item\Iface $item, $priceFormat )
{
	$price = 0;

	foreach( $item->getProducts() as $product )
	{
		if( strncmp( $this->site()->siteid(), $product->getSiteId(), strlen( $this->site()->siteid() ) ) === 0 ) {
			$price += $product->getPrice()->getValue() * $product->getQuantity();
		}
	}

	return sprintf( $priceFormat, $price, $item->getPrice()->getCurrencyId() );
};


$name = function( \Aimeos\MShop\Order\Item\Iface $item )
{
	$type = \Aimeos\MShop\Order\Item\Address\Base::TYPE_PAYMENT;

	if( ( $address = current( $item->getAddress( $type ) ) ) === false ) {
		return;
	}

	return $address->getCompany() ?: $address->getFirstName() . ' ' . $address->getLastName();
};


$payment = function( \Aimeos\MShop\Order\Item\Iface $item )
{
	$codes = [];
	$type = \Aimeos\MShop\Order\Item\Service\Base::TYPE_PAYMENT;

	foreach( $item->getService( $type ) as $service ) {
		$codes[] = $service->getCode();
	}

	return implode( ', ', $codes );
};


$status = function( array $list, $key )
{
	return ( isset( $list[$key] ) ? $list[$key] : '' );
};


$enc = $this->encoder();
$params = $this->param();
/// price format with value (%1$s) and currency (%2$s)
$priceFormat = $this->translate( 'admin', '%1$s %2$s' );

$statuslist = array(
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
<?php if( !$this->get( 'orderlatestItems', map() )->isEmpty() ) : ?>
	<div class="order-latest col-xl-12">
		<div class="box">
			<div class="header"
				data-bs-toggle="collapse" data-bs-target="#order-latest-data"
				aria-expanded="true" aria-controls="order-latest-data">
				<div class="card-tools-start">
					<div class="btn act-show fa"></div>
				</div>
				<h2 class="header-label">
					<?= $enc->html( $this->translate( 'admin', 'Latest orders' ) ) ?>
				</h2>
			</div>
			<div id="order-latest-data" class="content collapse show">
				<div class="table-responsive">
					<table class="list-items table table-hover">
						<tbody>
							<?php foreach( $this->get( 'orderlatestItems', [] ) as $item ) : ?>
								<?php $url = $enc->attr( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => $item->getId()] + $params ) ) ?>
								<tr>
									<td class="order-id">
										<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getId() ) ?></a>
									</td>
									<td class="order-address-name">
										<a class="items-field" href="<?= $url ?>"><?= $enc->html( $name( $item ) ) ?></a>
									</td>
									<td class="order-product-price">
										<a class="items-field" href="<?= $url ?>"><?= $enc->html( $price( $item, $priceFormat ) ) ?></a>
									</td>
									<td class="order-datepayment">
										<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getDatePayment() ) ?></a>
									</td>
									<td class="order-statuspayment">
										<a class="items-field" href="<?= $url ?>"><?= $enc->html( $status( $statuslist, $item->getStatusPayment() ) ) ?></a>
									</td>
									<td class="order-service-payment">
										<a class="items-field" href="<?= $url ?>"><?= $enc->html( $payment( $item ) ) ?></a>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?= $this->get( 'orderlatestBody' ) ?>
<?php endif ?>
