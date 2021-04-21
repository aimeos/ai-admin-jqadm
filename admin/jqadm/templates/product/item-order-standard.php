<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */


$enc = $this->encoder();

$target = $this->config( 'admin/jqadm/url/get/target' );
$cntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/get/action', 'get' );
$config = $this->config( 'admin/jqadm/url/get/config', [] );


/** admin/jqadm/product/order/fields
 * List of list and order columns that should be displayed in the product order view
 *
 * Changes the list of list and order columns shown by default in the product order view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "product.lists.status" for the status value.
 *
 * @param array List of field names, i.e. search keys
 * @since 2019.10
 * @category Developer
 */
$fields = ['order.base.id', 'order.base.currencyid', 'order.base.price', 'order.base.address.firstname', 'order.base.address.lastname', 'order.base.address.city'];
$fields = $this->config( 'admin/jqadm/product/order/fields', $fields );


?>
<div id="order" class="item-order tab-pane fade" role="tabpanel" aria-labelledby="order">

	<div class="order-list box"
		data-id="<?= $enc->attr( $this->param( 'id' ) ) ?>"
		data-fields="<?= $enc->attr( $fields ) ?>">

		<div class="table-responsive">
			<table class="list-items table table-striped">
				<thead class="list-header">
					<tr>
						<th v-if="fields.includes('order.base.id')" class="order-base-id">
							<a v-bind:class="sortclass('order.base.id')" v-on:click.prevent="orderby('order.base.id')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'ID' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.sitecode')" class="order-base-sitecode">
							<a v-bind:class="sortclass('order.base.sitecode')" v-on:click.prevent="orderby('order.base.sitecode')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Site' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.languageid')" class="order-base-languageid">
							<a v-bind:class="sortclass('order.base.languageid')" v-on:click.prevent="orderby('order.base.languageid')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.currencyid')" class="order-base-currencyid">
							<a v-bind:class="sortclass('order.base.currencyid')" v-on:click.prevent="orderby('order.base.currencyid')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Currency' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.price')" class="order-base-price">
							<a v-bind:class="sortclass('order.base.price')" v-on:click.prevent="orderby('order.base.price')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Price' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.costs')" class="order-base-costs">
							<a v-bind:class="sortclass('order.base.costs')" v-on:click.prevent="orderby('order.base.costs')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Costs' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.rebate')" class="order-base-rebate">
							<a v-bind:class="sortclass('order.base.rebate')" v-on:click.prevent="orderby('order.base.rebate')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Rabate' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.tax')" class="order-base-tax">
							<a v-bind:class="sortclass('order.base.tax')" v-on:click.prevent="orderby('order.base.tax')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Tax' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.customerref')" class="order-base-customerref">
							<a v-bind:class="sortclass('order.base.customerref')" v-on:click.prevent="orderby('order.base.customerref')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Customer reference' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.comment')" class="order-base-comment">
							<a v-bind:class="sortclass('order.base.comment')" v-on:click.prevent="orderby('order.base.comment')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Comment' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.company')" class="order-base-address-company">
							<a v-bind:class="sortclass('order.base.address.company')" v-on:click.prevent="orderby('order.base.address.company')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Company' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.vatid')" class="order-base-address-vatid">
							<a v-bind:class="sortclass('order.base.address.vatid')" v-on:click.prevent="orderby('order.base.address.vatid')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'VAT ID' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.salutation')" class="order-base-address-salutation">
							<a v-bind:class="sortclass('order.base.address.salutation')" v-on:click.prevent="orderby('order.base.address.salutation')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Salutation' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.title')" class="order-base-address-title">
							<a v-bind:class="sortclass('order.base.address.title')" v-on:click.prevent="orderby('order.base.address.title')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Title' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.firstname')" class="order-base-address-firstname">
							<a v-bind:class="sortclass('order.base.address.firstname')" v-on:click.prevent="orderby('order.base.address.firstname')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'First name' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.lastname')" class="order-base-address-lastname">
							<a v-bind:class="sortclass('order.base.address.lastname')" v-on:click.prevent="orderby('order.base.address.lastname')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Last name' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.address1')" class="order-base-address-address1">
							<a v-bind:class="sortclass('order.base.address.address1')" v-on:click.prevent="orderby('order.base.address.address1')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Address 1' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.address2')" class="order-base-address-address2">
							<a v-bind:class="sortclass('order.base.address.address2')" v-on:click.prevent="orderby('order.base.address.address2')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Address 2' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.address3')" class="order-base-address-address3">
							<a v-bind:class="sortclass('order.base.address.address3')" v-on:click.prevent="orderby('order.base.address.address3')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Address 3' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.postal')" class="order-base-address-postal">
							<a v-bind:class="sortclass('order.base.address.postal')" v-on:click.prevent="orderby('order.base.address.postal')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Zip code' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.city')" class="order-base-address-city">
							<a v-bind:class="sortclass('order.base.address.city')" v-on:click.prevent="orderby('order.base.address.city')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'City' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.state')" class="order-base-address-state">
							<a v-bind:class="sortclass('order.base.address.state')" v-on:click.prevent="orderby('order.base.address.state')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'State' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.countryid')" class="order-base-address-countryid">
							<a v-bind:class="sortclass('order.base.address.countryid')" v-on:click.prevent="orderby('order.base.address.countryid')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Country' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.telephone')" class="order-base-address-telephone">
							<a v-bind:class="sortclass('order.base.address.telephone')" v-on:click.prevent="orderby('order.base.address.telephone')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Telephone' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.telefax')" class="order-base-address-telefax">
							<a v-bind:class="sortclass('order.base.address.telefax')" v-on:click.prevent="orderby('order.base.address.telefax')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Facsimile' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.email')" class="order-base-address-email">
							<a v-bind:class="sortclass('order.base.address.email')" v-on:click.prevent="orderby('order.base.address.email')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'E-Mail' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.base.address.website')" class="order-base-address-website">
							<a v-bind:class="sortclass('order.base.address.website')" v-on:click.prevent="orderby('order.base.address.website')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Web site' ) ) ?>
							</a>
						</th>

						<th class="actions">
							<div class="dropdown filter-columns">
								<button class="btn act-columns fa" type="button" id="dropdownMenuButton-<?= $this->get( 'group' ) ?>"
									data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
									aria-label="<?= $enc->attr( $this->translate( 'admin', 'Columns' ) ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Columns' ) ) ?>">
								</button>
								<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton-<?= $this->get( 'group' ) ?>">
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.id')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.id')"
												v-bind:checked="fields.includes('order.base.id')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'ID' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.sitecode')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.sitecode')"
												v-bind:checked="fields.includes('order.base.sitecode')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Site' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.languageid')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.languageid')"
												v-bind:checked="fields.includes('order.base.languageid')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.currencyid')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.currencyid')"
												v-bind:checked="fields.includes('order.base.currencyid')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Currency' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.price')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.price')"
												v-bind:checked="fields.includes('order.base.price')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Price' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.costs')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.costs')"
												v-bind:checked="fields.includes('order.base.costs')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Costs' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.rebate')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.rebate')"
												v-bind:checked="fields.includes('order.base.rebate')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Rebate' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.tax')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.tax')"
												v-bind:checked="fields.includes('order.base.tax')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Tax' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.customerref')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.customerref')"
												v-bind:checked="fields.includes('order.base.customerref')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Customer reference' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.comment')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.comment')"
												v-bind:checked="fields.includes('order.base.comment')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Comment' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.company')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.company')"
												v-bind:checked="fields.includes('order.base.address.company')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Company' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.vatid')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.vatid')"
												v-bind:checked="fields.includes('order.base.address.vatid')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'VAT ID' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.salutation')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.salutation')"
												v-bind:checked="fields.includes('order.base.address.salutation')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Salutation' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.title')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.title')"
												v-bind:checked="fields.includes('order.base.address.title')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Title' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.firstname')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.firstname')"
												v-bind:checked="fields.includes('order.base.address.firstname')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'First name' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.lastname')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.lastname')"
												v-bind:checked="fields.includes('order.base.address.lastname')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Last name' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.address1')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.address1')"
												v-bind:checked="fields.includes('order.base.address.address1')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Address 1' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.address2')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.address2')"
												v-bind:checked="fields.includes('order.base.address.address2')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Address 2' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.address3')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.address3')"
												v-bind:checked="fields.includes('order.base.address.address3')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Address 3' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.postal')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.postal')"
												v-bind:checked="fields.includes('order.base.address.postal')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Zip code' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.city')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.city')"
												v-bind:checked="fields.includes('order.base.address.city')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'City' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.state')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.state')"
												v-bind:checked="fields.includes('order.base.address.state')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'State' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.countryid')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.countryid')"
												v-bind:checked="fields.includes('order.base.address.countryid')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Country' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.telephone')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.telephone')"
												v-bind:checked="fields.includes('order.base.address.telephone')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Telephone' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.telefax')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.telefax')"
												v-bind:checked="fields.includes('order.base.address.telefax')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Facsimile' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.email')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.email')"
												v-bind:checked="fields.includes('order.base.address.email')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'E-Mail' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.base.address.website')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.base.address.website')"
												v-bind:checked="fields.includes('order.base.address.website')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" />
											<?= $enc->html( $this->translate( 'admin', 'Web site' ) ) ?>
										</label></a>
									</li>
								</ul>
							</div>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr class="list-search">
						<td v-if="fields.includes('order.base.id')" class="order-base-id">
							<input v-on:change="find($event, 'order.base.id')" v-bind:value="value('order.base.id')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.sitecode')" class="order-base-sitecode">
							<input v-on:change="find($event, 'order.base.sitecode', '=~')" v-bind:value="value('order.base.sitecode')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.languageid')" class="order-base-languageid">
							<input v-on:change="find($event, 'order.base.languageid')" v-bind:value="value('order.base.languageid')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.currencyid')" class="order-base-currencyid">
							<input v-on:change="find($event, 'order.base.currencyid')" v-bind:value="value('order.base.currencyid')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.price')" class="order-base-price">
							<input v-on:change="find($event, 'order.base.price')" v-bind:value="value('order.base.price')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.costs')" class="order-base-costs">
							<input v-on:change="find($event, 'order.base.costs')" v-bind:value="value('order.base.costs')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.rebate')" class="order-base-rebate">
							<input v-on:change="find($event, 'order.base.rebate')" v-bind:value="value('order.base.rebate')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.tax')" class="order-base-tax">
							<input v-on:change="find($event, 'order.base.tax')" v-bind:value="value('order.base.tax')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.customerref')" class="order-base-customerref">
							<input v-on:change="find($event, 'order.base.customerref', '=~')" v-bind:value="value('order.base.customerref')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.comment')" class="order-base-comment">
							<input v-on:change="find($event, 'order.base.comment', '=~')" v-bind:value="value('order.base.comment')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.company')" class="order-base-address-company">
							<input v-on:change="find($event, 'order.base.address.company', '=~')" v-bind:value="value('order.base.address.company')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.vatid')" class="order-base-address-vatid">
							<input v-on:change="find($event, 'order.base.address.vatid', '=~')" v-bind:value="value('order.base.address.vatid')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.salutation')" class="order-base-address-salutation">
							<input v-on:change="find($event, 'order.base.address.salutation')" v-bind:value="value('order.base.address.salutation')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.title')" class="order-base-address-title">
							<input v-on:change="find($event, 'order.base.address.title', '=~')" v-bind:value="value('order.base.address.title')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.firstname')" class="order-base-address-firstname">
							<input v-on:change="find($event, 'order.base.address.firstname', '=~')" v-bind:value="value('order.base.address.firstname')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.lastname')" class="order-base-address-lastname">
							<input v-on:change="find($event, 'order.base.address.lastname', '=~')" v-bind:value="value('order.base.address.lastname')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.address1')" class="order-base-address-address1">
							<input v-on:change="find($event, 'order.base.address.address1', '=~')" v-bind:value="value('order.base.address.address1')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.address2')" class="order-base-address-address2">
							<input v-on:change="find($event, 'order.base.address.address2', '=~')" v-bind:value="value('order.base.address.address2')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.address3')" class="order-base-address-address3">
							<input v-on:change="find($event, 'order.base.address.address3', '=~')" v-bind:value="value('order.base.address.address3')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.postal')" class="order-base-address-postal">
							<input v-on:change="find($event, 'order.base.address.postal', '=~')" v-bind:value="value('order.base.address.postal')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.city')" class="order-base-address-city">
							<input v-on:change="find($event, 'order.base.address.city', '=~')" v-bind:value="value('order.base.address.city')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.state')" class="order-base-address-state">
							<input v-on:change="find($event, 'order.base.address.state', '=~')" v-bind:value="value('order.base.address.state')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.countryid')" class="order-base-address-countryid">
							<input v-on:change="find($event, 'order.base.address.countryid')" v-bind:value="value('order.base.address.countryid')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.telephone')" class="order-base-address-telephone">
							<input v-on:change="find($event, 'order.base.address.telephone', '=~')" v-bind:value="value('order.base.address.telephone')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.telefax')" class="order-base-address-telefax">
							<input v-on:change="find($event, 'order.base.address.telefax', '=~')" v-bind:value="value('order.base.address.telefax')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.email')" class="order-base-address-email">
							<input v-on:change="find($event, 'order.base.address.email', '=~')" v-bind:value="value('order.base.address.email')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>
						<td v-if="fields.includes('order.base.address.website')" class="order-base-address-website">
							<input v-on:change="find($event, 'order.base.address.website', '=~')" v-bind:value="value('order.base.address.website')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" />
						</td>

						<td class="actions">
							<a v-on:click.prevent="submit" class="btn act-search fa" href="#" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Search' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Search' ) ) ?>">
							</a>
							<a v-on:click.prevent="reset" class="btn act-reset fa" href="#" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Reset' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Reset' ) ) ?>"></a>
						</td>
					</tr>

					<tr v-for="(item, idx) in items" class="list-item">
						<td v-if="fields.includes('order.base.id')" class="order-base-id">
							<a class="items-field" v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'order', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.base.id'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.sitecode')" class="order-base-sitecode">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'order', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.base.sitecode'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.languageid')" class="order-base-languageid">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'order', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.base.languageid'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.currencyid')" class="order-base-currencyid">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'order', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.base.currencyid'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.price')" class="order-base-price">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'order', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.base.price'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.costs')" class="order-base-costs">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'order', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.base.costs'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.rebate')" class="order-base-rebate">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'order', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.base.rebate'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.tax')" class="order-base-tax">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'order', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.base.tax'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.customerref')" class="order-base-customerref">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'order', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.base.customerref'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.comment')" class="order-base-comment">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'order', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.base.comment'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.company')" class="order-base-address-company">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.company') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.vatid')" class="order-base-address-vatid">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.vatid') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.salutation')" class="order-base-address-salutation">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.salutation') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.title')" class="order-base-address-title">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.title') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.firstname')" class="order-base-address-firstname">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.firstname') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.lastname')" class="order-base-address-lastname">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.lastname') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.address1')" class="order-base-address-address1">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.address1') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.address2')" class="order-base-address-address2">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.address2') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.address3')" class="order-base-address-address3">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.address3') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.postal')" class="order-base-address-postal">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.postal') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.city')" class="order-base-address-city">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.city') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.state')" class="order-base-address-state">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.state') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.countryid')" class="order-base-address-countryid">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.countryid') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.telephone')" class="order-base-address-telephone">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.telephone') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.telefax')" class="order-base-address-telefax">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.telefax') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.email')" class="order-base-address-email">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.email') }}
							</a>
						</td>
						<td v-if="fields.includes('order.base.address.website')" class="order-base-address-website">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->url( $target, $cntl, $action, ['resource' => 'customer', 'id' => '_id_'], [], $config ) ) ?>`.replace('_id_', item.attributes['order.base.customerid'])">
								{{ related(item, 'order/base/address', 'order.base.address.website') }}
							</a>
						</td>
						<td class="actions"></td>
					</tr>

				</tbody>
			</table>
		</div>

		<div v-if="!items.length" class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ) ?></div>

		<nav class="list-page">
			<ul class="page-offset pagination">
				<li v-bind:class="{disabled: first === null}" class="page-item">
					<button v-on:click.prevent="offset = first" class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'First' ) ) ?>">
						<span class="fa fa-fast-backward" aria-hidden="true"></span>
					</button>
				</li><!--
				--><li v-bind:class="{disabled: prev === null}" class="page-item">
					<button v-on:click.prevent="offset = prev" class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Previous' ) ) ?>">
						<span class="fa fa-step-backward" aria-hidden="true"></span>
					</button>
				</li><!--
				--><li class="page-item disabled">
					<button class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
						{{ pagecnt(`<?= $enc->js( $this->translate( 'admin', 'Page %1$d of %2$d' ) ) ?>`) }}
					</button>
				</li><!--
				--><li v-bind:class="{disabled: next === null}" class="page-item">
					<button v-on:click.prevent="offset = next" class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Next' ) ) ?>">
						<span class="fa fa-step-forward" aria-hidden="true"></span>
					</button>
				</li><!--
				--><li v-bind:class="{disabled: last === null}" class="page-item">
					<button v-on:click.prevent="offset = last" class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Last' ) ) ?>">
						<span class="fa fa-fast-forward" aria-hidden="true"></span>
					</button>
				</li>
			</ul>
			<div class="page-limit btn-group dropup" role="group">
				<button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
					tabindex="<?= $this->get( 'tabindex', 1 ) ?>" aria-haspopup="true" aria-expanded="false">
					{{ limit }} <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li class="dropdown-item">
						<a v-on:click.prevent="limit = 25" href="#" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">25</a>
					</li>
					<li class="dropdown-item">
						<a v-on:click.prevent="limit = 50" href="#" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">50</a>
					</li>
					<li class="dropdown-item">
						<a v-on:click.prevent="limit = 100" href="#" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">100</a>
					</li>
					<li class="dropdown-item">
						<a v-on:click.prevent="limit = 250" href="#" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">250</a>
					</li>
				</ul>
			</div>
		</nav>

	</div>

	<?= $this->get( 'orderBody' ) ?>

</div>
