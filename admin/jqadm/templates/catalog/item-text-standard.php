<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */


/** admin/jqadm/catalog/item/text/config/suggest
 * List of suggested configuration keys in catalog text panel
 *
 * Item references can store arbitrary key value pairs. This setting gives
 * editors a hint which config keys are available and are used in the templates.
 *
 * @param string List of suggested config keys
 * @since 2020.01
 * @category Developer
 */


$enc = $this->encoder();

$keys = [
	'catalog.lists.siteid', 'catalog.lists.type', 'catalog.lists.datestart', 'catalog.lists.dateend', 'config',
	'text.siteid', 'text.type', 'text.languageid', 'text.content', 'text.status'
];


?>
<div id="text" class="item-text content-block tab-pane fade" role="tablist" aria-labelledby="text">
	<div id="item-text-group" role="tablist" aria-multiselectable="true"
		data-translate="<?= $enc->attr( $this->config( 'admin/jqadm/api/translate', [] ) ) ?>"
		data-items="<?= $enc->attr( $this->get( 'textData', [] ) ); ?>"
		data-listtype="<?= key( $this->get( 'textListTypes', [] ) ) ?>"
		data-keys="<?= $enc->attr( $keys ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>"
		data-domain="catalog" >

		<div class="group-list">
			<div is="draggable" v-model="items" group="text" handle=".act-move">
				<div v-for="(entry, idx) in items" v-bind:key="idx" class="group-item card">

					<div v-bind:id="'item-text-group-item-' + idx" v-bind:class="getCss(idx)"
						v-bind:data-target="'#item-text-group-data-' + idx" data-toggle="collapse" role="tab" class="card-header header"
						v-bind:aria-controls="'item-text-group-data-' + idx" aria-expanded="false">
						<div class="card-tools-left">
							<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry' ) ); ?>">
							</div>
						</div>
						<span class="item-label header-label" v-html="getLabel(idx)"></span>
						&nbsp;
						<div class="card-tools-right">
							<div v-if="!checkSite('catalog.lists.siteid', idx)" class="dropdown">
								<button class="btn btn-card-header act-translate fa dropdown-toggle" tabindex="<?= $this->get( 'tabindex' ); ?>"
									type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
									title="<?= $enc->attr( $this->translate( 'admin', 'Translate text' ) ); ?>">
								</button>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="#" v-on:click="translate(idx, 'DE')">Deutsch</a>
									<a class="dropdown-item" href="#" v-on:click="translate(idx, 'EN')">English</a>
									<a class="dropdown-item" href="#" v-on:click="translate(idx, 'ES')">Español</a>
									<a class="dropdown-item" href="#" v-on:click="translate(idx, 'FR')">Français</a>
									<a class="dropdown-item" href="#" v-on:click="translate(idx, 'IT')">Italiano</a>
									<a class="dropdown-item" href="#" v-on:click="translate(idx, 'NL')">Nederlands</a>
									<a class="dropdown-item" href="#" v-on:click="translate(idx, 'PL')">Polski</a>
									<a class="dropdown-item" href="#" v-on:click="translate(idx, 'PT')">Português</a>
									<a class="dropdown-item" href="#" v-on:click="translate(idx, 'RU')">русский язык</a>
								</div>
							</div>
							<div v-if="!checkSite('catalog.lists.siteid', idx)"
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

					<div v-bind:id="'item-text-group-data-' + idx" v-bind:class="getCss(idx)"
						v-bind:aria-labelledby="'item-text-group-item-' + idx" role="tabpanel" class="card-block collapse row">

						<input type="hidden" v-model="items[idx]['text.id']"
							v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', 'idx', 'text.id' ) ) ); ?>'.replace('idx', idx)" />

						<div class="col-xl-6">

							<div class="form-group row mandatory">
								<div class="col-sm-12">
									<textarea is="html-editor" class="form-control item-content" required="required"
										v-bind:key="idx"
										v-bind:id="'cke-' + idx"
										v-bind:value="items[idx]['text.content']"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', 'idx', 'text.content' ) ) ); ?>'.replace('idx', idx)"
										v-bind:readonly="checkSite('text.siteid', idx)"
										v-bind:tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-model="items[idx]['text.content']"
									></textarea>
								</div>
							</div>

						</div>

						<div class="col-xl-6">

							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ); ?></label>
								<div class="col-sm-8">
									<select class="form-control custom-select item-status" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', 'idx', 'text.status' ) ) ); ?>'.replace('idx', idx)"
										v-bind:readonly="checkSite('text.siteid', idx)"
										v-model="items[idx]['text.status']" >
										<option value="1" v-bind:selected="entry['text.status'] == 1" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ); ?>
										</option>
										<option value="0" v-bind:selected="entry['text.status'] == 0" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ); ?>
										</option>
										<option value="-1" v-bind:selected="entry['text.status'] == -1" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ); ?>
										</option>
										<option value="-2" v-bind:selected="entry['text.status'] == -2" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ); ?>
										</option>
									</select>
								</div>
							</div>

							<?php if( ( $languages = $this->get( 'pageLangItems', [] ) ) !== [] ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></label>
									<div class="col-sm-8">
										<select is="select-component" required class="form-control custom-select item-languageid" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
											v-bind:items="JSON.parse('<?= $enc->attr( $this->map( $languages, 'locale.language.code', 'locale.language.label' )->toArray() ) ?>')"
											v-bind:name="'<?= $enc->attr( $this->formparam( ['media', 'idx', 'text.languageid'] ) ); ?>'.replace('idx', idx)"
											v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
											v-bind:readonly="checkSite('text.siteid', idx)"
											v-model="entry['text.languageid']" >
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Language of the entered text' ) ); ?>
									</div>
								</div>
							<?php else : ?>
								<input class="text-langid" type="hidden"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', 'idx', 'text.languageid' ) ) ); ?>'.replace('idx', idx)"
									value="<?= $enc->attr( key( $languages ) ); ?>" />
							<?php endif; ?>

							<?php if( ( $textTypes = $this->get( 'textTypes', [] ) ) !== [] ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
									<div class="col-sm-8">
										<select is="select-component" required class="form-control custom-select item-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
											v-bind:items="JSON.parse('<?= $enc->attr( $this->map( $textTypes, 'text.type.code', 'text.type.label' )->toArray() ) ?>')"
											v-bind:name="'<?= $enc->attr( $this->formparam( ['text', 'idx', 'text.type'] ) ); ?>'.replace('idx', idx)"
											v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
											v-bind:readonly="checkSite('text.siteid', idx)"
											v-model="entry['text.type']" >
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Types for additional texts like per one lb/kg or per month' ) ); ?>
									</div>
								</div>
							<?php else : ?>
								<input class="item-type" type="hidden"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', 'idx', 'text.type' ) ) ); ?>'.replace('idx', idx)"
									value="<?= $enc->attr( key( $textTypes ) ); ?>" />
							<?php endif; ?>

							<div class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control item-label" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', 'idx', 'text.label' ) ) ); ?>'.replace('idx', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Label' ) ); ?>"
										v-bind:readonly="checkSite('text.siteid', idx)"
										v-model="items[idx]['text.label']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Description of the text content if it\'s in a foreign language' ) ); ?>
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

							<?php if( ( $listTypes = $this->get( 'textListTypes', [] ) ) !== [] ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'List type' ) ); ?></label>
									<div class="col-sm-8">
										<select is="select-component" required class="form-control custom-select listitem-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
											v-bind:items="JSON.parse('<?= $enc->attr( $this->map( $listTypes, 'catalog.lists.type.code', 'catalog.lists.type.label' )->toArray() ) ?>')"
											v-bind:name="'<?= $enc->attr( $this->formparam( ['text', 'idx', 'catalog.lists.type'] ) ); ?>'.replace('idx', idx)"
											v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
											v-bind:readonly="checkSite('catalog.lists.siteid', idx)"
											v-model="entry['catalog.lists.type']" >
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Second level type for grouping items' ) ); ?>
									</div>
								</div>
							<?php else : ?>
								<input class="listitem-type" type="hidden"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', 'idx', 'catalog.lists.type' ) ) ); ?>'.replace('idx', idx)"
									value="<?= $enc->attr( key( $listTypes ) ); ?>" />
							<?php endif; ?>

							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control listitem-datestart" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', 'idx', 'catalog.lists.datestart' ) ) ); ?>'.replace('idx', idx)"
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
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', 'idx', 'catalog.lists.dateend' ) ) ); ?>'.replace('idx', idx)"
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
							<config-table inline-template
								v-bind:index="idx" v-bind:readonly="entry['catalog.lists.siteid'] != siteid"
								v-bind:items="entry['config']" v-on:update:config="entry['config'] = $event">

								<table class="item-config table table-striped">
									<thead>
										<tr>
											<th class="config-row-key">
												<span class="help"><?= $enc->html( $this->translate( 'admin', 'Option' ) ); ?></span>
												<div class="form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Configuration options, will be available as key/value pairs in the list item' ) ); ?>
												</div>
											</th>
											<th class="config-row-value"><?= $enc->html( $this->translate( 'admin', 'Value' ) ); ?></th>
											<th class="actions">
												<div v-if="!readonly" class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>" v-on:click="add()"
													title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ); ?>"></div>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(entry, pos) in items" v-bind:key="pos" class="config-item">
											<td class="config-row-key">
												<input is="auto-complete" required class="form-control" v-bind:readonly="readonly" tabindex="<?= $this->get( 'tabindex' ); ?>"
													v-bind:name="'<?= $enc->attr( $this->formparam( ['text', '_idx_', 'config', '_pos_', 'key'] ) ); ?>'.replace('_idx_', index).replace('_pos_', pos)"
													v-bind:keys="JSON.parse('<?= $enc->attr( $this->config( 'admin/jqadm/catalog/item/text/config/suggest', [] ) ) ?>')"
													v-model="entry.key" />
											</td>
											<td class="config-row-value">
												<input class="form-control" v-bind:readonly="readonly" tabindex="<?= $this->get( 'tabindex' ); ?>"
													v-bind:name="'<?= $enc->attr( $this->formparam( ['text', '_idx_', 'config', '_pos_', 'val'] ) ); ?>'.replace('_idx_', index).replace('_pos_', pos)"
													v-model="entry.val" />
											</td>
											<td class="actions">
												<div v-if="!readonly" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>" v-on:click="remove(pos)"
													title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ); ?>"></div>
											</td>
										</tr>
									</tbody>
								</table>
							</config-table>
						</div>

						<?= $this->get( 'textBody' ); ?>

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
