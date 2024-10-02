<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
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

$columnList = [
	'order.id' => $this->translate( 'admin', 'ID' ),
	'order.sitecode' => $this->translate( 'admin', 'Site' ),
	'order.languageid' => $this->translate( 'admin', 'Language' ),
	'order.currencyid' => $this->translate( 'admin', 'Currency' ),
	'order.price' => $this->translate( 'admin', 'Price' ),
	'order.costs' => $this->translate( 'admin', 'Costs' ),
	'order.rebate' => $this->translate( 'admin', 'Rebate' ),
	'order.taxvalue' => $this->translate( 'admin', 'Tax' ),
	'order.customerref' => $this->translate( 'admin', 'Customer reference' ),
	'order.comment' => $this->translate( 'admin', 'Comment' ),
	'order.address.company' => $this->translate( 'admin', 'Company' ),
	'order.address.vatid' => $this->translate( 'admin', 'VAT ID' ),
	'order.address.salutation' => $this->translate( 'admin', 'Salutation' ),
	'order.address.title' => $this->translate( 'admin', 'Title' ),
	'order.address.firstname' => $this->translate( 'admin', 'First name' ),
	'order.address.lastname' => $this->translate( 'admin', 'Last name' ),
	'order.address.address1' => $this->translate( 'admin', 'Address 1' ),
	'order.address.address2' => $this->translate( 'admin', 'Address 2' ),
	'order.address.address3' => $this->translate( 'admin', 'Address 3' ),
	'order.address.postal' => $this->translate( 'admin', 'Zip code' ),
	'order.address.city' => $this->translate( 'admin', 'City' ),
	'order.address.state' => $this->translate( 'admin', 'State' ),
	'order.address.countryid' => $this->translate( 'admin', 'Country' ),
	'order.address.mobile' => $this->translate( 'admin', 'Mobile' ),
	'order.address.telephone' => $this->translate( 'admin', 'Telephone' ),
	'order.address.telefax' => $this->translate( 'admin', 'Facsimile' ),
	'order.address.email' => $this->translate( 'admin', 'E-Mail' ),
	'order.address.website' => $this->translate( 'admin', 'Web site' ),
	'order.ctime' => $this->translate( 'admin', 'Created' ),
	'order.mtime' => $this->translate( 'admin', 'Modified' ),
	'order.editor' => $this->translate( 'admin', 'Editor' ),
];


