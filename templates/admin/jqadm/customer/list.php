<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2024
 */

$enc = $this->encoder();


/** admin/jqadm/customer/fields
 * List of customer columns that should be displayed in the list view
 *
 * Changes the list of customer columns shown by default in the customer list view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "customer.id" for the customer ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.07
 */
$default = ['customer.code', 'customer.lastname', 'customer.postal', 'customer.city'];
$default = $this->config( 'admin/jqadm/customer/fields', $default );
$fields = $this->session( 'aimeos/admin/jqadm/customer/fields', $default );

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

$langList = $this->get( 'pageLanguages', map() )->map( function( $v, $langId ) {
	return $this->translate( 'language', $langId );
} )->all();

$columnList = [
	'customer.id' => $this->translate( 'admin', 'ID' ),
	'customer.status' => $this->translate( 'admin', 'Status' ),
	'customer.code' => $this->translate( 'admin', 'Code' ),
	'customer.label' => $this->translate( 'admin', 'Label' ),
	'customer.salutation' => $this->translate( 'admin', 'Salutation' ),
	'customer.company' => $this->translate( 'admin', 'Company' ),
	'customer.vatid' => $this->translate( 'admin', 'VAT ID' ),
	'customer.title' => $this->translate( 'admin', 'Title' ),
	'customer.firstname' => $this->translate( 'admin', 'First name' ),
	'customer.lastname' => $this->translate( 'admin', 'Last name' ),
	'customer.address1' => $this->translate( 'admin', 'Address 1' ),
	'customer.address2' => $this->translate( 'admin', 'Address 2' ),
	'customer.address3' => $this->translate( 'admin', 'Address 3' ),
	'customer.postal' => $this->translate( 'admin', 'Postal' ),
	'customer.city' => $this->translate( 'admin', 'City' ),
	'customer.state' => $this->translate( 'admin', 'State' ),
	'customer.languageid' => $this->translate( 'admin', 'Language ID' ),
	'customer.countryid' => $this->translate( 'admin', 'Country ID' ),
	'customer.telephone' => $this->translate( 'admin', 'Telephone' ),
	'customer.telefax' => $this->translate( 'admin', 'Facsimile' ),
	'customer.email' => $this->translate( 'admin', 'Email' ),
	'customer.website' => $this->translate( 'admin', 'Web site' ),
	'customer.birthday' => $this->translate( 'admin', 'Birthday' ),
	'customer.dateverified' => $this->translate( 'admin', 'Verified' ),
	'customer.ctime' => $this->translate( 'admin', 'Created' ),
	'customer.mtime' => $this->translate( 'admin', 'Modified' ),
	'customer.editor' => $this->translate( 'admin', 'Editor' ),
];


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<?= $this->partial( $this->config( 'admin/jqadm/partial/navsearch', 'navsearch' ) ) ?>
<?= $this->partial( $this->config( 'admin/jqadm/partial/columns', 'columns' ) ) ?>


