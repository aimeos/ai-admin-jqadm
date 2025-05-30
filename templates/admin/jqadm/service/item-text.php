<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2025
 */


/** admin/jqadm/service/item/text/config/suggest
 * List of suggested configuration keys in service text panel
 *
 * Item references can store arbitrary key value pairs. This setting gives
 * editors a hint which config keys are available and are used in the templates.
 *
 * @param string List of suggested config keys
 * @since 2020.01
 */


$enc = $this->encoder();


?>
<div id="text" class="item-text tab-pane fade" role="tablist" aria-labelledby="text">

	<div id="item-text-group"
		data-prompt="<?= $enc->attr( $this->translate( 'admin', 'Please insert the description of the text that should be generated here' ) ) ?>"
		data-openai="<?= $enc->attr( $this->config( 'admin/jqadm/api/openai' ) ) ?>"
		data-deepl="<?= $enc->attr( $this->config( 'admin/jqadm/api/translate', [] ) ) ?>"
		data-data="<?= $enc->attr( $this->get( 'textData', [] ) ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>"
		data-domain="service" >

		<div class="group-list" role="tablist" aria-multiselectable="true">
			<div is="vue:draggable" item-key="text.id" group="text" :list="items" handle=".act-move">
				<template #item="{element, index}">

					<div class="group-item card box" v-bind:class="{loading: element['_loading'], mismatch: !can('match', index)}">
						<div v-bind:id="'item-text-group-item-' + index" class="card-header header">
							<div class="card-tools-start">
								<div class="btn btn-card-header act-show icon" v-bind:class="element['_show'] ? 'show' : 'collapsed'" v-on:click="toggle('_show', index)"
									v-bind:data-bs-target="'#item-text-group-data-' + index" data-bs-toggle="collapse"
									v-bind:aria-controls="'item-text-group-data-' + index" aria-expanded="false"
									title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry' ) ) ?>" tabindex="<?= $this->get( 'tabindex' ) ?>">
								</div>
							</div>
							<div class="item-label header-label" v-bind:class="{disabled: !active(index)}" v-on:click="toggle('_show', index)">{{ label(index) }}</div>
							<div class="card-tools-end">
								<a class="btn btn-card-header act-generate icon" href="#" v-on:click.prevent="generate(index)"
									title="<?= $enc->attr( $this->translate( 'admin', 'Generate text' ) ) ?>">
								</a>
								<div class="dropdown">
									<a v-bind:id="'translate-menu-' + index" class="btn btn-card-header act-translate icon dropdown-toggle" href="#"
										tabindex="<?= $this->get( 'tabindex' ) ?>" role="button" data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}' aria-haspopup="true" aria-expanded="false"
										title="<?= $enc->attr( $this->translate( 'admin', 'Translate text' ) ) ?>">
									</a>
									<div class="dropdown-menu dropdown-menu-end" v-bind:aria-labelledby="'translate-menu-' + index">
										<?php foreach( ['bg', 'cs', 'da', 'de', 'el', 'en', 'es', 'et', 'fi', 'fr', 'hu', 'id', 'it', 'ja', 'lt', 'lv', 'nl', 'no', 'pl', 'pt', 'pt_BR', 'ro', 'ru', 'sk', 'sl', 'sv', 'tr', 'uk', 'zh'] as $lang ) : ?>
											<a class="dropdown-item" href="#" v-on:click.prevent="translate(index, `<?= $lang ?>`)"><?= $enc->html( $this->translate( 'language', $lang ) ) ?></a>
										<?php endforeach ?>
									</div>
								</div>
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

						<div v-bind:id="'item-text-group-data-' + index" v-bind:class="element['_show'] ? 'show' : 'collapsed'"
							v-bind:aria-labelledby="'item-text-group-item-' + index" role="tabpanel" class="card-block collapse row">

							<input type="hidden" v-model="element['text.id']"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'text', '_idx_', 'text.id' ) ) ) ?>`.replace('_idx_', index)">

							<div class="col-xl-6">

								<div class="form-group row mandatory">
									<div class="col-sm-12">
										<textarea is="vue:ckeditor" v-if="element['_show']" class="form-control item-content" required="required"
											tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"
											v-bind:editor="CKEditor"
											v-bind:config="Aimeos.ckeditor"
											v-bind:disabled="!can('change', index)"
											v-bind:model-value="element['text.content']"
											@blur="(...args) => update(element, ...args)"
										></textarea>
										<textarea v-show="!element['_show']" class="form-control item-content" readonly
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'text', '_idx_', 'text.content' ) ) ) ?>`.replace('_idx_', index)"
										>{{ element['text.content'] }}</textarea>
									</div>
								</div>

							</div>

							<div class="col-xl-6">

								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></label>
									<div class="col-sm-8">
										<select class="form-select item-status" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'text', '_idx_', 'text.status' ) ) ) ?>`.replace('_idx_', index)"
											v-bind:readonly="!can('change', index)"
											v-model="element['text.status']" >
											<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?></option>
											<option value="1" v-bind:selected="element['text.status'] == 1" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?>
											</option>
											<option value="0" v-bind:selected="element['text.status'] == 0" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?>
											</option>
											<option value="-1" v-bind:selected="element['text.status'] == -1" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?>
											</option>
											<option value="-2" v-bind:selected="element['text.status'] == -2" >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?>
											</option>
										</select>
									</div>
								</div>

								<?php if( ( $languages = $this->get( 'pageLangItems', map() ) )->count() !== 1 ) : ?>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?></label>
										<div class="col-sm-8">
											<select is="vue:select-component" required class="form-select item-languageid" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"
												v-bind:items="<?= $enc->attr( $languages->col( 'locale.language.label', 'locale.language.id' )->toArray() ) ?>"
												v-bind:name="`<?= $enc->js( $this->formparam( ['text', '_idx_', 'text.languageid'] ) ) ?>`.replace('_idx_', index)"
												v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`"
												v-bind:all="`<?= $enc->js( $this->translate( 'admin', 'All' ) ) ?>`"
												v-bind:readonly="!can('change', index)"
												v-model="element['text.languageid']" >
											</select>
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Language of the entered text' ) ) ?>
										</div>
									</div>
								<?php else : ?>
									<input class="text-langid" type="hidden"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'text', '_idx_', 'text.languageid' ) ) ) ?>`.replace('_idx_', index)"
										value="<?= $enc->attr( $languages->getCode()->first() ) ?>">
								<?php endif ?>

								<?php if( ( $textTypes = $this->get( 'textTypes', map() ) )->count() !== 1 ) : ?>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?></label>
										<div class="col-sm-8">
											<select is="vue:select-component" required class="form-select item-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"
												v-bind:items="<?= $enc->attr( $textTypes->col( null, 'text.type.code' )->getName()->toArray() ) ?>"
												v-bind:name="`<?= $enc->js( $this->formparam( ['text', '_idx_', 'text.type'] ) ) ?>`.replace('_idx_', index)"
												v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`"
												v-bind:readonly="!can('change', index)"
												v-model="element['text.type']" >
											</select>
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Types for additional texts like per one lb/kg or per month' ) ) ?>
										</div>
									</div>
								<?php else : ?>
									<input class="item-type" type="hidden"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'text', '_idx_', 'text.type' ) ) ) ?>`.replace('_idx_', index)"
										value="<?= $enc->attr( $textTypes->getCode()->first() ) ?>">
								<?php endif ?>

								<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ) ?></label>
									<div class="col-sm-8">
										<input class="form-control item-label" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'text', '_idx_', 'text.label' ) ) ) ?>`.replace('_idx_', index)"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'Label' ) ) ?>"
											v-bind:readonly="!can('change', index)"
											v-model="element['text.label']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Description of the text content if it\'s in a foreign language' ) ) ?>
									</div>
								</div>

							</div>

							<div class="col-12 secondary item-meta text-muted">
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Site' ) ) ?>:
									<span class="meta-value">{{ element['text.siteid'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
									<span class="meta-value">{{ element['text.editor'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
									<span class="meta-value">{{ element['text.ctime'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Modified' ) ) ?>:
									<span class="meta-value">{{ element['text.mtime'] }}</span>
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

								<?php if( ( $listTypes = $this->get( 'textListTypes', map() ) )->count() !== 1 ) : ?>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'List type' ) ) ?></label>
										<div class="col-sm-8">
											<select is="vue:select-component" required class="form-select listitem-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"
												v-bind:items="<?= $enc->attr( $listTypes->col( null, 'service.lists.type.code' )->getName()->toArray() ) ?>"
												v-bind:name="`<?= $enc->js( $this->formparam( ['text', '_idx_', 'service.lists.type'] ) ) ?>`.replace('_idx_', index)"
												v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`"
												v-bind:readonly="!can('change', index)"
												v-model="element['service.lists.type']" >
											</select>
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Second level type for grouping entries' ) ) ?>
										</div>
									</div>
								<?php else : ?>
									<input class="listitem-type" type="hidden"
										v-bind:name="`<?= $enc->js( $this->formparam( array( 'text', '_idx_', 'service.lists.type' ) ) ) ?>`.replace('_idx_', index)"
										value="<?= $enc->attr( $listTypes->getCode()->first() ) ?>">
								<?php endif ?>

								<div class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ) ?></label>
									<div class="col-sm-8">
										<input is="vue:flat-pickr" class="form-control listitem-datestart select" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'text', '_idx_', 'service.lists.datestart' ) ) ) ?>`.replace('_idx_', index)"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
											v-bind:disabled="!can('change', index)"
											v-bind:config="Aimeos.flatpickr.datetime"
											v-model="element['service.lists.datestart']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'The entry is only shown on the web site after that date and time' ) ) ?>
									</div>
								</div>
								<div class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?></label>
									<div class="col-sm-8">
										<input is="vue:flat-pickr" class="form-control listitem-dateend select" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
											v-bind:name="`<?= $enc->js( $this->formparam( array( 'text', '_idx_', 'service.lists.dateend' ) ) ) ?>`.replace('_idx_', index)"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
											v-bind:disabled="!can('change', index)"
											v-bind:config="Aimeos.flatpickr.datetime"
											v-model="element['service.lists.dateend']">
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'The entry is only shown on the web site until that date and time' ) ) ?>
									</div>
								</div>
							</div>

							<div v-show="element['_ext']" class="col-xl-6 secondary">
								<config-table v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
									v-bind:keys="<?= $enc->attr( $this->config( 'admin/jqadm/service/item/text/config/suggest', [] ) ) ?>"
									v-bind:name="`<?= $enc->js( $this->formparam( ['text', '_idx_', 'config', '_pos_', '_key_'] ) ) ?>`.replace('_idx_', index)"
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
									<span class="meta-value">{{ element['service.lists.siteid'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
									<span class="meta-value">{{ element['service.lists.editor'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
									<span class="meta-value">{{ element['service.lists.ctime'] }}</span>
								</small>
								<small>
									<?= $enc->html( $this->translate( 'admin', 'Modified' ) ) ?>:
									<span class="meta-value">{{ element['service.lists.mtime'] }}</span>
								</small>
							</div>

							<?= $this->get( 'textBody' ) ?>

						</div>
					</div>
				</template>
			</div>

			<div slot="footer" class="card-tools-more">
				<div class="btn btn-primary btn-card-more act-add icon" tabindex="<?= $this->get( 'tabindex' ) ?>"
					title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
					v-on:click="add()" >
				</div>
			</div>
		</div>
	</div>
</div>
