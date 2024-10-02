<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2024
 */

$enc = $this->encoder();


/** admin/jqadm/subscription/fields
 * List of subscription columns that should be displayed in the list view
 *
 * Changes the list of subscription columns shown by default in the subscription list view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "subscription.id" for the subscription ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2018.04
 */
$default = ['subscription.id', 'subscription.status', 'subscription.datenext', 'subscription.dateend', 'subscription.interval'];
$default = $this->config( 'admin/jqadm/subscription/fields', $default );
$fields = $this->session( 'aimeos/admin/jqadm/subscription/fields', $default );

$searchParams = $params = $this->get( 'pageParams', [] );
$searchParams['page']['start'] = 0;

$searchAttributes = map( $this->get( 'filterAttributes', [] ) )->filter( function( $item ) {
	return $item->isPublic();
} )->call( 'toArray' )->each( function( &$val ) {
	$val = $this->translate( 'admin/ext', $val['label'] ?? ' ' );
} )->all();

$operators = map( $this->get( 'filterOperators/compare', [] ) )->flip()->map( function( $val, $key ) {
	return $this->translate( 'admin/ext', $key );
} )->all();

$columnList = [
	'subscription.id' => $this->translate( 'admin', 'ID' ),
	'subscription.status' => $this->translate( 'admin', 'Status' ),
	'subscription.interval' => $this->translate( 'admin', 'Interval' ),
	'subscription.datenext' => $this->translate( 'admin', 'Next date' ),
	'subscription.dateend' => $this->translate( 'admin', 'End date' ),
	'subscription.reason' => $this->translate( 'admin', 'Reason' ),
	'subscription.period' => $this->translate( 'admin', 'Periods' ),
	'subscription.ctime' => $this->translate( 'admin', 'Created' ),
	'subscription.mtime' => $this->translate( 'admin', 'Modified' ),
	'subscription.editor' => $this->translate( 'admin', 'Editor' ),
	'order.id' => $this->translate( 'admin', 'Order ID' ),
	'order.customerid' => $this->translate( 'admin', 'Customer ID' ),
	'order.sitecode' => $this->translate( 'admin', 'Site' ),
	'order.languageid' => $this->translate( 'admin', 'Language' ),
	'order.currencyid' => $this->translate( 'admin', 'Currency' ),
	'order.taxflag' => $this->translate( 'admin', 'Incl. tax' ),
	'order.comment' => $this->translate( 'admin', 'Comment' ),
	'order.product.type' => $this->translate( 'admin', 'Product type' ),
	'order.product.stocktype' => $this->translate( 'admin', 'Stock type' ),
	'order.product.suppliername' => $this->translate( 'admin', 'Product supplier' ),
	'order.product.prodcode' => $this->translate( 'admin', 'Product code' ),
	'order.product.name' => $this->translate( 'admin', 'Product name' ),
	'order.product.quantity' => $this->translate( 'admin', 'Product quantity' ),
	'order.product.price' => $this->translate( 'admin', 'Product price' ),
	'order.product.costs' => $this->translate( 'admin', 'Product costs' ),
	'order.product.rebate' => $this->translate( 'admin', 'Product rebate' ),
	'order.product.taxvalue' => $this->translate( 'admin', 'Product tax' ),
	'order.product.statusdelivery' => $this->translate( 'admin', 'Product shipping' ),
	'order.product.statuspayment' => $this->translate( 'admin', 'Product payment' ),
	'order.address.salutation' => $this->translate( 'admin', 'Salutation' ),
	'order.address.company' => $this->translate( 'admin', 'Company' ),
	'order.address.vatid' => $this->translate( 'admin', 'VAT ID' ),
	'order.address.title' => $this->translate( 'admin', 'Title' ),
	'order.address.firstname' => $this->translate( 'admin', 'First name' ),
	'order.address.lastname' => $this->translate( 'admin', 'Last name' ),
	'order.address.address1' => $this->translate( 'admin', 'Street' ),
	'order.address.address2' => $this->translate( 'admin', 'House number' ),
	'order.address.address3' => $this->translate( 'admin', 'Floor' ),
	'order.address.postal' => $this->translate( 'admin', 'Zip code' ),
	'order.address.city' => $this->translate( 'admin', 'City' ),
	'order.address.state' => $this->translate( 'admin', 'State' ),
	'order.address.countryid' => $this->translate( 'admin', 'Country' ),
	'order.address.languageid' => $this->translate( 'admin', 'Language' ),
	'order.address.telephone' => $this->translate( 'admin', 'Telephone' ),
	'order.address.telefax' => $this->translate( 'admin', 'Facsimile' ),
	'order.address.email' => $this->translate( 'admin', 'E-Mail' ),
	'order.address.website' => $this->translate( 'admin', 'Web site' ),
];

