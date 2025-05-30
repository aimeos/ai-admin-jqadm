<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2025
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};


$enc = $this->encoder();
$params = $this->get( 'pageParams', [] );


/** admin/jqadm/catalog/item/config/suggest
 * List of suggested configuration keys in catalog item panel
 *
 * Catalog items can store arbitrary key value pairs. This setting gives editors
 * a hint which config keys are available and are used in the templates.
 *
 * @param string List of suggested config keys
 * @since 2017.10
 * @see admin/jqadm/product/item/config/suggest
 */
$cfgSuggest = $this->config( 'admin/jqadm/catalog/item/config/suggest', ['css-class'] );


?>
<?php $this->block()->start( 'jqadm_content' ) ?>
<div class="container-fluid">
	<div class="row item-container catalog">

		<div class="col-lg-4 tree-nodes box">
			<tree
				domain="catalog"
				placeholder="<?= $enc->attr( $this->translate( 'admin', 'Find category' ) ) ?>"
				siteid="<?= $enc->attr( $this->site()->siteid() ) ?>"
				url="<?= $enc->attr( $this->link( 'admin/jqadm/url/get', ['resource' => 'catalog', 'id' => '_id_'] + $params ) ) ?>"
				:i18n="{
					after: `<?= $enc->js( $this->translate( 'admin', 'Add after' ) ) ?>`,
					before: `<?= $enc->js( $this->translate( 'admin', 'Add before' ) ) ?>`,
					delete: `<?= $enc->js( $this->translate( 'admin', 'Delete' ) ) ?>`,
					insert: `<?= $enc->js( $this->translate( 'admin', 'Insert into' ) ) ?>`,
					menu: `<?= $enc->js( $this->translate( 'admin', 'Tree node menu' ) ) ?>`,
					new: `<?= $enc->js( $this->translate( 'admin', 'New category' ) ) ?>`,
				}"
				:rtl="rtl"
				@load="load"
			></tree>
		</div>

		<div class="col-lg-8 tree-content">
			<?php if( isset( $this->itemData ) ) : ?>
				<form class="item item-catalog item-tree form-horizontal" method="POST" enctype="multipart/form-data"
					action="<?= $enc->attr( $this->link( 'admin/jqadm/url/save', $params ) ) ?>">

					<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'catalog.id' ) ) ) ?>"
						value="<?= $enc->attr( $this->get( 'itemData/catalog.id' ) ) ?>">
					<?= $this->csrf()->formfield() ?>

					<nav class="main-navbar">
						<h1 class="navbar-brand">
							<span class="navbar-title"><?= $enc->html( $this->translate( 'admin', 'Categories' ) ) ?></span>
							<span class="navbar-id"><?= $enc->html( $this->get( 'itemData/catalog.id' ) ) ?></span>
							<span class="navbar-label"><?= $enc->html( $this->get( 'itemData/catalog.label' ) ?: $this->translate( 'admin', 'New' ) ) ?></span>
							<span class="navbar-site"><?= $enc->html( $this->site()->match( $this->get( 'itemData/catalog.siteid' ) ) ) ?></span>
						</h1>
						<div class="item-actions">
							<div class="btn btn-secondary act-help" title="<?= $enc->attr( $this->translate( 'admin', 'Display help texts' ) ) ?>">
								<?= $enc->html( $this->translate( 'admin', '?' ) ) ?>
							</div>

							<a class="btn btn-secondary act-cancel"
								title="<?= $enc->attr( $this->translate( 'admin', 'Cancel and return to list' ) ) ?>"
								href="<?= $enc->attr( $this->link( 'admin/jqadm/url/get', $params ) ) ?>">
								<?= $enc->html( $this->translate( 'admin', 'Cancel' ) ) ?>
							</a>

							<div class="btn-group">
								<button type="submit" class="btn btn-primary act-save"
									title="<?= $enc->attr( $this->translate( 'admin', 'Save entry (Ctrl+Enter)' ) ) ?>">
									<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
								</button>
							</div>
						</div>
					</nav>

					<div class="row">

						<div class="col-xl-12 item-navbar">
							<div class="navbar-content" v-bind:class="{show: show}">
								<ul class="nav nav-tabs flex-row flex-wrap d-flex box" role="tablist">
									<li class="nav-item basic">
										<a class="nav-link active" href="#basic" v-on:click="url(`basic`)"
											data-bs-toggle="tab" role="tab" aria-expanded="true" aria-controls="basic">
											<?= $enc->html( $this->translate( 'admin', 'Basic' ) ) ?>
										</a>
									</li>

									<?php foreach( array_values( $this->get( 'itemSubparts', [] ) ) as $idx => $subpart ) : ?>
										<li class="nav-item <?= $enc->attr( $subpart ) ?>">
											<a class="nav-link" href="#<?= $enc->attr( $subpart ) ?>" v-on:click="url(`<?= $enc->js( $subpart ) ?>`)"
												data-bs-toggle="tab" role="tab" tabindex="<?= ++$idx + 1 ?>">
												<?= $enc->html( $this->translate( 'admin', $subpart ) ) ?>
											</a>
										</li>
									<?php endforeach ?>
								</ul>

								<div class="item-meta text-muted">
									<small>
										<?= $enc->html( $this->translate( 'admin', 'Modified' ) ) ?>:
										<span class="meta-value"><?= $enc->html( $this->get( 'itemData/catalog.mtime' ) ) ?></span>
									</small>
									<small>
										<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
										<span class="meta-value"><?= $enc->html( $this->get( 'itemData/catalog.ctime' ) ) ?></span>
									</small>
									<small>
										<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
										<span class="meta-value"><?= $enc->html( $this->get( 'itemData/catalog.editor' ) ) ?></span>
									</small>
								</div>

								<div class="icon more" v-bind:class="{less: show}" v-on:click="toggle()"></div>
							</div>
						</div>

						<div class="col-xl-12 item-content tab-content">

							<div id="basic" class="item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic">

								<div class="box" v-bind:class="{mismatch: !can('match')}"
									data-data="<?= $enc->attr( $this->get( 'itemData', new stdClass() ) ) ?>"
									data-siteid="<?= $enc->attr( $this->site()->siteid() ) ?>">

									<div class="row">
										<div class="col-xl-6 block">
											<div class="form-group row mandatory">
												<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></label>
												<div class="col-sm-8">
													<select class="form-select item-status" required="required" tabindex="1"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'catalog.status' ) ) ) ?>"
														v-bind:readonly="!can('change')" >
														<option value="">
															<?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?>
														</option>
														<option value="1" <?= $selected( $this->get( 'itemData/catalog.status', 1 ), 1 ) ?> >
															<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?>
														</option>
														<option value="2" <?= $selected( $this->get( 'itemData/catalog.status', 1 ), 2 ) ?> >
															<?= $enc->html( $this->translate( 'mshop/code', 'status:2' ) ) ?>
														</option>
														<option value="0" <?= $selected( $this->get( 'itemData/catalog.status', 1 ), 0 ) ?> >
															<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?>
														</option>
														<option value="-1" <?= $selected( $this->get( 'itemData/catalog.status', 1 ), -1 ) ?> >
															<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?>
														</option>
														<option value="-2" <?= $selected( $this->get( 'itemData/catalog.status', 1 ), -2 ) ?> >
															<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?>
														</option>
													</select>
												</div>
											</div>
											<div class="form-group row mandatory">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Code' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-code" type="text" required="required" tabindex="1"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'catalog.code' ) ) ) ?>"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Unique category code (required)' ) ) ?>"
														value="<?= $enc->attr( $this->get( 'itemData/catalog.code' ) ) ?>"
														v-bind:readonly="!can('change')" >
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Unique category code, either from external system or self-invented' ) ) ?>
												</div>
											</div>
											<div class="form-group row mandatory">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-label" type="text" required="required" tabindex="1"
														name="<?= $this->formparam( array( 'item', 'catalog.label' ) ) ?>"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ) ?>"
														value="<?= $enc->attr( $this->get( 'itemData/catalog.label' ) ) ?>"
														v-bind:readonly="!can('change')" >
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Internal category name, will be used on the web site if no name for the language is available' ) ) ?>
												</div>
											</div>

											<div class="separator" v-on:click="toggle()">
												<i class="icon more" v-bind:class="{less: show}"></i>
											</div>

											<div class="form-group row optional collapse" v-bind:class="{show: show}">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'URL segment' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-label" type="text" tabindex="1"
														name="<?= $this->formparam( array( 'item', 'catalog.url' ) ) ?>"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Name in URL (optional)' ) ) ?>"
														value="<?= $enc->attr( $this->get( 'itemData/catalog.url' ) ) ?>"
														v-bind:readonly="!can('change')" >
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'The name of the category shown in the URL, will be used if no language specific URL segment exists' ) ) ?>
												</div>
											</div>
											<div class="form-group row optional warning collapse" v-bind:class="{show: show}">
												<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'URL target' ) ) ?></label>
												<div class="col-sm-8">
													<input class="form-control item-target" type="text" tabindex="1"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'catalog.target' ) ) ) ?>"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Route or page ID (optional)' ) ) ?>"
														value="<?= $enc->attr( $this->get( 'itemData/catalog.target' ) ) ?>"
														v-bind:readonly="!can('change')" >
												</div>
												<div class="col-sm-12 form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Route name or page ID of the category page if this category should shown on a different page' ) ) ?>
												</div>
											</div>
										</div>

										<div class="col-xl-6 block">
											<config-table
												v-bind:keys="<?= $enc->attr( $this->config( 'admin/jqadm/catalog/item/config/suggest', ['css-class'] ) ) ?>"
												v-bind:name="`<?= $enc->js( $this->formparam( array( 'item', 'config', '_pos_', '_key_' ) ) ) ?>`"
												v-bind:readonly="!can('change')"
												v-bind:items="item['config']" v-on:update:items="item['config'] = $event"
												v-bind:i18n="{
													value: `<?= $enc->js( $this->translate( 'admin', 'Value' ) ) ?>`,
													option: `<?= $enc->js( $this->translate( 'admin', 'Option' ) ) ?>`,
													help: `<?= $enc->js( $this->translate( 'admin', 'Entry specific configuration options, will be available as key/value pairs in the templates' ) ) ?>`,
													insert: `<?= $enc->js( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>`,
													delete: `<?= $enc->js( $this->translate( 'admin', 'Delete this entry' ) ) ?>`,
												}">
												<table class="item-config table">
													<thead>
														<tr>
															<th class="config-row-key"><span class="help"><?= $enc->html( $this->translate( 'admin', 'Option' ) ) ?></span></th>
															<th class="config-row-value"><?= $enc->html( $this->translate( 'admin', 'Value' ) ) ?></th>
															<th class="actions"><div class="btn act-add icon"></div></th>
														</tr>
													</thead>
												</table>
											</config-table>
										</div>

									</div>
								</div>
							</div>

							<?= $this->get( 'itemBody' ) ?>

						</div>

						<div class="item-actions">
							<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'itemactions' ), ['params' => $params] ) ?>
						</div>
					</div>

				</form>

			<?php endif ?>

		</div>
	</div>
</div>
<?php $this->block()->stop() ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'page' ) ) ?>
