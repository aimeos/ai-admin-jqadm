<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
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

$subparts = $this->get( 'itemSubparts', [] );
$params = $this->get( 'pageParams', [] );


?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<form class="item item-catalog form-horizontal" method="POST" enctype="multipart/form-data"
	action="<?= $enc->attr( $this->url( $target, $cntl, $action, $params, [], $config ) ); ?>"
	data-rootid="<?= $enc->attr( $this->get( 'itemRootId' ) ); ?>"
	data-geturl="<?= $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['resource' => 'catalog', 'id' => '_id_'], [], $getConfig ) ); ?>"
	data-createurl="<?= $enc->attr( $this->url( $newTarget, $newCntl, $newAction, ['resource' => 'catalog', 'id' => '_id_'], [], $newConfig ) ); ?>">

	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'catalog.id' ) ) ); ?>"
		value="<?= $enc->attr( $this->get( 'itemData/catalog.id' ) ); ?>" />
	<input id="item-parentid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'catalog.parentid' ) ) ); ?>"
		value="<?= $enc->attr( $this->get( 'itemData/catalog.parentid', $this->param( 'id' ) ) ); ?>" />
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ); ?>" value="get" />
	<?= $this->csrf()->formfield(); ?>

	<nav class="main-navbar">
		<span class="navbar-brand">
			<?= $enc->html( $this->translate( 'admin', 'Catalog' ) ); ?>:
			<?= $enc->html( $this->get( 'itemData/catalog.id' ) ); ?> -
			<?= $enc->html( $this->get( 'itemData/catalog.label', $this->translate( 'admin', 'New' ) ) ); ?>
			<span class="navbar-secondary">(<?= $enc->html( $this->site()->match( $this->get( 'itemData/catalog.siteid' ) ) ); ?>)</span>
		</span>
		<div class="item-actions">
			<a class="btn btn-secondary act-cancel"
				title="<?= $enc->attr( $this->translate( 'admin', 'Cancel') ); ?>"
				href="<?= $enc->attr( $this->url( $listTarget, $listCntl, $listAction, $params, [], $listConfig ) ); ?>">
				<?php if( $this->access( ['admin', 'editor'] ) ) : ?>
					<?= $enc->html( $this->translate( 'admin', 'Cancel' ) ); ?>
				<?php else : ?>
					<?= $enc->html( $this->translate( 'admin', 'Back' ) ); ?>
				<?php endif; ?>
			</a>

			<?php if( $this->access( ['admin', 'editor'] ) ) : ?>
				<div class="btn-group">
					<button type="submit" class="btn btn-primary act-save"
						title="<?= $enc->attr( $this->translate( 'admin', 'Save entry (Ctrl+S)') ); ?>">
						<?= $enc->html( $this->translate( 'admin', 'Save' ) ); ?>
					</button>
					<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"
						aria-haspopup="true" aria-expanded="false">
						<span class="sr-only"><?= $enc->html( $this->translate( 'admin', 'Toggle dropdown' ) ); ?></span>
					</button>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item next-action" href="#" data-next="copy"><?= $enc->html( $this->translate( 'admin', 'Save & Copy' ) ); ?></a>
						<a class="dropdown-item next-action" href="#" data-next="create"><?= $enc->html( $this->translate( 'admin', 'Save & New' ) ); ?></a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</nav>

	<div class="row item-container">

		<div class="col-lg-3 catalog-tree">
			<div class="tree-toolbar input-group">
				<span class="input-group-addon input-group-icon expand-all fa" tabindex="1"></span>
				<span class="input-group-addon input-group-icon collapse-all fa" tabindex="1"></span>
				<input type="text" class="form-control search-input" tabindex="1" placeholder="<?= $enc->attr( $this->translate( 'admin', 'Find category' ) ); ?>">
				<span class="input-group-addon input-group-icon act-delete fa" tabindex="1"></span>
				<span class="input-group-addon input-group-icon act-add fa" tabindex="1"></span>
			</div>
			<div class="tree-content">
			</div>
		</div>

		<div class="col-lg-9 catalog-content">
			<div class="row">

				<div class="col-xl-12 item-navbar">
					<ul class="nav nav-tabs flex-row flex-wrap d-flex justify-content-between" role="tablist">

						<li class="nav-item basic">
							<a class="nav-link active" href="#basic" data-toggle="tab" role="tab" aria-expanded="true" aria-controls="basic" tabindex="1">
								<?= $enc->html( $this->translate( 'admin', 'Basic' ) ); ?>
							</a>
						</li>

						<?php foreach( $subparts as $idx => $subpart ) : ?>
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
											<?= $enc->html( $this->translate( 'admin', 'status:enabled' ) ); ?>
										</option>
										<option value="0" <?= $selected( $this->get( 'itemData/catalog.status', 1 ), 0 ); ?> >
											<?= $enc->html( $this->translate( 'admin', 'status:disabled' ) ); ?>
										</option>
										<option value="-1" <?= $selected( $this->get( 'itemData/catalog.status', 1 ), -1 ); ?> >
											<?= $enc->html( $this->translate( 'admin', 'status:review' ) ); ?>
										</option>
										<option value="-2" <?= $selected( $this->get( 'itemData/catalog.status', 1 ), -2 ); ?> >
											<?= $enc->html( $this->translate( 'admin', 'status:archive' ) ); ?>
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
							<div class="form-group row optional">
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
							<table class="item-config table table-striped">
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
													title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+A)') ); ?>">
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
					<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-default.php' ), ['params' => $params] ); ?>
				</div>
			</div>

		</div>
	</div>
</form>

<?php $this->block()->stop(); ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-default.php' ) ); ?>
