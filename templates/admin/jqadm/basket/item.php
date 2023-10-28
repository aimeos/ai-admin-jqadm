<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2023
 */

$selected = function( $key, $code ) {
	return ( $key === $code ? 'selected="selected"' : '' );
};


$enc = $this->encoder();

$searchParams = $params = $this->get( 'pageParams', [] );
unset( $searchParams['id'] );

/// Price format with price value (%1$s) and currency (%2$s)
$priceFormat = $this->translate( 'client/code', '%1$s %2$s' );


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<?php if( isset( $this->item ) ) : ?>
	<?php $currency = $this->translate( 'currency', $this->item->getItem()->getPrice()->getCurrencyId() ) ?>

	<form class="item item-basket item-order form-horizontal container-fluid" method="POST" enctype="multipart/form-data" action="<?= $enc->attr( $this->link( 'admin/jqadm/url/save', $params ) ) ?>">
		<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'order.basket.id' ) ) ) ?>" value="<?= $enc->attr( $this->item->getId() ) ?>">
		<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ) ?>" value="get">
		<?= $this->csrf()->formfield() ?>

		<nav class="main-navbar">
			<h1 class="navbar-brand">
				<span class="navbar-title"><?= $enc->html( $this->translate( 'admin', 'Basket' ) ) ?></span>
				<span class="navbar-id"><?= $enc->html( $this->item->getId() ) ?></span>
				<span class="navbar-site"><?= $enc->html( $this->site()->match( $this->item->getSiteId() ) ) ?></span>
			</h1>
			<div class="item-actions">
				<a class="btn btn-secondary act-cancel"
					title="<?= $enc->attr( $this->translate( 'admin', 'Cancel and return to list' ) ) ?>"
					href="<?= $enc->attr( $this->link( 'admin/jqadm/url/search', $searchParams ) ) ?>">
					<?= $enc->html( $this->translate( 'admin', 'Cancel' ) ) ?>
				</a>
			</div>
		</nav>

		<div class="row item-container">

			<div class="col-xl-3 item-navbar">
				<div class="navbar-content">
					<ul class="nav nav-tabs flex-xl-column flex-wrap d-flex box" role="tablist">

						<li class="nav-item basket">
							<a class="nav-link active" href="#basket" data-bs-toggle="tab" role="tab" aria-expanded="true" aria-controls="basket">
								<?= $enc->html( $this->translate( 'admin', 'Basket' ) ) ?>
							</a>
						</li>

						<?php foreach( array_values( $this->get( 'itemSubparts', [] ) ) as $type => $subpart ) : ?>
							<li class="nav-item <?= $enc->attr( $subpart ) ?>">
								<a class="nav-link" href="#<?= $enc->attr( $subpart ) ?>" data-bs-toggle="tab" role="tab" tabindex="<?= ++$type + 1 ?>">
									<?= $enc->html( $this->translate( 'admin', $subpart ) ) ?>
								</a>
							</li>
						<?php endforeach ?>

					</ul>

					<div class="item-meta text-muted">
						<small>
							<?= $enc->html( $this->translate( 'admin', 'Modified' ) ) ?>:
							<span class="meta-value"><?= $enc->html( $this->item->getTimeModified() ) ?></span>
						</small>
						<small>
							<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
							<span class="meta-value"><?= $enc->html( $this->item->getTimeCreated() ) ?></span>
						</small>
						<small>
							<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
							<span class="meta-value"><?= $enc->html( $this->item->editor() ) ?></span>
						</small>
					</div>

					<div class="more"></div>
				</div>
			</div>

			<div class="col-xl-9 item-content tab-content">

				<div id="basket" class="basket-item tab-pane fade show active" role="tabpanel" aria-labelledby="basket"
					data-item="<?= $enc->attr( $this->get( 'itemData', [] ) ) ?>"
					data-siteid="<?= $enc->attr( $this->site()->siteid() ) ?>">

					<div class="row item-base">
						<div class="col-xl-6 block" :class="{readonly: !can('change')}">
							<div class="box">
								<div class="form-group row">
									<label class="col-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Customer ID' ) ) ?></label>
									<div class="col-8">
										<span class="form-control item-customerid">
											<?php if( $this->item->getCustomerId() && $this->access( $this->config( 'admin/jqadm/resource/customer/groups', [] ) ) ) : ?>
												<a class="link fa act-view" target="_blank"
													href="<?= $enc->attr( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => $this->item->getCustomerId(), 'locale' => $this->param( 'locale' )] ) ) ?>">
													<?= $enc->html( $this->item->getCustomerId() ) ?>
												</a>
											<?php else : ?>
												&nbsp
											<?php endif ?>
										</span>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-6 block" :class="{readonly: !can('change')}">
							<div class="box">
								<div class="form-group row">
									<label class="col-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Name' ) ) ?></label>
									<div class="col-8">
										<span class="form-control item-name"><?= $enc->html( $this->item->getName() ) ?: '&nbsp' ?></span>
									</div>
									<div class="col-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Name of the basket' ) ) ?>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="box">
						<div class="row item-product-list">

							<div class="col-sm-12">
								<h2 class="item-header"><?= $enc->html( $this->translate( 'admin', 'Products' ) ) ?></h2>

								<?php foreach( $this->item->getItem()->getProducts() as $pos => $orderProduct ) : ?>
									<?php if( strncmp( $this->site()->siteid(), $orderProduct->getSiteId(), strlen( $this->site()->siteid() ) ) === 0 ) : ?>

										<div class="list-item">
											<div class="row">
												<div class="col-sm-8 item-column column-desc">
													<div class="row">
														<label class="col-5 col-sm-12 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Name' ) ) ?></label>
														<div class="col-7 col-sm-12">
															<span class="product-name"><?= $enc->html( $orderProduct->getName() ) ?></span>
															<span class="product-attr">
																<?php foreach( $orderProduct->getAttributeItems() as $attrItem ) : ?>
																	<span class="attr-code"><?= $enc->html( $attrItem->getCode() ) ?></span>
																	<span class="attr-value">
																		<?php if( $attrItem->getQuantity() > 1 ) : ?>
																			<?= $enc->html( $attrItem->getQuantity() ) ?>×
																		<?php endif ?>
																		<?= $enc->html( join( ',', (array) $attrItem->getValue() ) ) ?>
																	</span>
																<?php endforeach ?>
															</span>
															<span class="product-sku"><?= $enc->html( $orderProduct->getProductCode() ) ?></span>
														</div>
													</div>
												</div>
												<div class="col-sm-2 item-column column-qtyopen">
													<div class="row">
														<label class="col-5 col-sm-12 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Quantity' ) ) ?></label>
														<div class="col-7 col-sm-12">
															<span class="product-quantity"><?= $enc->html( $orderProduct->getQuantity() ) ?></span>
														</div>
													</div>
												</div>
												<div class="col-sm-2 item-column column-sum">
													<div class="row">
														<label class="col-5 col-sm-12 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Unit price' ) ) ?></label>
														<div class="col-7 col-sm-12">
															<span class="product-price"><?= $enc->html( sprintf( $priceFormat, $this->number( $orderProduct->getPrice()->getValue() ), $currency ) ) ?></span>
															<span class="product-rebate"><?= $enc->html( sprintf( $priceFormat, $this->number( $orderProduct->getPrice()->getRebate() ), $currency ) ) ?></span>
														</div>
													</div>
												</div>
											</div>
										</div>

									<?php endif ?>
								<?php endforeach ?>

							</div>

						</div>
					</div>


					<div class="row item-misc">
						<div class="col-xl-6">
							<div class="box">
								<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Coupon' ) ) ?></h2>
								<?php if( !$this->item->getItem()->getCoupons()->isEmpty() ) : ?>
									<?php foreach( $this->item->getItem()->getCoupons() as $code => $product ) : ?>
										<div class="form-group row">
											<label class="col-sm-4"><?= $enc->html( $this->translate( 'admin', 'Code' ) ) ?></label>
											<div class="col-sm-8"><span class="item-coupon"><?= $enc->attr( $code ) ?></span></div>
										</div>
									<?php endforeach ?>
								<?php else : ?>
									<?= $enc->html( $this->translate( 'admin', 'No voucher' ) ) ?>
								<?php endif ?>
							</div>
						</div>

						<div class="col-xl-6">
							<div class="box">
								<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Comment' ) ) ?></h2>
								<div class="form-group optional">
									<textarea class="form-control item-title" type="text" tabindex="1" rows="3"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'order.comment' ) ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Customer comment (optional)' ) ) ?>"
										<?= $this->site()->readonly( $this->item->getSiteId() ) ?>
									><?= $enc->html( $this->item->getItem()->getComment() ) ?></textarea>
								</div>
							</div>
						</div>
					</div>


					<div class="row">
						<?php foreach( $this->item->getItem()->getAddresses()->krsort() as $type => $addresses ) : $code = 'address:' . $type ?>

							<div class="col-xl-6 item-address">
								<div class="box">
									<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin/ext', $code ) ) ?></h2>

									<div v-for="(addr, idx) in (item?.address?.<?= $enc->attr( $type ) ?> || [])" :key="idx">
										<div class="address-short" @click="$set(addr, '_edit', !addr['_edit'])">
											<span class="address-text">
												{{ addr['order.address.company'] }}<br>
												{{ addr['order.address.firstname'] }} {{ addr['order.address.lastname'] }}<br>
												{{ addr['order.address.address1'] }} {{ addr['order.address.address2'] }}<br>
												{{ addr['order.address.postal'] }} {{ addr['order.address.city'] }}<br>
												{{ addr['order.address.countryid'] }} {{ addr['order.address.state'] }}<br>
												{{ addr['order.address.email'] }}<br>
												{{ addr['order.address.telephone'] }}<br>
												{{ addr['order.address.mobile'] }}<br>
												{{ addr['order.address.vatid'] }}
											</span>
											<span class="address-edit"></span>
										</div>

										<fieldset v-show="addr['_edit']" class="address-form">
											<div class="form-group row mandatory">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'E-Mail' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-email" type="email" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.email' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'E-Mail address (required)' ) ) ?>"
														:value="addr['order.address.email']"
														:readonly="!can('change')">
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Customer e-mail address' ) ) ?>
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?></label>
												<div class="col-sm-8">
													<select class="form-select item-languageid" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.languageid' ) ) ) ?>`.replace('idx', idx)"
														:value="addr['order.address.languageid']"
														:readonly="!can('change')">
														<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?></option>

														<?php foreach( $this->get( 'pageLangItems', [] ) as $langId => $langItem ) : ?>
															<option value="<?= $enc->attr( $langId ) ?>" :selected="addr['order.address.languageid'] === `<?= $langId ?>`" >
																<?= $enc->html( $this->translate( 'language', $langId ) ) ?>
															</option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Salutation' ) ) ?></label>
												<div class="col-sm-8">
													<select class="form-select item-salutation" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.salutation' ) ) ) ?>`.replace('idx', idx)"
														:value="addr['order.address.salutation']"
														:readonly="!can('change')">
														<option value="" :selected="addr['order.address.salutation'] === ''" >
															<?= $enc->html( $this->translate( 'admin', 'none' ) ) ?>
														</option>
														<option value="company" :selected="addr['order.address.salutation'] === 'company'" >
															<?= $enc->html( $this->translate( 'client/code', 'company' ) ) ?>
														</option>
														<option value="mr" :selected="addr['order.address.salutation'] === 'mr'" >
															<?= $enc->html( $this->translate( 'client/code', 'mr' ) ) ?>
														</option>
														<option value="ms" :selected="addr['order.address.salutation'] === 'ms'" >
															<?= $enc->html( $this->translate( 'client/code', 'ms' ) ) ?>
														</option>
													</select>
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'How the customer is addressed in e-mails' ) ) ?>
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Title' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-title" type="text" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.title' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Honorary title (optional)' ) ) ?>"
														:value="addr['order.address.title']"
														:readonly="!can('change')">
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Honorary titles like Dr., Ph.D, etc.' ) ) ?>
												</div>
											</div>
											<div class="form-group row mandatory">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Last name' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-lastname" type="text" required="required" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.lastname' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Last name (required)' ) ) ?>"
														:value="addr['order.address.lastname']"
														:readonly="!can('change')">
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Last name of the person or full name in cultures where no first names are used' ) ) ?>
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'First name' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-firstname" type="text" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.firstname' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'First name (optional)' ) ) ?>"
														:value="addr['order.address.firstname']"
														:readonly="!can('change')">
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ) ?>
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Street' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-address1" type="text" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.address1' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Street name (optional)' ) ) ?>"
														:value="addr['order.address.address1']"
														:readonly="!can('change')">
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ) ?>
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'House number' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-address2" type="text" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.address2' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'House number (optional)' ) ) ?>"
														:value="addr['order.address.address2']"
														:readonly="!can('change')">
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Address identifier of the customer\'s house for delivery' ) ) ?>
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Floor / Appartment' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-address3" type="text" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.address3' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Floor and/or apartment (optional)' ) ) ?>"
														:value="addr['order.address.address3']"
														:readonly="!can('change')">
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Additional information where the customer\'s apartment can be found' ) ) ?>
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Zip code' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-postal" type="text" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.postal' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Zip code (optional)' ) ) ?>"
														:value="addr['order.address.postal']"
														:readonly="!can('change')">
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Postal code for delivery if used in the area the customer is living' ) ) ?>
												</div>
											</div>
											<div class="form-group row mandatory">
												<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'City' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-city" type="text" required="required" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.city' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'City or town name (required)' ) ) ?>"
														:value="addr['order.address.city']"
														:readonly="!can('change')">
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Country' ) ) ?></label>
												<div class="col-sm-8">
													<select class="form-select item-countryid" required="required" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.countryid' ) ) ) ?>`.replace('idx', idx)"
														:value="addr['order.address.countryid']"
														:readonly="!can('change')">
														<option value="" disabled>
															<?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?>
														</option>

														<?php foreach( $this->get( 'countries', [] ) as $code => $label ) : ?>
															<option value="<?= $enc->attr( $code ) ?>" :selected="addr['order.address.countryid'] === `<?= $code ?>`" >
																<?= $enc->html( $label ) ?>
															</option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Two letter ISO country code' ) ) ?>
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'State' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-state" type="text" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.state' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Country state code (optional)' ) ) ?>"
														:value="addr['order.address.state']"
														:readonly="!can('change')">
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Short state code (e.g. NY) if used in the country the customer is living' ) ) ?>
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Telephone' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-telephone" type="tel" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.telephone' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Telephone number (optional)' ) ) ?>"
														:value="addr['order.address.telephone']"
														:readonly="!can('change')">
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', '(International) telephone number without separation characters, can start with a "+"' ) ) ?>
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Mobile' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-mobile" type="tel" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.mobile' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Mobile number (optional)' ) ) ?>"
														:value="addr['order.address.mobile']"
														:readonly="!can('change')">
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', '(International) telephone number without separation characters, can start with a "+"' ) ) ?>
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Facsimile' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-telefax" type="text" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.telefax' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Facsimile number (optional)' ) ) ?>"
														:value="addr['order.address.telefax']"
														:readonly="!can('change')">
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', '(International) facsimilie number without separation characters, can start with a "+"' ) ) ?>
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Web site' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-website" type="url" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.website' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Web site URL (optional)' ) ) ?>"
														:value="addr['order.address.website']"
														:readonly="!can('change')">
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'URL of the customer web site' ) ) ?>
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Company' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-company" type="text" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.company' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Company name (optional)' ) ) ?>"
														:value="addr['order.address.company']"
														:readonly="!can('change')">
												</div>
											</div>
											<div class="form-group row optional">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'VAT ID' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-vatid" type="text" tabindex="1"
														:name="`<?= $enc->js( $this->formparam( array( 'item', 'address', $type, 'idx', 'order.address.vatid' ) ) ) ?>`.replace('idx', idx)"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Value added tax identifier (optional)' ) ) ?>"
														:value="addr['order.address.vatid']"
														:readonly="!can('change')">
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Official VAT ID to determine if the tax needs to be billed in invoices' ) ) ?>
												</div>
											</div>
										</fieldset>

									</div>
								</div>
							</div>

						<?php endforeach ?>
					</div>

					<div class="row">
						<?php foreach( $this->item->getItem()->getServices()->krsort() as $type => $services ) : $code = 'service:' . $type ?>
							<?php foreach( $services as $serviceItem ) : $serviceId = $serviceItem->getServiceId() ?>

								<div class="col-xl-6 item-service">
									<div class="box">
										<h2 class="col-12 item-header"><?= $enc->html( $this->translate( 'admin/ext', $code ) ) ?></h2>
										<div class="row">
											<div class="col-6">
												<span class="service-name"><?= $enc->html( $serviceItem->getName() ) ?></span>
												<span class="service-code"><?= $enc->html( $serviceItem->getCode() ) ?></span>
											</div>
											<div class="col-6">
												<span class="service-price"><?= $enc->html( sprintf( $priceFormat, $this->number( $serviceItem->getPrice()->getValue() + $serviceItem->getPrice()->getCosts() ), $currency ) ) ?></span>
												<?php if( $serviceItem->getPrice()->getRebate() > 0 ) : ?>
													<span class="service-rebate"><?= $enc->html( sprintf( $priceFormat, $this->number( $serviceItem->getPrice()->getRebate() ), $currency ) ) ?></span>
												<?php endif ?>
											</div>
										</div>

										<table class="service-attr table" data-id="<?= $enc->attr( $serviceId ) ?>"
											data-codes="<?= $enc->attr( isset( $serviceAttrCodes[$type] ) ? implode( ',', $serviceAttrCodes[$type] ) : '' ) ?>">
											<thead>
												<tr>
													<th>
														<span class="help"><?= $enc->html( $this->translate( 'admin', 'Code' ) ) ?></span>
														<div class="form-text text-muted help-text">
															<?= $enc->html( $this->translate( 'admin', 'Service attribute code' ) ) ?>
														</div>
													</th>
													<th>
														<?= $enc->html( $this->translate( 'admin', 'Value' ) ) ?>
													</th>
													<th class="actions">
														<?php if( !$this->site()->readonly( $this->item->getSiteId() ) ) : ?>
															<div class="btn act-add fa" tabindex="1"
																title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>">
															</div>
														<?php endif ?>
													</th>
												</tr>
											</thead>
											<tbody>

												<?php foreach( (array) $this->get( 'itemData/service/' . $type . '/' . $serviceId . '/order.service.attribute.id', [] ) as $idx => $attrId ) : ?>
													<tr class="service-attr-item">
														<td>
															<input type="hidden" class="service-attr-id" value="<?= $enc->attr( $attrId ) ?>"
																name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, $serviceId, 'order.service.attribute.id', '' ) ) ) ?>">
															<input type="hidden" class="service-attr-attributeid"
																name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, $serviceId, 'order.service.attribute.attrid', '' ) ) ) ?>"
																value="<?= $enc->attr( $this->get( 'itemData/service/' . $type . '/' . $serviceId . '/order.service.attribute.attrid/' . $idx ) ) ?>">
															<input type="hidden" class="service-attr-type"
																name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, $serviceId, 'order.service.attribute.type', '' ) ) ) ?>"
																value="<?= $enc->attr( $this->get( 'itemData/service/' . $type . '/' . $serviceId . '/order.service.attribute.type/' . $idx ) ) ?>">
															<input type="hidden" class="service-attr-name"
																name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, $serviceId, 'order.service.attribute.name', '' ) ) ) ?>"
																value="<?= $enc->attr( $this->get( 'itemData/service/' . $type . '/' . $serviceId . '/order.service.attribute.name/' . $idx ) ) ?>">
															<input type="hidden" class="service-attr-quantity"
																name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, $serviceId, 'order.service.attribute.quantity', '' ) ) ) ?>"
																value="<?= $enc->attr( $this->get( 'itemData/service/' . $type . '/' . $serviceId . '/order.service.attribute.quantity/' . $idx ) ) ?>">
															<input type="text" class="service-attr-code form-control" tabindex="1"
																name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, $serviceId, 'order.service.attribute.code', '' ) ) ) ?>"
																value="<?= $enc->attr( $this->get( 'itemData/service/' . $type . '/' . $serviceId . '/order.service.attribute.code/' . $idx ) ) ?>"
																<?= $this->site()->readonly( $this->item->getSiteId() ) ?>>
														</td>
														<td>
															<input type="text" class="service-attr-value form-control" tabindex="1"
																name="<?= $enc->attr( $this->formparam( array( 'item', 'service', $type, $serviceId, 'order.service.attribute.value', '' ) ) ) ?>"
																value="<?= $enc->attr( $this->get( 'itemData/service/' . $type . '/' . $serviceId . '/order.service.attribute.value/' . $idx ) ) ?>"
																<?= $this->site()->readonly( $this->item->getSiteId() ) ?>>
														</td>
														<td class="actions">
															<?php if( !$this->site()->readonly( $this->item->getSiteId() ) ) : ?>
																<div class="btn act-delete fa" tabindex="1"
																	title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>">
																</div>
															<?php endif ?>
														</td>
													</tr>
												<?php endforeach ?>
											</tbody>
										</table>
									</div>
								</div>

							<?php endforeach ?>
						<?php endforeach ?>
					</div>

					<?php if( $this->site()->siteid() == $this->item->getSiteId() ) : ?>
						<div class="row item-summary justify-content-end">
							<div class="col-xl-6 item-total">
								<div class="box">
									<h2 class="item-header"><?= $enc->html( $this->translate( 'admin', 'Order totals' ) ) ?></h2>
									<div class="form-group row total-subtotal">
										<div class="col-6 name"><?= $enc->html( $this->translate( 'admin', 'Sub-total' ) ) ?></div>
										<div class="col-6 value"><?= $enc->html( sprintf( $priceFormat, $this->number( $this->item->getItem()->getPrice()->getValue() ), $currency ) ) ?></div>
									</div>
									<div class="form-group row total-shipping">
										<div class="col-6 name"><?= $enc->html( $this->translate( 'admin', 'Shipping' ) ) ?></div>
										<div class="col-6 value"><?= $enc->html( sprintf( $priceFormat, $this->number( $this->item->getItem()->getPrice()->getCosts() ), $currency ) ) ?></div>
									</div>
									<?php if( $this->item->getItem()->getPrice()->getTaxFlag() === true ) : ?>
										<div class="form-group row total-value">
											<div class="col-6 name"><?= $enc->html( $this->translate( 'admin', 'Total' ) ) ?></div>
											<div class="col-6 value"><?= $enc->html( sprintf( $priceFormat, $this->number( $this->item->getItem()->getPrice()->getValue() + $this->item->getItem()->getPrice()->getCosts() ), $currency ) ) ?></div>
										</div>
									<?php endif ?>
									<div class="form-group row total-tax">
										<div class="col-6 name">
											<?php if( $this->item->getItem()->getPrice()->getTaxFlag() ) : ?>
												<?= $enc->html( $this->translate( 'admin', 'Incl. tax' ) ) ?>
											<?php else : ?>
												<?= $enc->html( $this->translate( 'admin', '+ Tax' ) ) ?>
											<?php endif ?>
										</div>
										<div class="col-6 value"><?= $enc->html( sprintf( $priceFormat, $this->number( $this->item->getItem()->getPrice()->getTaxValue() ), $currency ) ) ?></div>
									</div>
									<?php if( $this->item->getItem()->getPrice()->getTaxFlag() === false ) : ?>
										<div class="form-group row total-value">
											<div class="col-6 name"><?= $enc->html( $this->translate( 'admin', 'Total' ) ) ?></div>
											<div class="col-6 value"><?= $enc->html( sprintf( $priceFormat, $this->number( $this->item->getItem()->getPrice()->getValue() + $this->item->getItem()->getPrice()->getCosts() + $this->item->getItem()->getPrice()->getTaxValue() ), $currency ) ) ?></div>
										</div>
									<?php endif ?>
								</div>
							</div>
						</div>
					<?php endif ?>

				</div>

				<?= $this->get( 'itemBody' ) ?>

			</div>

			<div class="item-actions">
				<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'itemactions' ), ['params' => $params] ) ?>
			</div>
		</div>
	</form>

<?php endif ?>

<?php $this->block()->stop() ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'page' ) ) ?>
