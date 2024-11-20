<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$enc = $this->encoder();
$params = $this->get( 'pageParams', [] );


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<form class="item item-customer form-horizontal container-fluid" method="POST" enctype="multipart/form-data" action="<?= $enc->attr( $this->link( 'admin/jqadm/url/save', $params ) ) ?>" autocomplete="off">
	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.id' ) ) ) ?>" value="<?= $enc->attr( $this->get( 'itemData/customer.id' ) ) ?>">
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ) ?>" value="get">
	<?= $this->csrf()->formfield() ?>

	<nav class="main-navbar">
		<h1 class="navbar-brand">
			<span class="navbar-title"><?= $enc->html( $this->translate( 'admin', 'Customer' ) ) ?></span>
			<span class="navbar-id"><?= $enc->html( $this->get( 'itemData/customer.id' ) ) ?></span>
			<span class="navbar-label"><?= $enc->html( $this->get( 'itemData/customer.label' ) ?: $this->translate( 'admin', 'New' ) ) ?></span>
			<span class="navbar-site"><?= $enc->html( $this->site()->match( $this->get( 'itemData/customer.siteid' ) ) ) ?></span>
		</h1>
		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'itemactions' ), ['params' => $params] ) ?>
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
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/customer.mtime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/customer.ctime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/customer.editor' ) ) ?></span>
					</small>
				</div>

				<div class="icon more"></div>
			</div>
		</div>

		<div class="col-xl-9 item-content tab-content">
			<div id="basic" class="item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic"
				data-data="<?= $enc->attr( $this->get( 'itemData', new stdClass() ) ) ?>"
				data-groups="<?= $enc->attr( $this->get( 'itemGroups', new stdClass() ) ) ?>"
				data-siteid="<?= $enc->attr( $this->site()->siteid() ) ?>"
				data-super="<?= $enc->attr( $this->access( ['super'] ) ) ?>">

				<div class="row">
					<div class="col-xl-6">
						<div class="box" v-bind:class="{mismatch: !can('match')}">
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></label>
								<div class="col-sm-8">
									<select class="form-select item-status" required="required" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.status' ) ) ) ?>"
										v-bind:readonly="!can('change')" >
										<option value="">
											<?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?>
										</option>
										<option value="1" <?= $selected( $this->get( 'itemData/customer.status', 1 ), 1 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?>
										</option>
										<option value="0" <?= $selected( $this->get( 'itemData/customer.status', 1 ), 0 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?>
										</option>
										<option value="-1" <?= $selected( $this->get( 'itemData/customer.status', 1 ), -1 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?>
										</option>
										<option value="-2" <?= $selected( $this->get( 'itemData/customer.status', 1 ), -2 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?>
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Account' ) ) ?></label>
								<div class="col-sm-8">
									<?php if( $this->get( 'itemData/.modify' ) ) : ?>
										<input class="form-control item-code" type="text" required="required" tabindex="1" autocomplete="off"
											name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.code' ) ) ) ?>"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'E-Mail address or account name (required)' ) ) ?>"
											value="<?= $enc->attr( $this->get( 'itemData/customer.code' ) ) ?>"
											v-bind:readonly="!can('change')">
									<?php else : ?>
										<span class="form-control item-code">
											<?= $enc->html( $this->get( 'itemData/customer.code' ) ) ?>
										</span>
									<?php endif ?>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Unique customer account name' ) ) ?>
								</div>
							</div>
							<?php if( $this->get( 'itemData/.modify' ) ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Password' ) ) ?></label>
									<div class="col-sm-8">
										<input class="form-control item-password" type="password" required="required" tabindex="1" autocomplete="new-password"
											name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.password' ) ) ) ?>"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'Password (required)' ) ) ?>"
											value="<?= $enc->attr( $this->get( 'itemData/customer.password' ) ) ?>"
											v-bind:readonly="!can('change')">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Customer password' ) ) ?>
									</div>
								</div>
							<?php endif ?>
							<div class="form-group row">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Verified' ) ) ?></label>
								<div class="col-sm-8">
									<input is="vue:flat-pickr" class="form-control item-dateverified" type="date" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.dateverified' ) ) ) ?>"
										v-bind:value="`<?= $enc->js( $this->get( 'itemData/customer.dateverified' ) ) ?>`"
										v-bind:config="Aimeos.flatpickr.date"
										v-bind:readonly="!can('change')">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'When the account and e-mail address has been verified' ) ) ?>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xl-6">
						<div class="box" v-bind:class="{mismatch: !can('match')}">
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'User groups' ) ) ?></label>
								<div class="col-sm-8">
									<input v-if="item.groups.length" v-for="id in item.groups" :key="id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'groups', '' ) ) ) ?>" :value="id" />
									<Multiselect class="item-groups form-control multiselect"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Enter group ID, code or label' ) ) ?>"
										value-prop="id"
										track-by="id"
										label="code"
										mode="tags"
										@clear="clear"
										@deselect="deselect"
										@open="function(select) {return select.refreshOptions()}"
										@select="use"
										:value="list"
										:disabled="!can('change')"
										:options="async function(query) {return await fetch(query)}"
										:hide-selected="true"
										:searchable="true"
										:min-chars="1"
										:object="true"
										:delay="300"
									></Multiselect>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xl-12">
						<div class="box" v-bind:class="{mismatch: !can('match')}">
							<div class="row">
								<div class="col-xl-6">
									<h2 class="item-header"><?= $enc->html( $this->translate( 'admin', 'Personal data' ) ) ?></h2>

									<?php if( ( $languages = $this->get( 'pageLangItems', map() ) )->count() !== 1 ) : ?>
										<div class="form-group row mandatory">
											<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?></label>
											<div class="col-sm-8">
												<select class="form-select item-languageid" required="required" tabindex="1"
													name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.languageid' ) ) ) ?>"
													v-bind:readonly="!can('change')" >
													<option value="">
														<?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?>
													</option>

													<?php foreach( $languages as $langId => $langItem ) : ?>
														<option value="<?= $enc->attr( $langId ) ?>" <?= $selected( $this->get( 'itemData/customer.languageid', '' ), $langId ) ?> >
															<?= $enc->html( $this->translate( 'language', $langId ) ) ?>
														</option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
									<?php else : ?>
										<input class="item-languageid" type="hidden"
											name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.languageid' ) ) ) ?>"
											value="<?= $enc->attr( $languages->getCode()->first() ) ?>">
									<?php endif ?>

									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Salutation' ) ) ?></label>
										<div class="col-sm-8">
											<select class="form-select item-salutation" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.salutation' ) ) ) ?>"
												v-bind:readonly="!can('change')" >
												<option value="" <?= $selected( $this->get( 'itemData/customer.salutation', '' ), '' ) ?> >
													<?= $enc->html( $this->translate( 'admin', 'none' ) ) ?>
												</option>
												<option value="mr" <?= $selected( $this->get( 'itemData/customer.salutation', '' ), 'mr' ) ?> >
													<?= $enc->html( $this->translate( 'mshop/code', 'mr' ) ) ?>
												</option>
												<option value="ms" <?= $selected( $this->get( 'itemData/customer.salutation', '' ), 'ms' ) ?> >
													<?= $enc->html( $this->translate( 'mshop/code', 'ms' ) ) ?>
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
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.title' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Honorary title (optional)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.title' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Honorary titles like Dr., Ph.D, etc.' ) ) ?>
										</div>
									</div>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Last name' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-lastname" type="text" required="required" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.lastname' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Last name (required)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.lastname' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Last name of the person or full name in cultures where no first names are used' ) ) ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'First name' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-firstname" type="text" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.firstname' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'First name (optional)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.firstname' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ) ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Birthday' ) ) ?></label>
										<div class="col-sm-8">
											<input is="vue:flat-pickr" class="form-control item-birthday" type="date" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.birthday' ) ) ) ?>"
												v-bind:value="`<?= $enc->js( $this->get( 'itemData/customer.birthday' ) ) ?>`"
												v-bind:config="Aimeos.flatpickr.date"
												v-bind:readonly="!can('change')">
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Birthday of the customer' ) ) ?>
										</div>
									</div>
								</div>

								<div class="col-xl-6">
									<h2 class="item-header"><?= $enc->html( $this->translate( 'admin', 'Billing address' ) ) ?></h2>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Street' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-address1" type="text" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.address1' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Street name (optional)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.address1' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ) ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'House number' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-address2" type="text" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.address2' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'House number (optional)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.address2' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Address identifier of the customer\'s house for delivery' ) ) ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Floor / Appartment' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-address3" type="text" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.address3' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Floor and/or apartment (optional)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.address3' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Additional information where the customer\'s apartment can be found' ) ) ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Zip code' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-postal" type="text" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.postal' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Zip code (optional)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.postal' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Postal code for delivery if used in the area the customer is living' ) ) ?>
										</div>
									</div>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'City' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-city" type="text" required="required" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.city' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'City or town name (required)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.city' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
									</div>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Country' ) ) ?></label>
										<div class="col-sm-8">
											<select class="form-select item-countryid" required="required" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.countryid' ) ) ) ?>"
												v-bind:readonly="!can('change')" >
												<option value="">
													<?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?>
												</option>

												<?php foreach( $this->get( 'countries', [] ) as $code => $label ) : ?>
													<option value="<?= $enc->attr( $code ) ?>" <?= $selected( $this->get( 'itemData/customer.countryid' ), $code ) ?> >
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
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.state' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Country state code (optional)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.state' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Short state code (e.g. NY) if used in the country the customer is living' ) ) ?>
										</div>
									</div>
								</div>

								<div class="col-xl-6">
									<h2 class="item-header"><?= $enc->html( $this->translate( 'admin', 'Communication' ) ) ?></h2>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'E-Mail' ) ) ?></label>
										<div class="col-sm-8">
											<?php if( $this->get( 'itemData/.modify' ) ) : ?>
												<input class="form-control item-email" type="email" required="required" tabindex="1" autocomplete="off"
													name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.email' ) ) ) ?>"
													placeholder="<?= $enc->attr( $this->translate( 'admin', 'E-Mail address (required)' ) ) ?>"
													value="<?= $enc->attr( $this->get( 'itemData/customer.email' ) ) ?>"
													v-bind:readonly="!can('change')">
											<?php else : ?>
												<span class="form-control item-email">
													<?= $enc->html( $this->get( 'itemData/customer.email' ) ) ?>
												</span>
											<?php endif ?>
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Unique customer e-mail address' ) ) ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Telephone' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-telephone" type="tel" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.telephone' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Telephone number (optional)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.telephone' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', '(International) telephone number without separation characters, can start with a "+"' ) ) ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Mobile number' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-mobile" type="tel" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.mobile' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Mobile number (optional)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.mobile' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', '(International) telephone number without separation characters, can start with a "+"' ) ) ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Facsimile' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-telefax" type="text" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.telefax' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Facsimile number (optional)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.telefax' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', '(International) facsimilie number without separation characters, can start with a "+"' ) ) ?>
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Web site' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-website" type="url" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.website' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Web site URL (optional)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.website' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'URL of the customer web site' ) ) ?>
										</div>
									</div>
								</div>

								<div class="col-xl-6">
									<h2 class="item-header"><?= $enc->html( $this->translate( 'admin', 'Company details' ) ) ?></h2>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Company' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-company" type="text" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.company' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Company name (optional)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.company' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
									</div>
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'VAT ID' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-vatid" type="text" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.vatid' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Value added tax identifier (optional)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/customer.vatid' ) ) ?>"
												v-bind:readonly="!can('change')">
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Official VAT ID to determine if the tax needs to be billed in invoices' ) ) ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

			<?= $this->get( 'itemBody' ) ?>

		</div>

		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'itemactions' ), ['params' => $params] ) ?>
		</div>
	</div>
</form>

<?php $this->block()->stop() ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'page' ) ) ?>
