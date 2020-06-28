<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2020
 */


/** admin/jqadm/product/item/text/config/suggest
 * List of suggested configuration keys in product text panel
 *
 * Item references can store arbitrary key value pairs. This setting gives
 * editors a hint which config keys are available and are used in the templates.
 *
 * @param string List of suggested config keys
 * @since 2020.01
 * @category Developer
 */


/** admin/jqadm/api/translate
 * Configuration for realtime online translation service
 *
 * Contains the required settings for configuring the online translation service.
 * Currently, only DeepL is supported and a paid DeepL API account is required to
 * use the service. The necessary settings for DeepL are:
 *
 *  [
 *    'url' => 'https://api.deepl.com/v2',
 *    'key' => '<your-DeepL-API-key>',
 *  ]
 *
 * @param array Associative list of key/value pairs
 * @category Developer
 * @category User
 * @since 2019.10
 */


$enc = $this->encoder();


?>
<div id="text" class="item-text content-block tab-pane fade" role="tablist" aria-labelledby="text">

	<div id="item-text-group"
		data-translate="<?= $enc->attr( $this->config( 'admin/jqadm/api/translate', [] ) ) ?>"
		data-items="<?= $enc->attr( $this->get( 'textData', [] ) ); ?>"
		data-siteid="<?= $this->site()->siteid() ?>"
		data-domain="product" >

		<div class="group-list" role="tablist" aria-multiselectable="true">
			<div is="draggable" v-model="items" group="text" handle=".act-move">
				<div v-for="(item, idx) in items" v-bind:key="idx" class="group-item card">

					<div v-bind:id="'item-text-group-item-' + idx" v-bind:class="item['_show'] ? 'show' : 'collapsed'"
						v-bind:data-target="'#item-text-group-data-' + idx" data-toggle="collapse" role="tab" class="card-header header"
						v-bind:aria-controls="'item-text-group-data-' + idx" aria-expanded="false" v-on:click.self.stop="toggle('_show', idx)">
						<div class="card-tools-left">
							<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry' ) ); ?>">
							</div>
						</div>
						<span class="item-label header-label" v-html="label(idx)"></span>
						&nbsp;
						<div class="card-tools-right">
							<div class="dropdown">
								<a v-bind:id="'translate-menu-' + idx" class="btn btn-card-header act-translate fa dropdown-toggle" href="#"
									tabindex="<?= $this->get( 'tabindex' ); ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
									title="<?= $enc->attr( $this->translate( 'admin', 'Translate text' ) ); ?>">
 								</a>
								<div class="dropdown-menu dropdown-menu-right" v-bind:aria-labelledby="'translate-menu-' + idx">
									<?php foreach( ['de', 'en', 'es', 'fr', 'it', 'nl', 'pl', 'pt', 'ru'] as $lang ) : ?>
										<a class="dropdown-item" href="#" v-on:click="translate(idx, '<?= strtoupper( $lang ) ?>')"><?= $enc->html( $this->translate( 'language', $lang ) ); ?></a>
									<?php endforeach ?>
								</div>
							</div>
							<div v-if="item['product.lists.siteid'] == siteid && !item['_nosort']"
								class="btn btn-card-header act-move fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down' ) ); ?>">
							</div>
							<div v-if="item['product.lists.siteid'] == siteid"
								class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ); ?>"
								v-on:click.stop="remove(idx)">
							</div>
						</div>
					</div>

					<div v-bind:id="'item-text-group-data-' + idx" v-bind:class="item['_show'] ? 'show' : 'collapsed'"
						v-bind:aria-labelledby="'item-text-group-item-' + idx" role="tabpanel" class="card-block collapse row">

						<input type="hidden" v-model="item['text.id']"
							v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', '_idx_', 'text.id' ) ) ); ?>'.replace('_idx_', idx)" />

						<div class="col-xl-6">

							<div class="form-group row mandatory">
								<div class="col-sm-12">
									<textarea is="html-editor" class="form-control item-content" required="required"
										v-bind:key="idx"
										v-bind:id="'cke-' + idx"
										v-bind:value="item['text.content']"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', '_idx_', 'text.content' ) ) ); ?>'.replace('_idx_', idx)"
										v-bind:readonly="item['text.siteid'] != siteid"
										v-bind:tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-model="item['text.content']"
									></textarea>
								</div>
							</div>

						</div>

						<div class="col-xl-6">

							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ); ?></label>
								<div class="col-sm-8">
									<select class="form-control custom-select item-status" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', '_idx_', 'text.status' ) ) ); ?>'.replace('_idx_', idx)"
										v-bind:readonly="item['text.siteid'] != siteid"
										v-model="item['text.status']" >
										<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?></option>
										<option value="1" v-bind:selected="item['text.status'] == 1" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ); ?>
										</option>
										<option value="0" v-bind:selected="item['text.status'] == 0" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ); ?>
										</option>
										<option value="-1" v-bind:selected="item['text.status'] == -1" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ); ?>
										</option>
										<option value="-2" v-bind:selected="item['text.status'] == -2" >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ); ?>
										</option>
									</select>
								</div>
							</div>

							<?php if( ( $languages = $this->get( 'pageLangItems', map() ) )->count() !== 1 ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></label>
									<div class="col-sm-8">
										<select is="select-component" required class="form-control custom-select item-languageid" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
											v-bind:items="JSON.parse('<?= $enc->attr( $languages->col( 'locale.language.label', 'locale.language.id' )->toArray() ) ?>')"
											v-bind:name="'<?= $enc->attr( $this->formparam( ['text', '_idx_', 'text.languageid'] ) ); ?>'.replace('_idx_', idx)"
											v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
											v-bind:all="'<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>'"
											v-bind:readonly="item['text.siteid'] != siteid"
											v-model="item['text.languageid']" >
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Language of the entered text' ) ); ?>
									</div>
								</div>
							<?php else : ?>
								<input class="text-langid" type="hidden"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', '_idx_', 'text.languageid' ) ) ); ?>'.replace('_idx_', idx)"
									value="<?= $enc->attr( $languages->getCode()->first() ) ?>" />
							<?php endif; ?>

							<?php if( ( $textTypes = $this->get( 'textTypes', map() ) )->count() !== 1 ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
									<div class="col-sm-8">
										<select is="select-component" required class="form-control custom-select item-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
											v-bind:items="JSON.parse('<?= $enc->attr( $textTypes->col( 'text.type.label', 'text.type.code' )->toArray() ) ?>')"
											v-bind:name="'<?= $enc->attr( $this->formparam( ['text', '_idx_', 'text.type'] ) ); ?>'.replace('_idx_', idx)"
											v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
											v-bind:readonly="item['text.siteid'] != siteid"
											v-model="item['text.type']" >
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Types for additional texts like per one lb/kg or per month' ) ); ?>
									</div>
								</div>
							<?php else : ?>
								<input class="item-type" type="hidden"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', '_idx_', 'text.type' ) ) ); ?>'.replace('_idx_', idx)"
									value="<?= $enc->attr( $textTypes->getCode()->first() ) ?>" />
							<?php endif; ?>

							<div class="form-group row optional">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control item-label" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', '_idx_', 'text.label' ) ) ); ?>'.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Label' ) ); ?>"
										v-bind:readonly="item['text.siteid'] != siteid"
										v-model="item['text.label']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Description of the text content if it\'s in a foreign language' ) ); ?>
								</div>
							</div>

						</div>


						<div v-on:click="toggle('_ext', idx)" class="col-xl-12 advanced" v-bind:class="{'collapsed': !item['_ext']}">
							<div class="card-tools-left">
								<div class="btn act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide advanced data' ) ); ?>">
								</div>
							</div>
							<span class="header-label"><?= $enc->html( $this->translate( 'admin', 'Advanced' ) ); ?></span>
						</div>

						<div v-show="item['_ext']" class="col-xl-6 content-block secondary">

							<?php if( ( $listTypes = $this->get( 'textListTypes', map() ) )->count() !== 1 ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'List type' ) ); ?></label>
									<div class="col-sm-8">
										<select is="select-component" required class="form-control custom-select listitem-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ); ?>"
											v-bind:items="JSON.parse('<?= $enc->attr( $listTypes->col( 'product.lists.type.label', 'product.lists.type.code' )->toArray() ) ?>')"
											v-bind:name="'<?= $enc->attr( $this->formparam( ['text', '_idx_', 'product.lists.type'] ) ); ?>'.replace('_idx_', idx)"
											v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
											v-bind:readonly="item['product.lists.siteid'] != siteid"
											v-model="item['product.lists.type']" >
										</select>
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Second level type for grouping items' ) ); ?>
									</div>
								</div>
							<?php else : ?>
								<input class="listitem-type" type="hidden"
									v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', '_idx_', 'product.lists.type' ) ) ); ?>'.replace('_idx_', idx)"
									value="<?= $enc->attr( $listTypes->getCode()->first() ) ?>" />
							<?php endif; ?>

							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control listitem-datestart" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', '_idx_', 'product.lists.datestart' ) ) ); ?>'.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
										v-bind:readonly="item['product.lists.siteid'] != siteid"
										v-model="item['product.lists.datestart']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The item is only shown on the web site after that date and time' ) ); ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control listitem-dateend" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
										v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', '_idx_', 'product.lists.dateend' ) ) ); ?>'.replace('_idx_', idx)"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
										v-bind:readonly="item['product.lists.siteid'] != siteid"
										v-model="item['product.lists.dateend']" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The item is only shown on the web site until that date and time' ) ); ?>
								</div>
							</div>
						</div>

						<div v-show="item['_ext']" class="col-xl-6 content-block secondary" v-bind:class="{readonly: item['product.lists.siteid'] != siteid}">
							<config-table inline-template
								v-bind:readonly="item['product.lists.siteid'] != siteid" v-bind:index="idx"
								v-bind:items="item['config']" v-on:update:config="item['config'] = $event">

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
													v-bind:keys="JSON.parse('<?= $enc->attr( $this->config( 'admin/jqadm/product/item/text/config/suggest', [] ) ) ?>')"
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
					v-on:click="add()" >
				</div>
			</div>
		</div>
	</div>
</div>
