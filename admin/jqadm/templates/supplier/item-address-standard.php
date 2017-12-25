<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */


$enc = $this->encoder();

$keys = [
	'supplier.address.id', 'supplier.address.siteid', 'supplier.address.languageid', 'supplier.address.salutation',
	'supplier.address.title', 'supplier.address.firstname', 'supplier.address.lastname', 'supplier.address.address1',
	'supplier.address.address2', 'supplier.address.address3', 'supplier.address.postal', 'supplier.address.city',
	'supplier.address.countryid', 'supplier.address.state', 'supplier.address.telephone', 'supplier.address.telefax',
	'supplier.address.email', 'supplier.address.website', 'supplier.address.company', 'supplier.address.vatid'
];


?>
<div id="address" class="item-address content-block tab-pane fade" role="tabpanel" aria-labelledby="address">
	<div id="item-address-group" role="tablist" aria-multiselectable="true"
		data-items="<?= $enc->attr( json_encode( $this->get( 'addressData', [] ) ) ); ?>"
		data-keys="<?= $enc->attr( json_encode( $keys ) ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>" >

		<div v-for="(id, idx) in items['supplier.address.id']" v-bind:key="idx" class="group-item card">
			<input class="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.id', '' ) ) ); ?>"
				v-bind:value="items['supplier.address.id'][idx]" />

			<div v-bind:id="'item-address-group-item-' + idx" v-bind:class="getCss(idx, 'supplier.address.')"
				v-bind:data-target="'#item-address-group-data-' + idx" data-toggle="collapse" role="tab" class="card-header header"
				v-bind:aria-controls="'item-address-group-data-' + idx" aria-expanded="false">
				<div class="card-tools-left">
					<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry') ); ?>">
					</div>
				</div>
				<span class="item-label header-label">{{ getLabel(idx, 'supplier.address.') }}</span>
				&nbsp;
				<div class="card-tools-right">
					<div class="btn btn-card-header act-copy fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Duplicate entry (Ctrl+D)') ); ?>"
						v-on:click.stop="duplicateItem(idx)">
					</div>
					<div v-if="!checkSite('supplier.address.siteid', idx)"
						class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
						v-on:click="removeItem(idx)">
					</div>
				</div>
			</div>

			<div v-bind:id="'item-address-group-data-' + idx" v-bind:class="getCss(idx, 'supplier.address.')"
				v-bind:aria-labelledby="'item-address-group-item-' + idx" role="tabpanel" class="card-block collapse row">

				<div class="col-xl-6 content-block">
					<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Personal data' ) ); ?></h2>

					<?php $languages = $this->get( 'pageLangItems', [] ) ?>
					<?php if( count( $languages ) > 1 ) : ?>
						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></label>
							<div class="col-sm-8">
								<select class="form-control custom-select item-languageid" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.languageid', '' ) ) ); ?>"
									v-bind:readonly="checkSite('supplier.address.siteid', idx)"
									v-model="items['supplier.address.languageid'][idx]" >

									<option value="" disable >
										<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
									</option>

									<?php foreach( $languages as $langId => $langItem ) : ?>
										<option value="<?= $enc->attr( $langId ); ?>" v-bind:selected="items['supplier.address.languageid'] == '<?= $enc->attr( $langId ) ?>'" >
											<?= $enc->html( $langItem->getLabel() ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					<?php else : ?>
						<input class="item-languageid" type="hidden"
							name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.languageid', '' ) ) ); ?>"
							value="<?= $enc->attr( key( $languages ) ); ?>" />
					<?php endif; ?>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Salutation' ) ); ?></label>
						<div class="col-sm-8">
							<select class="form-control custom-select item-salutation" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.salutation', '' ) ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.salutation'][idx]" >
								<option value="company" v-bind:selected="items['supplier.address.salutation'][idx] == 'company'" >
									<?= $enc->html( $this->translate( 'mshop/code', 'company' ) ); ?>
								</option>
								<option value="mr" v-bind:selected="items['supplier.address.salutation'][idx] == 'mr'" >
									<?= $enc->html( $this->translate( 'mshop/code', 'mr' ) ); ?>
								</option>
								<option value="mrs" v-bind:selected="items['supplier.address.salutation'][idx] == 'mrs'" >
									<?= $enc->html( $this->translate( 'mshop/code', 'mrs' ) ); ?>
								</option>
								<option value="miss" v-bind:selected="items['supplier.address.salutation'][idx] == 'miss'" >
									<?= $enc->html( $this->translate( 'mshop/code', 'miss' ) ); ?>
								</option>
							</select>
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'How the supplier is addressed in e-mails' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Title' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-title" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.title', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Honorary title (optional)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.title'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Honorary titles like Dr., Ph.D, etc.' ) ); ?>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Last name' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-lastname" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.lastname', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Last name (required)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.lastname'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Last name of the person or full name in cultures where no first names are used' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'First name' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-firstname" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.firstname', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'First name (optional)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.firstname'][idx]" />
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
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.address1', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Street name (optional)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.address1'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'House number' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-address2" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.address2', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'House number (optional)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.address2'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Address identifier of the supplier\'s office' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Floor / Appartment' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-address3" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.address3', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Floor and/or appartment (optional)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.address3'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Additional information where the supplier\'s office can be found' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Zip code' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-postal" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.postal', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Zip code (optional)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.postal'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Postal code for delivery if used in the area the supplier\'s office' ) ); ?>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'City' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-city" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.city', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'City or town name (required)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.city'][idx]" />
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Country' ) ); ?></label>
						<div class="col-sm-8">
							<select is="combo-box" class="form-control c-select item-countryid" required="required"
								v-bind:name="'<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.countryid', '' ) ) ); ?>'"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-bind:tabindex="'<?= $this->get( 'tabindex' ); ?>'"
								v-bind:getfcn="getCountries"
								v-model="items['supplier.address.countryid'][idx]" >
								<option value=""></option>
							</select>
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Two letter ISO country code' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'State' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-state" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.state', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Country state code (optional)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.state'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Short state code (e.g. NY) if used in the country the supplier has his office' ) ); ?>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block">
					<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Communication' ) ); ?></h2>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Telephone' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-telephone" type="tel" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.telephone', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Telephone number (optional)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.telephone'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', '(International) telephone number without separation characters, can start with a "+"' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Facsimile' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-telefax" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.telefax', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Facsimile number (optional)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.telefax'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', '(International) facsimilie number without separation characters, can start with a "+"' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'E-mail' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-email" type="email" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.email', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'E-mail address (optional)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.email'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'E-mail address that belongs to the address' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Web site' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-website" type="url" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.website', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Web site URL (optional)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.website'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'URL of the supplier web site' ) ); ?>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block">
					<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Company details' ) ); ?></h2>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Company' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-company" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.company', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Company name (optional)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.company'][idx]" />
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'VAT ID' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-vatid" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'address', 'supplier.address.vatid', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Value added tax identifier (optional)' ) ); ?>"
								v-bind:readonly="checkSite('supplier.address.siteid', idx)"
								v-model="items['supplier.address.vatid'][idx]" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Official VAT ID to determine if the tax needs to be billed in invoices' ) ); ?>
						</div>
					</div>
				</div>

				<?= $this->get( 'addressBody' ); ?>

			</div>
		</div>
	</div>

	<div class="card-tools-more">
		<div class="btn btn-primary btn-card-more act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
			title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
			v-on:click="addItem('supplier.address.')" >
		</div>
	</div>
</div>
