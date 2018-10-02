<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$enc = $this->encoder();


$target = $this->config( 'admin/jqadm/url/save/target' );
$cntl = $this->config( 'admin/jqadm/url/save/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/save/action', 'save' );
$config = $this->config( 'admin/jqadm/url/save/config', [] );

$params = $this->get( 'pageParams', [] );


?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<form class="item item-customer form-horizontal" method="POST" enctype="multipart/form-data" action="<?= $enc->attr( $this->url( $target, $cntl, $action, $params, [], $config ) ); ?>">
	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.id' ) ) ); ?>" value="<?= $enc->attr( $this->get( 'itemData/customer.id' ) ); ?>" />
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ); ?>" value="get" />
	<?= $this->csrf()->formfield(); ?>

	<nav class="main-navbar">
		<span class="navbar-brand">
			<?= $enc->html( $this->translate( 'admin', 'Customer' ) ); ?>:
			<?= $enc->html( $this->get( 'itemData/customer.id' ) ); ?> -
			<?= $enc->html( $this->get( 'itemData/customer.label', $this->translate( 'admin', 'New' ) ) ); ?>
			<span class="navbar-secondary">(<?= $enc->html( $this->site()->match( $this->get( 'itemData/customer.siteid' ) ) ); ?>)</span>
		</span>
		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard.php' ), ['params' => $params] ); ?>
		</div>
	</nav>

	<div class="row item-container">

		<div class="col-md-3 item-navbar">
			<ul class="nav nav-tabs flex-md-column flex-wrap d-flex justify-content-between" role="tablist">

				<li class="nav-item basic">
					<a class="nav-link active" href="#basic" data-toggle="tab" role="tab" aria-expanded="true" aria-controls="basic">
						<?= $enc->html( $this->translate( 'admin', 'Basic' ) ); ?>
					</a>
				</li>

				<?php foreach( array_values( $this->get( 'itemSubparts', [] ) ) as $idx => $subpart ) : ?>
					<li class="nav-item <?= $enc->attr( $subpart ); ?>">
						<a class="nav-link" href="#<?= $enc->attr( $subpart ); ?>" data-toggle="tab" role="tab" tabindex="<?= ++$idx+1; ?>">
							<?= $enc->html( $this->translate( 'admin', $subpart ) ); ?>
						</a>
					</li>
				<?php endforeach; ?>

			</ul>

			<div class="item-meta text-muted">
				<small>
					<?= $enc->html( $this->translate( 'admin', 'Modified' ) ); ?>:
					<span class="meta-value"><?= $enc->html( $this->get( 'itemData/customer.mtime' ) ); ?></span>
				</small>
				<small>
					<?= $enc->html( $this->translate( 'admin', 'Created' ) ); ?>:
					<span class="meta-value"><?= $enc->html( $this->get( 'itemData/customer.ctime' ) ); ?></span>
				</small>
				<small>
					<?= $enc->html( $this->translate( 'admin', 'Editor' ) ); ?>:
					<span class="meta-value"><?= $enc->html( $this->get( 'itemData/customer.editor' ) ); ?></span>
				</small>
			</div>
		</div>

		<div class="col-md-9 item-content tab-content">
			<?php $readonly = ( $this->access( 'admin' ) === false ? $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ) : '' ); ?>

			<div id="basic" class="row item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic">

				<div class="col-xl-6 content-block <?= $readonly ?>">
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ); ?></label>
						<div class="col-sm-8">
							<select class="form-control custom-select item-status" required="required" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.status' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> >
								<option value="">
									<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
								</option>
								<option value="1" <?= $selected( $this->get( 'itemData/customer.status', 1 ), 1 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ); ?>
								</option>
								<option value="0" <?= $selected( $this->get( 'itemData/customer.status', 1 ), 0 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ); ?>
								</option>
								<option value="-1" <?= $selected( $this->get( 'itemData/customer.status', 1 ), -1 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ); ?>
								</option>
								<option value="-2" <?= $selected( $this->get( 'itemData/customer.status', 1 ), -2 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ); ?>
								</option>
							</select>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'E-Mail' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-email" type="email" required="required" tabindex="1" autocomplete="off"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.email' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'E-Mail address (required)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.email' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Unique customer e-mail address' ) ); ?>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Password' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-password" type="password" required="required" tabindex="1" autocomplete="off"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.password' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Password (required)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.password' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Customer password' ) ); ?>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block <?= $readonly ?>">
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'User groups' ) ); ?></label>
						<div class="col-sm-8">
							<select class="form-control item-groups" tabindex="1" size="7" multiple
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.groups', '' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> >

								<?php foreach( $this->get( 'itemGroups', [] ) as $groupId => $groupItem ) : ?>
									<option value="<?= $enc->attr( $groupId ); ?>" <?= $selected( in_array( $groupId, $this->get( 'itemData/customer.groups', [] ) ), true ); ?> >
										<?= $enc->html( $groupItem->getLabel() ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block <?= $readonly ?>">
					<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Personal data' ) ); ?></h2>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></label>
						<div class="col-sm-8">

							<?php $languages = $this->get( 'pageLangItems', [] ); ?>
							<?php if( count( $languages ) > 1 ) : ?>
								<select class="form-control custom-select item-languageid" required="required" tabindex="1"
									name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.languageid' ) ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> >
									<option value="">
										<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
									</option>

									<?php foreach( $languages as $langId => $langItem ) : ?>
										<option value="<?= $enc->attr( $langId ); ?>" <?= $selected( $this->get( 'itemData/customer.languageid', '' ), $langId ); ?> >
											<?= $enc->html( $this->translate( 'language', $langId ) ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							<?php else : ?>
								<?php $language = ( ( $item = reset( $languages ) ) !== false ? $item->getId() : '' ); ?>
								<input class="item-languageid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.languageid' ) ) ); ?>" value="<?= $enc->attr( $language ); ?>" />
							<?php endif; ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Salutation' ) ); ?></label>
						<div class="col-sm-8">
							<select class="form-control custom-select item-salutation" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.salutation' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> >
								<option value="" <?= $selected( $this->get( 'itemData/customer.salutation', '' ), '' ); ?> >
									<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
								</option>
								<option value="company" <?= $selected( $this->get( 'itemData/customer.salutation', '' ), 'company' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'company' ) ); ?>
								</option>
								<option value="mr" <?= $selected( $this->get( 'itemData/customer.salutation', '' ), 'mr' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'mr' ) ); ?>
								</option>
								<option value="mrs" <?= $selected( $this->get( 'itemData/customer.salutation', '' ), 'mrs' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'mrs' ) ); ?>
								</option>
								<option value="miss" <?= $selected( $this->get( 'itemData/customer.salutation', '' ), 'miss' ); ?> >
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
							<input class="form-control item-title" type="text" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.title' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Honorary title (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.title' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Honorary titles like Dr., Ph.D, etc.' ) ); ?>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Last name' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-lastname" type="text" required="required" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.lastname' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Last name (required)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.lastname' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Last name of the person or full name in cultures where no first names are used' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'First name' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-firstname" type="text" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.firstname' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'First name (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.firstname' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Birthday' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-birthday" type="date" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.birthday' ) ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.birthday' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Birthday of the customer' ) ); ?>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block <?= $readonly ?>">
					<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Billing address' ) ); ?></h2>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Street' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-address1" type="text" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.address1' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Street name (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.address1' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'House number' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-address2" type="text" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.address2' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'House number (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.address2' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Address identifier of the customer\'s house for delivery' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Floor / Appartment' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-address3" type="text" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.address3' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Floor and/or apartment (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.address3' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Additional information where the customer\'s apartment can be found' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Zip code' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-postal" type="text" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.postal' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Zip code (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.postal' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Postal code for delivery if used in the area the customer is living' ) ); ?>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'City' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-city" type="text" required="required" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.city' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'City or town name (required)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.city' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Country' ) ); ?></label>
						<div class="col-sm-8">
							<select class="combobox item-countryid" required="required" tabindex="1" maxlength="2" pattern="^[a-zA-Z]{2}$"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.countryid' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
								<option value="<?= $enc->attr( $this->get( 'itemData/customer.countryid' ) ); ?>" >
									<?= $enc->html( $this->get( 'itemData/customer.countryid' ) ); ?>
								</option>
							</select>
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Two letter ISO country code' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'State' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-state" type="text" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.state' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Country state code (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.state' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Short state code (e.g. NY) if used in the country the customer is living' ) ); ?>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block <?= $readonly ?>">
					<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Communication' ) ); ?></h2>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Telephone' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-telephone" type="tel" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.telephone' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Telephone number (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.telephone' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', '(International) telephone number without separation characters, can start with a "+"' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Facsimile' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-telefax" type="text" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.telefax' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Facsimile number (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.telefax' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', '(International) facsimilie number without separation characters, can start with a "+"' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Web site' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-website" type="url" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.website' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Web site URL (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.website' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'URL of the customer web site' ) ); ?>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block <?= $readonly ?>">
					<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Company details' ) ); ?></h2>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Company' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-company" type="text" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.company' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Company name (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.company' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'VAT ID' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-vatid" type="text" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.vatid' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Value added tax identifier (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/customer.vatid' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Official VAT ID to determine if the tax needs to be billed in invoices' ) ); ?>
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

<?php $this->block()->stop(); ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard.php' ) ); ?>
