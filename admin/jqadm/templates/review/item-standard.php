<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2020
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};


$target = $this->config( 'admin/jqadm/url/save/target' );
$cntl = $this->config( 'admin/jqadm/url/save/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/save/action', 'save' );
$config = $this->config( 'admin/jqadm/url/save/config', [] );

$params = $this->get( 'pageParams', [] );

$enc = $this->encoder();


?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<form class="item item-review form-horizontal" method="POST" enctype="multipart/form-data"
	action="<?= $enc->attr( $this->url( $target, $cntl, $action, $params, [], $config ) ); ?>">
	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'review.id' ) ) ); ?>"
		value="<?= $enc->attr( $this->get( 'itemData/review.id' ) ); ?>" />
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ); ?>" value="get" />
	<?= $this->csrf()->formfield(); ?>

	<nav class="main-navbar">
		<h1 class="navbar-brand">
			<span class="navbar-title"><?= $enc->html( $this->translate( 'admin', 'Review' ) ); ?></span>
			<span class="navbar-id"><?= $enc->html( $this->get( 'itemData/review.id' ) ); ?></span>
			<span class="navbar-name"><?= $enc->html( $this->get( 'itemData/review.name' ) ?: $this->translate( 'admin', 'New' ) ); ?></span>
			<span class="navbar-site"><?= $enc->html( $this->site()->match( $this->get( 'itemData/review.siteid' ) ) ); ?></span>
		</h1>
		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard' ), ['params' => $params] ); ?>
		</div>
	</nav>

	<div class="row item-container">

		<div class="col-md-3 item-navbar">
			<div class="navbar-content">
				<ul class="nav nav-tabs flex-md-column flex-wrap d-flex justify-content-between" role="tablist">

					<li class="nav-item basic">
						<a class="nav-link active" href="#basic" data-toggle="tab" role="tab" aria-expanded="true" aria-controls="basic">
							<?= $enc->html( $this->translate( 'admin', 'Basic' ) ); ?>
						</a>
					</li>

					<?php foreach( array_values( $this->get( 'itemSubparts', [] ) ) as $idx => $subpart ) : ?>
						<li class="nav-item <?= $enc->attr( $subpart ); ?>">
							<a class="nav-link" href="#<?= $enc->attr( $subpart ); ?>" data-toggle="tab" role="tab" tabindex="<?= ++$idx + 1; ?>">
								<?= $enc->html( $this->translate( 'admin', $subpart ) ); ?>
							</a>
						</li>
					<?php endforeach; ?>

				</ul>

				<div class="item-meta text-muted">
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Modified' ) ); ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/review.mtime' ) ); ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Created' ) ); ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/review.ctime' ) ); ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Editor' ) ); ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/review.editor' ) ); ?></span>
					</small>
				</div>
			</div>
		</div>

		<div class="col-md-9 item-content tab-content">

			<div id="basic" class="row item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic">

				<div class="col-xl-6 content-block <?= $this->site()->readonly( $this->get( 'itemData/review.siteid' ) ); ?>">
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ); ?></label>
						<div class="col-sm-8">
							<select class="form-control custom-select item-status" required="required" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'review.status' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/review.siteid' ) ); ?> >
								<option value="">
									<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
								</option>
								<option value="1" <?= $selected( $this->get( 'itemData/review.status', 1 ), 1 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ); ?>
								</option>
								<option value="0" <?= $selected( $this->get( 'itemData/review.status', 1 ), 0 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ); ?>
								</option>
								<option value="-1" <?= $selected( $this->get( 'itemData/review.status', 1 ), -1 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ); ?>
								</option>
								<option value="-2" <?= $selected( $this->get( 'itemData/review.status', 1 ), -2 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ); ?>
								</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Name' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-name" type="text" tabindex="1"
								name="<?= $this->formparam( array( 'item', 'review.name' ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Reviewer name' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/review.name' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/review.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Name of the reviewer' ) ); ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Rating' ) ); ?></label>
						<div class="col-sm-8">
							<?= $enc->html( str_pad( '', $this->get( 'itemData/review.rating', 1 ), '★' ) ); ?>
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Rating of the reviewer' ) ); ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Comment' ) ); ?></label>
						<div class="col-sm-8">
							<p class="form-control item-comment"><?= $enc->html( $this->get( 'itemData/review.comment' ) ); ?></p>
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Comment of the reviewer' ) ); ?>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block <?= $this->site()->readonly( $this->get( 'itemData/review.siteid' ) ); ?>">
					<div class="form-group">
							<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Response' ) ); ?></label>
							<textarea class="form-control item-response" tabindex="1" maxlength="255"
								name="<?= $this->formparam( array( 'item', 'review.response' ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Your response' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/review.siteid' ) ); ?> >
								<?= $enc->html( $this->get( 'itemData/review.response' ) ); ?>
							</textarea>
							<div class="form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Response to the reviewer' ) ); ?>
							</div>
						</div>
					</div>
				</div>

			</div>

			<?= $this->get( 'itemBody' ); ?>

		</div>

		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard' ), ['params' => $params] ); ?>
		</div>
	</div>
</form>

<?php $this->block()->stop(); ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard' ) ); ?>
