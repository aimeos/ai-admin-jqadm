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

$listTarget = $this->config( 'admin/jqadm/url/search/target' );
$listCntl = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$listAction = $this->config( 'admin/jqadm/url/search/action', 'search' );
$listConfig = $this->config( 'admin/jqadm/url/search/config', [] );

$cancelParams = $params = $this->get( 'pageParams', [] );
unset( $cancelParams['id'] );

$subparts = $this->get( 'itemSubparts', [] );


?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<form class="item item-order form-horizontal" method="POST" enctype="multipart/form-data" action="<?= $enc->attr( $this->url( $target, $cntl, $action, $params, [], $config ) ); ?>">
	<input id="item-baseid" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'order.base.id' ) ) ); ?>" value="<?= $enc->attr( $this->get( 'itemData/order.base.id' ) ); ?>" />
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ); ?>" value="get" />
	<?= $this->csrf()->formfield(); ?>

	<nav class="main-navbar">
		<span class="navbar-brand">
			<?= $enc->html( $this->translate( 'admin', 'Order' ) ); ?>:
			<?= $enc->html( $this->get( 'itemData/order.base.id' ) ); ?> -
			<?= $enc->html( $this->get( 'itemData/order.base.price', $this->translate( 'admin', 'New' ) ) ); ?>
			<span class="navbar-secondary">(<?= $enc->html( $this->site()->match( $this->get( 'itemData/order.base.siteid' ) ) ); ?>)</span>
		</span>
		<div class="item-actions">
			<a class="btn btn-secondary act-cancel"
				title="<?= $enc->attr( $this->translate( 'admin', 'Cancel and return to list') ); ?>"
				href="<?= $enc->attr( $this->url( $listTarget, $listCntl, $listAction, $cancelParams, [], $listConfig ) ); ?>">
				<?php if( $this->access( ['admin', 'editor'] ) ) : ?>
					<?= $enc->html( $this->translate( 'admin', 'Cancel' ) ); ?>
				<?php else : ?>
					<?= $enc->html( $this->translate( 'admin', 'Back' ) ); ?>
				<?php endif; ?>
			</a>

			<?php if( $this->access( ['admin', 'editor'] ) ) : ?>
				<div class="btn-group">
					<button type="submit" class="btn btn-primary act-save"
						title="<?= $enc->attr( $this->translate( 'admin', 'Save item (Ctrl+S)') ); ?>">
						<?= $enc->html( $this->translate( 'admin', 'Save' ) ); ?>
					</button>
						<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false">
							<span class="sr-only"><?= $enc->html( $this->translate( 'admin', 'Toggle Dropdown' ) ); ?></span>
						</button>
						<div class="dropdown-menu">
							<a class="dropdown-item next-action" href="#" data-next="search"><?= $enc->html( $this->translate( 'admin', 'Save & Close' ) ); ?></a>
							<a class="dropdown-item next-action" href="#" data-next="copy"><?= $enc->html( $this->translate( 'admin', 'Save & Copy' ) ); ?></a>
							<a class="dropdown-item next-action" href="#" data-next="create"><?= $enc->html( $this->translate( 'admin', 'Save & New' ) ); ?></a>
						</div>
				</div>
			<?php endif; ?>
		</div>
	</nav>

	<div class="row item-container">

		<div class="col-md-3 item-navbar">
			<ul class="nav nav-tabs flex-md-column flex-wrap d-flex justify-content-between" role="tablist">

				<li class="nav-item basic">
					<a class="nav-link active" href="#basic" data-toggle="tab" role="tab" aria-expanded="true" aria-controls="basic">
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
					<span class="meta-value"><?= $enc->html( $this->get( 'itemData/order.base.mtime' ) ); ?></span>
				</small>
				<small>
					<?= $enc->html( $this->translate( 'admin', 'Created' ) ); ?>:
					<span class="meta-value"><?= $enc->html( $this->get( 'itemData/order.base.ctime' ) ); ?></span>
				</small>
				<small>
					<?= $enc->html( $this->translate( 'admin', 'Editor' ) ); ?>:
					<span class="meta-value"><?= $enc->html( $this->get( 'itemData/order.base.editor' ) ); ?></span>
				</small>
			</div>
		</div>

		<div class="col-md-9 item-content tab-content">

			<div id="basic" class="row item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic">

				<div class="col-xl-6 content-block <?= $this->site()->readonly( $this->get( 'itemData/order.siteid' ) ); ?>">
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ); ?></label>
						<div class="col-sm-8">
							<select class="form-control c-select item-status" required="required" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'order.base.status' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/order.siteid' ) ); ?> >
								<option value="">
									<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
								</option>
								<option value="1" <?= $selected( $this->get( 'itemData/order.base.status', 1 ), 1 ); ?> >
									<?= $enc->html( $this->translate( 'admin', 'status:enabled' ) ); ?>
								</option>
								<option value="0" <?= $selected( $this->get( 'itemData/order.status', 1 ), 0 ); ?> >
									<?= $enc->html( $this->translate( 'admin', 'status:disabled' ) ); ?>
								</option>
							</select>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Comment' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-comment" type="text" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'order.base.comment' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Comment (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/order.base.comment' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/order.base.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Comment added by the customer' ) ); ?>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block <?= $this->site()->readonly( $this->get( 'itemData/order.base.siteid' ) ); ?>">
				</div>

			</div>

			<?= $this->get( 'itemBody' ); ?>

		</div>

		<div class="item-actions">
			<a class="btn btn-secondary act-cancel"
				title="<?= $enc->attr( $this->translate( 'admin', 'Cancel and return to list') ); ?>"
				href="<?= $enc->attr( $this->url( $listTarget, $listCntl, $listAction, $cancelParams, [], $listConfig ) ); ?>">
				<?php if( $this->access( ['admin', 'editor'] ) ) : ?>
					<?= $enc->html( $this->translate( 'admin', 'Cancel' ) ); ?>
				<?php else : ?>
					<?= $enc->html( $this->translate( 'admin', 'Back' ) ); ?>
				<?php endif; ?>
			</a>

			<?php if( $this->access( ['admin', 'editor'] ) ) : ?>
				<div class="btn-group">
					<button type="submit" class="btn btn-primary act-save"
						title="<?= $enc->attr( $this->translate( 'admin', 'Save item (Ctrl+S)') ); ?>">
						<?= $enc->html( $this->translate( 'admin', 'Save' ) ); ?>
					</button>
						<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false">
							<span class="sr-only"><?= $enc->html( $this->translate( 'admin', 'Toggle Dropdown' ) ); ?></span>
						</button>
						<div class="dropdown-menu">
							<a class="dropdown-item next-action" href="#" data-next="search"><?= $enc->html( $this->translate( 'admin', 'Save & Close' ) ); ?></a>
							<a class="dropdown-item next-action" href="#" data-next="copy"><?= $enc->html( $this->translate( 'admin', 'Save & Copy' ) ); ?></a>
							<a class="dropdown-item next-action" href="#" data-next="create"><?= $enc->html( $this->translate( 'admin', 'Save & New' ) ); ?></a>
						</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</form>

<?php $this->block()->stop(); ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-default.php' ) ); ?>
