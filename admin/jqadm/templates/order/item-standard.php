<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$sortItems = function( array $items )
{
	$result = [];

	if( isset( $items['payment'] ) ) {
		$result['payment'] = $items['payment']; unset( $items['payment'] );
	}

	if( isset( $items['delivery'] ) ) {
		$result['delivery'] = $items['delivery']; unset( $items['delivery'] );
	}

	return $result + $items;
};


$enc = $this->encoder();


$target = $this->config( 'admin/jqadm/url/save/target' );
$cntl = $this->config( 'admin/jqadm/url/save/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/save/action', 'save' );
$config = $this->config( 'admin/jqadm/url/save/config', [] );

$getTarget = $this->config( 'admin/jqadm/url/get/target' );
$getCntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$getAction = $this->config( 'admin/jqadm/url/get/action', 'get' );
$getConfig = $this->config( 'admin/jqadm/url/get/config', [] );

$listTarget = $this->config( 'admin/jqadm/url/search/target' );
$listCntl = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$listAction = $this->config( 'admin/jqadm/url/search/action', 'search' );
$listConfig = $this->config( 'admin/jqadm/url/search/config', [] );

$newTarget = $this->config( 'admin/jqadm/url/create/target' );
$newCntl = $this->config( 'admin/jqadm/url/create/controller', 'Jqadm' );
$newAction = $this->config( 'admin/jqadm/url/create/action', 'create' );
$newConfig = $this->config( 'admin/jqadm/url/create/config', [] );


$searchParams = $params = $this->get( 'pageParams', [] );
unset( $searchParams['id'] );

/// Price format with price value (%1$s) and currency (%2$s)
$priceFormat = $this->translate( 'client/code', '%1$s %2$s' );

$serviceAttrCodes = [
	/** admin/jqadm/order/service/delivery/attribute/suggest
	 * List of suggested configuration keys for delivery service attributes in orders
	 *
	 * Service attributes in orders can store arbitrary key value pairs. This
	 * setting gives editors a hint which config keys are available and are used
	 * in the templates.
	 *
	 * @param string List of suggested config keys
	 * @since 2017.10
	 * @category Developer
	 * @see admin/jqadm/order/service/payment/attribute/suggest
	 */
	'delivery' => $this->config( 'admin/jqadm/order/service/delivery/attribute/suggest', ['trackingid'] ),

	/** admin/jqadm/order/service/payment/attribute/suggest
	 * List of suggested configuration keys for payment service attributes in orders
	 *
	 * Service attributes in orders can store arbitrary key value pairs. This
	 * setting gives editors a hint which config keys are available and are used
	 * in the templates.
	 *
	 * @param string List of suggested config keys
	 * @since 2017.10
	 * @category Developer
	 * @see admin/jqadm/order/service/delivery/attribute/suggest
	 */
	'payment' => $this->config( 'admin/jqadm/order/service/payment/attribute/suggest', [] ),
];

