<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2022
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$params = $this->get( 'pageParams', [] );
$enc = $this->encoder();


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<form class="item item-review form-horizontal container-fluid" method="POST" enctype="multipart/form-data"
	action="<?= $enc->attr( $this->link( 'admin/jqadm/url/save', $params ) ) ?>">
	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'review.id' ) ) ) ?>"
		value="<?= $enc->attr( $this->get( 'itemData/review.id' ) ) ?>" />
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ) ?>" value="get" />
	<?= $this->csrf()->formfield() ?>

	<nav class="main-navbar">
		<h1 class="navbar-brand">
			<span class="navbar-title"><?= $enc->html( $this->translate( 'admin', 'Review' ) ) ?></span>
			<span class="navbar-id"><?= $enc->html( $this->get( 'itemData/review.id' ) ) ?></span>
			<span class="navbar-name"><?= $enc->html( $this->get( 'itemData/review.name' ) ?: $this->translate( 'admin', 'New' ) ) ?></span>
			<span class="navbar-site"><?= $enc->html( $this->site()->match( $this->get( 'itemData/review.siteid' ) ) ) ?></span>
		</h1>
		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'itemactions' ), ['params' => $params] ) ?>
		</div>
	</nav>

	<div class="row item-container">

		<div class="col-xl-3 item-navbar">
			<div class="navbar-content">
				<ul class="nav nav-tabs flex-xl-column flex-wrap d-flex box" role="tablist">

					<li class="nav-item basic">
						<a class="nav-link active" href="#basic" data-bs-toggle="tab" role="tab" aria-expanded="true" aria-controls="basic">
							<?= $enc->html( $this->translate( 'admin', 'Basic' ) ) ?>
						</a>
					</li>

					<?php foreach( array_values( $this->get( 'itemSubparts', [] ) ) as $idx => $subpart ) : ?>
						<li class="nav-item <?= $enc->attr( $subpart ) ?>">
							<a class="nav-link" href="#<?= $enc->attr( $subpart ) ?>" data-bs-toggle="tab" role="tab" tabindex="<?= ++$idx + 1 ?>">
								<?= $enc->html( $this->translate( 'admin', $subpart ) ) ?>
							</a>
						</li>
					<?php endforeach ?>

				</ul>

				<div class="item-meta text-muted">
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Modified' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/review.mtime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/review.ctime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/review.editor' ) ) ?></span>
					</small>
				</div>

				<div class="more"></div>
			</div>
		</div>

		<div class="col-xl-9 item-content tab-content">

			<div id="basic" class="item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic">

				<div class="row">
					<div class="col-xl-6 block <?= $this->site()->readonly( $this->get( 'itemData/review.siteid' ) ) ?>">
						<div class="box">
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></label>
								<div class="col-sm-8">
									<?php if( $this->access( 'super', 'admin' ) ) : ?>
										<select class="form-select item-status" required="required" tabindex="1"
											name="<?= $enc->attr( $this->formparam( array( 'item', 'review.status' ) ) ) ?>"
											<?= $this->site()->readonly( $this->get( 'itemData/review.siteid' ) ) ?> >
											<option value="">
												<?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?>
											</option>
											<option value="1" <?= $selected( $this->get( 'itemData/review.status', 1 ), 1 ) ?> >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?>
											</option>
											<option value="0" <?= $selected( $this->get( 'itemData/review.status', 1 ), 0 ) ?> >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?>
											</option>
											<option value="-1" <?= $selected( $this->get( 'itemData/review.status', 1 ), -1 ) ?> >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?>
											</option>
											<option value="-2" <?= $selected( $this->get( 'itemData/review.status', 1 ), -2 ) ?> >
												<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?>
											</option>
										</select>
									<?php else : ?>
										<span class="form-control item-status">
											<?php $key = 'status:' . $this->get( 'itemData/review.status' ) ?>
											<?= $enc->html( $this->translate( 'mshop/code', $key ) ) ?>
										</span>
									<?php endif ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Name' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-name" type="text" tabindex="1"
										name="<?= $this->formparam( array( 'item', 'review.name' ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Reviewer name' ) ) ?>"
										value="<?= $enc->attr( $this->get( 'itemData/review.name' ) ) ?>"
										<?= $this->site()->readonly( $this->get( 'itemData/review.siteid' ) ) ?> />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Name of the reviewer' ) ) ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Rating' ) ) ?></label>
								<div class="col-sm-8">
									<span class="form-control item-comment">
										<?= $enc->html( str_repeat( '★', $this->get( 'itemData/review.rating', 1 ) ) ) ?>
									</span>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Rating of the reviewer' ) ) ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Comment' ) ) ?></label>
								<div class="col-sm-8">
									<span class="form-control item-comment"><?= $enc->html( $this->get( 'itemData/review.comment' ) ) ?></span>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Comment of the reviewer' ) ) ?>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xl-6 block <?= $this->site()->readonly( $this->get( 'itemData/review.siteid' ) ) ?>">
						<div class="box">
							<?php if( $this->access( $this->config( 'admin/jqadm/resource/' . $this->item->getDomain() . '/groups', [] ) ) ) : ?>
								<div class="form-group row">
									<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Reviewed item' ) ) ?></label>
									<div class="col-sm-8">
										<span class="form-control item-refid">
											<a class="btn fa act-view" target="_blank"
												href="<?= $enc->attr( $this->link( 'admin/jqadm/url/get', ['resource' => $this->item->getDomain(), 'id' => $this->item->getRefId()] ) ) ?>">
												<?= $enc->html( $this->item->getDomain() ) ?>: <?= $enc->html( $this->item->getRefId() ) ?>
											</a>
										</span>
									</div>
								</div>
							<?php endif ?>
							<div class="form-group row">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Response' ) ) ?></label>
								<div class="col-sm-8">
									<textarea class="form-control item-response" tabindex="1" maxlength="1024"
										name="<?= $this->formparam( array( 'item', 'review.response' ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Your response' ) ) ?>"
										<?= $this->site()->readonly( $this->get( 'itemData/review.siteid' ) ) ?> >
										<?= $enc->html( $this->get( 'itemData/review.response' ) ) ?>
									</textarea>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Response to the reviewer' ) ) ?>
								</div>
							</div>
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

<?php $this->block()->stop() ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'page' ) ) ?>