?>
<div id="order" class="item-order tab-pane fade" role="tabpanel" aria-labelledby="order">

	<?= $this->partial( $this->config( 'admin/jqadm/partial/columns', 'columns' ) ) ?>

	<div class="order-list box"
		data-id="<?= $enc->attr( $this->param( 'id' ) ) ?>"
		data-fields="<?= $enc->attr( $fields ) ?>">

		<column-select tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
			v-bind:titles="<?= $enc->attr( $columnList ) ?>"
			v-bind:fields="<?= $enc->attr( $fields ) ?>"
			v-bind:submit="false"
			v-bind:show="columns"
			v-on:close="columns = false"
			v-on:submit="update($event)">
		</column-select>

		<div class="table-responsive">
			<table class="list-items table table-striped">
				<thead class="list-header">
					<tr>
						<th v-if="fieldlist.includes('order.id')" class="order-id">
							<a v-bind:class="sortclass('order.id')" v-on:click.prevent="orderby('order.id')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'ID' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.sitecode')" class="order-sitecode">
							<a v-bind:class="sortclass('order.sitecode')" v-on:click.prevent="orderby('order.sitecode')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Site' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.languageid')" class="order-languageid">
							<a v-bind:class="sortclass('order.languageid')" v-on:click.prevent="orderby('order.languageid')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.currencyid')" class="order-currencyid">
							<a v-bind:class="sortclass('order.currencyid')" v-on:click.prevent="orderby('order.currencyid')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Currency' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.price')" class="order-price">
							<a v-bind:class="sortclass('order.price')" v-on:click.prevent="orderby('order.price')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Price' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.costs')" class="order-costs">
							<a v-bind:class="sortclass('order.costs')" v-on:click.prevent="orderby('order.costs')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Costs' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.rebate')" class="order-rebate">
							<a v-bind:class="sortclass('order.rebate')" v-on:click.prevent="orderby('order.rebate')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Rabate' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.taxvalue')" class="order-taxvalue">
							<a v-bind:class="sortclass('order.taxvalue')" v-on:click.prevent="orderby('order.taxvalue')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Tax' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.customerref')" class="order-customerref">
							<a v-bind:class="sortclass('order.customerref')" v-on:click.prevent="orderby('order.customerref')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Customer reference' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.comment')" class="order-comment">
							<a v-bind:class="sortclass('order.comment')" v-on:click.prevent="orderby('order.comment')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Comment' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.company')" class="order-address-company">
							<a v-bind:class="sortclass('order.address.company')" v-on:click.prevent="orderby('order.address.company')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Company' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.vatid')" class="order-address-vatid">
							<a v-bind:class="sortclass('order.address.vatid')" v-on:click.prevent="orderby('order.address.vatid')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'VAT ID' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.salutation')" class="order-address-salutation">
							<a v-bind:class="sortclass('order.address.salutation')" v-on:click.prevent="orderby('order.address.salutation')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Salutation' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.title')" class="order-address-title">
							<a v-bind:class="sortclass('order.address.title')" v-on:click.prevent="orderby('order.address.title')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Title' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.firstname')" class="order-address-firstname">
							<a v-bind:class="sortclass('order.address.firstname')" v-on:click.prevent="orderby('order.address.firstname')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'First name' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.lastname')" class="order-address-lastname">
							<a v-bind:class="sortclass('order.address.lastname')" v-on:click.prevent="orderby('order.address.lastname')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Last name' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.address1')" class="order-address-address1">
							<a v-bind:class="sortclass('order.address.address1')" v-on:click.prevent="orderby('order.address.address1')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Address 1' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.address2')" class="order-address-address2">
							<a v-bind:class="sortclass('order.address.address2')" v-on:click.prevent="orderby('order.address.address2')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Address 2' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.address3')" class="order-address-address3">
							<a v-bind:class="sortclass('order.address.address3')" v-on:click.prevent="orderby('order.address.address3')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Address 3' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.postal')" class="order-address-postal">
							<a v-bind:class="sortclass('order.address.postal')" v-on:click.prevent="orderby('order.address.postal')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Zip code' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.city')" class="order-address-city">
							<a v-bind:class="sortclass('order.address.city')" v-on:click.prevent="orderby('order.address.city')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'City' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.state')" class="order-address-state">
							<a v-bind:class="sortclass('order.address.state')" v-on:click.prevent="orderby('order.address.state')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'State' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.countryid')" class="order-address-countryid">
							<a v-bind:class="sortclass('order.address.countryid')" v-on:click.prevent="orderby('order.address.countryid')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Country' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.telephone')" class="order-address-telephone">
							<a v-bind:class="sortclass('order.address.telephone')" v-on:click.prevent="orderby('order.address.telephone')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Telephone' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.telefax')" class="order-address-telefax">
							<a v-bind:class="sortclass('order.address.telefax')" v-on:click.prevent="orderby('order.address.telefax')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Facsimile' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.mobile')" class="order-address-mobile">
							<a v-bind:class="sortclass('order.address.mobile')" v-on:click.prevent="orderby('order.address.mobile')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Mobile' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.email')" class="order-address-email">
							<a v-bind:class="sortclass('order.address.email')" v-on:click.prevent="orderby('order.address.email')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'E-Mail' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('order.address.website')" class="order-address-website">
							<a v-bind:class="sortclass('order.address.website')" v-on:click.prevent="orderby('order.address.website')"
								tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Web site' ) ) ?>
							</a>
						</th>

						<th class="actions">
							<a class="btn act-columns icon" href="#" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Columns' ) ) ?>"
								v-on:click.prevent.stop="columns = true">
							</a>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr class="list-search">
						<td v-if="fieldlist.includes('order.id')" class="order-id">
							<input v-on:change="find($event, 'order.id')" v-bind:value="value('order.id')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.sitecode')" class="order-sitecode">
							<input v-on:change="find($event, 'order.sitecode', '=~')" v-bind:value="value('order.sitecode')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.languageid')" class="order-languageid">
							<input v-on:change="find($event, 'order.languageid')" v-bind:value="value('order.languageid')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.currencyid')" class="order-currencyid">
							<input v-on:change="find($event, 'order.currencyid')" v-bind:value="value('order.currencyid')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.price')" class="order-price">
							<input v-on:change="find($event, 'order.price')" v-bind:value="value('order.price')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.costs')" class="order-costs">
							<input v-on:change="find($event, 'order.costs')" v-bind:value="value('order.costs')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.rebate')" class="order-rebate">
							<input v-on:change="find($event, 'order.rebate')" v-bind:value="value('order.rebate')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.taxvalue')" class="order-taxvalue">
							<input v-on:change="find($event, 'order.taxvalue')" v-bind:value="value('order.taxvalue')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.customerref')" class="order-customerref">
							<input v-on:change="find($event, 'order.customerref', '=~')" v-bind:value="value('order.customerref')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.comment')" class="order-comment">
							<input v-on:change="find($event, 'order.comment', '=~')" v-bind:value="value('order.comment')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.company')" class="order-address-company">
							<input v-on:change="find($event, 'order.address.company', '=~')" v-bind:value="value('order.address.company')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.vatid')" class="order-address-vatid">
							<input v-on:change="find($event, 'order.address.vatid', '=~')" v-bind:value="value('order.address.vatid')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.salutation')" class="order-address-salutation">
							<input v-on:change="find($event, 'order.address.salutation')" v-bind:value="value('order.address.salutation')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.title')" class="order-address-title">
							<input v-on:change="find($event, 'order.address.title', '=~')" v-bind:value="value('order.address.title')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.firstname')" class="order-address-firstname">
							<input v-on:change="find($event, 'order.address.firstname', '=~')" v-bind:value="value('order.address.firstname')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.lastname')" class="order-address-lastname">
							<input v-on:change="find($event, 'order.address.lastname', '=~')" v-bind:value="value('order.address.lastname')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.address1')" class="order-address-address1">
							<input v-on:change="find($event, 'order.address.address1', '=~')" v-bind:value="value('order.address.address1')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.address2')" class="order-address-address2">
							<input v-on:change="find($event, 'order.address.address2', '=~')" v-bind:value="value('order.address.address2')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.address3')" class="order-address-address3">
							<input v-on:change="find($event, 'order.address.address3', '=~')" v-bind:value="value('order.address.address3')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.postal')" class="order-address-postal">
							<input v-on:change="find($event, 'order.address.postal', '=~')" v-bind:value="value('order.address.postal')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.city')" class="order-address-city">
							<input v-on:change="find($event, 'order.address.city', '=~')" v-bind:value="value('order.address.city')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.state')" class="order-address-state">
							<input v-on:change="find($event, 'order.address.state', '=~')" v-bind:value="value('order.address.state')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.countryid')" class="order-address-countryid">
							<input v-on:change="find($event, 'order.address.countryid')" v-bind:value="value('order.address.countryid')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.mobile')" class="order-address-mobile">
							<input v-on:change="find($event, 'order.address.mobile', '=~')" v-bind:value="value('order.address.mobile')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.telephone')" class="order-address-telephone">
							<input v-on:change="find($event, 'order.address.telephone', '=~')" v-bind:value="value('order.address.telephone')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.telefax')" class="order-address-telefax">
							<input v-on:change="find($event, 'order.address.telefax', '=~')" v-bind:value="value('order.address.telefax')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.email')" class="order-address-email">
							<input v-on:change="find($event, 'order.address.email', '=~')" v-bind:value="value('order.address.email')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('order.address.website')" class="order-address-website">
							<input v-on:change="find($event, 'order.address.website', '=~')" v-bind:value="value('order.address.website')" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>

						<td class="actions">
							<a v-on:click.prevent="submit" class="btn act-search icon" href="#" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Search' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Search' ) ) ?>">
							</a>
							<a v-on:click.prevent="reset" class="btn act-reset icon" href="#" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Reset' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Reset' ) ) ?>"></a>
						</td>
					</tr>

					<tr v-for="(item, idx) in items" class="list-item">
						<td v-if="fieldlist.includes('order.id')" class="order-id">
							<a class="items-field" v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item['id'] }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.sitecode')" class="order-sitecode">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item['sitecode'] }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.languageid')" class="order-languageid">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item['languageid'] }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.currencyid')" class="order-currencyid">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item['currencyid'] }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.price')" class="order-price">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item['price'] }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.costs')" class="order-costs">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item['costs'] }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.rebate')" class="order-rebate">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item['rebate'] }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.taxvalue')" class="order-taxvalue">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item['taxvalue'] }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.customerref')" class="order-customerref">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item['customerref'] }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.comment')" class="order-comment">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'order', 'id' => '_id_'] ) ) ?>`.replace('_id_', item.id)">
								{{ item['comment'] }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.company')" class="order-address-company">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'company') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.vatid')" class="order-address-vatid">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'vatid') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.salutation')" class="order-address-salutation">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'salutation') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.title')" class="order-address-title">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'title') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.firstname')" class="order-address-firstname">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'firstname') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.lastname')" class="order-address-lastname">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'lastname') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.address1')" class="order-address-address1">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'address1') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.address2')" class="order-address-address2">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'address2') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.address3')" class="order-address-address3">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'address3') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.postal')" class="order-address-postal">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'postal') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.city')" class="order-address-city">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'city') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.state')" class="order-address-state">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'state') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.countryid')" class="order-address-countryid">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'countryid') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.mobile')" class="order-address-mobile">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'mobile') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.telephone')" class="order-address-telephone">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'telephone') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.telefax')" class="order-address-telefax">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'telefax') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.email')" class="order-address-email">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'email') }}
							</a>
						</td>
						<td v-if="fieldlist.includes('order.address.website')" class="order-address-website">
							<a class="items-field"  v-bind:href="`<?= $enc->js( $this->link( 'admin/jqadm/url/get', ['resource' => 'customer', 'id' => '_id_'] ) ) ?>`.replace('_id_', item['customerid'])">
								{{ address(item, 'website') }}
							</a>
						</td>
						<td class="actions"></td>
					</tr>

				</tbody>
			</table>
		</div>

		<div v-if="!items.length" class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No entries found' ) ) ) ?></div>

		<nav class="list-page">
			<page-offset v-model="offset" v-bind:limit="limit" v-bind:total="total" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"></page-offset>
			<page-limit v-model="limit" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"></page-limit>
		</nav>

	</div>

	<?= $this->get( 'orderBody' ) ?>

</div>