<div class="list-view"
	data-domain="customer"
	data-siteid="<?= $enc->attr( $this->site()->siteid() ) ?>"
	data-filter="<?= $enc->attr( $this->session( 'aimeos/admin/jqadm/customer/filter', new \stdClass ) ) ?>"
	data-items="<?= $enc->attr( $this->get( 'items', map() )->call( 'toArray', [true] )->all() ) ?>">

	<nav class="main-navbar">

		<span class="navbar-brand">
			<?= $enc->html( $this->translate( 'admin', 'Customer' ) ) ?>
			<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ) ?>)</span>
		</span>

		<div class="btn icon act-search" v-on:click="search = true"
			title="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>"
			aria-label="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>">
		</div>
	</nav>

	<nav-search v-bind:show="search" v-on:close="search = false"
		v-bind:url="`<?= $enc->js( $this->link( 'admin/jqadm/url/search', map( $searchParams )->except( 'filter' )->all() ) ) ?>`"
		v-bind:filter="<?= $enc->attr( (object) $this->session( 'aimeos/admin/jqadm/customer/filter', new \stdClass ) ) ?>"
		v-bind:operators="<?= $enc->attr( $operators ) ?>"
		v-bind:name="`<?= $enc->js( $this->formparam( ['filter', '_key_', '0'] ) ) ?>`"
		v-bind:attributes="<?= $enc->attr( $searchAttributes ) ?>">
	</nav-search>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
			['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/customer/page', [] )]
		);
	?>

	<form ref="form" class="list list-customer" method="POST"
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
								$this->config( 'admin/jqadm/partial/listhead', 'listhead' ),
								['fields' => $fields, 'params' => $params, 'data' => $columnList, 'sort' => $this->session( 'aimeos/admin/jqadm/customer/sort' )]
							);
						?>

						<th class="actions">
							<a class="btn icon act-add" tabindex="1"
								href="<?= $enc->attr( $this->link( 'admin/jqadm/url/create', $params ) ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ) ?>">
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
							'fields' => array_merge( $fields, ['select'] ), 'filter' => $this->session( 'aimeos/admin/jqadm/customer/filter', [] ),
							'data' => [
								'customer.id' => ['op' => '=='],
								'customer.status' => ['op' => '==', 'type' => 'select', 'val' => [
									'1' => $this->translate( 'mshop/code', 'status:1' ),
									'0' => $this->translate( 'mshop/code', 'status:0' ),
									'-1' => $this->translate( 'mshop/code', 'status:-1' ),
									'-2' => $this->translate( 'mshop/code', 'status:-2' ),
								]],
								'customer.code' => [],
								'customer.label' => [],
								'customer.salutation' => ['op' => '==', 'type' => 'select', 'val' => [
									'' => 'none', 'company' => 'company', 'mr' => 'mr', 'ms' => 'ms'
								]],
								'customer.company' => [],
								'customer.vatid' => [],
								'customer.title' => [],
								'customer.firstname' => [],
								'customer.lastname' => [],
								'customer.address1' => [],
								'customer.address2' => [],
								'customer.address3' => [],
								'customer.postal' => [],
								'customer.city' => [],
								'customer.state' => [],
								'customer.languageid' => ['op' => '==', 'type' => 'select', 'val' => $langList],
								'customer.countryid' => ['op' => '=='],
								'customer.telephone' => [],
								'customer.telefax' => [],
								'customer.email' => [],
								'customer.website' => [],
								'customer.birthday' => ['op' => '-', 'type' => 'date'],
								'customer.dateverified' => ['op' => '-', 'type' => 'date'],
								'customer.ctime' => ['op' => '-', 'type' => 'datetime-local'],
								'customer.mtime' => ['op' => '-', 'type' => 'datetime-local'],
								'customer.editor' => [],
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
									<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'customer'] ) ) ?>">
										<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
									</button>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-xl-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-status" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.status')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-status">
													<?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select" v-bind:disabled="state('item/customer.status')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.status' ) ) ) ?>">
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
													<input id="batch-customer-label" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.label')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-label">
													<?= $enc->html( $this->translate( 'admin', 'Label' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.label')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.label' ) ) ) ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-password" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.password')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-password">
													<?= $enc->html( $this->translate( 'admin', 'Password' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="password" v-bind:disabled="state('item/customer.password')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.password' ) ) ) ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-dateverified" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.dateverified')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-dateverified">
													<?= $enc->html( $this->translate( 'admin', 'Verified' ) ) ?>
												</label>
												<div class="col-7">
													<input is="vue:flat-pickr" class="form-control" type="date"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.dateverified' ) ) ) ?>"
														v-bind:disabled="state('item/customer.dateverified')"
														v-bind:config="Aimeos.flatpickr.date"
														v-bind:value="`<?= $enc->js( $this->get( 'itemData/customer.dateverified' ) ) ?>`"
													>
												</div>
											</div>
										</div>
										<div class="col-xl-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-groups" class="form-check-input" type="checkbox" v-on:click="setState('item/groups')">
												</div>
												<label class="col-4 form-control-label" for="batch-groups">
													<?= $enc->html( $this->translate( 'admin', 'Groups' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select item-groups" tabindex="1" size="6" multiple v-bind:disabled="state('item/groups')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'groups', '' ) ) ) ?>">
														<option value=""><?= $enc->html( $this->translate( 'admin', 'None' ) ) ?></option>

														<?php foreach( $this->get( 'itemGroups', [] ) as $groupId => $groupItem ) : ?>
															<option value="<?= $enc->attr( $groupId ) ?>" >
																<?= $enc->html( $groupItem->getLabel() . ' (' . $groupItem->getCode() . ')' ) ?>
															</option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="card">
								<div class="card-header">
									<span><?= $enc->html( $this->translate( 'admin', 'Personal data' ) ) ?></span>
									<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'customer'] ) ) ?>">
										<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
									</button>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-xl-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-languageid" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.languageid')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-languageid">
													<?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select item-languageid" v-bind:disabled="state('item/customer.languageid')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.languageid' ) ) ) ?>" >
														<option value=""></option>

														<?php foreach( $this->get( 'pageLangItems', [] ) as $langId => $langItem ) : ?>
															<option value="<?= $enc->attr( $langId ) ?>">
																<?= $enc->html( $this->translate( 'language', $langId ) ) ?>
															</option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-salutation" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.salutation')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-salutation">
													<?= $enc->html( $this->translate( 'admin', 'Salutation' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select item-salutation" v-bind:disabled="state('item/customer.salutation')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.salutation' ) ) ) ?>" >
														<option value=""></option>
														<option value="mr"><?= $enc->html( $this->translate( 'mshop/code', 'mr' ) ) ?></option>
														<option value="ms"><?= $enc->html( $this->translate( 'mshop/code', 'ms' ) ) ?></option>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-title" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.title')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-title">
													<?= $enc->html( $this->translate( 'admin', 'Title' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.title')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.title' ) ) ) ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-lastname" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.lastname')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-lastname">
													<?= $enc->html( $this->translate( 'admin', 'Last name' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.lastname')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.lastname' ) ) ) ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-firstname" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.firstname')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-firstname">
													<?= $enc->html( $this->translate( 'admin', 'First name' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.firstname')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.firstname' ) ) ) ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-birthday" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.birthday')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-birthday">
													<?= $enc->html( $this->translate( 'admin', 'Birthday' ) ) ?>
												</label>
												<div class="col-7">
													<input is="vue:flat-pickr" class="form-control" type="date"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.birthday' ) ) ) ?>"
														v-bind:disabled="state('item/customer.birthday')"
														v-bind:config="Aimeos.flatpickr.date"
														v-bind:value="`<?= $enc->js( $this->get( 'itemData/customer.birthday' ) ) ?>`"
													>
												</div>
											</div>
										</div>
										<div class="col-xl-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-address1" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.address1')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-address1">
													<?= $enc->html( $this->translate( 'admin', 'Street' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.address1')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.address1' ) ) ) ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-address2" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.address2')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-address2">
													<?= $enc->html( $this->translate( 'admin', 'House number' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.address2')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.address2' ) ) ) ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-address3" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.address3')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-address3">
													<?= $enc->html( $this->translate( 'admin', 'Floor / Appartment' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.address3')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.address3' ) ) ) ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-postal" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.postal')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-postal">
													<?= $enc->html( $this->translate( 'admin', 'Zip code' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.postal')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.postal' ) ) ) ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-city" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.city')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-city">
													<?= $enc->html( $this->translate( 'admin', 'City' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.city')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.city' ) ) ) ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-countryid" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.countryid')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-countryid">
													<?= $enc->html( $this->translate( 'admin', 'Country' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select item-countryid" v-bind:disabled="state('item/customer.countryid')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.countryid' ) ) ) ?>">
														<option value=""></option>

														<?php foreach( $this->get( 'countries', [] ) as $code => $label ) : ?>
															<option value="<?= $enc->attr( $code ) ?>"><?= $enc->html( $label ) ?></option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-state" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.state')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-state">
													<?= $enc->html( $this->translate( 'admin', 'State' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.state')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.state' ) ) ) ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="card">
								<div class="card-header">
									<span><?= $enc->html( $this->translate( 'admin', 'Communication' ) ) ?></span>
									<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'customer'] ) ) ?>">
										<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
									</button>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-xl-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-telephone" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.telephone')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-telephone">
													<?= $enc->html( $this->translate( 'admin', 'Telephone' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.telephone')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.telephone' ) ) ) ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-telefax" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.telefax')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-telefax">
													<?= $enc->html( $this->translate( 'admin', 'Facsimile' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.telefax')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.telefax' ) ) ) ?>">
												</div>
											</div>
										</div>
										<div class="col-xl-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-website" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.website')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-website">
													<?= $enc->html( $this->translate( 'admin', 'Web site' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.website')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.website' ) ) ) ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="card">
								<div class="card-header">
									<span><?= $enc->html( $this->translate( 'admin', 'Company details' ) ) ?></span>
									<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'customer'] ) ) ?>">
										<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
									</button>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-xl-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-company" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.company')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-company">
													<?= $enc->html( $this->translate( 'admin', 'Company' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.company')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.company' ) ) ) ?>">
												</div>
											</div>
										</div>
										<div class="col-xl-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-customer-vatid" class="form-check-input" type="checkbox" v-on:click="setState('item/customer.vatid')">
												</div>
												<label class="col-4 form-control-label" for="batch-customer-vatid">
													<?= $enc->html( $this->translate( 'admin', 'VAT ID' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="text" v-bind:disabled="state('item/customer.vatid')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.vatid' ) ) ) ?>">
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
								<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'customer'] ) ) ?>">
									<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
								</button>
							</div>
						</td>
					</tr>

					<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
						<?php $address = $item->getPaymentAddress() ?>
						<?php $url = $enc->attr( $this->link( 'admin/jqadm/url/get', ['id' => $id] + $params ) ) ?>
						<tr class="list-item <?= $this->site()->mismatch( $item->getSiteId() ) ?>" data-label="<?= $enc->attr( $item->getLabel() ?: $item->getCode() ) ?>">
							<td class="select">
								<input class="form-check-input" type="checkbox" tabindex="1"
									name="<?= $enc->attr( $this->formparam( ['id', ''] ) ) ?>"
									value="<?= $enc->attr( $item->getId() ) ?>"
									v-on:click="toggle(`<?= $enc->js( $id ) ?>`)"
									v-bind:checked="checked(`<?= $enc->js( $id ) ?>`)"
									v-bind:disabled="readonly(`<?= $enc->js( $id ) ?>`)">
							</td>
							<?php if( in_array( 'customer.id', $fields ) ) : ?>
								<td class="customer-id"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.status', $fields ) ) : ?>
								<td class="customer-status"><a class="items-field" href="<?= $url ?>"><div class="icon status-<?= $enc->attr( $item->getStatus() ) ?>"></div></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.code', $fields ) ) : ?>
								<td class="customer-code"><a class="items-field" href="<?= $url ?>" tabindex="1"><?= $enc->html( $item->getCode() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.label', $fields ) ) : ?>
								<td class="customer-label"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getLabel() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.salutation', $fields ) ) : ?>
								<td class="customer-salutation"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getSalutation() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.company', $fields ) ) : ?>
								<td class="customer-company"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getCompany() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.vatid', $fields ) ) : ?>
								<td class="customer-vatid"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getVatID() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.title', $fields ) ) : ?>
								<td class="customer-title"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getTitle() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.firstname', $fields ) ) : ?>
								<td class="customer-firstname"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getFirstname() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.lastname', $fields ) ) : ?>
								<td class="customer-lastname"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getLastname() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.address1', $fields ) ) : ?>
								<td class="customer-address1"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getAddress1() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.address2', $fields ) ) : ?>
								<td class="customer-address2"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getAddress2() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.address3', $fields ) ) : ?>
								<td class="customer-address3"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getAddress3() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.postal', $fields ) ) : ?>
								<td class="customer-postal"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getPostal() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.city', $fields ) ) : ?>
								<td class="customer-city"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getCity() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.state', $fields ) ) : ?>
								<td class="customer-state"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getState() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.languageid', $fields ) ) : ?>
								<td class="customer-languageid"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getLanguageId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.countryid', $fields ) ) : ?>
								<td class="customer-countryid"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getCountryId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.telephone', $fields ) ) : ?>
								<td class="customer-telephone"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getTelephone() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.telefax', $fields ) ) : ?>
								<td class="customer-telefax"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getTelefax() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.email', $fields ) ) : ?>
								<td class="customer-email"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getEmail() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.website', $fields ) ) : ?>
								<td class="customer-website"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getWebsite() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.birthday', $fields ) ) : ?>
								<td class="customer-birthday"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $address->getBirthday() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.dateverified', $fields ) ) : ?>
								<td class="customer-dateverified"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getDateVerified() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.ctime', $fields ) ) : ?>
								<td class="customer-ctime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeCreated() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.mtime', $fields ) ) : ?>
								<td class="customer-mtime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeModified() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'customer.editor', $fields ) ) : ?>
								<td class="customer-editor"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->editor() ) ?></a></td>
							<?php endif ?>

							<td class="actions">
								<a class="btn act-copy icon" tabindex="1"
									href="<?= $enc->attr( $this->link( 'admin/jqadm/url/copy', ['id' => $id] + $params ) ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry' ) ) ?>"
									aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ) ?>">
								</a>
								<?php if( $this->access( ['super', 'admin'] ) && !$this->site()->readonly( $item->getSiteId() ) ) : ?>
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
			'page' => $this->session( 'aimeos/admin/jqadm/customer/page', [] )]
		);
	?>

	<confirm-delete v-bind:items="unconfirmed" v-bind:show="dialog"
		v-on:close="confirmDelete(false)" v-on:confirm="confirmDelete(true)">
	</confirm-delete>

</div>
<?php $this->block()->stop() ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'page' ) ) ?>
