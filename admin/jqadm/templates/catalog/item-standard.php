<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};


$enc = $this->encoder();

$target = $this->config( 'admin/jqadm/url/save/target' );
$cntl = $this->config( 'admin/jqadm/url/save/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/save/action', 'save' );
$config = $this->config( 'admin/jqadm/url/save/config', [] );

$getTarget = $this->config( 'admin/jqadm/url/get/target' );
$getCntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$getAction = $this->config( 'admin/jqadm/url/get/action', 'get' );
$getConfig = $this->config( 'admin/jqadm/url/get/config', [] );

$listTarget = $this->config( 'admin/jqadm/url/search/target' );
$listCntl = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$listAction = $this->config( 'admin/jqadm/url/search/action', 'search' );
$listConfig = $this->config( 'admin/jqadm/url/search/config', [] );

$newTarget = $this->config( 'admin/jqadm/url/create/target' );
$newCntl = $this->config( 'admin/jqadm/url/create/controller', 'Jqadm' );
$newAction = $this->config( 'admin/jqadm/url/create/action', 'create' );
$newConfig = $this->config( 'admin/jqadm/url/create/config', [] );

$jsonTarget = $this->config( 'admin/jsonadm/url/target' );
$jsonCntl = $this->config( 'admin/jsonadm/url/controller', 'Jsonadm' );
$jsonAction = $this->config( 'admin/jsonadm/url/action', 'get' );
$jsonConfig = $this->config( 'admin/jsonadm/url/config', [] );

$params = $this->get( 'pageParams', [] );


/** admin/jqadm/catalog/item/config/suggest
 * List of suggested configuration keys in catalog item panel
 *
 * Catalog items can store arbitrary key value pairs. This setting gives editors
 * a hint which config keys are available and are used in the templates.
 *
 * @param string List of suggested config keys
 * @since 2017.10
 * @category Developer
 * @see admin/jqadm/product/item/config/suggest
 */
$cfgSuggest = $this->config( 'admin/jqadm/catalog/item/config/suggest', ['css-class'] );


