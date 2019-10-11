<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */


$enc = $this->encoder();

$keys = [
	'catalog.lists.id', 'catalog.lists.siteid', 'catalog.lists.type', 'catalog.lists.datestart', 'catalog.lists.dateend',
	'media.id', 'media.siteid', 'media.preview', 'media.label', 'media.status', 'media.type', 'media.languageid'
];


?>
<div id="media" class="item-media content-block tab-pane fade" role="tablist" aria-labelledby="media">
	<div id="item-media-group" role="tablist" aria-multiselectable="true"
		data-items="<?= $enc->attr( json_encode( $this->get( 'mediaData', [] ), JSON_HEX_AMP ) ); ?>"
		data-listtype="<?= key( $this->get( 'mediaListTypes', [] ) ) ?>"
		data-keys="<?= $enc->attr( json_encode( $keys, JSON_HEX_AMP ) ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>" >

		<div class="group-list">
			<div is="draggable" v-model="items" group="media" handle=".act-move">
				<div v-for="(entry, idx) in items" v-bind:key="idx" class="group-item card">

					<div v-bind:id="'item-media-group-item-' + idx" v-bind:class="getCss(idx)"
						v-bind:data-target="'#item-media-group-data-' + idx" data-toggle="collapse" role="tab" class="card-header header"
						v-bind:aria-controls="'item-media-group-data-' + idx" aria-expanded="false">
						<div class="card-tools-left">
							<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry' ) ); ?>">
							</div>
						</div>
						<span class="item-label header-label" v-html="getLabel(idx)"></span>
						&nbsp;
						<div class="card-tools-right">
							<div v-if="!checkSite('catalog.lists.siteid', idx) && entry['catalog.lists.id'] != ''"
								class="btn btn-card-header act-move fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down' ) ); ?>">
							</div>
							<div v-if="!checkSite('catalog.lists.siteid', idx)"
								class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ); ?>"
								v-on:click.stop="removeItem(idx)">
							</div>
						</div>
					</div>

					<div v-bind:id="'item-media-group-data-' + idx" v-bind:class="getCss(idx)"
						v-bind:aria-labelledby="'item-media-group-item-' + idx" role="tabpanel" class="card-block collapse row">

						<input type="hidden" v-model="items[idx]['media.id']"
							v-bind:name="'<?= $enc->attr( $this->formparam( array( 'media', 'idx', 'media.id' ) ) ); ?>'.replace( 'idx', idx )" />

						<div class="col-xl-6">

							<div class="form-group row media-preview">
								<input v-on:change="updateFile(idx, $event.target.files)"
									class="fileupload" type="file" tabindex="<?= $this->get( 'tabindex' ); ?>"
									v-bind:name="'media[idx][file]'.replace( 'idx', idx )" />
								<input class="item-url" type="hidden"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'media', 'idx', 'media.url' ) ) ); ?>'.replace( 'idx', idx )"
									v-model="items[idx]['media.url']" />
								<img v-if="items[idx]['media.preview']" class="item-preview"
									v-bind:src="getUrl('<?= $this->content( '' ) ?>', items[idx]['media.preview'])"
									v-bind:alt="items[idx]['media.label']" />
								<p v-else class="item-preview">
									{{ items[idx]['media.label'] || '<?= $enc->html( $this->translate( 'admin', 'Select file' ) ) ?>' }}
								</p>
							</div>

						</div>

						<div class="col-xl-6">

							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ); ?></label>
								<div class="col-sm-8">
									<select class="form-control custom-select item-status" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'media', 'idx', 'media.status' ) ) ); ?>'.replace( 'idx', idx )"
										v-bind:readonly="checkSite('media.siteid', idx)"
										v-model="items[idx]['media.status']" >
										<option value="1" v-bind:selected="items[idx]['media.status'] == 1" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ); ?>
										</option>
										<option value="0" v-bind:selected="items[idx]['media.status'] == 0" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ); ?>
										</option>
										<option value="-1" v-bind:selected="items[idx]['media.status'] == -1" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ); ?>
										</option>
										<option value="-2" v-bind:selected="items[idx]['media.status'] == -2" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ); ?>
										</option>
									</select>
								</div>
							</div>
							<?php $mediaTypes = $this->get( 'mediaTypes', [] ); ?>
							<?php if( count( $mediaTypes ) > 1 ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
									<div class="col-sm-8">
										<select class="form-control custom-select item-type" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
											v-bind:name="'<?= $enc->attr( $this->formparam( array( 'media', 'idx', 'media.type' ) ) ); ?>'.replace( 'idx', idx )"
											data-default="<?= $enc->attr( key( $mediaTypes ) ) ?>"
											v-bind:readonly="checkSite('media.siteid', idx)"
											v-model="items[idx]['media.type']" >

											<option value="" disabled >
												<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
											</option>

											<?php foreach( (array) $mediaTypes as $type => $item ) : ?>
												<option value="<?= $enc->attr( $type ); ?>" v-bind:selected="items[idx]['media.type'] == '<?= $enc->attr( $type ) ?>'" >
													<?= $enc->html( $item->getLabel() ); ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Types for additional images like icons' ) ); ?>
									</div>
								</div>
							<?php else : ?>
								<input class="item-type" type="hidden"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'media', 'idx', 'media.type' ) ) ); ?>'.replace( 'idx', idx )"
									value="<?= $enc->attr( key( $mediaTypes ) ); ?>" />
							<?php endif; ?>

							<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Title' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'media', 'idx', 'media.label' ) ) ); ?>'.replace( 'idx', idx )"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Title' ) ); ?>"
										v-bind:readonly="checkSite('media.siteid', idx)"
										v-model="items[idx]['media.label']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The media title is used for the title tag of the media on the web site' ) ); ?>
								</div>
							</div>

							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></label>
								<div class="col-sm-8">
									<select class="form-control custom-select item-languageid" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'media', 'idx', 'media.languageid' ) ) ); ?>'.replace( 'idx', idx )"
										v-bind:readonly="checkSite('media.siteid', idx)"
										v-model="items[idx]['media.languageid']" >

										<option v-bind:value="null">
											<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>
										</option>

										<?php foreach( $this->get( 'pageLangItems', [] ) as $langId => $langItem ) : ?>
											<option value="<?= $enc->attr( $langId ); ?>" v-bind:selected="items[idx]['media.languageid'] == '<?= $enc->attr( $langId ) ?>'" >
												<?= $enc->html( $langItem->getLabel() ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Images will only be shown for that language, useful if the media contains text or is language sepecific' ) ); ?>
								</div>
							</div>

						</div>


						<div v-on:click="toggle(idx)" class="col-xl-12 advanced" v-bind:class="{ 'collapsed': !advanced[idx] }">
							<div class="card-tools-left">
								<div class="btn act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide advanced data' ) ); ?>">
								</div>
							</div>
							<span class="header-label"><?= $enc->html( $this->translate( 'admin', 'Advanced' ) ); ?></span>
						</div>

						<div v-show="advanced[idx]" class="col-xl-6 content-block secondary">
							<input type="hidden" v-model="items[idx]['catalog.lists.type']"
								v-bind:name="'<?= $enc->attr( $this->formparam( array( 'media', 'idx', 'catalog.lists.type' ) ) ); ?>'.replace( 'idx', idx )" />
							<?php $listTypes = $this->get( 'mediaListTypes', [] ); ?>
							<?php if( count( $listTypes ) > 1 ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'List type' ) ); ?></label>
									<div class="col-sm-8">
										<select class="form-control custom-select listitem-type" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
											v-bind:name="'<?= $enc->attr( $this->formparam( array( 'media', 'idx', 'catalog.lists.type' ) ) ); ?>'.replace( 'idx', idx )"
											v-bind:readonly="checkSite('catalog.lists.siteid', idx)"
											v-model="items[idx]['catalog.lists.type']" >

											<?php foreach( $this->get( 'mediaListTypes', [] ) as $type => $item ) : ?>
												<option value="<?= $enc->attr( $type ); ?>" v-bind:selected="items[idx]['catalog.lists.type'] == '<?= $enc->attr( $type ) ?>'" >
													<?= $enc->html( $item->getLabel() ); ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Second level type for grouping items' ) ); ?>
									</div>
								</div>
							<?php else : ?>
								<input class="listitem-type" type="hidden"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'media', 'idx', 'catalog.lists.type' ) ) ); ?>'.replace( 'idx', idx )"
									value="<?= $enc->attr( key( $listTypes ) ); ?>"
									v-model="items[idx]['catalog.lists.type']" />
							<?php endif; ?>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control listitem-datestart" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'media', 'idx', 'catalog.lists.datestart' ) ) ); ?>'.replace( 'idx', idx )"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
										v-bind:readonly="checkSite('catalog.lists.siteid', idx)"
										v-model="items[idx]['catalog.lists.datestart']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The item is only shown on the web site after that date and time' ) ); ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control listitem-dateend" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'media', 'idx', 'catalog.lists.dateend' ) ) ); ?>'.replace( 'idx', idx )"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
										v-bind:readonly="checkSite('catalog.lists.siteid', idx)"
										v-model="items[idx]['catalog.lists.dateend']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The item is only shown on the web site until that date and time' ) ); ?>
								</div>
							</div>
						</div>

						<div v-show="advanced[idx]" class="col-xl-6 content-block secondary" v-bind:class="checkSite('catalog.lists.siteid', idx) ? 'readonly' : ''">
							<table class="item-config table table-striped">
								<thead>
									<tr>
										<th>
											<span class="help"><?= $enc->html( $this->translate( 'admin', 'Option' ) ); ?></span>
											<div class="form-text text-muted help-text">
												<?= $enc->html( $this->translate( 'admin', 'Configuration options, will be available as key/value pairs in the list item' ) ); ?>
											</div>
										</th>
										<th>
											<?= $enc->html( $this->translate( 'admin', 'Value' ) ); ?>
										</th>
										<th class="actions">
											<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
												title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ); ?>"
												v-bind:readonly="checkSite('catalog.lists.siteid', idx)"
												v-on:click="addConfig(idx)" >
											</div>
										</th>
									</tr>
								</thead>
								<tbody>

									<tr v-for="(key, pos) in getConfig(idx)" v-bind:key="pos">
										<td>
											<input is="auto-complete"
												v-model="items[idx]['config']['key'][pos]"
												v-bind:name="'<?= $enc->attr( $this->formparam( array( 'media', 'idx', 'config', 'key', '' ) ) ); ?>'.replace('idx', idx)"
												v-bind:readonly="checkSite('catalog.lists.siteid', idx)"
												v-bind:tabindex="<?= $this->get( 'tabindex' ); ?>"
												v-bind:keys="[]" />
										</td>
										<td>
											<input type="text" class="form-control" tabindex="<?= $this->get( 'tabindex' ); ?>"
												v-bind:name="'<?= $enc->attr( $this->formparam( array( 'media', 'idx', 'config', 'val', '' ) ) ); ?>'.replace('idx', idx)"
												v-bind:readonly="checkSite('catalog.lists.siteid', idx)"
												v-model="items[idx]['config']['val'][pos]" />
										</td>
										<td class="actions">
											<div v-if="!checkSite('catalog.lists.siteid', idx)" v-on:click="removeConfig(idx, pos)"
												class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
												title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ); ?>">
											</div>
										</td>
									</tr>

								</tbody>
							</table>
						</div>

						<?= $this->get( 'mediaBody' ); ?>

					</div>
				</div>
			</div>

			<div slot="footer" class="card-tools-more">
				<div class="btn btn-primary btn-card-more act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
					title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ); ?>"
					v-on:click="addItem('catalog.lists.')" >
				</div>
			</div>
		</div>
	</div>
</div>
