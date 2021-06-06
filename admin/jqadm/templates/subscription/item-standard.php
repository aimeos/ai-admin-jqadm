<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2021
 */

$selected = function( $key, $code ) {
	return ( $key === $code ? 'selected="selected"' : '' );
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

$params = $this->get( 'pageParams', [] );
$basket = $this->itemBase;

/// Price format with price value (%1$s) and currency (%2$s)
$priceFormat = $this->translate( 'client/code', '%1$s %2$s' );
$currency = $this->translate( 'currency', $basket->getPrice()->getCurrencyId() );


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<form class="item item-subscription form-horizontal container-fluid" method="POST" enctype="multipart/form-data" action="<?= $enc->attr( $this->url( $target, $cntl, $action, $params, [], $config ) ) ?>">
	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'subscription.id' ) ) ) ?>" value="<?= $enc->attr( $this->get( 'itemData/subscription.id' ) ) ?>" />
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ) ?>" value="get" />
	<?= $this->csrf()->formfield() ?>

	<nav class="main-navbar">
		<h1 class="navbar-brand">
			<span class="navbar-title"><?= $enc->html( $this->translate( 'admin', 'Subscription' ) ) ?></span>
			<span class="navbar-id"><?= $enc->html( $this->get( 'itemData/subscription.id' ) ) ?></span>
			<span class="navbar-label">
				<?php if( $this->get( 'itemData/subscription.id' ) ) : ?>
					<?= $enc->html( $this->get( 'itemData/subscription.datenext' ) . ' ' . $this->get( 'itemData/subscription.interval' ) ) ?>
				<?php else : ?>
					<?= $enc->html( $this->translate( 'admin', 'New' ) ) ?>
				<?php endif ?>
			</span>
			<span class="navbar-site"><?= $enc->html( $this->site()->match( $this->get( 'itemData/subscription.siteid' ) ) ) ?></span>
		</h1>
		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard' ), ['params' => $params] ) ?>
		</div>
	</nav>

	<div class="row item-container">

		<div class="col-xl-3 item-navbar">
			<div class="navbar-content">
				<ul class="nav nav-tabs flex-xl-column flex-wrap d-flex box" role="tablist">

					<li class="nav-item basic">
						<a class="nav-link active" href="#basic" data-bs-toggle="tab" role="tab" aria-expanded="true" aria-controls="basic">
							<?= $enc->html( $this->translate( 'admin', 'Basic' ) ) ?>
						</a>
					</li>

					<?php foreach( array_values( $this->get( 'itemSubparts', [] ) ) as $idx => $subpart ) : ?>
						<li class="nav-item <?= $enc->attr( $subpart ) ?>">
							<a class="nav-link" href="#<?= $enc->attr( $subpart ) ?>" data-bs-toggle="tab" role="tab" tabindex="<?= ++$idx + 1 ?>">
								<?= $enc->html( $this->translate( 'admin', $subpart ) ) ?>
							</a>
						</li>
					<?php endforeach ?>

				</ul>

				<div class="item-meta text-muted">
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Modified' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/subscription.mtime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/subscription.ctime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/subscription.editor' ) ) ?></span>
					</small>
				</div>

				<div class="more"></div>
			</div>
		</div>

		<div class="col-xl-9 item-content tab-content">
			<?php $readonly = ( $this->access( 'admin' ) === false ? $this->site()->readonly( $this->get( 'itemData/subscription.siteid' ) ) : '' ) ?>

			<div id="basic" class="item-basic vue tab-pane fade show active" role="tabpanel" aria-labelledby="basic"
				data-data="<?= $enc->attr( $this->get( 'subscriptionData' ) ) ?>">

				<input class="item-ordbaseid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'subscription.ordbaseid' ) ) ) ?>"
					value="<?= $enc->attr( $this->param( 'subscription.ordbaseid', $this->get( 'itemData/subscription.ordbaseid' ) ) ) ?>" />
				<input class="item-ordprodid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'subscription.ordprodid' ) ) ) ?>"
					value="<?= $enc->attr( $this->param( 'subscription.ordprodid', $this->get( 'itemData/subscription.ordprodid' ) ) ) ?>" />

				<div class="row">
					<div class="col-xl-6 <?= $readonly ?>">
						<div class="box">
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></label>
								<div class="col-sm-8">
									<select class="form-select item-status" required="required" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'subscription.status' ) ) ) ?>"
										<?= $this->site()->readonly( $this->get( 'itemData/subscription.siteid' ) ) ?> >
										<option value="">
											<?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?>
										</option>
										<option value="1" <?= $selected( $this->get( 'itemData/subscription.status', 1 ), 1 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?>
										</option>
										<option value="0" <?= $selected( $this->get( 'itemData/subscription.status', 1 ), 0 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?>
										</option>
										<option value="-1" <?= $selected( $this->get( 'itemData/subscription.status', 1 ), -1 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?>
										</option>
										<option value="-2" <?= $selected( $this->get( 'itemData/subscription.status', 1 ), -2 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?>
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Interval' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-interval" type="text" required="required" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'subscription.interval' ) ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Interval (required)' ) ) ?>"
										value="<?= $enc->attr( $this->get( 'itemData/subscription.interval', 'P1Y0M0W0D' ) ) ?>"
										<?= $this->site()->readonly( $this->get( 'itemData/subscription.siteid' ) ) ?> />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Subscription interval with years, months, weeks and days, e.g. "P0Y1M2W3D" for zero years, one month, two weeks and three days' ) ) ?>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Next date' ) ) ?></label>
								<div class="col-sm-8">
									<input is="flat-pickr" class="form-control item-datenext select" type="date" required="required" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'subscription.datenext' ) ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Next date (optional)' ) ) ?>"
										v-bind:value="`<?= $enc->js( $this->get( 'itemData/subscription.datenext' ) ) ?>`"
										v-bind:config="Aimeos.flatpickr.date"
										<?= $this->site()->readonly( $this->get( 'itemData/subscription.siteid' ) ) ?> />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Next date the subscription is renewed' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?></label>
								<div class="col-sm-8">
									<input is="flat-pickr" class="form-control item-dateend select" type="date" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'subscription.dateend' ) ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'End date (optional)' ) ) ?>"
										v-bind:value="`<?= $enc->js( $this->get( 'itemData/subscription.dateend' ) ) ?>`"
										v-bind:config="Aimeos.flatpickr.date"
										<?= $this->site()->readonly( $this->get( 'itemData/subscription.siteid' ) ) ?> />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Date the subscription ends' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Reason' ) ) ?></label>
								<div class="col-sm-8">
									<select class="form-select item-reason" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'subscription.reason' ) ) ) ?>"
										<?= $this->site()->readonly( $this->get( 'itemData/subscription.siteid' ) ) ?> >
										<option value="">
											<?= $enc->attr( $this->translate( 'admin', 'None' ) ) ?>
										</option>
										<option value="1" <?= $selected( $this->get( 'itemData/subscription.reason' ), 1 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'reason:1' ) ) ?>
										</option>
										<option value="0" <?= $selected( $this->get( 'itemData/subscription.reason' ), 0 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'reason:0' ) ) ?>
										</option>
										<option value="-1" <?= $selected( $this->get( 'itemData/subscription.reason' ), -1 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'reason:-1' ) ) ?>
										</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xl-6 block <?= $this->site()->readonly( $basket->getLocale()->getSiteId() ) ?>">
						<div class="box">
							<div class="form-group row">
								<label class="col-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Site' ) ) ?></label>
								<div class="col-8">
									<span class="form-control item-sitecode"><?= $enc->html( $basket->getSiteCode() ) ?></span>
								</div>
								<div class="col-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Site the order was placed at' ) ) ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Customer ID' ) ) ?></label>
								<div class="col-8">
									<span class="form-control item-customerid">
										<?php if( $basket->getCustomerId() ) : ?>
											<a class="btn fa act-view" target="_blank"
												href="<?= $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['resource' => 'customer', 'id' => $basket->getCustomerId()], [], $getConfig ) ) ?>">
												<?= $enc->attr( $basket->getCustomerId() ) ?>
											</a>
										<?php endif ?>
									</span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Order ID' ) ) ?></label>
								<div class="col-8">
									<span class="form-control item-orderid">
										<a class="btn fa act-view" target="_blank"
											href="<?= $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['resource' => 'order', 'id' => $basket->getId()], [], $getConfig ) ) ?>">
											<?= $enc->attr( $basket->getId() ) ?>
										</a>
									</span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Currency' ) ) ?></label>
								<div class="col-8">
									<span class="form-control item-currencyid"><?= $enc->html( $basket->getLocale()->getCurrencyId() ) ?></span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?></label>
								<div class="col-8">
									<span class="form-control item-languageid"><?= $enc->html( $this->translate( 'language', $basket->getLocale()->getLanguageId() ) ) ?></span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-12">
								<div class="box">
									<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Product' ) ) ?></h2>
									<div class="table-responsive">
										<table class="item-product-list table table-striped">
											<thead>
												<tr>
													<th class="item-column column-desc"><?= $enc->html( $this->translate( 'admin', 'Name' ) ) ?></th>
													<th class="item-column column-quantity"><?= $enc->html( $this->translate( 'admin', 'Quantity' ) ) ?></th>
													<th class="item-column column-price"><?= $enc->html( $this->translate( 'admin', 'Price' ) ) ?></th>
													<th class="item-column column-sum"><?= $enc->html( $this->translate( 'admin', 'Sum' ) ) ?></th>
												</tr>
											</thead>
											<tbody>

												<?php foreach( $basket->getProducts() as $pos => $orderProduct ) : ?>
													<?php if( $orderProduct->getId() == $this->param( 'subscription.ordprodid', $this->get( 'itemData/subscription.ordprodid' ) ) ) : ?>
														<tr class="list-item">
															<td class="item-column column-desc">
																<span class="product-name"><?= $enc->html( $orderProduct->getName() ) ?></span>
																<span class="product-attr">
																	<?php foreach( $orderProduct->getAttributeItems() as $attrItem ) : ?>
																		<span class="attr-code"><?= $enc->html( $attrItem->getCode() ) ?></span>
																		<span class="attr-value">
																			<?php if( $attrItem->getQuantity() > 1 ) : ?>
																				<?= $enc->html( $attrItem->getQuantity() ) ?>×
																			<?php endif ?>
																			<?= $enc->html( $attrItem->getValue() ) ?>
																		</span>
																	<?php endforeach ?>
																</span>
																<span class="product-sku"><?= $enc->html( $orderProduct->getProductCode() ) ?></span>
															</td>
															<td class="item-column column-quantity">
																<span class="product-quantity"><?= $enc->html( $orderProduct->getQuantity() ) ?></span>
															</td>
															<td class="item-column column-price">
																<span class="product-price"><?= $enc->html( sprintf( $priceFormat, $orderProduct->getPrice()->getValue(), $currency ) ) ?></span>
																<span class="product-costs"><?= $enc->html( sprintf( $priceFormat, $orderProduct->getPrice()->getCosts(), $currency ) ) ?></span>
																<span class="product-rebate"><?= $enc->html( sprintf( $priceFormat, $orderProduct->getPrice()->getRebate(), $currency ) ) ?></span>
															</td>
															<td class="item-column column-sum">
																<span class="product-price"><?= $enc->html( sprintf( $priceFormat, $this->number( $orderProduct->getPrice()->getValue() * $orderProduct->getQuantity() ), $currency ) ) ?></span>
																<span class="product-costs"><?= $enc->html( sprintf( $priceFormat, $this->number( $orderProduct->getPrice()->getCosts() * $orderProduct->getQuantity() ), $currency ) ) ?></span>
																<span class="product-rebate"><?= $enc->html( sprintf( $priceFormat, $this->number( $orderProduct->getPrice()->getRebate() * $orderProduct->getQuantity() ), $currency ) ) ?></span>
															</td>
														</tr>
													<?php endif ?>
												<?php endforeach ?>

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

					<?php foreach( $basket->getAddresses()->krsort() as $type => $list ) : $code = 'address:' . $type ?>

						<div class="col-xl-6 item-address">
							<div class="box">
								<h2 class="item-header"><?= $enc->html( $this->translate( 'admin/ext', $code ) ) ?></h2>

								<?php foreach( $list as $addr ) : ?>
									<div class="address-short">
										<span class="address-text">
											<?php
												$salutations = array(
													\Aimeos\MShop\Common\Item\Address\Base::SALUTATION_MR,
													\Aimeos\MShop\Common\Item\Address\Base::SALUTATION_MS,
												);

												echo preg_replace( "/\n+/m", "<br/>", trim( $enc->html( sprintf(
													/// Address format with company (%1$s), salutation (%2$s), title (%3$s), first name (%4$s), last name (%5$s),
													/// address part one (%6$s, e.g street), address part two (%7$s, e.g house number), address part three (%8$s, e.g additional information),
													/// postal/zip code (%9$s), city (%10$s), state (%11$s), country (%12$s), language (%13$s),
													/// e-mail (%14$s), phone (%15$s), facsimile/telefax (%16$s), web site (%17$s), vatid (%18$s)
													$this->translate( 'client', '%1$s
	%2$s %3$s %4$s %5$s
	%6$s %7$s
	%8$s
	%9$s %10$s
	%11$s
	%12$s
	%13$s
	%14$s
	%15$s
	%16$s
	%17$s
	%18$s
	'
													),
													$addr->getCompany(),
													( in_array( $addr->getSalutation(), $salutations ) ? $this->translate( 'mshop/code', $addr->getSalutation() ) : '' ),
													$addr->getTitle(),
													$addr->getFirstName(),
													$addr->getLastName(),
													$addr->getAddress1(),
													$addr->getAddress2(),
													$addr->getAddress3(),
													$addr->getPostal(),
													$addr->getCity(),
													$addr->getState(),
													$this->translate( 'country', $addr->getCountryId() ),
													$this->translate( 'language', $addr->getLanguageId() ),
													$addr->getEmail(),
													$addr->getTelephone(),
													$addr->getTelefax(),
													$addr->getWebsite(),
													$addr->getVatID()
												) ) ) );
											?>
										</span>
									</div>
								</div>

							<?php endforeach ?>
						</div>

					<?php endforeach ?>
				</div>

			<?= $this->get( 'itemBody' ) ?>

		</div>

		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard' ), ['params' => $params] ) ?>
		</div>
	</div>
</form>

<?php $this->block()->stop() ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard' ) ) ?>
