<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
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
$params = $this->get( 'pageParams', [] );
$baseItems = $this->get( 'orderBaseItems', [] );

/// price format with value (%1$s) and currency (%2$s)
$priceFormat = $this->translate( 'admin', '%1$s %2$s' );


$getTarget = $this->config( 'admin/jqadm/url/get/target' );
$getCntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$getAction = $this->config( 'admin/jqadm/url/get/action', 'get' );
$getConfig = $this->config( 'admin/jqadm/url/get/config', [] );

$newTarget = $this->config( 'admin/jqadm/url/create/target' );
$newCntl = $this->config( 'admin/jqadm/url/create/controller', 'Jqadm' );
$newAction = $this->config( 'admin/jqadm/url/create/action', 'create' );
$newConfig = $this->config( 'admin/jqadm/url/create/config', [] );

$copyTarget = $this->config( 'admin/jqadm/url/copy/target' );
$copyCntl = $this->config( 'admin/jqadm/url/copy/controller', 'Jqadm' );
$copyAction = $this->config( 'admin/jqadm/url/copy/action', 'copy' );
$copyConfig = $this->config( 'admin/jqadm/url/copy/config', [] );


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
 * @category Developer
 */
$default = ['order.id', 'order.datepayment', 'order.statuspayment', 'order.baseid'];
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
	'order.baseid' => $this->translate( 'admin', 'Basket' ),
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


?>
<div id="order" class="item-order content-block tab-pane fade" role="tabpanel" aria-labelledby="order">

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard.php' ),
			['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'orderTotal' ),
			'group' => 'uo', 'action' => 'get', 'fragment' => 'order',
			'page' =>$this->session( 'aimeos/admin/jqadm/customerorder/page', [] )]
		);
	?>

	<table class="list-items table table-striped table-hover">
		<thead class="list-header">
			<tr>
				<?= $this->partial(
					$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-standard.php' ), [
						'fields' => $fields, 'params' => $params, 'tabindex' => $this->get( 'tabindex' ),
						'data' => $columns, 'group' => 'uo', 'action' => 'get', 'fragment' => 'order',
						'sort' => $this->session( 'aimeos/admin/jqadm/customerorder/sort' ),
					] );
				?>

				<th class="actions">
					<!-- a class="btn fa act-add" tabindex="<?= $this->get( 'tabindex' ); ?>"
						href="<?= $enc->attr( $this->url( $newTarget, $newCntl, $newAction, ['resource' => 'order'] + $params, [], $newConfig ) ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ); ?>">
					</a -->

					<?= $this->partial(
						$this->config( 'admin/jqadm/partial/columns', 'common/partials/columns-standard.php' ), [
							'data' => $columns, 'fields' => $fields, 'group' => 'uo', 'tabindex' => $this->get( 'tabindex' ),
						] );
					?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?= $this->partial(
				$this->config( 'admin/jqadm/partial/listsearch', 'common/partials/listsearch-standard.php' ), [
					'fields' => $fields, 'group' => 'uo', 'tabindex' => $this->get( 'tabindex' ),
					'filter' => $this->session( 'aimeos/admin/jqadm/customerorder/filter', [] ),
					'data' => [
						'order.id' => ['op' => '==', 'type' => 'number'],
						'order.type' => ['op' => '=~'],
						'order.datepayment' => ['op' => '>=', 'type' => 'datetime-local'],
						'order.statuspayment' => ['op' => '==', 'type' => 'select', 'val' => $paymentStatusList],
						'order.datedelivery' => ['op' => '>=', 'type' => 'datetime-local'],
						'order.statusdelivery' => ['op' => '==', 'type' => 'select', 'val' => $deliveryStatusList],
						'order.relatedid' => ['op' => '=='],
						'order.ctime' => ['op' => '>=', 'type' => 'datetime-local'],
						'order.mtime' => ['op' => '>=', 'type' => 'datetime-local'],
						'order.editor' => ['op' => '=~'],
						'order.baseid' => ['op' => '=='],
					]
				] );
			?>

			<?php foreach( $this->get( 'orderItems', [] ) as $id => $item ) : ?>
				<?php $url = $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['resource' => 'order', 'id' => $item->getBaseId()] + $params, [], $getConfig ) ); ?>
				<tr class="list-item <?= $this->site()->readonly( $item->getSiteId() ); ?>">
					<?php if( in_array( 'order.id', $fields ) ) : ?>
						<td class="order-id">
							<a class="items-field" href="<?= $url; ?>" tabindex="1"><?= $enc->html( $item->getId() ); ?></a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.type', $fields ) ) : ?>
						<td class="order-type">
							<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getType() ); ?></a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.statuspayment', $fields ) ) : ?>
						<td class="order-statuspayment">
							<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $paymentStatusList[$item->getPaymentStatus()] ); ?></a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.datepayment', $fields ) ) : ?>
						<td class="order-datepayment">
							<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDatePayment() ); ?></a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.statusdelivery', $fields ) ) : ?>
						<td class="order-statusdelivery">
							<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $deliveryStatusList[$item->getDeliveryStatus()] ); ?></a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.datedelivery', $fields ) ) : ?>
						<td class="order-datedelivery">
							<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDateDelivery() ); ?></a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.relatedid', $fields ) ) : ?>
						<td class="order-relatedid">
							<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getRelatedId() ); ?></a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.ctime', $fields ) ) : ?>
						<td class="order-ctime">
							<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeCreated() ); ?></a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.mtime', $fields ) ) : ?>
						<td class="order-mtime">
							<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeModified() ); ?></a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.editor', $fields ) ) : ?>
						<td class="order-editor">
							<a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getEditor() ); ?></a>
						</td>
					<?php endif; ?>

					<?php $baseItem = ( isset( $baseItems[$item->getBaseId()] ) ? $baseItems[$item->getBaseId()] : null ); ?>

					<?php if( in_array( 'order.baseid', $fields ) ) : ?>
						<td class="order-baseid">
							<a class="items-field" href="<?= $url; ?>">
								<?= $enc->html( $item->getBaseId() ); ?>
								<?php if( $baseItem ) : ?>
									- <?= $enc->html( $baseItem->getPrice()->getValue() . '+' . $baseItem->getPrice()->getCosts() . ' ' . $baseItem->getPrice()->getCurrencyId() ); ?>
								<?php endif; ?>
							</a>
						</td>
					<?php endif; ?>

					<td class="actions">
						<a class="btn act-view fa" tabindex="<?= $this->get( 'tabindex' ); ?>" target="_blank"
							href="<?= $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['resource' => 'order', 'id' => $item->getBaseId()] + $params, [], $getConfig ) ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'View details') ); ?>"></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if( $this->get( 'orderItems', [] ) === [] ) : ?>
		<div class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ); ?></div>
	<?php endif; ?>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard.php' ),
			['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get( 'orderTotal' ),
			'group' => 'uo', 'action' => 'get', 'fragment' => 'order',
			'page' =>$this->session( 'aimeos/admin/jqadm/customerorder/page', [] )]
		);
	?>

</div>
<?= $this->get( 'orderBody' ); ?>
