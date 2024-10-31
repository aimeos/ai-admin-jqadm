<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */

$enc = $this->encoder();


/** admin/jqadm/order/actions
 * Actions available in the list view of the order panel
 *
 * List of actions, the editor can select from in the list header of the order
 * panel. You can dynamically extend the available actions like exporting the
 * selected orders in CSV format and translate the action names using the
 * "admin/ext" translation domain.
 *
 * The action names will be passed as "queue" parameter to the export method
 * of the JQADM order class, which will create an entry for the message queue
 * from the selected filter criteria. You have to implement a suitable controller
 * which must fetch the entries from the message queue and generate the appropriate
 * files. If files should be offered for download in the dashboard, a new job
 * entry must be created using the MAdmin Job manager.
 *
 * @param array List of action queue names
 * @since 2020.10
 */

/** admin/jqadm/url/export/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2017.10
 * @see admin/jqadm/url/export/controller
 * @see admin/jqadm/url/export/action
 * @see admin/jqadm/url/export/config
 */

/** admin/jqadm/url/export/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @see admin/jqadm/url/export/target
 * @see admin/jqadm/url/export/action
 * @see admin/jqadm/url/export/config
 */

/** admin/jqadm/url/export/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @see admin/jqadm/url/export/target
 * @see admin/jqadm/url/export/controller
 * @see admin/jqadm/url/export/config
 */

/** admin/jqadm/url/export/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  admin/jqadm/url/export/config = ['absoluteUri' => true )
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2016.04
 * @see admin/jqadm/url/export/target
 * @see admin/jqadm/url/export/controller
 * @see admin/jqadm/url/export/action
 */

/** admin/jqadm/url/export/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2016.04
 * @see admin/jqadm/url/export/target
 * @see admin/jqadm/url/export/controller
 * @see admin/jqadm/url/export/action
 * @see admin/jqadm/url/export/config
 */


/** admin/jqadm/order/fields
 * List of order columns that should be displayed in the list view
 *
 * Changes the list of order columns shown by default in the order list view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "order.id" for the order ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.07
 */
$default = $this->config( 'admin/jqadm/order/fields', ['order.invoiceno', 'order.ctime', 'order.statuspayment', 'order.address.lastname'] );
$fields = $this->session( 'aimeos/admin/jqadm/order/fields', $default );

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
	'order.id' => $this->translate( 'admin', 'ID' ),
	'order.invoiceno' => $this->translate( 'admin', 'Invoice no.' ),
	'order.channel' => $this->translate( 'admin', 'Type' ),
	'order.statuspayment' => $this->translate( 'admin', 'Pay status' ),
	'order.datepayment' => $this->translate( 'admin', 'Pay date' ),
	'order.statusdelivery' => $this->translate( 'admin', 'Ship status' ),
	'order.datedelivery' => $this->translate( 'admin', 'Ship date' ),
	'order.relatedid' => $this->translate( 'admin', 'Related order' ),
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
	'order.ctime' => $this->translate( 'admin', 'Ordered at' ),
	'order.mtime' => $this->translate( 'admin', 'Modified' ),
	'order.editor' => $this->translate( 'admin', 'Editor' ),
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
	'order.address.mobile' => $this->translate( 'admin', 'Mobile' ),
	'order.address.email' => $this->translate( 'admin', 'E-Mail' ),
	'order.address.website' => $this->translate( 'admin', 'Web site' ),
	'order.coupon.code' => $this->translate( 'admin', 'Coupon' ),
	'order.service.code' => $this->translate( 'admin', 'Service' ),
	'order.service.name' => $this->translate( 'admin', 'Service name' ),
	'order.service.price' => $this->translate( 'admin', 'Service price' ),
	'order.service.costs' => $this->translate( 'admin', 'Service costs' ),
	'order.service.rebate' => $this->translate( 'admin', 'Service rebate' ),
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

$statusList = [
	0 => $this->translate( 'admin', 'no' ),
	1 => $this->translate( 'admin', 'yes' ),
];


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<?= $this->partial( $this->config( 'admin/jqadm/partial/navsearch', 'navsearch' ) ) ?>
<?= $this->partial( $this->config( 'admin/jqadm/partial/columns', 'columns' ) ) ?>


