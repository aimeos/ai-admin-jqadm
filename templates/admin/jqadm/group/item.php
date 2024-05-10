<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2024
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$enc = $this->encoder();
$params = $this->get( 'pageParams', [] );


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<form class="item item-group form-horizontal container-fluid" method="POST" enctype="multipart/form-data" action="<?= $enc->attr( $this->link( 'admin/jqadm/url/save', $params ) ) ?>">
	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'group.id' ) ) ) ?>" value="<?= $enc->attr( $this->get( 'itemData/group.id' ) ) ?>">
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ) ?>" value="get">
	<?= $this->csrf()->formfield() ?>

	<nav class="main-navbar">
		<h1 class="navbar-brand">
			<span class="navbar-title"><?= $enc->html( $this->translate( 'admin', 'Group' ) ) ?></span>
			<span class="navbar-id"><?= $enc->html( $this->get( 'itemData/group.id' ) ) ?></span>
			<span class="navbar-label"><?= $enc->html( $this->get( 'itemData/group.label' ) ?: $this->translate( 'admin', 'New' ) ) ?></span>
			<span class="navbar-site"><?= $enc->html( $this->site()->match( $this->get( 'itemData/group.siteid' ) ) ) ?></span>
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
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/group.mtime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/group.ctime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/group.editor' ) ) ?></span>
					</small>
				</div>

				<div class="icon more"></div>
			</div>
		</div>

		<div class="col-xl-9 item-content tab-content">
			<div id="basic" class="item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic">

				<div class="box <?= $this->site()->mismatch( $this->get( 'itemData/group.siteid' ) ) ?>">
					<div class="row">
						<div class="col-xl-6">
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Code' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-code" type="text" required="required" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'group.code' ) ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Unique group code (required)' ) ) ?>"
										value="<?= $enc->attr( $this->get( 'itemData/group.code' ) ) ?>"
										<?= $this->site()->readonly( $this->get( 'itemData/group.siteid' ) ) ?>>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Alphanumeric string that can identify the group uniquely' ) ) ?>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-label" type="text" required="required" tabindex="1"
										name="<?= $this->formparam( array( 'item', 'group.label' ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ) ?>"
										value="<?= $enc->attr( $this->get( 'itemData/group.label' ) ) ?>"
										<?= $this->site()->readonly( $this->get( 'itemData/group.siteid' ) ) ?>>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Group name describing for which members the group is for' ) ) ?>
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
