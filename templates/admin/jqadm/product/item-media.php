<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */


/** admin/jqadm/product/item/media/config/suggest
 * List of suggested configuration keys in product media panel
 *
 * Item references can store arbitrary key value pairs. This setting gives
 * editors a hint which config keys are available and are used in the templates.
 *
 * @param string List of suggested config keys
 * @since 2020.01
 */


$enc = $this->encoder();


?>
<div id="media" class="item-media tab-pane fade" role="tablist" aria-labelledby="media">

	<div id="item-media-group"
		data-removebg="<?= $enc->attr( $this->config( 'admin/jqadm/api/removebg', new \stdClass ) ) ?>"
		data-openai="<?= $enc->attr( $this->config( 'admin/jqadm/api/openai', new \stdClass ) ) ?>"
		data-data="<?= $enc->attr( $this->get( 'mediaData', [] ) ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>"
		data-domain="product" >

		<div class="group-list" role="tablist" aria-multiselectable="true">
			<div is="vue:draggable" item-key="media.id" group="media" :list="items" handle=".act-move">
				<template #item="{element, index}">

					<div class="group-item card box" v-bind:class="{loading: element['_loading'], mismatch: !can('match', index)}">
						<div v-bind:id="'item-media-group-item-' + index" class="card-header header">
							<div class="card-tools-start">
								<div class="btn btn-card-header act-show icon" v-bind:class="element['_show'] ? 'show' : 'collapsed'"
									title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry' ) ) ?>"
									tabindex="<?= $this->get( 'tabindex' ) ?>" v-on:click="toggle('_show', index)"
									v-bind:data-bs-target="'#item-media-group-data-' + index" data-bs-toggle="collapse"
									v-bind:aria-controls="'item-media-group-data-' + index" aria-expanded="false">
								</div>
							</div>
							<div class="item-label header-label" v-bind:class="{disabled: !active(index)}" v-on:click="toggle('_show', index)">{{ label(index) }}</div>
							<div class="card-tools-end">
								<div v-if="can('move', index)"
									class="btn btn-card-header act-move icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down' ) ) ?>">
								</div>
								<div v-if="can('delete', index)"
									class="btn btn-card-header act-delete icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
									v-on:click.stop="remove(index)">
								</div>
							</div>
						</div>

						<div v-bind:id="'item-media-group-data-' + index" v-bind:class="element['_show'] ? 'show' : 'collapsed'"
							v-bind:aria-labelledby="'item-media-group-item-' + index" role="tabpanel" class="card-block collapse row">

							<input type="hidden" v-model="element['media.id']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'media.id'] ) ) ?>`.replace('_idx_', index)">

							<div class="col-xl-6">

								<div class="form-group media-preview">
									<div v-if="element['file'] && element['file'][0] && element['media.mimetype']?.startsWith('image/')"
										title="<?= $enc->attr( $this->translate( 'admin', 'Remove background' ) ) ?>"
										class="btn act-magic icon btn-background"
										@click.stop="background(element)">
									</div>
									<input v-bind:files="element['preview']" class="d-none" type="file"
										v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'preview'] ) ) ?>`.replace('_idx_', index)">
									<input v-bind:files="element['file']" class="fileupload" type="file" tabindex="<?= $this->get( 'tabindex' ) ?>"
										v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'file'] ) ) ?>`.replace('_idx_', index)"
										v-bind:readonly="!can('change', index)"
										v-on:change="files(items[index], $event.target.files)">
									<img v-if="element['media.preview']" class="item-preview"
										v-bind:src="element['media.preview']"
										v-bind:alt="element['media.label']">
									<p v-else class="item-preview">
										{{ element['media.label'] || `<?= $enc->js( $this->translate( 'admin', 'Select file' ) ) ?>` }}
									</p>
								</div>

							</div>

							<div class="col-xl-6">

								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></label>
									<div class="col-sm-8">
										<select class="form-select item-status" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'media.status'] ) ) ?>`.replace('_idx_', index)"
											v-bind:readonly="!can('change', index)"
											v-model="element['media.status']" >
											<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?></option>
											<option value="1" v-bind:selected="element['media.status'] == 1" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?>
											</option>
											<option value="0" v-bind:selected="element['media.status'] == 0" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?>
											</option>
											<option value="-1" v-bind:selected="element['media.status'] == -1" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?>
											</option>
											<option value="-2" v-bind:selected="element['media.status'] == -2" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?>
											</option>
										</select>
									</div>
								</div>
								<?php if( ( $mediaTypes = $this->get( 'mediaTypes', map() ) )->count() !== 1 ) : ?>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?></label>
										<div class="col-sm-8">
											<select is="vue:select-component" required class="form-select item-type" tabindex="<?= $this->get( 'tabindex' ) ?>"
												v-bind:items="<?= $enc->attr( $mediaTypes->col( null, 'media.type.code' )->getName()->toArray() ) ?>"
												v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'media.type'] ) ) ?>`.replace('_idx_', index)"
												v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`"
												v-bind:readonly="!can('change', index)"
												v-model="element['media.type']" >
											</select>
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Types for additional images like icons' ) ) ?>
										</div>
									</div>
								<?php else : ?>
									<input class="item-type" type="hidden"
										v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'media.type'] ) ) ?>`.replace('_idx_', index)"
										value="<?= $enc->attr( $mediaTypes->getCode()->first() ) ?>">
								<?php endif ?>

								<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Title' ) ) ?></label>
									<div class="col-sm-8">
										<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'media.label'] ) ) ?>`.replace('_idx_', index)"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'Title' ) ) ?>"
											v-bind:readonly="!can('change', index)"
											v-model="element['media.label']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'The media title is used for the title tag of the media on the web site' ) ) ?>
									</div>
								</div>

								<div class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?></label>
									<div class="col-sm-8">
										<select is="vue:select-component" class="form-select item-languageid" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:items="<?= $enc->attr( $this->get( 'pageLangItems', map() )->col( 'locale.language.label', 'locale.language.id' )->toArray() ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'media.languageid'] ) ) ?>`.replace('_idx_', index)"
											v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'All' ) ) ?>`"
											v-bind:readonly="!can('change', index)"
											v-model="element['media.languageid']" >
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Images will only be shown for that language, useful if the media contains text or is language sepecific' ) ) ?>
									</div>
								</div>

							</div>

							<div class="col-12 secondary item-meta text-muted">
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Site' ) ) ?>:
									<span class="meta-value">{{ element['media.siteid'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
									<span class="meta-value">{{ element['media.editor'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
									<span class="meta-value">{{ element['media.ctime'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Modified' ) ) ?>:
									<span class="meta-value">{{ element['media.mtime'] }}</span>
								</small>
							</div>


							<div v-on:click="toggle('_ext', index)" class="col-xl-12 advanced" v-bind:class="{'collapsed': !element['_ext']}">
								<div class="card-tools-start">
									<div class="btn act-show icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide advanced data' ) ) ?>">
									</div>
								</div>
								<span class="header-label"><?= $enc->html( $this->translate( 'admin', 'Advanced' ) ) ?></span>
							</div>

							<div v-show="element['_ext']" class="col-xl-6 secondary">
								<?php if( ( $listTypes = $this->get( 'mediaListTypes', map() ) )->count() !== 1 ) : ?>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'List type' ) ) ?></label>
										<div class="col-sm-8">
											<select is="vue:select-component" required class="form-select listitem-type" tabindex="<?= $this->get( 'tabindex' ) ?>"
												v-bind:items="<?= $enc->attr( $listTypes->col( null, 'product.lists.type.code' )->getName()->toArray() ) ?>"
												v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'product.lists.type'] ) ) ?>`.replace('_idx_', index)"
												v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`"
												v-bind:readonly="!can('change', index)"
												v-model="element['product.lists.type']" >
											</select>
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Second level type for grouping entries' ) ) ?>
										</div>
									</div>
								<?php else : ?>
									<input class="listitem-type" type="hidden"
										v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'product.lists.type'] ) ) ?>`.replace('_idx_', index)"
										value="<?= $enc->attr( $listTypes->getCode()->first() ) ?>">
								<?php endif ?>
								<div class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ) ?></label>
									<div class="col-sm-8">
										<input is="vue:flat-pickr" class="form-control listitem-datestart select" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'product.lists.datestart'] ) ) ?>`.replace('_idx_', index)"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
											v-bind:disabled="!can('change', index)"
											v-bind:config="Aimeos.flatpickr.datetime"
											v-model="element['product.lists.datestart']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'The entry is only shown on the web site after that date and time' ) ) ?>
									</div>
								</div>
								<div class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?></label>
									<div class="col-sm-8">
										<input is="vue:flat-pickr" class="form-control listitem-dateend select" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'product.lists.dateend'] ) ) ?>`.replace('_idx_', index)"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
											v-bind:disabled="!can('change', index)"
											v-bind:config="Aimeos.flatpickr.datetime"
											v-model="element['product.lists.dateend']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'The entry is only shown on the web site until that date and time' ) ) ?>
									</div>
								</div>
							</div>

							<div v-show="element['_ext']" class="col-xl-6 secondary">
								<config-table v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
									v-bind:keys="<?= $enc->attr( $this->config( 'admin/jqadm/product/item/media/config/suggest', [] ) ) ?>"
									v-bind:name="`<?= $enc->js( $this->formparam( ['media', '_idx_', 'config', '_pos_', '_key_'] ) ) ?>`.replace('_idx_', index)"
									v-bind:index="index" v-bind:readonly="!can('change', index)"
									v-bind:items="element['config']" v-on:update:items="element['config'] = $event"
									v-bind:i18n="{
										value: `<?= $enc->js( $this->translate( 'admin', 'Value' ) ) ?>`,
										option: `<?= $enc->js( $this->translate( 'admin', 'Option' ) ) ?>`,
										help: `<?= $enc->js( $this->translate( 'admin', 'Entry specific configuration options, will be available as key/value pairs in the templates' ) ) ?>`,
										insert: `<?= $enc->js( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>`,
										delete: `<?= $enc->js( $this->translate( 'admin', 'Delete this entry' ) ) ?>`,
									}">
								</config-table>
							</div>

							<div v-show="element['_ext']" class="col-12 secondary item-meta text-muted">
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Site' ) ) ?>:
									<span class="meta-value">{{ element['product.lists.siteid'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
									<span class="meta-value">{{ element['product.lists.editor'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
									<span class="meta-value">{{ element['product.lists.ctime'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Modified' ) ) ?>:
									<span class="meta-value">{{ element['product.lists.mtime'] }}</span>
								</small>
							</div>

							<?= $this->get( 'mediaBody' ) ?>

						</div>
					</div>
				</template>
			</div>

			<div slot="footer" class="card-tools-more">
				<div class="btn btn-primary btn-card-more act-add icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
					title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
					v-on:click="$refs.add.click()" >
					<input ref="add" class="d-none" type="file" multiple v-on:change="create($event)">
				</div>
				<div class="btn btn-primary btn-card-more act-magic icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
					title="<?= $enc->attr( $this->translate( 'admin', 'Generate image' ) ) ?>"
					v-on:click="generate = true" >
				</div>
			</div>
		</div>

		<imagegen v-bind:show="generate" v-bind:config="JSON.parse(openai)"
			v-on:close="generate = false" v-on:confirm="use($event)">
		</imagegen>

	</div>

	<?= $this->partial( $this->config( 'admin/jqadm/partial/imagegen', 'imagegen' ) ) ?>
</div>