$deliveryStatusList = [
	null => '',
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

$paymentStatusList = [
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
];

$statusList = [
	0 => $this->translate( 'admin', 'no' ),
	1 => $this->translate( 'admin', 'yes' ),
];

$reasonList = [
	null => '',
	-1 => $this->translate( 'mshop/code', 'reason:-1' ),
	0 => $this->translate( 'mshop/code', 'reason:0' ),
	1 => $this->translate( 'mshop/code', 'reason:1' ),
];


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<?= $this->partial( $this->config( 'admin/jqadm/partial/navsearch', 'navsearch' ) ) ?>
<?= $this->partial( $this->config( 'admin/jqadm/partial/columns', 'columns' ) ) ?>


<div class="list-view"
	data-domain="subscription"
	data-siteid="<?= $enc->attr( $this->site()->siteid() ) ?>"
	data-filter="<?= $enc->attr( $this->session( 'aimeos/admin/jqadm/subscription/filter', new \stdClass ) ) ?>"
	data-items="<?= $enc->attr( $this->get( 'items', map() )->call( 'toArray', [true] )->all() ) ?>">

	<nav class="main-navbar">

		<span class="navbar-brand">
			<?= $enc->html( $this->translate( 'admin', 'Subscription' ) ) ?>
			<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ) ?>)</span>
		</span>

		<div class="btn icon act-search" v-on:click="search = true"
			title="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>"
			aria-label="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>">
		</div>
	</nav>

	<nav-search v-bind:show="search" v-on:close="search = false"
		v-bind:url="`<?= $enc->js( $this->link( 'admin/jqadm/url/search', map( $searchParams )->except( 'filter' )->all() ) ) ?>`"
		v-bind:filter="<?= $enc->attr( (object) $this->session( 'aimeos/admin/jqadm/subscription/filter', new \stdClass ) ) ?>"
		v-bind:operators="<?= $enc->attr( $operators ) ?>"
		v-bind:name="`<?= $enc->js( $this->formparam( ['filter', '_key_', '0'] ) ) ?>`"
		v-bind:attributes="<?= $enc->attr( $searchAttributes ) ?>">
	</nav-search>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
			['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/subscription/page', [] )]
		);
	?>

	<form ref="form" class="list list-subscription" method="POST"
		action="<?= $enc->attr( $this->link( 'admin/jqadm/url/search', $searchParams ) ) ?>">

		<?= $this->csrf()->formfield() ?>

		<column-select tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
			name="<?= $enc->attr( $this->formparam( ['fields', ''] ) ) ?>"
			v-bind:titles="<?= $enc->attr( $columnList ) ?>"
			v-bind:fields="<?= $enc->attr( $fields ) ?>"
			v-bind:show="columns"
			v-on:close="columns = false">
		</column-select>

		<div class="table-responsive">
			<table class="list-items table table-hover table-striped">
				<thead class="list-header">
					<tr>
						<th class="select">
							<button class="btn icon-menu" type="button" data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}'
								aria-expanded="false" title="<?= $enc->attr( $this->translate( 'admin', 'Menu' ) ) ?>">
							</button>
							<ul class="dropdown-menu">
								<li>
									<a class="btn" v-on:click.prevent="batch = true" href="#" tabindex="1">
										<?= $enc->html( $this->translate( 'admin', 'Edit' ) ) ?>
									</a>
								</li>
								<li>
									<a class="btn" v-on:click.prevent="askDelete(null, $event)" tabindex="1"
										href="<?= $enc->attr( $this->link( 'admin/jqadm/url/delete', $params ) ) ?>">
										<?= $enc->html( $this->translate( 'admin', 'Delete' ) ) ?>
									</a>
								</li>
							</ul>
						</th>

						<?= $this->partial(
							$this->config( 'admin/jqadm/partial/listhead', 'listhead' ), [
								'fields' => $fields, 'params' => $params, 'data' => $columnList,
								'sort' => $this->session( 'aimeos/admin/jqadm/subscription/sort' )
							] );
						?>

						<th class="actions">
							<a class="btn icon act-download" tabindex="1"
								href="<?= $enc->attr( $this->link( 'admin/jqadm/url/export', $params ) ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Download' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Download' ) ) ?>">
							</a>

							<a class="btn act-columns icon" href="#" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Columns' ) ) ?>"
								v-on:click.prevent.stop="columns = true">
							</a>
						</th>
					</tr>
				</thead>
				<tbody>

					<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listsearch', 'listsearch' ), [
							'fields' => array_merge( $fields, ['select'] ), 'filter' => $this->session( 'aimeos/admin/jqadm/subscription/filter', [] ),
							'data' => [
								'subscription.id' => ['op' => '=='],
								'subscription.status' => ['op' => '==', 'type' => 'select', 'val' => [
									'1' => $this->translate( 'mshop/code', 'status:1' ),
									'0' => $this->translate( 'mshop/code', 'status:0' ),
									'-1' => $this->translate( 'mshop/code', 'status:-1' ),
									'-2' => $this->translate( 'mshop/code', 'status:-2' ),
								]],
								'subscription.interval' => ['op' => '=~', 'type' => 'string'],
								'subscription.datenext' => ['op' => '-', 'type' => 'datetime-local'],
								'subscription.dateend' => ['op' => '-', 'type' => 'datetime-local'],
								'subscription.reason' => ['op' => '==', 'type' => 'select', 'val' => $reasonList],
								'subscription.period' => ['op' => '==', 'type' => 'string'],
								'subscription.ctime' => ['op' => '-', 'type' => 'datetime-local'],
								'subscription.mtime' => ['op' => '-', 'type' => 'datetime-local'],
								'subscription.editor' => [],
								'order.id' => ['op' => '=='],
								'order.customerid' => ['op' => '=='],
								'order.sitecode' => ['op' => '=='],
								'order.languageid' => ['op' => '=='],
								'order.currencyid' => ['op' => '=='],
								'order.taxflag' => ['op' => '==', 'type' => 'select', 'val' => $statusList],
								'order.comment' => [],
								'order.product.type' => [],
								'order.product.stocktype' => [],
								'order.product.suppliername' => ['op' => '=~', 'type' => 'string'],
								'order.product.prodcode' => [],
								'order.product.name' => [],
								'order.product.quantity' => ['op' => '==', 'type' => 'number'],
								'order.product.price' => ['op' => '==', 'type' => 'number'],
								'order.product.costs' => ['op' => '==', 'type' => 'number'],
								'order.product.rebate' => ['op' => '==', 'type' => 'number'],
								'order.product.taxvalue' => ['op' => '==', 'type' => 'number'],
								'order.product.statusdelivery' => ['op' => '==', 'type' => 'select', 'val' => $deliveryStatusList],
								'order.product.statuspayment' => ['op' => '==', 'type' => 'select', 'val' => $paymentStatusList],
								'order.address.salutation' => ['op' => '==', 'type' => 'select', 'val' => [
									'' => 'none', 'company' => 'company', 'mr' => 'mr', 'ms' => 'ms'
								]],
								'order.address.company' => [],
								'order.address.vatid' => [],
								'order.address.title' => [],
								'order.address.firstname' => [],
								'order.address.lastname' => [],
								'order.address.address1' => [],
								'order.address.address2' => [],
								'order.address.address3' => [],
								'order.address.postal' => [],
								'order.address.city' => [],
								'order.address.state' => [],
								'order.address.countryid' => ['op' => '=='],
								'order.address.languageid' => ['op' => '=='],
								'order.address.telephone' => [],
								'order.address.telefax' => [],
								'order.address.email' => [],
								'order.address.website' => [],
							]
						] );
					?>

					<tr class="batch" v-bind:class="{show: batch}" v-show="batch">
						<td colspan="<?= count( $fields ) + 2 ?>">
							<div class="batch-header">
								<div class="intro">
									<span class="name"><?= $enc->html( $this->translate( 'admin', 'Bulk edit' ) ) ?></span>
									<span class="count">{{ selected }} <?= $enc->html( $this->translate( 'admin', 'selected' ) ) ?></span>
								</div>
								<a class="btn btn-secondary" href="#" v-on:click.prevent="batch = false">
									<?= $enc->html( $this->translate( 'admin', 'Close' ) ) ?>
								</a>
							</div>
							<div class="card">
								<div class="card-header">
									<span><?= $enc->html( $this->translate( 'admin', 'Basic' ) ) ?></span>
									<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'subscription'] ) ) ?>">
										<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
									</button>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-xl-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-subscription-status" class="form-check-input" type="checkbox" v-on:click="setState('item/subscription.status')">
												</div>
												<label class="col-4 form-control-label" for="batch-subscription-status">
													<?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select" v-bind:disabled="state('item/subscription.status')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'subscription.status' ) ) ) ?>">
														<option value=""></option>
														<option value="1"><?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?></option>
														<option value="0"><?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?></option>
														<option value="-1"><?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?></option>
														<option value="-2"><?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?></option>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-subscription-interval" class="form-check-input" type="checkbox" v-on:click="setState('item/subscription.interval')">
												</div>
												<label class="col-4 form-control-label" for="batch-subscription-interval">
													<?= $enc->html( $this->translate( 'admin', 'Interval' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/subscription.interval')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'subscription.interval' ) ) ) ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-subscription-reason" class="form-check-input" type="checkbox" v-on:click="setState('item/subscription.reason')">
												</div>
												<label class="col-4 form-control-label" for="batch-subscription-reason">
													<?= $enc->html( $this->translate( 'admin', 'Reason' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select" v-bind:disabled="state('item/subscription.reason')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'subscription.reason' ) ) ) ?>">
														<option value=""></option>
														<option value="1"><?= $enc->html( $this->translate( 'mshop/code', 'reason:1' ) ) ?></option>
														<option value="0"><?= $enc->html( $this->translate( 'mshop/code', 'reason:0' ) ) ?></option>
														<option value="-1"><?= $enc->html( $this->translate( 'mshop/code', 'reason:-1' ) ) ?></option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-xl-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-subscription-datenext" class="form-check-input" type="checkbox" v-on:click="setState('item/subscription.datenext')">
												</div>
												<label class="col-4 form-control-label" for="batch-subscription-datenext">
													<?= $enc->html( $this->translate( 'admin', 'Next date' ) ) ?>
												</label>
												<div class="col-7">
													<input is="vue:flat-pickr" class="form-control" type="date"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'subscription.datenext' ) ) ) ?>"
														v-bind:disabled="state('item/subscription.datenext')"
														v-bind:config="Aimeos.flatpickr.datetime">
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-subscription-dateend" class="form-check-input" type="checkbox" v-on:click="setState('item/subscription.dateend')">
												</div>
												<label class="col-4 form-control-label" for="batch-subscription-dateend">
													<?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?>
												</label>
												<div class="col-7">
													<input is="vue:flat-pickr" class="form-control" type="date"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'subscription.dateend' ) ) ) ?>"
														v-bind:disabled="state('item/subscription.dateend')"
														v-bind:config="Aimeos.flatpickr.datetime">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="batch-footer">
								<a class="btn btn-secondary" href="#" v-on:click.prevent="batch = false">
									<?= $enc->html( $this->translate( 'admin', 'Close' ) ) ?>
								</a>
								<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'subscription'] ) ) ?>">
									<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
								</button>
							</div>
						</td>
					</tr>

					<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
						<?php $url = $enc->attr( $this->link( 'admin/jqadm/url/get', ['id' => $id] + $params ) ) ?>
						<tr class="list-item <?= $this->site()->mismatch( $item->getSiteId() ) ?>" data-label="<?= $enc->attr( $item->getId() ) ?>">
							<td class="select">
								<input class="form-check-input" type="checkbox" tabindex="1"
									name="<?= $enc->attr( $this->formparam( ['id', ''] ) ) ?>"
									value="<?= $enc->attr( $item->getId() ) ?>"
									v-on:click="toggle(`<?= $enc->js( $id ) ?>`)"
									v-bind:checked="checked(`<?= $enc->js( $id ) ?>`)"
									v-bind:disabled="readonly(`<?= $enc->js( $id ) ?>`)">
							</td>
							<?php if( in_array( 'subscription.id', $fields ) ) : ?>
								<td class="subscription-id"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'subscription.status', $fields ) ) : ?>
								<td class="subscription-status"><a class="items-field" href="<?= $url ?>"><div class="icon status-<?= $enc->attr( $item->getStatus() ) ?>"></div></a></td>
							<?php endif ?>
							<?php if( in_array( 'subscription.interval', $fields ) ) : ?>
								<td class="subscription-interval"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getInterval() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'subscription.datenext', $fields ) ) : ?>
								<td class="subscription-datenext"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getDateNext() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'subscription.dateend', $fields ) ) : ?>
								<td class="subscription-dateend"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getDateEnd() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'subscription.reason', $fields ) ) : ?>
								<td class="subscription-reason"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $reasonList[$item->getReason()] ) ?></td>
							<?php endif ?>
							<?php if( in_array( 'subscription.period', $fields ) ) : ?>
								<td class="subscription-period"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getPeriod() ) ?></td>
							<?php endif ?>
							<?php if( in_array( 'subscription.ctime', $fields ) ) : ?>
								<td class="subscription-ctime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeCreated() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'subscription.mtime', $fields ) ) : ?>
								<td class="subscription-mtime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeModified() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'subscription.editor', $fields ) ) : ?>
								<td class="subscription-editor"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->editor() ) ?></a></td>
							<?php endif ?>

							<?php $order = $item->getOrderItem() ?>

							<?php if( in_array( 'order.id', $fields ) ) : ?>
								<td class="order-id"><a class="items-field" href="<?= $url ?>"><?= $order ? $enc->html( $order->getId() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.customerid', $fields ) ) : ?>
								<td class="order-customerid"><a class="items-field" href="<?= $url ?>"><?= $order ? $enc->html( $order->getCustomerId() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.sitecode', $fields ) ) : ?>
								<td class="order-sitecode"><a class="items-field" href="<?= $url ?>"><?= $order ? $enc->html( $order->getSiteCode() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.languageid', $fields ) ) : ?>
								<td class="order-languageid"><a class="items-field" href="<?= $url ?>"><?= $order ? $enc->html( $order->locale()->getLanguageId() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.currencyid', $fields ) ) : ?>
								<td class="order-currencyid"><a class="items-field" href="<?= $url ?>"><?= $order ? $enc->html( $order->locale()->getCurrencyId() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.taxflag', $fields ) ) : ?>
								<td class="order-taxflag"><a class="items-field" href="<?= $url ?>"><?= $order ? $enc->html( $statusList[$order->getPrice()->getTaxFlag()] ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.comment', $fields ) ) : ?>
								<td class="order-comment"><a class="items-field" href="<?= $url ?>"><?= $order ? $enc->html( $order->getComment() ) : '' ?></a></td>
							<?php endif ?>

							<?php $prodItem = $order ? $order->getProducts()->filter( function( $product ) use ( $item ) {
									return $product->getId() == $item->getOrderProductId();
								} )->first() : null;
							?>

							<?php if( in_array( 'order.product.type', $fields ) ) : ?>
								<td class="order-product-type"><a class="items-field" href="<?= $url ?>"><?= $prodItem ? $enc->html( $prodItem->getType() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.product.stocktype', $fields ) ) : ?>
								<td class="order-product-stocktype"><a class="items-field" href="<?= $url ?>"><?= $prodItem ? $enc->html( $prodItem->getStockType() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.product.suppliername', $fields ) ) : ?>
								<td class="order-product-suppliername"><a class="items-field" href="<?= $url ?>"><?= $prodItem ? $enc->html( $prodItem->getSupplierName() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.product.prodcode', $fields ) ) : ?>
								<td class="order-product-prodcode"><a class="items-field" href="<?= $url ?>"><?= $prodItem ? $enc->html( $prodItem->getProductCode() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.product.name', $fields ) ) : ?>
								<td class="order-product-name"><a class="items-field" href="<?= $url ?>"><?= $prodItem ? $enc->html( $prodItem->getName() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.product.quantity', $fields ) ) : ?>
								<td class="order-product-quantity"><a class="items-field" href="<?= $url ?>"><?= $prodItem ? $enc->html( $prodItem->getQuantity() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.product.price', $fields ) ) : ?>
								<td class="order-product-price"><a class="items-field" href="<?= $url ?>"><?= $prodItem ? $enc->html( $prodItem->getPrice()->getValue() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.product.costs', $fields ) ) : ?>
								<td class="order-product-costs"><a class="items-field" href="<?= $url ?>"><?= $prodItem ? $enc->html( $prodItem->getPrice()->getCosts() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.product.rebate', $fields ) ) : ?>
								<td class="order-product-rebate"><a class="items-field" href="<?= $url ?>"><?= $prodItem ? $enc->html( $prodItem->getPrice()->getRebate() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.product.taxvalue', $fields ) ) : ?>
								<td class="order-product-taxvalue"><a class="items-field" href="<?= $url ?>"><?= $prodItem ? $enc->html( $prodItem->getPrice()->getTaxValue() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.product.statusdelivery', $fields ) ) : ?>
								<td class="order-product-statusdelivery"><a class="items-field" href="<?= $url ?>"><?= $prodItem ? $enc->html( $deliveryStatusList[$prodItem->getStatusDelivery()] ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.product.statuspayment', $fields ) ) : ?>
								<td class="order-product-statuspayment"><a class="items-field" href="<?= $url ?>"><?= $prodItem ? $enc->html( $deliveryStatusList[$prodItem->getStatusPayment()] ) : '' ?></a></td>
							<?php endif ?>

							<?php $addrItem = $order ? current( $order->getAddress( \Aimeos\MShop\Order\Item\Address\Base::TYPE_PAYMENT ) ) : null ?>

							<?php if( in_array( 'order.address.salutation', $fields ) ) : ?>
								<td class="order-address-salutation"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getSalutation() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.company', $fields ) ) : ?>
								<td class="order-address-company"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getCompany() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.vatid', $fields ) ) : ?>
								<td class="order-address-vatid"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getVatID() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.title', $fields ) ) : ?>
								<td class="order-address-title"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getTitle() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.firstname', $fields ) ) : ?>
								<td class="order-address-firstname"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getFirstname() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.lastname', $fields ) ) : ?>
								<td class="order-address-lastname"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getLastname() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.address1', $fields ) ) : ?>
								<td class="order-address-address1"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getAddress1() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.address2', $fields ) ) : ?>
								<td class="order-address-address2"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getAddress2() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.address3', $fields ) ) : ?>
								<td class="order-address-address3"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getAddress3() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.postal', $fields ) ) : ?>
								<td class="order-address-postal"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getPostal() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.city', $fields ) ) : ?>
								<td class="order-address-city"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getCity() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.state', $fields ) ) : ?>
								<td class="order-address-state"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getState() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.countryid', $fields ) ) : ?>
								<td class="order-address-countryid"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getCountryId() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.languageid', $fields ) ) : ?>
								<td class="order-address-languageid"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getLanguageId() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.telephone', $fields ) ) : ?>
								<td class="order-address-telephone"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getTelephone() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.telefax', $fields ) ) : ?>
								<td class="order-address-telefax"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getTelefax() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.email', $fields ) ) : ?>
								<td class="order-address-email"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getEmail() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.website', $fields ) ) : ?>
								<td class="order-address-website"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getWebsite() ) : '' ?></a></td>
							<?php endif ?>

							<td class="actions">
								<a class="btn act-copy icon" tabindex="1"
									href="<?= $enc->attr( $this->link( 'admin/jqadm/url/copy', ['id' => $id] + $params ) ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry' ) ) ?>"
									aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ) ?>">
								</a>
								<?php if( !$this->site()->readonly( $item->getSiteId() ) ) : ?>
									<a class="btn act-delete icon" tabindex="1"
										v-on:click.prevent.stop="askDelete(`<?= $enc->js( $id ) ?>`, $event)"
										href="<?= $enc->attr( $this->link( 'admin/jqadm/url/delete', ['id' => $id] + $params ) ) ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
										aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ) ?>">
									</a>
								<?php endif ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>

		<?php if( $this->get( 'items', map() )->isEmpty() ) : ?>
			<div class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No entries found' ) ) ) ?></div>
		<?php endif ?>
	</form>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
			['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/subscription/page', [] )]
		);
	?>

	<confirm-delete v-bind:items="unconfirmed" v-bind:show="dialog"
		v-on:close="confirmDelete(false)" v-on:confirm="confirmDelete(true)">
	</confirm-delete>

</div>
<?php $this->block()->stop() ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'page' ) ) ?>