?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<form class="item item-catalog item-tree form-horizontal" method="POST" enctype="multipart/form-data"
	action="<?= $enc->attr( $this->url( $target, $cntl, $action, $params, [], $config ) ); ?>"
	data-rootid="<?= $enc->attr( $this->get( 'itemRootId' ) ); ?>"
	data-geturl="<?= $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['resource' => 'catalog', 'id' => '_ID_'] + $params, [], $getConfig ) ); ?>"
	data-createurl="<?= $enc->attr( $this->url( $newTarget, $newCntl, $newAction, ['resource' => 'catalog', 'id' => '_ID_'] + $params, [], $newConfig ) ); ?>"
	data-jsonurl="<?= $enc->attr( $this->url( $jsonTarget, $jsonCntl, $jsonAction, ['resource' => 'catalog'], [], $jsonConfig ) ); ?>"
	data-idname="<?= $this->formparam( 'id' ); ?>" >

	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'catalog.id' ) ) ); ?>"
		value="<?= $enc->attr( $this->get( 'itemData/catalog.id' ) ); ?>" />
	<input id="item-parentid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'catalog.parentid' ) ) ); ?>"
		value="<?= $enc->attr( $this->get( 'itemData/catalog.parentid', $this->param( 'id', $this->get( 'itemRootId' ) ) ) ); ?>" />
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ); ?>" value="get" />
	<?= $this->csrf()->formfield(); ?>

	<nav class="main-navbar">
		<span class="navbar-brand">
			<?= $enc->html( $this->translate( 'admin', 'Catalog' ) ); ?>:
			<?php if( $this->get( 'itemData/catalog.id' ) ) : ?>
				<?= $enc->html( $this->get( 'itemData/catalog.id' ) ); ?> -
			<?php endif; ?>
			<?= $enc->html( $this->get( 'itemData/catalog.label', $this->translate( 'admin', 'New' ) ) ); ?>
			<?php if( $this->get( 'itemData/catalog.siteid' ) ) : ?>
				<span class="navbar-secondary">(<?= $enc->html( $this->site()->match( $this->get( 'itemData/catalog.siteid' ) ) ); ?>)</span>
			<?php endif; ?>
		</span>

		<div class="item-actions">
			<?php if( isset( $this->itemData ) ) : ?>
				<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard.php' ), ['params' => $params] ); ?>
			<?php else : ?>
				<span class="placeholder">&nbsp;</span>
			<?php endif; ?>
		</div>
	</nav>

	<div class="row item-container">

		<div class="col-lg-3 catalog-tree">
			<div class="tree-toolbar input-group">
				<div class="input-group-prepend">
					<span class="btn btn-secondary fa expand-all" tabindex="1"></span>
					<span class="btn btn-secondary fa collapse-all" tabindex="1"></span>
				</div>
				<input type="text" class="form-control search-input" tabindex="1" placeholder="<?= $enc->attr( $this->translate( 'admin', 'Find category' ) ); ?>">
				<div class="input-group-append">
					<span class="btn btn-secondary fa act-delete " tabindex="1"></span>
					<span class="btn btn-primary fa act-add" tabindex="1"></span>
				</div>
			</div>
			<div class="tree-content">
			</div>
		</div>

		<?php if( isset( $this->itemData ) ) : ?>

			<div class="col-lg-9 catalog-content">
				<div class="row">

					<div class="col-xl-12 item-navbar">
						<ul class="nav nav-tabs flex-row flex-wrap d-flex justify-content-between" role="tablist">

							<li class="nav-item basic">
								<a class="nav-link active" href="#basic" data-toggle="tab" role="tab" aria-expanded="true" aria-controls="basic" tabindex="1">
									<?= $enc->html( $this->translate( 'admin', 'Basic' ) ); ?>
								</a>
							</li>

							<?php foreach( array_values( $this->get( 'itemSubparts', [] ) ) as $idx => $subpart ) : ?>
								<li class="nav-item <?= $enc->attr( $subpart ); ?>">
									<a class="nav-link" href="#<?= $enc->attr( $subpart ); ?>" data-toggle="tab" role="tab" tabindex="<?= ++$idx+1; ?>">
										<?= $enc->html( $this->translate( 'admin', $subpart ) ); ?>
									</a>
								</li>
							<?php endforeach; ?>

						</ul>

						<div class="item-meta text-muted">
							<small>
								<?= $enc->html( $this->translate( 'admin', 'Modified' ) ); ?>:
								<span class="meta-value"><?= $enc->html( $this->get( 'itemData/catalog.mtime' ) ); ?></span>
							</small>
							<small>
								<?= $enc->html( $this->translate( 'admin', 'Created' ) ); ?>:
								<span class="meta-value"><?= $enc->html( $this->get( 'itemData/catalog.ctime' ) ); ?></span>
							</small>
							<small>
								<?= $enc->html( $this->translate( 'admin', 'Editor' ) ); ?>:
								<span class="meta-value"><?= $enc->html( $this->get( 'itemData/catalog.editor' ) ); ?></span>
							</small>
						</div>
					</div>

					<div class="col-xl-12 item-content tab-content">

						<div id="basic" class="row item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic">

							<div class="col-xl-6 content-block <?= $this->site()->readonly( $this->get( 'itemData/catalog.siteid' ) ); ?>">
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ); ?></label>
									<div class="col-sm-8">
										<select class="form-control custom-select item-status" required="required" tabindex="1"
											name="<?= $enc->attr( $this->formparam( array( 'item', 'catalog.status' ) ) ); ?>"
											<?= $this->site()->readonly( $this->get( 'itemData/catalog.siteid' ) ); ?> >
											<option value="">
												<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
											</option>
											<option value="1" <?= $selected( $this->get( 'itemData/catalog.status', 1 ), 1 ); ?> >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ); ?>
											</option>
											<option value="0" <?= $selected( $this->get( 'itemData/catalog.status', 1 ), 0 ); ?> >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ); ?>
											</option>
											<option value="-1" <?= $selected( $this->get( 'itemData/catalog.status', 1 ), -1 ); ?> >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ); ?>
											</option>
											<option value="-2" <?= $selected( $this->get( 'itemData/catalog.status', 1 ), -2 ); ?> >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ); ?>
											</option>
										</select>
									</div>
								</div>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Code' ) ); ?></label>
									<div class="col-sm-8">
										<input class="form-control item-code" type="text" required="required" tabindex="1"
											name="<?= $enc->attr( $this->formparam( array( 'item', 'catalog.code' ) ) ); ?>"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'Unique category code (required)' ) ); ?>"
											value="<?= $enc->attr( $this->get( 'itemData/catalog.code' ) ); ?>"
											<?= $this->site()->readonly( $this->get( 'itemData/catalog.siteid' ) ); ?> />
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Unique category code, either from external system or self-invented' ) ); ?>
									</div>
								</div>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ); ?></label>
									<div class="col-sm-8">
										<input class="form-control item-label" type="text" required="required" tabindex="1"
											name="<?= $this->formparam( array( 'item', 'catalog.label' ) ); ?>"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ); ?>"
											value="<?= $enc->attr( $this->get( 'itemData/catalog.label' ) ); ?>"
											<?= $this->site()->readonly( $this->get( 'itemData/catalog.siteid' ) ); ?> />
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Internal category name, will be used on the web site if no name for the language is available' ) ); ?>
									</div>
								</div>
								<div class="form-group row optional warning">
									<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'URL target' ) ); ?></label>
									<div class="col-sm-8">
										<input class="form-control item-target" type="text" tabindex="1"
											name="<?= $enc->attr( $this->formparam( array( 'item', 'catalog.target' ) ) ); ?>"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'Route or page ID (optional)' ) ); ?>"
											value="<?= $enc->attr( $this->get( 'itemData/catalog.target' ) ); ?>"
											<?= $this->site()->readonly( $this->get( 'itemData/catalog.siteid' ) ); ?> />
									</div>
									<div class="col-sm-12 form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Route name or page ID of the category page if this category should shown on a different page' ) ); ?>
									</div>
								</div>
							</div><!--

							--><div class="col-xl-6 content-block <?= $this->site()->readonly( $this->get( 'itemData/catalog.siteid' ) ); ?>">
								<table class="item-config table table-striped" data-keys="<?= $enc->attr( json_encode( $cfgSuggest ) ); ?>">
									<thead>
										<tr>
											<th>
												<span class="help"><?= $enc->html( $this->translate( 'admin', 'Option' ) ); ?></span>
												<div class="form-text text-muted help-text">
													<?= $enc->html( $this->translate( 'admin', 'Category specific configuration options, will be available as key/value pairs in the templates' ) ); ?>
												</div>
											</th>
											<th>
												<?= $enc->html( $this->translate( 'admin', 'Value' ) ); ?>
											</th>
											<th class="actions">
												<?php if( !$this->site()->readonly( $this->get( 'itemData/catalog.siteid' ) ) ) : ?>
													<div class="btn act-add fa" tabindex="1"
														title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>">
													</div>
												<?php endif; ?>
											</th>
										</tr>
									</thead>
									<tbody>

										<?php foreach( (array) $this->get( 'itemData/config/key', [] ) as $idx => $key ) : ?>
											<tr class="config-item">
												<td>
													<input type="text" class="config-key form-control" tabindex="1"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'key', '' ) ) ); ?>"
														value="<?= $enc->attr( $this->get( 'itemData/config/key/' . $idx, $key ) ); ?>"
														<?= $this->site()->readonly( $this->get( 'itemData/catalog.siteid' ) ); ?> />
												</td>
												<td>
													<input type="text" class="config-value form-control" tabindex="1"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'val', '' ) ) ); ?>"
														value="<?= $enc->attr( $this->get( 'itemData/config/val/' . $idx ) ); ?>"
														<?= $this->site()->readonly( $this->get( 'itemData/catalog.siteid' ) ); ?> />
												</td>
												<td class="actions">
													<?php if( !$this->site()->readonly( $this->get( 'itemData/catalog.siteid' ) ) ) : ?>
														<div class="btn act-delete fa" tabindex="1"
															title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
														</div>
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; ?>

										<tr class="prototype">
											<td>
												<input type="text" class="config-key form-control" tabindex="1" disabled="disabled"
													name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'key', '' ) ) ); ?>" />
											</td>
											<td>
												<input type="text" class="config-value form-control" tabindex="1" disabled="disabled"
													name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'val', '' ) ) ); ?>" />
											</td>
											<td class="actions">
												<?php if( !$this->site()->readonly( $this->get( 'itemData/catalog.siteid' ) ) ) : ?>
													<div class="btn act-delete fa" tabindex="1"
														title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
													</div>
												<?php endif; ?>
											</td>
										</tr>
									</tbody>
								</table>
							</div>

						</div>

						<?= $this->get( 'itemBody' ); ?>

					</div>

					<div class="item-actions">
						<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard.php' ), ['params' => $params] ); ?>
					</div>
				</div>

			</div>

		<?php endif; ?>

	</div>
</form>

<?php $this->block()->stop(); ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard.php' ) ); ?>
