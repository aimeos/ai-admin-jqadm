<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2017
 */

$enc = $this->encoder();


?>
<div id="text" class="item-text content-block tab-pane fade" role="tablist" aria-labelledby="text">
	<div id="item-text-group" role="tablist" aria-multiselectable="true"
		data-items="<?= $enc->attr( json_encode( $this->get( 'textData', [] ) ) ); ?>"
		data-types="<?= $enc->attr( json_encode( $this->get( 'textTypes', [] ) ) ); ?>"
		data-siteid="<?= $this->site()->siteid() ?>" >

		<div v-for="(item, idx) in items" v-bind:key="idx" class="group-item card">

			<div v-bind:id="'item-text-group-item-' + idx" v-bind:class="getCss(idx)"
				v-bind:data-target="'#item-text-group-data-' + idx" data-toggle="collapse" role="tab" class="card-header header"
				v-bind:aria-controls="'item-text-group-data-' + idx" aria-expanded="false">
				<div class="card-tools-left">
					<div class="btn btn-card-header act-show fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Show/hide this entry') ); ?>">
					</div>
				</div>
				<span class="item-label header-label">{{ getLabel(idx) }}</span>
				&nbsp;
				<div class="card-tools-right">
					<div class="btn btn-card-header act-move fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Move this entry up/down') ); ?>">
					</div>
					<div v-if="!checkSite('text.siteid', idx)"
						class="btn btn-card-header act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
						v-on:click.stop="removeItem(idx)">
					</div>
				</div>
			</div>

			<div v-bind:id="'item-text-group-data-' + idx" v-bind:class="getCss(idx)"
				v-bind:aria-labelledby="'item-text-group-item-' + idx" role="tabpanel" class="card-block collapse row">

				<?php $languages = $this->get( 'pageLangItems', [] ); ?>
				<?php if( count( $languages ) > 1 ) : ?>
					<div class="col-xl-6">
						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Language' ) ); ?></label>
							<div class="col-sm-8">
								<select class="form-control custom-select item-languageid" tabindex="<?= $this->get( 'tabindex' ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'text', 'text.languageid', '' ) ) ); ?>"
									v-bind:readonly="checkSite('text.siteid', idx)"
									v-model="item['text.languageid']" >

									<option value="" disable >
										<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
									</option>

									<?php foreach( $languages as $langId => $langItem ) : ?>
										<option value="<?= $enc->attr( $langId ); ?>" v-bind:selected="item['text.languageid'] == '<?= $enc->attr( $langId ) ?>'" >
											<?= $enc->html( $langItem->getLabel() ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Images will only be shown for that language, useful if the image contains text or is language sepecific' ) ); ?>
							</div>
						</div>
					</div>
					<div class="col-xl-6">
					</div>
				<?php else : ?>
					<input class="text-langid" type="hidden"
						name="<?= $enc->attr( $this->formparam( array( 'text', 'text.languageid', '' ) ) ); ?>"
						value="<?= $enc->attr( key( $languages ) ); ?>" />
				<?php endif; ?>

				<div class="col-xl-6">
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Product name' ) ); ?></label>
						<div class="col-sm-8">
							<input class="item-name-listid" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'text', 'name', 'product.lists.id', '' ) ) ); ?>"
								v-model="item['product.lists.id']['name']" />
							<input class="form-control item-name-content" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'text', 'name', 'text.content', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Product name' ) ); ?>"
								v-bind:readonly="checkSite('text.siteid', idx)"
								v-model="item['text.content']['name']" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Label will be used if empty but then there must be no name for all languages. Otherwise, items will not be shown when sorting by name' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Short description' ) ); ?></label>
						<div class="col-sm-8">
							<input class="item-short-listid" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'text', 'short', 'product.lists.id', '' ) ) ); ?>"
								v-model="item['product.lists.id']['short']" />
							<textarea class="form-control item-short-content" rows="2" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'text', 'short', 'text.content', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Short description' ) ); ?>"
								v-bind:readonly="checkSite('text.siteid', idx)"
								v-model="item['text.content']['short']"
							></textarea>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Long description' ) ); ?></label>
						<div class="col-sm-8">
							<input class="item-long-listid" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'text', 'long', 'product.lists.id', '' ) ) ); ?>"
								v-model="item['product.lists.id']['long']" />
							<textarea is="html-editor" class="item-long-content" v-once
								v-model="item['text.content']['long']"
								v-bind:value="item['text.content']['long']"
								v-bind:name="'<?= $enc->attr( $this->formparam( array( 'text', 'long', 'text.content', '' ) ) ); ?>'"
								v-bind:readonly="checkSite('text.siteid', idx)"
								v-bind:tabindex="<?= $this->get( 'tabindex' ); ?>"
							></textarea>
						</div>
					</div>
				</div>

				<div class="col-xl-6">
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'URL segment' ) ); ?></label>
						<div class="col-sm-8">
							<input class="item-url-listid" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'text', 'url', 'product.lists.id', '' ) ) ); ?>"
								v-model="item['product.lists.id']['url']" />
							<input class="form-control item-url-content" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'text', 'url', 'text.content', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'URL segment' ) ); ?>"
								v-bind:readonly="checkSite('text.siteid', idx)"
								v-model="item['text.content']['url']" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'The article name as used in URLs, e.g. for non-latin languages' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Meta keywords' ) ); ?></label>
						<div class="col-sm-8">
							<input class="item-meta-keyword-listid" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'text', 'meta-keyword', 'product.lists.id', '' ) ) ); ?>"
								v-model="item['product.lists.id']['meta-keyword']" />
							<textarea class="form-control item-meta-keyword-content" rows="2" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'text', 'meta-keyword', 'text.content', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Meta keywords' ) ); ?>"
								v-bind:readonly="checkSite('text.siteid', idx)"
								v-model="item['text.content']['meta-keyword']"
							></textarea>
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Keywords for search engines, added to the head section of the product detail page' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Meta description' ) ); ?></label>
						<div class="col-sm-8">
							<input class="item-meta-description-listid" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'text', 'meta-description', 'product.lists.id', '' ) ) ); ?>"
								v-model="item['product.lists.id']['meta-description']" />
							<textarea class="form-control item-meta-description-content" rows="10" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'text', 'meta-description', 'text.content', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Meta description' ) ); ?>"
								v-bind:readonly="checkSite('text.siteid', idx)"
								v-model="item['text.content']['meta-description']"
							></textarea>
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Article description shown by search engines, added to the head section of the product detail page' ) ); ?>
						</div>
					</div>
				</div>

				<?= $this->get( 'textBody' ); ?>

			</div>
		</div>
	</div>

	<div class="card-tools-more">
		<div class="btn btn-primary btn-card-more act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
			title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
			v-on:click="addItem('product.lists.id')" >
		</div>
	</div>
</div>
