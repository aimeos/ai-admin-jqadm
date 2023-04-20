<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


$enc = $this->encoder();


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
 */
$fields = ['order.id', 'order.currencyid', 'order.price', 'order.address.firstname', 'order.address.lastname', 'order.address.city'];
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
						<th v-if="fields.includes('order.id')" class="order-id">
							<a v-bind:class="sortclass('order.id')" v-on:click.prevent="orderby('order.id')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'ID' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.sitecode')" class="order-sitecode">
							<a v-bind:class="sortclass('order.sitecode')" v-on:click.prevent="orderby('order.sitecode')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Site' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.languageid')" class="order-languageid">
							<a v-bind:class="sortclass('order.languageid')" v-on:click.prevent="orderby('order.languageid')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.currencyid')" class="order-currencyid">
							<a v-bind:class="sortclass('order.currencyid')" v-on:click.prevent="orderby('order.currencyid')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Currency' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.price')" class="order-price">
							<a v-bind:class="sortclass('order.price')" v-on:click.prevent="orderby('order.price')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Price' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.costs')" class="order-costs">
							<a v-bind:class="sortclass('order.costs')" v-on:click.prevent="orderby('order.costs')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Costs' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.rebate')" class="order-rebate">
							<a v-bind:class="sortclass('order.rebate')" v-on:click.prevent="orderby('order.rebate')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Rabate' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.tax')" class="order-tax">
							<a v-bind:class="sortclass('order.tax')" v-on:click.prevent="orderby('order.tax')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Tax' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.customerref')" class="order-customerref">
							<a v-bind:class="sortclass('order.customerref')" v-on:click.prevent="orderby('order.customerref')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Customer reference' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.comment')" class="order-comment">
							<a v-bind:class="sortclass('order.comment')" v-on:click.prevent="orderby('order.comment')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Comment' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.company')" class="order-address-company">
							<a v-bind:class="sortclass('order.address.company')" v-on:click.prevent="orderby('order.address.company')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Company' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.vatid')" class="order-address-vatid">
							<a v-bind:class="sortclass('order.address.vatid')" v-on:click.prevent="orderby('order.address.vatid')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'VAT ID' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.salutation')" class="order-address-salutation">
							<a v-bind:class="sortclass('order.address.salutation')" v-on:click.prevent="orderby('order.address.salutation')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Salutation' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.title')" class="order-address-title">
							<a v-bind:class="sortclass('order.address.title')" v-on:click.prevent="orderby('order.address.title')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Title' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.firstname')" class="order-address-firstname">
							<a v-bind:class="sortclass('order.address.firstname')" v-on:click.prevent="orderby('order.address.firstname')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'First name' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.lastname')" class="order-address-lastname">
							<a v-bind:class="sortclass('order.address.lastname')" v-on:click.prevent="orderby('order.address.lastname')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Last name' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.address1')" class="order-address-address1">
							<a v-bind:class="sortclass('order.address.address1')" v-on:click.prevent="orderby('order.address.address1')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Address 1' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.address2')" class="order-address-address2">
							<a v-bind:class="sortclass('order.address.address2')" v-on:click.prevent="orderby('order.address.address2')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Address 2' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.address3')" class="order-address-address3">
							<a v-bind:class="sortclass('order.address.address3')" v-on:click.prevent="orderby('order.address.address3')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Address 3' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.postal')" class="order-address-postal">
							<a v-bind:class="sortclass('order.address.postal')" v-on:click.prevent="orderby('order.address.postal')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Zip code' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.city')" class="order-address-city">
							<a v-bind:class="sortclass('order.address.city')" v-on:click.prevent="orderby('order.address.city')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'City' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.state')" class="order-address-state">
							<a v-bind:class="sortclass('order.address.state')" v-on:click.prevent="orderby('order.address.state')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'State' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.countryid')" class="order-address-countryid">
							<a v-bind:class="sortclass('order.address.countryid')" v-on:click.prevent="orderby('order.address.countryid')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Country' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.telephone')" class="order-address-telephone">
							<a v-bind:class="sortclass('order.address.telephone')" v-on:click.prevent="orderby('order.address.telephone')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Telephone' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.telefax')" class="order-address-telefax">
							<a v-bind:class="sortclass('order.address.telefax')" v-on:click.prevent="orderby('order.address.telefax')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Facsimile' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.email')" class="order-address-email">
							<a v-bind:class="sortclass('order.address.email')" v-on:click.prevent="orderby('order.address.email')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'E-Mail' ) ) ?>
							</a>
						</th>
						<th v-if="fields.includes('order.address.website')" class="order-address-website">
							<a v-bind:class="sortclass('order.address.website')" v-on:click.prevent="orderby('order.address.website')"
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
										<a v-on:click.prevent.stop="toggleField('order.id')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.id')"
												v-bind:checked="fields.includes('order.id')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'ID' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.sitecode')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.sitecode')"
												v-bind:checked="fields.includes('order.sitecode')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Site' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.languageid')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.languageid')"
												v-bind:checked="fields.includes('order.languageid')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.currencyid')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.currencyid')"
												v-bind:checked="fields.includes('order.currencyid')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Currency' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.price')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.price')"
												v-bind:checked="fields.includes('order.price')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Price' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.costs')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.costs')"
												v-bind:checked="fields.includes('order.costs')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Costs' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.rebate')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.rebate')"
												v-bind:checked="fields.includes('order.rebate')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Rebate' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.tax')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.tax')"
												v-bind:checked="fields.includes('order.tax')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Tax' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.customerref')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.customerref')"
												v-bind:checked="fields.includes('order.customerref')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Customer reference' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.comment')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.comment')"
												v-bind:checked="fields.includes('order.comment')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Comment' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.company')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.company')"
												v-bind:checked="fields.includes('order.address.company')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Company' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.vatid')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.vatid')"
												v-bind:checked="fields.includes('order.address.vatid')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'VAT ID' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.salutation')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.salutation')"
												v-bind:checked="fields.includes('order.address.salutation')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Salutation' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.title')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.title')"
												v-bind:checked="fields.includes('order.address.title')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Title' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.firstname')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.firstname')"
												v-bind:checked="fields.includes('order.address.firstname')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'First name' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.lastname')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.lastname')"
												v-bind:checked="fields.includes('order.address.lastname')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Last name' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.address1')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.address1')"
												v-bind:checked="fields.includes('order.address.address1')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Address 1' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.address2')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.address2')"
												v-bind:checked="fields.includes('order.address.address2')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Address 2' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.address3')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.address3')"
												v-bind:checked="fields.includes('order.address.address3')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Address 3' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.postal')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.postal')"
												v-bind:checked="fields.includes('order.address.postal')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Zip code' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.city')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.city')"
												v-bind:checked="fields.includes('order.address.city')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'City' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.state')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.state')"
												v-bind:checked="fields.includes('order.address.state')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'State' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.countryid')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.countryid')"
												v-bind:checked="fields.includes('order.address.countryid')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Country' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.telephone')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.telephone')"
												v-bind:checked="fields.includes('order.address.telephone')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Telephone' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.telefax')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.telefax')"
												v-bind:checked="fields.includes('order.address.telefax')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Facsimile' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.email')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.email')"
												v-bind:checked="fields.includes('order.address.email')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'E-Mail' ) ) ?>
										</label></a>
									</li>
									<li class="dropdown-item">
										<a v-on:click.prevent.stop="toggleField('order.address.website')" href="#"><label>
											<input class="form-check-input"
												v-on:click.capture.stop="toggleField('order.address.website')"
												v-bind:checked="fields.includes('order.address.website')"
												type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>">
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
						<td v-if="fields.includes('order.id')" class="order-id">
							<input v-on:change="find($event, 'order.id')" v-bind:value="value('order.id')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.sitecode')" class="order-sitecode">
							<input v-on:change="find($event, 'order.sitecode', '=~')" v-bind:value="value('order.sitecode')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.languageid')" class="order-languageid">
							<input v-on:change="find($event, 'order.languageid')" v-bind:value="value('order.languageid')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.currencyid')" class="order-currencyid">
							<input v-on:change="find($event, 'order.currencyid')" v-bind:value="value('order.currencyid')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.price')" class="order-price">
							<input v-on:change="find($event, 'order.price')" v-bind:value="value('order.price')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.costs')" class="order-costs">
							<input v-on:change="find($event, 'order.costs')" v-bind:value="value('order.costs')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.rebate')" class="order-rebate">
							<input v-on:change="find($event, 'order.rebate')" v-bind:value="value('order.rebate')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.tax')" class="order-tax">
							<input v-on:change="find($event, 'order.tax')" v-bind:value="value('order.tax')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.customerref')" class="order-customerref">
							<input v-on:change="find($event, 'order.customerref', '=~')" v-bind:value="value('order.customerref')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.comment')" class="order-comment">
							<input v-on:change="find($event, 'order.comment', '=~')" v-bind:value="value('order.comment')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.company')" class="order-address-company">
							<input v-on:change="find($event, 'order.address.company', '=~')" v-bind:value="value('order.address.company')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.vatid')" class="order-address-vatid">
							<input v-on:change="find($event, 'order.address.vatid', '=~')" v-bind:value="value('order.address.vatid')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.salutation')" class="order-address-salutation">
							<input v-on:change="find($event, 'order.address.salutation')" v-bind:value="value('order.address.salutation')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.title')" class="order-address-title">
							<input v-on:change="find($event, 'order.address.title', '=~')" v-bind:value="value('order.address.title')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.firstname')" class="order-address-firstname">
							<input v-on:change="find($event, 'order.address.firstname', '=~')" v-bind:value="value('order.address.firstname')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.lastname')" class="order-address-lastname">
							<input v-on:change="find($event, 'order.address.lastname', '=~')" v-bind:value="value('order.address.lastname')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.address1')" class="order-address-address1">
							<input v-on:change="find($event, 'order.address.address1', '=~')" v-bind:value="value('order.address.address1')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.address2')" class="order-address-address2">
							<input v-on:change="find($event, 'order.address.address2', '=~')" v-bind:value="value('order.address.address2')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.address3')" class="order-address-address3">
							<input v-on:change="find($event, 'order.address.address3', '=~')" v-bind:value="value('order.address.address3')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.postal')" class="order-address-postal">
							<input v-on:change="find($event, 'order.address.postal', '=~')" v-bind:value="value('order.address.postal')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.city')" class="order-address-city">
							<input v-on:change="find($event, 'order.address.city', '=~')" v-bind:value="value('order.address.city')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.state')" class="order-address-state">
							<input v-on:change="find($event, 'order.address.state', '=~')" v-bind:value="value('order.address.state')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.countryid')" class="order-address-countryid">
							<input v-on:change="find($event, 'order.address.countryid')" v-bind:value="value('order.address.countryid')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.telephone')" class="order-address-telephone">
							<input v-on:change="find($event, 'order.address.telephone', '=~')" v-bind:value="value('order.address.telephone')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.telefax')" class="order-address-telefax">
							<input v-on:change="find($event, 'order.address.telefax', '=~')" v-bind:value="value('order.address.telefax')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.email')" class="order-address-email">
							<input v-on:change="find($event, 'order.address.email', '=~')" v-bind:value="value('order.address.email')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fields.includes('order.address.website')" class="order-address-website">
							<input v-on:change="find($event, 'order.address.website', '=~')" v-bind:value="value('order.address.website')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
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
						<td v-if="fields.includes('order.id')" class="order-id">
							<a class="items-field" v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.id'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.sitecode')" class="order-sitecode">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.sitecode'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.languageid')" class="order-languageid">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.languageid'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.currencyid')" class="order-currencyid">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.currencyid'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.price')" class="order-price">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.price'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.costs')" class="order-costs">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.costs'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.rebate')" class="order-rebate">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.rebate'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.tax')" class="order-tax">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.tax'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.customerref')" class="order-customerref">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.customerref'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.comment')" class="order-comment">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item.attributes['order.comment'] }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.company')" class="order-address-company">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.company') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.vatid')" class="order-address-vatid">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.vatid') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.salutation')" class="order-address-salutation">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.salutation') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.title')" class="order-address-title">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.title') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.firstname')" class="order-address-firstname">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.firstname') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.lastname')" class="order-address-lastname">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.lastname') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.address1')" class="order-address-address1">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.address1') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.address2')" class="order-address-address2">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.address2') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.address3')" class="order-address-address3">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.address3') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.postal')" class="order-address-postal">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.postal') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.city')" class="order-address-city">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.city') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.state')" class="order-address-state">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.state') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.countryid')" class="order-address-countryid">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.countryid') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.telephone')" class="order-address-telephone">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.telephone') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.telefax')" class="order-address-telefax">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.telefax') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.email')" class="order-address-email">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.email') }}
							</a>
						</td>
						<td v-if="fields.includes('order.address.website')" class="order-address-website">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.attributes['order.customerid'])">
								{{ related(item, 'order/address', 'order.address.website') }}
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
						<span class="fa icon-first" aria-hidden="true"></span>
					</button>
				</li><!--
				--><li v-bind:class="{disabled: prev === null}" class="page-item">
					<button v-on:click.prevent="offset = prev" class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Previous' ) ) ?>">
						<span class="fa icon-prev" aria-hidden="true"></span>
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
						<span class="fa icon-next" aria-hidden="true"></span>
					</button>
				</li><!--
				--><li v-bind:class="{disabled: last === null}" class="page-item">
					<button v-on:click.prevent="offset = last" class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Last' ) ) ?>">
						<span class="fa icon-last" aria-hidden="true"></span>
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
