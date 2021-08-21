<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */


/** admin/jqadm/supplier/item/media/config/suggest
 * List of suggested configuration keys in supplier media panel
 *
 * Item references can store arbitrary key value pairs. This setting gives
 * editors a hint which config keys are available and are used in the templates.
 *
 * @param string List of suggested config keys
 * @since 2020.01
 * @category Developer
 */


$enc = $this->encoder();


?>
<div id="media" class="item-media tab-pane fade" role="tablist" aria-labelledby="media">

	<div id="item-media-group"
		data-items="<?= $enc->attr( $this->get( 'mediaData', [] ) ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>"
		data-domain="supplier" >

		<div class="group-list" role="tablist" aria-multiselectable="true">
			<div is="draggable" group="media" v-model="items" handle=".act-move">
				<div v-for="(item, idx) in items" v-bind:key="idx" class="group-item card">

					<div v-bind:id="'item-media-group-item-' + idx" class="card-header header">
						<div class="card-tools-start">
							<div class="btn btn-card-header act-show fa" v-bind:class="item['_show'] ? 'show' : 'collapsed'"
								title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry' ) ) ?>"
								tabindex="<?= $this->get( 'tabindex' ) ?>" v-on:click="toggle('_show', idx)"
								v-bind:data-bs-target="'#item-media-group-data-' + idx" data-bs-toggle="collapse"
								v-bind:aria-controls="'item-media-group-data-' + idx" aria-expanded="false">
							</div>
						</div>
						<span class="item-label header-label" v-bind:class="{disabled: !active(idx)}">{{ label(idx) }}</span>
						<div class="card-tools-end">
							<div v-if="item['supplier.lists.siteid'] == siteid && !item['_nosort']"
								class="btn btn-card-header act-move fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down' ) ) ?>">
							</div>
							<div v-if="item['supplier.lists.siteid'] == siteid"
								class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
								v-on:click.stop="remove(idx)">
							</div>
						</div>
					</div>

					<div v-bind:id="'item-media-group-data-' + idx" v-bind:class="item['_show'] ? 'show' : 'collapsed'"
						v-bind:aria-labelledby="'item-media-group-item-' + idx" role="tabpanel" class="card-block collapse row">

						<input type="hidden" v-model="item['media.id']"
							v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'media.id'] ) ) ?>`.replace('_idx_', idx)" />

						<div class="col-xl-6">

							<div class="form-group media-preview">
								<input ref="preview" class="d-none" type="file" v-bind:name="'media[_idx_][preview]'.replace('_idx_', idx)" />
								<input ref="file" class="fileupload" type="file" tabindex="<?= $this->get( 'tabindex' ) ?>"
									v-bind:name="'media[_idx_][file]'.replace('_idx_', idx)"
									v-bind:readonly="item['media.siteid'] != siteid"
									v-on:change="files(idx, $event.target.files)" />
								<input class="item-url" type="hidden"
									v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'media.url'] ) ) ?>`.replace('_idx_', idx)"
									v-model="item['media.url']" />
								<img v-if="item['media.preview']" class="item-preview"
									v-bind:src="url(`<?= $this->content( $this->config( 'resource/fs/baseurl' ) ) ?>/`, item['media.preview'])"
									v-bind:alt="item['media.label']" />
								<p v-else class="item-preview">
									{{ item['media.label'] || `<?= $enc->js( $this->translate( 'admin', 'Select file' ) ) ?>` }}
								</p>
							</div>

						</div>

						<div class="col-xl-6">

							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></label>
								<div class="col-sm-8">
									<select class="form-select item-status" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'media.status'] ) ) ?>`.replace('_idx_', idx)"
										v-bind:readonly="item['media.siteid'] != siteid"
										v-model="item['media.status']" >
										<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?></option>
										<option value="1" v-bind:selected="item['media.status'] == 1" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?>
										</option>
										<option value="0" v-bind:selected="item['media.status'] == 0" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?>
										</option>
										<option value="-1" v-bind:selected="item['media.status'] == -1" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?>
										</option>
										<option value="-2" v-bind:selected="item['media.status'] == -2" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?>
										</option>
									</select>
								</div>
							</div>
							<?php if( ( $mediaTypes = $this->get( 'mediaTypes', map() ) )->count() !== 1 ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?></label>
									<div class="col-sm-8">
										<select is="select-component" required class="form-select item-type" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:items="<?= $enc->attr( $mediaTypes->col( 'media.type.label', 'media.type.code' )->toArray() ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'media.type'] ) ) ?>`.replace('_idx_', idx)"
											v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`"
											v-bind:readonly="item['media.siteid'] != siteid"
											v-model="item['media.type']" >
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Types for additional images like icons' ) ) ?>
									</div>
								</div>
							<?php else : ?>
								<input class="item-type" type="hidden"
									v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'media.type'] ) ) ?>`.replace('_idx_', idx)"
									value="<?= $enc->attr( $mediaTypes->getCode()->first() ) ?>" />
							<?php endif ?>

							<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Title' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'media.label'] ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Title' ) ) ?>"
										v-bind:readonly="item['media.siteid'] != siteid"
										v-model="item['media.label']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The media title is used for the title tag of the media on the web site' ) ) ?>
								</div>
							</div>

							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?></label>
								<div class="col-sm-8">
									<select is="select-component" class="form-select item-languageid" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:items="<?= $enc->attr( $this->get( 'pageLangItems', map() )->col( 'locale.language.label', 'locale.language.id' )->toArray() ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'media.languageid'] ) ) ?>`.replace('_idx_', idx)"
										v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'All' ) ) ?>`"
										v-bind:readonly="item['media.siteid'] != siteid"
										v-model="item['media.languageid']" >
									</select>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Images will only be shown for that language, useful if the media contains text or is language sepecific' ) ) ?>
								</div>
							</div>

						</div>


						<div v-on:click="toggle('_ext', idx)" class="col-xl-12 advanced" v-bind:class="{'collapsed': !item['_ext']}">
							<div class="card-tools-start">
								<div class="btn act-show fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide advanced data' ) ) ?>">
								</div>
							</div>
							<span class="header-label"><?= $enc->html( $this->translate( 'admin', 'Advanced' ) ) ?></span>
						</div>

						<div v-show="item['_ext']" class="col-xl-6 secondary">
							<?php if( ( $listTypes = $this->get( 'mediaListTypes', map() ) )->count() !== 1 ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'List type' ) ) ?></label>
									<div class="col-sm-8">
										<select is="select-component" required class="form-select listitem-type" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:items="<?= $enc->attr( $listTypes->col( 'supplier.lists.type.label', 'supplier.lists.type.code' )->toArray() ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'supplier.lists.type'] ) ) ?>`.replace('_idx_', idx)"
											v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`"
											v-bind:readonly="item['supplier.lists.siteid'] != siteid"
											v-model="item['supplier.lists.type']" >
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Second level type for grouping items' ) ) ?>
									</div>
								</div>
							<?php else : ?>
								<input class="listitem-type" type="hidden"
									v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'supplier.lists.type'] ) ) ?>`.replace('_idx_', idx)"
									value="<?= $enc->attr( $listTypes->getCode()->first() ) ?>" />
							<?php endif ?>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ) ?></label>
								<div class="col-sm-8">
									<input is="flat-pickr" class="form-control listitem-datestart select" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'supplier.lists.datestart'] ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
										v-bind:disabled="item['supplier.lists.siteid'] != siteid"
										v-bind:config="Aimeos.flatpickr.datetime"
										v-model="item['supplier.lists.datestart']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The item is only shown on the web site after that date and time' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?></label>
								<div class="col-sm-8">
									<input is="flat-pickr" class="form-control listitem-dateend select" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'supplier.lists.dateend'] ) ) ?>`.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
										v-bind:disabled="item['supplier.lists.siteid'] != siteid"
										v-bind:config="Aimeos.flatpickr.datetime"
										v-model="item['supplier.lists.dateend']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The item is only shown on the web site until that date and time' ) ) ?>
								</div>
							</div>
						</div>

						<div v-show="item['_ext']" class="col-xl-6 secondary" v-bind:class="{readonly: item['supplier.lists.siteid'] != siteid}">
							<config-table v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
								v-bind:keys="<?= $enc->attr( $this->config( 'admin/jqadm/supplier/item/media/config/suggest', [] ) ) ?>"
								v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'config', '_pos_', '_key_'] ) ) ?>`.replace('_idx_', idx)"
								v-bind:index="idx" v-bind:readonly="item['supplier.lists.siteid'] != siteid"
								v-bind:items="item['config']" v-on:update:config="item['config'] = $event"
								v-bind:i18n="{
									value: `<?= $enc->js( $this->translate( 'admin', 'Value' ) ) ?>`,
									option: `<?= $enc->js( $this->translate( 'admin', 'Option' ) ) ?>`,
									help: `<?= $enc->js( $this->translate( 'admin', 'Item specific configuration options, will be available as key/value pairs in the templates' ) ) ?>`,
									insert: `<?= $enc->js( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>`,
									delete: `<?= $enc->js( $this->translate( 'admin', 'Delete this entry' ) ) ?>`,
								}">
							</config-table>
						</div>

						<?= $this->get( 'mediaBody' ) ?>

					</div>
				</div>
			</div>

			<div slot="footer" class="card-tools-more">
				<div class="btn btn-primary btn-card-more act-add fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
					title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
					v-on:click="$refs.add.click()" >
					<input ref="add" class="d-none" type="file" multiple v-on:change="create($event)" />
				</div>
			</div>
		</div>

	</div>
</div>