<div class="list-view"
	data-domain="order"
	data-siteid="<?= $enc->attr( $this->site()->siteid() ) ?>"
	data-filter="<?= $enc->attr( $this->session( 'aimeos/admin/jqadm/order/filter', new \stdClass ) ) ?>"
	data-items="<?= $enc->attr( $this->get( 'items', map() )->call( 'toArray', [true] )->all() ) ?>">

	<nav class="main-navbar">

		<span class="navbar-brand">
			<?= $enc->html( $this->translate( 'admin', 'Order' ) ) ?>
			<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ) ?>)</span>
		</span>

		<div class="btn icon act-search" v-on:click="search = true"
			title="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>"
			aria-label="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>">
		</div>
	</nav>

	<nav-search v-bind:show="search" v-on:close="search = false"
		v-bind:url="`<?= $enc->js( $this->link( 'admin/jqadm/url/search', map( $searchParams )->except( 'filter' )->all() ) ) ?>`"
		v-bind:filter="<?= $enc->attr( (object) $this->session( 'aimeos/admin/jqadm/order/filter', new \stdClass ) ) ?>"
		v-bind:operators="<?= $enc->attr( $operators ) ?>"
		v-bind:name="`<?= $enc->js( $this->formparam( ['filter', '_key_', '0'] ) ) ?>`"
		v-bind:attributes="<?= $enc->attr( $searchAttributes ) ?>">
	</nav-search>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
			['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/order/page', [] )]
		);
	?>

	<form ref="form" class="list list-order" method="POST"
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
							</ul>
						</th>

						<?= $this->partial(
								$this->config( 'admin/jqadm/partial/listhead', 'listhead' ),
								['fields' => $fields, 'params' => $params, 'data' => $columnList, 'sort' => $this->session( 'aimeos/admin/jqadm/order/sort' )]
							);
						?>

						<th class="actions">
							<?php if( count( $actions = $this->config( 'admin/jqadm/order/actions', ['order-export'] ) ) > 0 ) : ?>
								<div class="dropdown list-menu">
									<button class="btn act-download icon" type="button" id="menuButton"
										data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}' aria-haspopup="true" aria-expanded="false" tabindex="<?= $this->get( 'tabindex' ) ?>"
										aria-label="<?= $enc->attr( $this->translate( 'admin', 'Export' ) ) ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Export' ) ) ?>">
									</button>
									<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuButton">
										<?php foreach( $actions as $code ) : ?>
											<li class="dropdown-item">
												<a class="btn icon act-download" tabindex="1"
													href="<?= $enc->attr( $this->link( 'admin/jqadm/url/export', $params + ['queue' => $code] ) ) ?>"
													aria-label="<?= $enc->attr( $this->translate( 'admin/ext', $code ) ) ?>"
													title="<?= $enc->attr( $this->translate( 'admin/ext', $code ) ) ?>">
													<?= $enc->html( $this->translate( 'admin/ext', $code ) ) ?>
												</a>
											</li>
										<?php endforeach ?>
									</ul>
								</div>
							<?php endif ?>

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
							'fields' => array_merge( $fields, ['select'] ), 'filter' => $this->session( 'aimeos/admin/jqadm/order/filter', [] ),
							'data' => [
								'order.id' => ['op' => '=='],
								'order.invoiceno' => ['op' => '=~'],
								'order.channel' => [],
								'order.statuspayment' => ['op' => '==', 'type' => 'select', 'val' => $paymentStatusList],
								'order.datepayment' => ['op' => '-', 'type' => 'date'],
								'order.statusdelivery' => ['op' => '==', 'type' => 'select', 'val' => $deliveryStatusList],
								'order.datedelivery' => ['op' => '-', 'type' => 'date'],
								'order.relatedid' => ['op' => '=='],
								'order.customerid' => ['op' => '=='],
								'order.sitecode' => ['op' => '=='],
								'order.languageid' => ['op' => '=='],
								'order.currencyid' => ['op' => '=='],
								'order.price' => ['op' => '==', 'type' => 'number'],
								'order.costs' => ['op' => '==', 'type' => 'number'],
								'order.rebate' => ['op' => '==', 'type' => 'number'],
								'order.taxvalue' => ['op' => '==', 'type' => 'number'],
								'order.taxflag' => ['op' => '==', 'type' => 'select', 'val' => $statusList],
								'order.customerref' => [],
								'order.comment' => [],
								'order.ctime' => ['op' => '-', 'type' => 'date'],
								'order.mtime' => ['op' => '-', 'type' => 'date'],
								'order.editor' => [],
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
								'order.address.mobile' => [],
								'order.address.email' => [],
								'order.address.website' => [],
								'order.coupon.code' => ['op' => '=~'],
								'order.service.code' => [],
								'order.service.name' => [],
								'order.service.price' => ['op' => '==', 'type' => 'number'],
								'order.service.costs' => ['op' => '==', 'type' => 'number'],
								'order.service.rebate' => ['op' => '==', 'type' => 'number'],
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
									<span><?= $enc->html( $this->translate( 'admin', 'Order' ) ) ?></span>
									<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'order'] ) ) ?>">
										<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
									</button>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-xl-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-order-statuspayment" class="form-check-input" type="checkbox" v-on:click="setState('item/order.statuspayment')">
												</div>
												<label class="col-4 form-control-label" for="batch-order-statuspayment">
													<?= $enc->html( $this->translate( 'admin', 'Pay status' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select" v-bind:disabled="state('item/order.statuspayment')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'order.statuspayment' ) ) ) ?>">
														<option value=""></option>
														<option value="-1"><?= $enc->html( $this->translate( 'mshop/code', 'pay:-1' ) ) ?></option>
														<option value="0"><?= $enc->html( $this->translate( 'mshop/code', 'pay:0' ) ) ?></option>
														<option value="1"><?= $enc->html( $this->translate( 'mshop/code', 'pay:1' ) ) ?></option>
														<option value="2"><?= $enc->html( $this->translate( 'mshop/code', 'pay:2' ) ) ?></option>
														<option value="3"><?= $enc->html( $this->translate( 'mshop/code', 'pay:3' ) ) ?></option>
														<option value="4"><?= $enc->html( $this->translate( 'mshop/code', 'pay:4' ) ) ?></option>
														<option value="5"><?= $enc->html( $this->translate( 'mshop/code', 'pay:5' ) ) ?></option>
														<option value="6"><?= $enc->html( $this->translate( 'mshop/code', 'pay:6' ) ) ?></option>
														<option value="7"><?= $enc->html( $this->translate( 'mshop/code', 'pay:7' ) ) ?></option>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-order-datepayment" class="form-check-input" type="checkbox" v-on:click="setState('item/order.datepayment')">
												</div>
												<label class="col-4 form-control-label" for="batch-order-datepayment">
													<?= $enc->html( $this->translate( 'admin', 'Pay date' ) ) ?>
												</label>
												<div class="col-7">
													<input is="vue:flat-pickr" class="form-control" type="date"
													   name="<?= $enc->attr( $this->formparam( array( 'item', 'order.datepayment' ) ) ) ?>"
													   v-bind:disabled="state('item/order.datepayment')"
													   v-bind:config="Aimeos.flatpickr.date"
													   v-bind:value="`<?= $enc->js( $this->get( 'itemData/order.datepayment' ) ) ?>`"
													 >
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-order-channel" class="form-check-input" type="checkbox" v-on:click="setState('item/order.channel')">
												</div>
												<label class="col-4 form-control-label" for="batch-order-channel">
													<?= $enc->html( $this->translate( 'admin', 'Channel' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/order.channel')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'order.channel' ) ) ) ?>">
												</div>
											</div>
										</div>
										<div class="col-xl-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-order-statusdelivery" class="form-check-input" type="checkbox" v-on:click="setState('item/order.statusdelivery')">
												</div>
												<label class="col-4 form-control-label" for="batch-order-statusdelivery">
													<?= $enc->html( $this->translate( 'admin', 'Ship status' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select" v-bind:disabled="state('item/order.statusdelivery')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'order.statusdelivery' ) ) ) ?>">
														<option value=""></option>
														<option value="-1"><?= $enc->html( $this->translate( 'mshop/code', 'stat:-1' ) ) ?></option>
														<option value="0"><?= $enc->html( $this->translate( 'mshop/code', 'stat:0' ) ) ?></option>
														<option value="1"><?= $enc->html( $this->translate( 'mshop/code', 'stat:1' ) ) ?></option>
														<option value="2"><?= $enc->html( $this->translate( 'mshop/code', 'stat:2' ) ) ?></option>
														<option value="3"><?= $enc->html( $this->translate( 'mshop/code', 'stat:3' ) ) ?></option>
														<option value="4"><?= $enc->html( $this->translate( 'mshop/code', 'stat:4' ) ) ?></option>
														<option value="5"><?= $enc->html( $this->translate( 'mshop/code', 'stat:5' ) ) ?></option>
														<option value="6"><?= $enc->html( $this->translate( 'mshop/code', 'stat:6' ) ) ?></option>
														<option value="7"><?= $enc->html( $this->translate( 'mshop/code', 'stat:7' ) ) ?></option>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-order-datedelivery" class="form-check-input" type="checkbox" v-on:click="setState('item/order.datedelivery')">
												</div>
												<label class="col-4 form-control-label" for="batch-order-datedelivery">
													<?= $enc->html( $this->translate( 'admin', 'Ship date' ) ) ?>
												</label>
												<div class="col-7">
													<input is="vue:flat-pickr" class="form-control" type="date"
													   name="<?= $enc->attr( $this->formparam( array( 'item', 'order.datedelivery' ) ) ) ?>"
													   v-bind:disabled="state('item/order.datedelivery')"
													   v-bind:config="Aimeos.flatpickr.date"
													   v-bind:value="`<?= $enc->js( $this->get( 'itemData/order.datedelivery' ) ) ?>`"
																				 >
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-order-relatedid" class="form-check-input" type="checkbox" v-on:click="setState('item/order.relatedid')">
												</div>
												<label class="col-4 form-control-label" for="batch-order-relatedid">
													<?= $enc->html( $this->translate( 'admin', 'Related ID' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/order.relatedid')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'order.relatedid' ) ) ) ?>">
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
								<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'order'] ) ) ?>">
									<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
								</button>
							</div>
						</td>
					</tr>

					<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
						<?php $url = $enc->attr( $this->link( 'admin/jqadm/url/get', ['id' => $item->getId()] + $params ) ) ?>
						<tr class="list-item <?= $this->site()->mismatch( $item->getSiteId() ) ?>">
							<td class="select">
								<input class="form-check-input" type="checkbox" tabindex="1"
									name="<?= $enc->attr( $this->formparam( ['id', ''] ) ) ?>"
									value="<?= $enc->attr( $item->getId() ) ?>"
									v-on:click="toggle(`<?= $enc->js( $id ) ?>`)"
									v-bind:checked="checked(`<?= $enc->js( $id ) ?>`)"
									v-bind:disabled="readonly(`<?= $enc->js( $id ) ?>`)">
							</td>
							<?php if( in_array( 'order.id', $fields ) ) : ?>
								<td class="order-id"><a class="items-field" href="<?= $url ?>" tabindex="1"><?= $enc->html( $item->getId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.invoiceno', $fields ) ) : ?>
								<td class="order-invoiceno"><a class="items-field" href="<?= $url ?>" tabindex="1"><?= $enc->html( $item->getInvoiceNumber() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.channel', $fields ) ) : ?>
								<td class="order-channel"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getChannel() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.statuspayment', $fields ) ) : ?>
								<td class="order-statuspayment"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $paymentStatusList[$item->getStatusPayment()] ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.datepayment', $fields ) ) : ?>
								<td class="order-datepayment"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getDatePayment() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.statusdelivery', $fields ) ) : ?>
								<td class="order-statusdelivery"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $deliveryStatusList[$item->getStatusDelivery()] ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.datedelivery', $fields ) ) : ?>
								<td class="order-datedelivery"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getDateDelivery() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.relatedid', $fields ) ) : ?>
								<td class="order-relatedid"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getRelatedId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.customerid', $fields ) ) : ?>
								<td class="order-customerid"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getCustomerId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.sitecode', $fields ) ) : ?>
								<td class="order-sitecode"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getSiteCode() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.languageid', $fields ) ) : ?>
								<td class="order-languageid"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->locale()->getLanguageId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.currencyid', $fields ) ) : ?>
								<td class="order-currencyid"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->locale()->getCurrencyId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.price', $fields ) ) : ?>
								<td class="order-price"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getPrice()->getValue() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.costs', $fields ) ) : ?>
								<td class="order-costs"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getPrice()->getCosts() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.rebate', $fields ) ) : ?>
								<td class="order-rebate"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getPrice()->getRebate() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.taxvalue', $fields ) ) : ?>
								<td class="order-taxvalue"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getPrice()->getTaxValue() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.taxflag', $fields ) ) : ?>
								<td class="order-taxflag"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $statusList[$item->getPrice()->getTaxFlag()] ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.customerref', $fields ) ) : ?>
								<td class="order-customerref"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getCustomerReference() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.comment', $fields ) ) : ?>
								<td class="order-comment"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getComment() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.ctime', $fields ) ) : ?>
								<td class="order-ctime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeCreated() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.mtime', $fields ) ) : ?>
								<td class="order-mtime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeModified() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.editor', $fields ) ) : ?>
								<td class="order-editor"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->editor() ) ?></a></td>
							<?php endif ?>

							<?php $addrItem = ( $item ? current( $item->getAddress( \Aimeos\MShop\Order\Item\Address\Base::TYPE_PAYMENT ) ) : null ) ?>

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
							<?php if( in_array( 'order.address.mobile', $fields ) ) : ?>
								<td class="order-address-mobile"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getMobile() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.email', $fields ) ) : ?>
								<td class="order-address-email"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getEmail() ) : '' ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.address.website', $fields ) ) : ?>
								<td class="order-address-website"><a class="items-field" href="<?= $url ?>"><?= $addrItem ? $enc->html( $addrItem->getWebsite() ) : '' ?></a></td>
							<?php endif ?>

							<?php $coupons = ( $item ? $item->getCoupons() : [] ) ?>

							<?php if( in_array( 'order.coupon.code', $fields ) ) : ?>
								<td class="order-coupon-code"><a class="items-field" href="<?= $url ?>">
									<?php foreach( $coupons as $code => $itemProducts ) : ?>
										<?= $enc->html( $code ) ?><br>
									<?php endforeach ?>
								</a></td>
							<?php endif ?>

							<?php $services = ( $item ? $item->getServices() : [] ) ?>

							<?php if( in_array( 'order.service.code', $fields ) ) : ?>
								<td class="order-service-code">
									<a class="items-field" href="<?= $url ?>">
										<?php foreach( $services as $type => $list ) : ?>
											<?php foreach( $list as $serviceItem ) : ?>
												<span class="line"><?= $enc->html( $serviceItem->getCode() ) ?></span>
											<?php endforeach ?>
										<?php endforeach ?>
									</a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.service.name', $fields ) ) : ?>
								<td class="order-service-name">
									<a class="items-field" href="<?= $url ?>">
										<?php foreach( $services as $type => $list ) : ?>
											<?php foreach( $list as $serviceItem ) : ?>
												<span class="line"><?= $enc->html( $serviceItem->getName() ) ?></span>
											<?php endforeach ?>
										<?php endforeach ?>
									</a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.service.price', $fields ) ) : ?>
								<td class="order-service-price">
									<a class="items-field" href="<?= $url ?>">
										<?php foreach( $services as $type => $list ) : ?>
											<?php foreach( $list as $serviceItem ) : ?>
												<span class="line"><?= $enc->html( $serviceItem->getPrice()->getValue() ) ?></span>
											<?php endforeach ?>
										<?php endforeach ?>
									</a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.service.costs', $fields ) ) : ?>
								<td class="order-service-costs">
									<a class="items-field" href="<?= $url ?>">
										<?php foreach( $services as $type => $list ) : ?>
											<?php foreach( $list as $serviceItem ) : ?>
												<span class="line"><?= $enc->html( $serviceItem->getPrice()->getCosts() ) ?></span>
											<?php endforeach ?>
										<?php endforeach ?>
									</a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'order.service.rebate', $fields ) ) : ?>
								<td class="order-service-rebate">
									<a class="items-field" href="<?= $url ?>">
										<?php foreach( $services as $type => $list ) : ?>
											<?php foreach( $list as $serviceItem ) : ?>
												<span class="line"><?= $enc->html( $serviceItem->getPrice()->getRebate() ) ?></span>
											<?php endforeach ?>
										<?php endforeach ?>
									</a>
								</td>
							<?php endif ?>

							<td class="actions">
								<a class="btn act-copy icon"
									href="<?= $enc->attr( $this->link( 'admin/jqadm/url/copy', ['id' => $item->getId()] + $params ) ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry' ) ) ?>"
									aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ) ?>">
								</a>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>

		<?php if( $this->get( 'items', map() )->isEmpty() ) : ?>
			<?= $enc->html( sprintf( $this->translate( 'admin', 'No entries found' ) ) ) ?>
		<?php endif ?>
	</form>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
			['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/order/page', [] )]
		);
	?>

</div>
<?php $this->block()->stop() ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'page' ) ) ?>
