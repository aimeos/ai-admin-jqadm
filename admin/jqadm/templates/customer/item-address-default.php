<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$enc = $this->encoder();


?>
<div id="address" class="item-address content-block tab-pane fade" role="tabpanel" aria-labelledby="address">
	<div id="item-address-group" role="tablist" aria-multiselectable="true">

		<?php foreach( (array) $this->get( 'addressData/customer.address.id', [] ) as $idx => $addressid ) : ?>
			<?php $readonly = ( $this->access( 'admin' ) === false ? $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ) : '' ); ?>

			<div class="group-item card <?= $readonly ?>">
				<input class="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.id', '' ) ) ); ?>"
					value="<?= $enc->attr( $this->get( 'addressData/customer.address.id/' . $idx ) ); ?>" />

				<div id="item-address-group-item-<?= $enc->attr( $idx ); ?>" class="card-header header  <?= ( $idx !== 0 ? 'collapsed' : '' ); ?>" role="tab"
					data-toggle="collapse" data-target="#item-address-group-data-<?= $enc->attr( $idx ); ?>"
					aria-expanded="false" aria-controls="item-address-group-data-<?= $enc->attr( $idx ); ?>">
					<div class="card-tools-left">
						<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry') ); ?>">
						</div>
					</div>
					<span class="item-label header-label">
						<?= $enc->html( $this->get( 'addressData/customer.address.firstname/' . $idx ) ); ?>
						<?= $enc->html( $this->get( 'addressData/customer.address.lastname/' . $idx ) ); ?>
						-
						<?= $enc->html( $this->get( 'addressData/customer.address.postal/' . $idx ) ); ?>
						<?= $enc->html( $this->get( 'addressData/customer.address.city/' . $idx ) ); ?>
					</span>
					&nbsp;
					<div class="card-tools-right">
						<div class="btn btn-card-header act-copy fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Duplicate entry (Ctrl+D)') ); ?>">
						</div>
						<?php if( !$this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ) ) : ?>
							<div class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div id="item-address-group-data-<?= $enc->attr( $idx ); ?>" class="card-block collapse row <?= ( $idx === 0 ? 'show' : '' ); ?>"
					role="tabpanel" aria-labelledby="item-address-group-item-<?= $enc->attr( $idx ); ?>">

					<div class="col-xl-6 content-block">
						<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Personal data' ) ); ?></h2>
						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></label>
							<div class="col-sm-8">

								<?php $languages = $this->get( 'pageLanguages', [] ); ?>
								<?php if( count( $languages ) > 1 ) : ?>
									<select class="form-control custom-select item-languageid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'customer.languageid' ) ) ); ?>"
										<?= $this->site()->readonly( $this->get( 'itemData/customer.siteid' ) ); ?> >
										<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?></option>

										<?php foreach( $languages as $langId => $langItem ) : ?>
											<option value="<?= $enc->attr( $langId ); ?>" <?= $selected( $this->get( 'itemData/customer.languageid', '' ), $langId ); ?> >
												<?= $enc->html( $this->translate( 'client/language', $langId ) ); ?>
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
								<select class="form-control custom-select item-salutation" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.salutation', '' ) ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> >
									<option value="" <?= $selected( $this->get( 'addressData/customer.address.salutation/' . $idx, '' ), '' ); ?> >
										<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
									</option>
									<option value="company" <?= $selected( $this->get( 'addressData/customer.address.salutation/' . $idx, '' ), 'company' ); ?> >
										<?= $enc->html( $this->translate( 'client/code', 'company' ) ); ?>
									</option>
									<option value="mr" <?= $selected( $this->get( 'addressData/customer.address.salutation/' . $idx, '' ), 'mr' ); ?> >
										<?= $enc->html( $this->translate( 'client/code', 'mr' ) ); ?>
									</option>
									<option value="mrs" <?= $selected( $this->get( 'addressData/customer.address.salutation/' . $idx, '' ), 'mrs' ); ?> >
										<?= $enc->html( $this->translate( 'client/code', 'mrs' ) ); ?>
									</option>
									<option value="miss" <?= $selected( $this->get( 'addressData/customer.address.salutation/' . $idx, '' ), 'miss' ); ?> >
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
								<input class="form-control item-title" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.title', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Honorary title (optional)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.title/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Honorary titles like Dr., Ph.D, etc.' ) ); ?>
							</div>
						</div>
						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Last name' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-lastname" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.lastname', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Last name (required)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.lastname/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Last name of the person or full name in cultures where no first names are used' ) ); ?>
							</div>
						</div>
						<div class="form-group row optional">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'First name' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-firstname" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.firstname', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'First name (optional)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.firstname/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ); ?>
							</div>
						</div>
					</div><!--

					--><div class="col-xl-6 content-block">
						<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Delivery address' ) ); ?></h2>
						<div class="form-group row optional">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Street' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-address1" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.address1', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Street name (optional)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.address1/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ); ?>
							</div>
						</div>
						<div class="form-group row optional">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'House number' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-address2" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.address2', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'House number (optional)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.address2/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Address identifier of the customer\'s house for delivery' ) ); ?>
							</div>
						</div>
						<div class="form-group row optional">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Floor / Appartment' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-address3" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.address3', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Floor and/or appartment (optional)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.address3/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Additional information where the customer\'s appartment can be found' ) ); ?>
							</div>
						</div>
						<div class="form-group row optional">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Zip code' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-postal" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.postal', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Zip code (optional)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.postal/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Postal code for delivery if used in the area the customer is living' ) ); ?>
							</div>
						</div>
						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'City' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-city" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.city', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'City or town name (required)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.city/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
						</div>
						<div class="form-group row optional">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'State' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-state" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.state', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Country state code (optional)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.state/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Short state code (e.g. NY) if used in the country the customer is living' ) ); ?>
							</div>
						</div>
						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Country' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-countryid" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" maxlength="2" pattern="^[a-zA-Z]{2}$"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.countryid', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Country code (required)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.countryid/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Two letter ISO country code' ) ); ?>
							</div>
						</div>
					</div><!--

					--><div class="col-xl-6 content-block">
						<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Communication' ) ); ?></h2>
						<div class="form-group row optional">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Telephone' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-telephone" type="tel" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.telephone', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Telephone number (optional)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.telephone/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', '(International) telephone number without separation characters, can start with a "+"' ) ); ?>
							</div>
						</div>
						<div class="form-group row optional">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Facsimile' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-telefax" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.telefax', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Facsimile number (optional)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.telefax/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', '(International) facsimilie number without separation characters, can start with a "+"' ) ); ?>
							</div>
						</div>
						<div class="form-group row optional">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Web site' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-website" type="url" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.website', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Web site URL (optional)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.website/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'URL of the customer web site' ) ); ?>
							</div>
						</div>
					</div><!--

					--><div class="col-xl-6 content-block">
						<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Company details' ) ); ?></h2>
						<div class="form-group row optional">
							<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Company' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-company" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.company', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Company name (optional)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.company/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
						</div>
						<div class="form-group row optional">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'VAT ID' ) ); ?></label>
							<div class="col-sm-8">
								<input class="form-control item-vatid" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.vatid', '' ) ) ); ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Value added tax identifier (optional)' ) ); ?>"
									value="<?= $enc->attr( $this->get( 'addressData/customer.address.vatid/' . $idx ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'addressData/customer.address.siteid/' . $idx ) ); ?> />
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Official VAT ID to determine if the tax needs to be billed in invoices' ) ); ?>
							</div>
						</div>
					</div>

				</div>
			</div>

		<?php endforeach; ?>

		<div class="group-item card prototype">

			<div id="item-address-group-item-" class="card-header header" role="tab"
				data-toggle="collapse" data-target="#item-address-group-data-">
				<div class="card-tools-left">
					<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry') ); ?>">
					</div>
				</div>
				<span class="item-label header-label"></span>
				&nbsp;
				<div class="card-tools-right">
					<div class="btn btn-card-header act-copy fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Duplicate entry (Ctrl+D)') ); ?>">
					</div>
					<div class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
					</div>
				</div>
			</div>

			<div id="item-address-group-data-" class="card-block collapse show row" role="tabpanel">
				<input class="item-id" type="hidden" value="" disabled="disabled"
					name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.id', '' ) ) ); ?>" />

				<div class="col-xl-6 content-block">
					<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Personal data' ) ); ?></h2>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Salutation' ) ); ?></label>
						<div class="col-sm-8">
							<select class="form-control custom-select item-salutation" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.salutation', '' ) ) ); ?>"  >
								<option value="" <?= $selected( $this->get( 'addressData/customer.address.salutation', '' ), '' ); ?> >
									<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
								</option>
								<option value="company" <?= $selected( $this->get( 'addressData/customer.address.salutation', '' ), 'company' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'company' ) ); ?>
								</option>
								<option value="mr" <?= $selected( $this->get( 'addressData/customer.address.salutation', '' ), 'mr' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'mr' ) ); ?>
								</option>
								<option value="mrs" <?= $selected( $this->get( 'addressData/customer.address.salutation', '' ), 'mrs' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'mrs' ) ); ?>
								</option>
								<option value="miss" <?= $selected( $this->get( 'addressData/customer.address.salutation', '' ), 'miss' ); ?> >
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
							<input class="form-control item-title" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.title', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Honorary title (optional)' ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Honorary titles like Dr., Ph.D, etc.' ) ); ?>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Last name' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-lastname" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.lastname', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Last name (required)' ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Last name of the person or full name in cultures where no first names are used' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'First name' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-firstname" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.firstname', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'First name (optional)' ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ); ?>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block">
					<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Delivery address' ) ); ?></h2>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Street' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-address1" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.address1', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Street name (optional)' ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'House number' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-address2" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.address2', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'House number (optional)' ) ); ?>"/>
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Address identifier of the customer\'s house for delivery' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Floor / Appartment' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-address3" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.address3', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Floor and/or appartment (optional)' ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Additional information where the customer\'s appartment can be found' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Zip code' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-postal" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.postal', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Zip code (optional)' ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Postal code for delivery if used in the area the customer is living' ) ); ?>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'City' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-city" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.city', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'City or town name (required)' ) ); ?>" />
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'State' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-state" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.state', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Country state code (optional)' ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Short state code (e.g. NY) if used in the country the customer is living' ) ); ?>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Country' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-countryid" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" maxlength="2" pattern="^[a-zA-Z]{2}$" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.countryid', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Country code (required)' ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Two letter ISO country code' ) ); ?>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block">
					<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Communication' ) ); ?></h2>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Telephone' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-telephone" type="tel" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.telephone', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Telephone number (optional)' ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', '(International) telephone number without separation characters, can start with a "+"' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Facsimile' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-telefax" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.telefax', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Facsimile number (optional)' ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', '(International) facsimilie number without separation characters, can start with a "+"' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Web site' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-website" type="url" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.website', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Web site URL (optional)' ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'URL of the customer web site' ) ); ?>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block">
					<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Company details' ) ); ?></h2>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Company' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-company" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.company', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Company name (optional)' ) ); ?>" />
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'VAT ID' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-vatid" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'customer.address.vatid', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Value added tax identifier (optional)' ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Official VAT ID to determine if the tax needs to be billed in invoices' ) ); ?>
						</div>
					</div>
				</div>

			</div>
		</div>

		<div class="card-tools-more">
			<div class="btn btn-primary btn-card-more act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
				title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+A)') ); ?>">
			</div>
		</div>

	</div>

	<?= $this->get( 'addressBody' ); ?>
</div>
