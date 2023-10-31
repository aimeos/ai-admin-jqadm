<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


$enc = $this->encoder();


?>
<div id="address" class="item-address tab-pane fade" role="tabpanel" aria-labelledby="address">
	<div id="item-address-group" role="tablist" aria-multiselectable="true"
		data-items="<?= $enc->attr( $this->get( 'addressData', [] ) ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>"
		data-domain="supplier" >

		<div class="group-list">
			<div is="draggable" v-model="items" group="address" handle=".act-move">
				<div v-for="(entry, idx) in items" v-bind:key="idx" class="group-item card">

					<div v-bind:id="'item-address-group-item-' + idx" class="card-header header">
						<div class="card-tools-start">
							<div class="btn btn-card-header act-show fa" v-bind:class="entry['_show'] ? 'show' : 'collapsed'"
								v-bind:data-bs-target="'#item-address-group-data-' + idx" data-bs-toggle="collapse"
								v-bind:aria-controls="'item-address-group-data-' + idx" aria-expanded="false" v-on:click="toggle('_show', idx)"
								title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry' ) ) ?>" tabindex="<?= $this->get( 'tabindex' ) ?>">
							</div>
						</div>
						<span class="item-label header-label">{{ label(idx) }}</span>
						<div class="card-tools-end">
							<div class="btn btn-card-header act-copy fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Duplicate entry (Ctrl+D)' ) ) ?>"
								v-on:click.stop="duplicate(idx)">
							</div>
							<div v-if="entry['supplier.address.siteid'] == siteid"
								class="btn btn-card-header act-move fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down' ) ) ?>">
							</div>
							<div v-if="entry['supplier.address.siteid'] == siteid"
								class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
								v-on:click="remove(idx)">
							</div>
						</div>
					</div>

					<div v-bind:id="'item-address-group-data-' + idx" v-bind:class="entry['_show'] ? 'show' : 'collapsed'"
						v-bind:aria-labelledby="'item-address-group-item-' + idx" role="tabpanel" class="card-block collapse row">

						<div class="col-xl-6" v-bind:class="{readonly: !can('change', idx)}">
							<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Personal data' ) ) ?></h2>

							<input class="item-id" type="hidden"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.id' ) ) ) ?>`.replace('_idx_', idx)"
								v-bind:value="entry['supplier.address.id']">

							<?php if( ( $languages = $this->get( 'pageLangItems', map() ) )->count() !== 1 ) : ?>
								<div class="form-group row optional">
									<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?></label>
									<div class="col-sm-8">
										<select class="form-select item-languageid" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.languageid' ) ) ) ?>`.replace('_idx_', idx)"
											v-bind:readonly="entry['supplier.address.siteid'] != siteid"
											v-model="entry['supplier.address.languageid']" >

											<option value="" disable >
												<?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?>
											</option>

											<?php foreach( $languages as $langId => $langItem ) : ?>
												<option value="<?= $enc->attr( $langId ) ?>" v-bind:selected="entry['supplier.address.languageid'] == `<?= $enc->js( $langId ) ?>`" >
													<?= $enc->html( $langItem->getLabel() ) ?>
												</option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
							<?php else : ?>
								<input class="item-languageid" type="hidden"
									v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.languageid' ) ) ) ?>`.replace('_idx_', idx)"
									value="<?= $enc->attr( $languages->getCode()->first() ) ?>">
							<?php endif ?>

							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Salutation' ) ) ?></label>
								<div class="col-sm-8">
									<select class="form-select item-salutation" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.salutation' ) ) ) ?>`.replace('_idx_', idx)"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.salutation']" >
										<option value="" v-bind:selected="entry['supplier.address.salutation'] == ''" >
											<?= $enc->html( $this->translate( 'admin', 'none' ) ) ?>
										</option>
										<option value="company" v-bind:selected="entry['supplier.address.salutation'] == 'company'" >
											<?= $enc->html( $this->translate( 'mshop/code', 'company' ) ) ?>
										</option>
										<option value="mr" v-bind:selected="entry['supplier.address.salutation'] == 'mr'" >
											<?= $enc->html( $this->translate( 'mshop/code', 'mr' ) ) ?>
										</option>
										<option value="ms" v-bind:selected="entry['supplier.address.salutation'] == 'ms'" >
											<?= $enc->html( $this->translate( 'mshop/code', 'ms' ) ) ?>
										</option>
									</select>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'How the supplier is addressed in e-mails' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Title' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-title" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.title' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Honorary title (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.title']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Honorary titles like Dr., Ph.D, etc.' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Last name' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-lastname" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.lastname' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Last name (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.lastname']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Last name of the person or full name in cultures where no first names are used' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'First name' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-firstname" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.firstname' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'First name (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.firstname']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ) ?>
								</div>
							</div>
						</div><!--

						--><div class="col-xl-6" v-bind:class="{readonly: !can('change', idx)}">
							<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Delivery address' ) ) ?></h2>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Street' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-address1" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.address1' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Street name (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.address1']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'First name of the person if used in cultures where they are used' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'House number' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-address2" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.address2' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'House number (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.address2']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Address identifier of the supplier\'s house for delivery' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Floor / Appartment' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-address3" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.address3' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Floor and/or apartment (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.address3']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Additional information where the supplier\'s apartment can be found' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Zip code' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-postal" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.postal' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Zip code (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.postal']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Postal code for delivery if used in the area the supplier is living' ) ) ?>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'City' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-city" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.city' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'City or town name (required)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.city']">
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Country' ) ) ?></label>
								<div class="col-sm-8">
									<select class="form-select item-countryid" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.countryid' ) ) ) ?>`.replace('_idx_', idx)"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.countryid']">
										<option value="">
											<?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?>
										</option>

										<?php foreach( $this->get( 'countries', [] ) as $code => $label ) : ?>
											<option value="<?= $enc->attr( $code ) ?>" >
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
									<input class="form-control item-state" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.state' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Country state code (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.state']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Short state code (e.g. NY) if used in the country the supplier is living' ) ) ?>
								</div>
							</div>
						</div><!--

						--><div class="col-xl-6" v-bind:class="{readonly: !can('change', idx)}">
							<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Communication' ) ) ?></h2>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Telephone' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-telephone" type="tel" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.telephone' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Telephone number (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.telephone']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', '(International) telephone number without separation characters, can start with a "+"' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Mobile' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-mobile" type="tel" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.mobile' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Mobile number (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.mobile']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', '(International) telephone number without separation characters, can start with a "+"' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Facsimile' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-telefax" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.telefax' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Facsimile number (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.telefax']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', '(International) facsimilie number without separation characters, can start with a "+"' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'E-mail' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-email" type="email" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.email' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'E-mail address (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.email']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'E-mail address that belongs to the address' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Web site' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-website" type="url" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.website' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Web site URL (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.website']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'URL of the supplier web site' ) ) ?>
								</div>
							</div>
						</div><!--

						--><div class="col-xl-6" v-bind:class="{readonly: !can('change', idx)}">
							<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Company details' ) ) ?></h2>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Company' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-company" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.company' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Company name (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.company']">
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'VAT ID' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-vatid" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.vatid' ) ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Value added tax identifier (optional)' ) ) ?>"
										v-bind:readonly="entry['supplier.address.siteid'] != siteid"
										v-model="entry['supplier.address.vatid']">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Official VAT ID to determine if the tax needs to be billed in invoices' ) ) ?>
								</div>
							</div>
						</div>

						<div class="col-xl-12" v-bind:class="{readonly: !can('change', idx)}">
							<h2 class="col-sm-12 item-header"><?= $enc->html( $this->translate( 'admin', 'Map' ) ) ?></h2>
							<div class="osm-map">
								<input type="hidden" v-bind:value="entry['supplier.address.latitude']"
									v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.latitude' ) ) ) ?>`.replace('_idx_', idx)">
								<input type="hidden" v-bind:value="entry['supplier.address.longitude']"
									v-bind:name="`<?= $enc->js( $this->formparam( array( 'address', '_idx_', 'supplier.address.longitude' ) ) ) ?>`.replace('_idx_', idx)">
								<l-map ref="map" v-if="show" :center="point(entry)" :zoom="zoom(idx)" @click="setPoint(idx, $event)">
									<l-tile-layer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" attribution="&copy; OpenStreetMap contributors"></l-tile-layer>
									<l-marker v-if="entry['supplier.address.latitude'] && entry['supplier.address.longitude']" :lat-lng="point(entry)"></l-marker>
								</l-map>
							</div>
						</div>

						<?= $this->get( 'addressBody' ) ?>

						<div class="col-12 secondary item-meta text-muted">
							<small>
								<?= $enc->html( $this->translate( 'admin', 'Site' ) ) ?>:
								<span class="meta-value">{{ entry['supplier.address.siteid'] }}</span>
							</small>
							<small>
								<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
								<span class="meta-value">{{ entry['supplier.address.editor'] }}</span>
							</small>
							<small>
								<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
								<span class="meta-value">{{ entry['supplier.address.ctime'] }}</span>
							</small>
							<small>
								<?= $enc->html( $this->translate( 'admin', 'Modified' ) ) ?>:
								<span class="meta-value">{{ entry['supplier.address.mtime'] }}</span>
							</small>
						</div>

					</div>
				</div>
			</div>

			<div slot="footer" class="card-tools-more">
				<div class="btn btn-primary btn-card-more act-add fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
					title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
					v-on:click="add()" >
				</div>
			</div>
		</div>
	</div>
</div>