$statusList = [
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
<?php $this->block()->start( 'jqadm_content' ); ?>

<?php if( isset( $this->item ) ) : ?>
	<?php $basket = $this->item; $currency = $this->translate( 'currency', $basket->getPrice()->getCurrencyId() ); ?>

	<form class="item item-order form-horizontal" method="POST" enctype="multipart/form-data" action="<?= $enc->attr( $this->url( $target, $cntl, $action, $params, [], $config ) ); ?>">
		<input id="item-baseid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'order.base.id' ) ) ); ?>" value="<?= $enc->attr( $basket->getId() ); ?>" />
		<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ); ?>" value="get" />
		<?= $this->csrf()->formfield(); ?>

		<nav class="main-navbar">
			<span class="navbar-brand">
				<?= $enc->html( $this->translate( 'admin', 'Order' ) ); ?>:
				<?= $enc->html( $basket->getId() ); ?> -
				<?= $enc->html( $this->number( $basket->getPrice()->getValue() ) ); ?>
				<?= $enc->html( $basket->getPrice()->getCurrencyId() ); ?>
				<span class="navbar-secondary">(<?= $enc->html( $this->site()->match( $basket->getLocale()->getSiteId() ) ); ?>)</span>
			</span>
			<div class="item-actions">
				<a class="btn btn-secondary act-cancel"
					title="<?= $enc->attr( $this->translate( 'admin', 'Cancel and return to list') ); ?>"
					href="<?= $enc->attr( $this->url( $listTarget, $listCntl, $listAction, $searchParams, [], $listConfig ) ); ?>">
					<?= $enc->html( $this->translate( 'admin', 'Cancel' ) ); ?>
				</a>

				<div class="btn-group">
					<button type="submit" class="btn btn-primary act-save"
						title="<?= $enc->attr( $this->translate( 'admin', 'Save entry (Ctrl+S)') ); ?>">
						<?= $enc->html( $this->translate( 'admin', 'Save' ) ); ?>
					</button>
					<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"
						aria-haspopup="true" aria-expanded="false">
						<span class="sr-only"><?= $enc->html( $this->translate( 'admin', 'Toggle dropdown' ) ); ?></span>
					</button>
					<ul class="dropdown-menu dropdown-menu-right">
						<li class="dropdown-item"><a class="next-action" href="#" data-next="search"><?= $enc->html( $this->translate( 'admin', 'Save & Close' ) ); ?></a></li>
					</ul>
				</div>
			</div>
		</nav>

		<div class="row item-container">

			<div class="col-md-3 item-navbar">
				<ul class="nav nav-tabs flex-md-column flex-wrap d-flex justify-content-between" role="tablist">

					<li class="nav-item order">
						<a class="nav-link active" href="#order" data-toggle="tab" role="tab" aria-expanded="true" aria-controls="order">
							<?= $enc->html( $this->translate( 'admin', 'Order' ) ); ?>
						</a>
					</li>

					<?php foreach( array_values( $this->get( 'itemSubparts', [] ) ) as $type => $subpart ) : ?>
						<li class="nav-item <?= $enc->attr( $subpart ); ?>">
							<a class="nav-link" href="#<?= $enc->attr( $subpart ); ?>" data-toggle="tab" role="tab" tabindex="<?= ++$type+1; ?>">
								<?= $enc->html( $this->translate( 'admin', $subpart ) ); ?>
							</a>
						</li>
					<?php endforeach; ?>

				</ul>

				<div class="item-meta text-muted">
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Modified' ) ); ?>:
						<span class="meta-value"><?= $enc->html( $basket->getTimeModified() ); ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Created' ) ); ?>:
						<span class="meta-value"><?= $enc->html( $basket->getTimeCreated() ); ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Editor' ) ); ?>:
						<span class="meta-value"><?= $enc->html( $basket->getEditor() ); ?></span>
					</small>
				</div>
			</div>

			<div class="col-md-9 item-content tab-content">

				<div id="order" class="item-order tab-pane fade show active" role="tabpanel" aria-labelledby="order">

					<div class="row item-base">
						<div class="col-xl-6 content-block <?= $this->site()->readonly( $basket->getSiteId() ); ?>">
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></label>
								<div class="col-sm-8">
									<select class="form-control custom-select item-languageid" required="required" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'order.base.languageid' ) ) ); ?>"
										<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> >
										<option value="">
											<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
										</option>

										<?php foreach( $this->get( 'pageLangItems', [] ) as $langId => $langItem ) : ?>
											<option value="<?= $enc->attr( $langId ); ?>" <?= $selected( $basket->getLocale()->getLanguageId(), $langId ); ?> >
												<?= $enc->html( $this->translate( 'language', $langId ) ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Site' ) ); ?></label>
								<div class="col-8">
									<span class="form-control item-sitecode"><?= $enc->html( $basket->getSiteCode() ); ?></span>
								</div>
								<div class="col-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Site the order was placed at' ) ); ?>
								</div>
							</div>
						</div>

						<div class="col-xl-6 content-block <?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?>">
							<div class="form-group row">
								<label class="col-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Customer' ) ); ?></label>
								<div class="col-8">
									<select class="combobox item-customer" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'order.base.customerid' ) ) ); ?>"
										<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										<option value="<?= $enc->attr( $this->get( 'itemData/order.base.customerid' ) ); ?>" >
											<?= $enc->html( $this->get( 'itemData/customer.code' ) ); ?>
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Customer ID' ) ); ?></label>
								<div class="col-8">
									<span class="form-control item-customerid">
										<?php if( $basket->getCustomerId() ) : ?>
											<a class="act-view" target="_blank"
												href="<?= $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['resource' => 'customer', 'id' => $basket->getCustomerId()], [], $getConfig ) ); ?>">
												<?= $enc->attr( $basket->getCustomerId() ); ?>
											</a>
										<?php endif; ?>
									</span>
								</div>
							</div>
						</div>
					</div>


					<div class="row item-product">

						<div class="col-sm-12 content-block">
							<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Products' ) ); ?></h2>
							<table class="item-product-list table table-striped">
								<thead>
									<tr>
										<th class="item-column column-subscription"><?= $enc->html( $this->translate( 'admin', 'Renew' ) ); ?></th>
										<th class="item-column column-status"><?= $enc->html( $this->translate( 'admin', 'Status' ) ); ?></th>
										<th class="item-column column-desc"><?= $enc->html( $this->translate( 'admin', 'Name' ) ); ?></th>
										<th class="item-column column-quantity"><?= $enc->html( $this->translate( 'admin', 'Quantity' ) ); ?></th>
										<th class="item-column column-price"><?= $enc->html( $this->translate( 'admin', 'Price' ) ); ?></th>
										<th class="item-column column-sum"><?= $enc->html( $this->translate( 'admin', 'Sum' ) ); ?></th>
									</tr>
								</thead>
								<tbody>

									<?php foreach( (array) $basket->getProducts() as $pos => $orderProduct ) : ?>
										<tr class="list-item">
											<td class="item-column column-subscription">
												<?php $newParams = [
														'item' => ['subscription.ordbaseid' => $basket->getId(), 'subscription.ordprodid' => $orderProduct->getId()],
														'site' => $this->param( 'site' ),
														'lang' => $this->param( 'lang' ),
														'resource' => 'subscription'
													];
												?>
												<a class="btn btn-subscription fa" href="<?= $this->url( $newTarget, $newCntl, $newAction, $newParams, [], $newConfig ) ?>"></a>
											</td>
											<td class="item-column column-status">
												<select class="form-control custom-select product-status" required="required" tabindex="1"
													name="<?= $enc->attr( $this->formparam( array( 'item', 'product', $pos, 'order.base.product.status' ) ) ); ?>"
													<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> >
													<option value="">
														<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
													</option>
													<?php foreach( $statusList as $code => $label ) : ?>
														<option value="<?= $code ?>" <?= $selected( $this->get( 'itemData/product/' . $pos . '/order.base.product.status' ), $code ); ?> >
															<?= $enc->html( $label ); ?>
														</option>
													<?php endforeach; ?>
												</select>
											</td>
											<td class="item-column column-desc">
												<span class="product-name"><?= $enc->html( $orderProduct->getName() ); ?></span>
												<span class="product-attr">
													<?php foreach( $orderProduct->getAttributes() as $attrItem ) : ?>
														<span class="attr-code"><?= $enc->html( $attrItem->getCode() ); ?></span>
														<span class="attr-value">
															<?php if( $attrItem->getQuantity() > 1 ) : ?>
																<?= $enc->html( $attrItem->getQuantity() ); ?>×
															<?php endif; ?>
															<?= $enc->html( $attrItem->getValue() ); ?>
														</span>
													<?php endforeach; ?>
												</span>
												<span class="product-sku"><?= $enc->html( $orderProduct->getProductCode() ); ?></span>
											</td>
											<td class="item-column column-quantity">
												<span class="product-quantity"><?= $enc->html( $orderProduct->getQuantity() ); ?></span>
											</td>
											<td class="item-column column-price">
												<span class="product-price"><?= $enc->html( sprintf( $priceFormat, $orderProduct->getPrice()->getValue(), $currency ) ); ?></span>
												<span class="product-costs"><?= $enc->html( sprintf( $priceFormat, $orderProduct->getPrice()->getCosts(), $currency ) ); ?></span>
												<span class="product-rebate"><?= $enc->html( sprintf( $priceFormat, $orderProduct->getPrice()->getRebate(), $currency ) ); ?></span>
											</td>
											<td class="item-column column-sum">
												<span class="product-price"><?= $enc->html( sprintf( $priceFormat, $this->number( $orderProduct->getPrice()->getValue() * $orderProduct->getQuantity() ), $currency ) ); ?></span>
												<span class="product-costs"><?= $enc->html( sprintf( $priceFormat, $this->number( $orderProduct->getPrice()->getCosts() * $orderProduct->getQuantity() ), $currency ) ); ?></span>
												<span class="product-rebate"><?= $enc->html( sprintf( $priceFormat, $this->number( $orderProduct->getPrice()->getRebate() * $orderProduct->getQuantity() ), $currency ) ); ?></span>
											</td>
										</tr>
									<?php endforeach; ?>

								</tbody>
							</table>
						</div>

					</div>


					<div class="row item-misc">

						<div class="col-xl-6 content-block">
							<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Coupon' ) ); ?></h2>
							<?php if( $basket->getCoupons() !== [] ) : ?>
								<?php foreach( $basket->getCoupons() as $code => $product ) : ?>
									<div class="form-group row">
										<label class="col-sm-4"><?= $enc->html( $this->translate( 'admin', 'Code' ) ); ?></label>
										<div class="col-sm-8"><span class="item-coupon"><?= $enc->attr( $code ); ?></span></div>
									</div>
								<?php endforeach; ?>
							<?php else : ?>
								<?= $enc->html( $this->translate( 'admin', 'No voucher' ) ); ?>
							<?php endif; ?>
						</div>

						<div class="col-xl-6 content-block">
							<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Comment' ) ); ?></h2>
							<div class="form-group optional">
								<textarea class="form-control item-title" type="text" tabindex="1" rows="3"
									name="<?= $enc->attr( $this->formparam( array( 'item', 'order.base.comment' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Customer comment (optional)' ) ); ?>"
									<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?>
								><?= $enc->html( $basket->getComment() ); ?></textarea>
							</div>
						</div>

					</div>


					<div class="row">
						<?php foreach( $sortItems( $basket->getAddresses() ) as $type => $addressItem ) : $code = 'address:' . $type; ?>

							<div class="col-xl-6 content-block item-address">
								<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin/ext', $code ) ); ?></h2>

								<div class="address-short">
									<?php
										/// short order address with company (%1$s), first name (%2$s), last name (%3$s), street (%4$s), house number (%5$s),
										/// zip code (%6$s), city (%7$s),state (%8$s), countryid (%9$s), e-mail (%10$s), telephone (%11$s), VAT ID  (%12$s)
										$addrFormat = $this->translate( 'admin', "%1\$s\n%2\$s %3\$s\n%5\$s %4\$s\n%7\$s, %6\$s\n%8\$s, %9\$s\n%10\$s\n%11\$s\n%12\$s" );
									?>
									<span class="address-text" data-format="<?= $enc->attr( $addrFormat ); ?>"><!-- inserted by order.js --></span>
									<span class="address-edit"></span>
								</div>

								<fieldset class="address-form">
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'E-Mail' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-email" type="email" tabindex="1" data-field="email"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.email' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'E-Mail address (required)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.email' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Customer e-mail address' ) ); ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></label>
										<div class="col-sm-8">
											<select class="form-control custom-select item-languageid" tabindex="1" data-field="languageid"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.languageid' ) ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> >
												<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?></option>

												<?php foreach( $this->get( 'pageLangItems', [] ) as $langId => $langItem ) : ?>
													<option value="<?= $enc->attr( $langId ); ?>" <?= $selected( $this->get( 'itemData/address/' . $type . '/order.base.address.languageid' ), $langId ); ?> >
														<?= $enc->html( $this->translate( 'language', $langId ) ); ?>
													</option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Salutation' ) ); ?></label>
										<div class="col-sm-8">
											<select class="form-control custom-select item-salutation" tabindex="1" data-field="salutation"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.salutation' ) ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> >
												<option value="" <?= $selected( $this->get( 'itemData/address/' . $type . '/order.base.address.salutation' ), '' ); ?> >
													<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
												</option>
												<option value="company" <?= $selected( $this->get( 'itemData/address/' . $type . '/order.base.address.salutation' ), 'company' ); ?> >
													<?= $enc->html( $this->translate( 'client/code', 'company' ) ); ?>
												</option>
												<option value="mr" <?= $selected( $this->get( 'itemData/address/' . $type . '/order.base.address.salutation' ), 'mr' ); ?> >
													<?= $enc->html( $this->translate( 'client/code', 'mr' ) ); ?>
												</option>
												<option value="mrs" <?= $selected( $this->get( 'itemData/address/' . $type . '/order.base.address.salutation' ), 'mrs' ); ?> >
													<?= $enc->html( $this->translate( 'client/code', 'mrs' ) ); ?>
												</option>
												<option value="miss" <?= $selected( $this->get( 'itemData/address/' . $type . '/order.base.address.salutation' ), 'miss' ); ?> >
													<?= $enc->html( $this->translate( 'client/code', 'miss' ) ); ?>
												</option>
											</select>
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'How the customer is addressed in e-mails' ) ); ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Title' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-title" type="text" tabindex="1" data-field="title"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.title' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Honorary title (optional)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.title' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Honorary titles like Dr., Ph.D, etc.' ) ); ?>
										</div>
									</div>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Last name' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-lastname" type="text" required="required" tabindex="1" data-field="lastname"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.lastname' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Last name (required)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.lastname' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Last name of the person or full name in cultures where no first names are used' ) ); ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'First name' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-firstname" type="text" tabindex="1" data-field="firstname"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.firstname' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'First name (optional)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.firstname' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ); ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Street' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-address1" type="text" tabindex="1" data-field="address1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.address1' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Street name (optional)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.address1' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ); ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'House number' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-address2" type="text" tabindex="1" data-field="address2"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.address2' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'House number (optional)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.address2' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Address identifier of the customer\'s house for delivery' ) ); ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Floor / Appartment' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-address3" type="text" tabindex="1" data-field="address3"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.address3' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Floor and/or apartment (optional)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.address3' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Additional information where the customer\'s apartment can be found' ) ); ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Zip code' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-postal" type="text" tabindex="1" data-field="postal"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.postal' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Zip code (optional)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.postal' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Postal code for delivery if used in the area the customer is living' ) ); ?>
										</div>
									</div>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'City' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-city" type="text" required="required" tabindex="1" data-field="city"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.city' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'City or town name (required)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.city' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'State' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-state" type="text" tabindex="1" data-field="state"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.state' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Country state code (optional)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.state' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Short state code (e.g. NY) if used in the country the customer is living' ) ); ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Country' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-countryid" type="text" tabindex="1" maxlength="2" pattern="^[a-zA-Z]{2}$" data-field="countryid"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.countryid' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Country code (required)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.countryid' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Two letter ISO country code' ) ); ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Telephone' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-telephone" type="tel" tabindex="1" data-field="telephone"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.telephone' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Telephone number (optional)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.telephone' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', '(International) telephone number without separation characters, can start with a "+"' ) ); ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Facsimile' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-telefax" type="text" tabindex="1" data-field="telefax"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.telefax' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Facsimile number (optional)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.telefax' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', '(International) facsimilie number without separation characters, can start with a "+"' ) ); ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Web site' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-website" type="url" tabindex="1" data-field="website"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.website' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Web site URL (optional)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.website') ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'URL of the customer web site' ) ); ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Company' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-company" type="text" tabindex="1" data-field="company"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.company' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Company name (optional)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.company' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'VAT ID' ) ); ?></label>
										<div class="col-sm-8">
											<input class="form-control item-vatid" type="text" tabindex="1" data-field="vatid"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'address', $type, 'order.base.address.vatid' ) ) ); ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Value added tax identifier (optional)' ) ); ?>"
												value="<?= $enc->attr( $this->get( 'itemData/address/' . $type . '/order.base.address.vatid' ) ); ?>"
												<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Official VAT ID to determine if the tax needs to be billed in invoices' ) ); ?>
										</div>
									</div>
								</div>
							</fieldset>

						<?php endforeach; ?>
					</div>

					<div class="row">
						<?php foreach( $sortItems( $basket->getServices() ) as $type => $services ) : $code = 'service:' . $type; ?>
							<?php foreach( $services as $serviceItem ) : $serviceId = $serviceItem->getServiceId(); ?>

								<div class="col-xl-6 content-block item-service">
									<h2 class="col-12 item-header"><?= $enc->html( $this->translate( 'admin/ext', $code ) ); ?></h2>
									<div class="row">
										<div class="col-6 content-block">
											<span class="service-name"><?= $enc->html( $serviceItem->getName() ); ?></span>
											<span class="service-code"><?= $enc->html( $serviceItem->getCode() ); ?></span>
										</div>
										<div class="col-6 content-block">
											<span class="service-price"><?= $enc->html( sprintf( $priceFormat, $this->number( $serviceItem->getPrice()->getValue() + $serviceItem->getPrice()->getCosts() ), $currency ) ); ?></span>
											<?php if( $serviceItem->getPrice()->getRebate() > 0 ) : ?>
												<span class="service-rebate"><?= $enc->html( sprintf( $priceFormat, $this->number( $serviceItem->getPrice()->getRebate() ), $currency ) ); ?></span>
											<?php endif; ?>
										</div>
									</div>

									<table class="service-attr table table-striped" data-id="<?= $enc->attr($serviceId ) ?>"
										data-codes="<?= $enc->attr( isset( $serviceAttrCodes[$type] ) ? implode( ',', $serviceAttrCodes[$type] ) : '' ); ?>">
										<thead>
											<tr>
												<th>
													<span class="help"><?= $enc->html( $this->translate( 'admin', 'Code' ) ); ?></span>
													<div class="form-text text-muted help-text">
														<?= $enc->html( $this->translate( 'admin', 'Service attribute code' ) ); ?>
													</div>
												</th>
												<th>
													<?= $enc->html( $this->translate( 'admin', 'Value' ) ); ?>
												</th>
												<th class="actions">
													<?php if( !$this->site()->readonly( $basket->getLocale()->getSiteId() ) ) : ?>
														<div class="btn act-add fa" tabindex="1"
															title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>">
														</div>
													<?php endif; ?>
												</th>
											</tr>
										</thead>
										<tbody>

											<?php foreach( (array) $this->get( 'itemData/service/' . $type . '/' . $serviceId . '/order.base.service.attribute.id', [] ) as $idx => $attrId ) : ?>
												<tr class="service-attr-item">
													<td>
														<input type="hidden" class="service-attr-id" value="<?= $enc->attr( $attrId ); ?>"
															name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, $serviceId, 'order.base.service.attribute.id', '' ) ) ); ?>" />
														<input type="hidden" class="service-attr-attributeid"
															name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, $serviceId, 'order.base.service.attribute.attrid', '' ) ) ); ?>"
															value="<?= $enc->attr( $this->get( 'itemData/service/' . $type . '/' . $serviceId . '/order.base.service.attribute.attrid/' . $idx ) ); ?>" />
														<input type="hidden" class="service-attr-type"
															name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, $serviceId, 'order.base.service.attribute.type', '' ) ) ); ?>"
															value="<?= $enc->attr( $this->get( 'itemData/service/' . $type . '/' . $serviceId . '/order.base.service.attribute.type/' . $idx ) ); ?>" />
														<input type="hidden" class="service-attr-name"
															name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, $serviceId, 'order.base.service.attribute.name', '' ) ) ); ?>"
															value="<?= $enc->attr( $this->get( 'itemData/service/' . $type . '/' . $serviceId . '/order.base.service.attribute.name/' . $idx ) ); ?>" />
														<input type="hidden" class="service-attr-quantity"
															name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, $serviceId, 'order.base.service.attribute.quantity', '' ) ) ); ?>"
															value="<?= $enc->attr( $this->get( 'itemData/service/' . $type . '/' . $serviceId . '/order.base.service.attribute.quantity/' . $idx ) ); ?>" />
														<input type="text" class="service-attr-code form-control" tabindex="1"
															name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, $serviceId, 'order.base.service.attribute.code', '' ) ) ); ?>"
															value="<?= $enc->attr( $this->get( 'itemData/service/' . $type . '/' . $serviceId . '/order.base.service.attribute.code/' . $idx ) ); ?>"
															<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
													</td>
													<td>
														<input type="text" class="service-attr-value form-control" tabindex="1"
															name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, $serviceId, 'order.base.service.attribute.value', '' ) ) ); ?>"
															value="<?= $enc->attr( $this->get( 'itemData/service/' . $type . '/' . $serviceId . '/order.base.service.attribute.value/' . $idx ) ); ?>"
															<?= $this->site()->readonly( $basket->getLocale()->getSiteId() ); ?> />
													</td>
													<td class="actions">
														<?php if( !$this->site()->readonly( $basket->getLocale()->getSiteId() ) ) : ?>
															<div class="btn act-delete fa" tabindex="1"
																title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
															</div>
														<?php endif; ?>
													</td>
												</tr>
											<?php endforeach; ?>

											<tr class="prototype">
												<td>
													<input type="hidden" class="service-attr-id" value="" disabled="disabled"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, '_id_', 'order.base.service.attribute.id', '' ) ) ); ?>" />
													<input type="hidden" class="service-attr-attributeid" value="" disabled="disabled"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, '_id_', 'order.base.service.attribute.attrid', '' ) ) ); ?>" />
													<input type="hidden" class="service-attr-type" value="<?= $enc->attr( $type ); ?>" disabled="disabled"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, '_id_', 'order.base.service.attribute.type', '' ) ) ); ?>" />
													<input type="hidden" class="service-attr-name" value="" disabled="disabled"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, '_id_', 'order.base.service.attribute.name', '' ) ) ); ?>" />
													<input type="hidden" class="service-attr-quantity" value="1" disabled="disabled"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, '_id_', 'order.base.service.attribute.quantity', '' ) ) ); ?>" />
													<input type="text" class="service-attr-code form-control" value="" disabled="disabled" tabindex="1"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, '_id_', 'order.base.service.attribute.code', '' ) ) ); ?>" />
												</td>
												<td>
													<input type="text" class="service-attr-value form-control" value="" disabled="disabled" tabindex="1"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, '_id_', 'order.base.service.attribute.value', '' ) ) ); ?>" />
												</td>
												<td class="actions">
													<?php if( !$this->site()->readonly( $basket->getLocale()->getSiteId() ) ) : ?>
														<div class="btn act-delete fa" tabindex="1"
															title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
														</div>
													<?php endif; ?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>

							<?php endforeach; ?>
						<?php endforeach; ?>
					</div>

					<div class="row item-summary">
						<div class="col-xl-6 content-block"></div>

						<div class="col-xl-6 content-block item-total">
							<h2 class="item-header"><?= $enc->html( $this->translate( 'admin', 'Order totals' ) ); ?></h2>
							<div class="form-group row total-subtotal">
								<div class="col-6 name"><?= $enc->html( $this->translate( 'admin', 'Sub-total' ) ); ?></div>
								<div class="col-6 value"><?= $enc->html( sprintf( $priceFormat, $this->number( $basket->getPrice()->getValue() ), $currency ) ); ?></div>
							</div>
							<div class="form-group row total-shipping">
								<div class="col-6 name"><?= $enc->html( $this->translate( 'admin', 'Shipping' ) ); ?></div>
								<div class="col-6 value"><?= $enc->html( sprintf( $priceFormat, $this->number( $basket->getPrice()->getCosts() ), $currency ) ); ?></div>
							</div>
							<div class="form-group row total-value">
								<div class="col-6 name"><?= $enc->html( $this->translate( 'admin', 'Total' ) ); ?></div>
								<div class="col-6 value"><?= $enc->html( sprintf( $priceFormat, $this->number( $basket->getPrice()->getValue() + $basket->getPrice()->getCosts() ), $currency ) ); ?></div>
							</div>
							<div class="form-group row total-tax">
								<div class="col-6 name">
									<?php if( $basket->getPrice()->getTaxFlag() ) : ?>
										<?= $enc->html( $this->translate( 'admin', 'Incl. tax' ) ); ?>
									<?php else : ?>
										<?= $enc->html( $this->translate( 'admin', 'Excl. tax' ) ); ?>
									<?php endif; ?>
								</div>
								<div class="col-6 value"><?= $enc->html( sprintf( $priceFormat, $this->number( $basket->getPrice()->getTaxValue() ), $currency ) ); ?></div>
							</div>
						</div>
					</div>

				</div>

				<?= $this->get( 'itemBody' ); ?>

			</div>

			<div class="item-actions">
				<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard.php' ), ['params' => $params] ); ?>
			</div>
		</div>
	</form>

<?php endif; ?>

<?php $this->block()->stop(); ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard.php' ) ); ?>
