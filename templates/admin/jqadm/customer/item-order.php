<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */

$price = function( \Aimeos\MShop\Order\Item\Iface $item, $priceFormat )
{
	$price = $item->getPrice();
	return sprintf( $priceFormat, $price->getValue(), $price->getCurrencyId() );
};


$name = function( \Aimeos\MShop\Order\Item\Iface $item )
{
	$addresses = $item->getAddresses();

	if( !isset( $addresses[\Aimeos\MShop\Order\Item\Address\Base::TYPE_PAYMENT] ) ) {
		return;
	}

	$address = $addresses[\Aimeos\MShop\Order\Item\Address\Base::TYPE_PAYMENT];

	if( $address->getSalutation() !== \Aimeos\MShop\Common\Item\Address\Base::SALUTATION_COMPANY ) {
		return $address->getFirstName() . ' ' . $address->getLastName();
	} else {
		return $address->getCompany();
	}
};


$payment = function( \Aimeos\MShop\Order\Item\Iface $item )
{
	$services = $item->getServices();

	if( isset( $services[\Aimeos\MShop\Order\Item\Service\Base::TYPE_PAYMENT] ) ) {
		return $services[\Aimeos\MShop\Order\Item\Service\Base::TYPE_PAYMENT]->getCode();
	}
};


$status = function( $list, $key )
{
	return ( isset( $list[$key] ) ? $list[$key] : '' );
};


$enc = $this->encoder();
$params = $this->get( 'pageParams', [] );

/// price format with value (%1$s) and currency (%2$s)
$priceFormat = $this->translate( 'admin', '%1$s %2$s' );


/** admin/jqadm/customer/order/fields
 * List of order columns that should be displayed in the customer order view
 *
 * Changes the list of order columns shown by default in the customer order view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "order.id" for the ID value.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.10
 */
$default = ['order.id', 'order.datepayment', 'order.statuspayment', 'order.currencyid', 'order.price'];
$default = $this->config( 'admin/jqadm/customer/order/fields', $default );
$fields = $this->session( 'aimeos/admin/jqadm/customerorder/fields', $default );

$columns = [
	'order.id' => $this->translate( 'admin', 'ID' ),
	'order.type' => $this->translate( 'admin', 'Type' ),
	'order.datepayment' => $this->translate( 'admin', 'Purchase' ),
	'order.statuspayment' => $this->translate( 'admin', 'Pay status' ),
	'order.datedelivery' => $this->translate( 'admin', 'Delivery' ),
	'order.statusdelivery' => $this->translate( 'admin', 'Ship status' ),
	'order.relatedid' => $this->translate( 'admin', 'Related ID' ),
	'order.customerid' => $this->translate( 'admin', 'Customer ID' ),
	'order.sitecode' => $this->translate( 'admin', 'Site' ),
	'order.languageid' => $this->translate( 'admin', 'Language' ),
	'order.currencyid' => $this->translate( 'admin', 'Currency' ),
	'order.price' => $this->translate( 'admin', 'Price' ),
	'order.costs' => $this->translate( 'admin', 'Costs' ),
	'order.rebate' => $this->translate( 'admin', 'Rebate' ),
	'order.taxvalue' => $this->translate( 'admin', 'Tax' ),
	'order.taxflag' => $this->translate( 'admin', 'Incl. tax' ),
	'order.customerref' => $this->translate( 'admin', 'Reference' ),
	'order.comment' => $this->translate( 'admin', 'Comment' ),
	'order.ctime' => $this->translate( 'admin', 'Created' ),
	'order.mtime' => $this->translate( 'admin', 'Modifed' ),
	'order.editor' => $this->translate( 'admin', 'Editor' ),
];

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

$deliveryStatusList = [
	'-1' => $this->translate( 'mshop/code', 'stat:-1' ),
	'0' => $this->translate( 'mshop/code', 'stat:0' ),
	'1' => $this->translate( 'mshop/code', 'stat:1' ),
	'2' => $this->translate( 'mshop/code', 'stat:2' ),
	'3' => $this->translate( 'mshop/code', 'stat:3' ),
	'4' => $this->translate( 'mshop/code', 'stat:4' ),
	'5' => $this->translate( 'mshop/code', 'stat:5' ),
	'6' => $this->translate( 'mshop/code', 'stat:6' ),
	'7' => $this->translate( 'mshop/code', 'stat:7' ),
];

$taxflagList = [
	0 => $this->translate( 'mshop/code', 'tax:0' ),
	1 => $this->translate( 'mshop/code', 'tax:1' ),
];


?>
<div id="order" class="item-order tab-pane fade" role="tabpanel" aria-labelledby="order">
	<div class="box">
		<?= $this->partial(
				$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
				['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'orderTotal' ),
				'group' => 'uo', 'action' => 'get', 'fragment' => 'order',
				'page' =>$this->session( 'aimeos/admin/jqadm/customerorder/page', [] )]
			);
		?>

		<div class="table-responsive">
			<table class="list-items table table-striped">
				<thead class="list-header">
					<tr>
						<?= $this->partial(
							$this->config( 'admin/jqadm/partial/listhead', 'listhead' ), [
								'fields' => $fields, 'params' => $params, 'tabindex' => $this->get( 'tabindex' ),
								'data' => $columns, 'group' => 'uo', 'action' => ( $this->param( 'id' ) ? 'get' : 'search' ),
								'fragment' => 'order', 'sort' => $this->session( 'aimeos/admin/jqadm/customerorder/sort' ),
							] );
						?>

						<th class="actions">
							<?= $this->partial(
								$this->config( 'admin/jqadm/partial/columns', 'columns' ), [
									'data' => $columns, 'fields' => $fields, 'group' => 'uo', 'tabindex' => $this->get( 'tabindex' ),
								] );
							?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listsearch', 'listsearch' ), [
							'fields' => $fields, 'group' => 'uo', 'tabindex' => $this->get( 'tabindex' ),
							'filter' => $this->session( 'aimeos/admin/jqadm/customerorder/filter', [] ),
							'data' => [
								'order.id' => ['op' => '==', 'type' => 'number'],
								'order.type' => ['op' => '=~'],
								'order.datepayment' => ['op' => '-', 'type' => 'datetime-local'],
								'order.statuspayment' => ['op' => '==', 'type' => 'select', 'val' => $paymentStatusList],
								'order.datedelivery' => ['op' => '-', 'type' => 'datetime-local'],
								'order.statusdelivery' => ['op' => '==', 'type' => 'select', 'val' => $deliveryStatusList],
								'order.relatedid' => ['op' => '=='],
								'order.customerid' => ['op' => '=='],
								'order.sitecode' => ['op' => '=~'],
								'order.languageid' => ['op' => '=='],
								'order.currencyid' => ['op' => '=='],
								'order.price' => ['op' => '=='],
								'order.costs' => ['op' => '=='],
								'order.rebate' => ['op' => '=='],
								'order.taxvalue' => ['op' => '=='],
								'order.taxflag' => ['op' => '==', 'type' => 'select', 'val' => $taxflagList],
								'order.customerref' => ['op' => '=~'],
								'order.comment' => ['op' => '~='],
								'order.ctime' => ['op' => '-', 'type' => 'datetime-local'],
								'order.mtime' => ['op' => '-', 'type' => 'datetime-local'],
								'order.editor' => ['op' => '=~'],
							]
						] );
					?>

					<?php foreach( $this->get( 'orderItems', [] ) as $id => $item ) : ?>
						<?php $url = $enc->attr( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => $item->getId()] + $params ) ) ?>
						<tr class="list-item <?= $this->site()->readonly( $item->getSiteId() ) ?>">
							<?php if( in_array( 'order.id', $fields ) ) : ?>
								<td class="order-id">
									<a class="items-field" href="<?= $url ?>" tabindex="1"><?= $enc->html( $item->getId() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.type', $fields ) ) : ?>
								<td class="order-type">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getType() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.datepayment', $fields ) ) : ?>
								<td class="order-datepayment">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getDatePayment() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.statuspayment', $fields ) ) : ?>
								<td class="order-statuspayment">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $paymentStatusList[$item->getStatusPayment()] ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.datedelivery', $fields ) ) : ?>
								<td class="order-datedelivery">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getDateDelivery() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.statusdelivery', $fields ) ) : ?>
								<td class="order-statusdelivery">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $deliveryStatusList[$item->getStatusDelivery()] ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.relatedid', $fields ) ) : ?>
								<td class="order-relatedid">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getRelatedId() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.customerid', $fields ) ) : ?>
								<td class="order-customerid">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getCustomerId() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.sitecode', $fields ) ) : ?>
								<td class="order-sitecode">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getSiteCode() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.languageid', $fields ) ) : ?>
								<td class="order-languageid">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getLanguageId() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.currencyid', $fields ) ) : ?>
								<td class="order-currencyid">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getPrice()->getCurrencyId() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.price', $fields ) ) : ?>
								<td class="order-price">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getPrice()->getValue() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.costs', $fields ) ) : ?>
								<td class="order-costs">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getPrice()->getCosts() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.rebate', $fields ) ) : ?>
								<td class="order-rebate">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getPrice()->getRebate() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.taxvalue', $fields ) ) : ?>
								<td class="order-taxvalue">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getPrice()->getTaxValue() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.taxflag', $fields ) ) : ?>
								<td class="order-taxflag">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $taxflagList[$item->getPrice()->getTaxFlag()] ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.customerref', $fields ) ) : ?>
								<td class="order-customerref">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getPrice()->getCustomerReference() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.comment', $fields ) ) : ?>
								<td class="order-comment">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getPrice()->getComment() ) ?></a>
								</td>
							<?php endif ?>

							<?php if( in_array( 'order.ctime', $fields ) ) : ?>
								<td class="order-ctime">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeCreated() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.mtime', $fields ) ) : ?>
								<td class="order-mtime">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeModified() ) ?></a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.editor', $fields ) ) : ?>
								<td class="order-editor">
									<a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->editor() ) ?></a>
								</td>
							<?php endif ?>

							<td class="actions">
								<a class="btn act-view fa" tabindex="<?= $this->get( 'tabindex' ) ?>" target="_blank"
									href="<?= $enc->attr( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => $item->getId()] + $params ) ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'View details' ) ) ?>"></a>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>

		<?php if( $this->get( 'orderItems', map() )->isEmpty() ) : ?>
			<div class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ) ?></div>
		<?php endif ?>

		<?= $this->partial(
				$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
				['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get( 'orderTotal' ),
				'group' => 'uo', 'action' => 'get', 'fragment' => 'order',
				'page' =>$this->session( 'aimeos/admin/jqadm/customerorder/page', [] )]
			);
		?>

	</div>
</div>
<?= $this->get( 'orderBody' ) ?>
