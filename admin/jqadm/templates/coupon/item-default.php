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

$subparts = $this->get( 'itemSubparts', [] );
$params = $this->get( 'pageParams', [] );


?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<form class="item item-coupon form-horizontal" method="POST" enctype="multipart/form-data" action="<?= $enc->attr( $this->url( $target, $cntl, $action, $params, [], $config ) ); ?>">
	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'coupon.id' ) ) ); ?>" value="<?= $enc->attr( $this->get( 'itemData/coupon.id' ) ); ?>" />
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ); ?>" value="get" />
	<?= $this->csrf()->formfield(); ?>

	<nav class="main-navbar">
		<span class="navbar-brand">
			<?= $enc->html( $this->translate( 'admin', 'Voucher' ) ); ?>:
			<?= $enc->html( $this->get( 'itemData/coupon.id' ) ); ?> -
			<?= $enc->html( $this->get( 'itemData/coupon.label', $this->translate( 'admin', 'New' ) ) ); ?>
			<span class="navbar-secondary">(<?= $enc->html( $this->site()->match( $this->get( 'itemData/coupon.siteid' ) ) ); ?>)</span>
		</span>
		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-default.php' ), ['params' => $params] ); ?>
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
					<span class="meta-value"><?= $enc->html( $this->get( 'itemData/coupon.mtime' ) ); ?></span>
				</small>
				<small>
					<?= $enc->html( $this->translate( 'admin', 'Created' ) ); ?>:
					<span class="meta-value"><?= $enc->html( $this->get( 'itemData/coupon.ctime' ) ); ?></span>
				</small>
				<small>
					<?= $enc->html( $this->translate( 'admin', 'Editor' ) ); ?>:
					<span class="meta-value"><?= $enc->html( $this->get( 'itemData/coupon.editor' ) ); ?></span>
				</small>
			</div>
		</div>

		<div class="col-md-9 item-content tab-content">

			<div id="basic" class="row item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic">

				<div class="col-xl-6 content-block <?= $this->site()->readonly( $this->get( 'itemData/coupon.siteid' ) ); ?>">
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ); ?></label>
						<div class="col-sm-8">
							<select class="form-control custom-select item-status" required="required" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'coupon.status' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/coupon.siteid' ) ); ?> >
								<option value="">
									<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
								</option>
								<option value="1" <?= $selected( $this->get( 'itemData/coupon.status', 1 ), 1 ); ?> >
									<?= $enc->html( $this->translate( 'admin', 'status:enabled' ) ); ?>
								</option>
								<option value="0" <?= $selected( $this->get( 'itemData/coupon.status', 1 ), 0 ); ?> >
									<?= $enc->html( $this->translate( 'admin', 'status:disabled' ) ); ?>
								</option>
								<option value="-1" <?= $selected( $this->get( 'itemData/coupon.status', 1 ), -1 ); ?> >
									<?= $enc->html( $this->translate( 'admin', 'status:review' ) ); ?>
								</option>
								<option value="-2" <?= $selected( $this->get( 'itemData/coupon.status', 1 ), -2 ); ?> >
									<?= $enc->html( $this->translate( 'admin', 'status:archive' ) ); ?>
								</option>
							</select>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-label" type="text" required="required" tabindex="1"
								name="<?= $this->formparam( array( 'item', 'coupon.label' ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/coupon.label' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/coupon.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Internal article name, will be used on the web site if no product name for the language is available' ) ); ?>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Provider' ) ); ?></label>
						<div class="col-sm-8 input-group">
							<input class="form-control item-provider combobox" type="text" required="required" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'coupon.provider' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Provider/decorator class names (required)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/coupon.provider' ) ); ?>"
								data-names="<?= implode( ',', $this->get( 'itemProviders', [] ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/coupon.siteid' ) ); ?> />
							<div class="dropdown input-group-addon">
								<a class="dropdown-add" id="decoratorButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
								<div class="dropdown-menu dropdown-menu-right" aria-labelledby="decoratorButton">
									<?php foreach( $this->get( 'itemDecorators', [] ) as $name ) : ?>
										<a class="dropdown-item decorator-name" href="#" data-name="<?= $enc->attr( $name ); ?>"><?= $enc->html( $name ); ?></a>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'One provider and zero or more decorator class names separated by commas' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-datestart date" type="text" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'coupon.datestart' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
								value="<?= $enc->attr( str_replace( ' ', 'T', $this->get( 'itemData/coupon.datestart' ) ) ); ?>"
								data-format="<?= $this->translate( 'admin', 'yy-mm-dd' ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/coupon.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'The article is only shown on the web site after that date and time, useful or seasonal articles' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-dateend date" type="text" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'coupon.dateend' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
								value="<?= $enc->attr( str_replace( ' ', 'T', $this->get( 'itemData/coupon.dateend' ) ) ); ?>"
								data-format="<?= $this->translate( 'admin', 'yy-mm-dd' ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/coupon.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'The article is only shown on the web site until that date and time, useful or seasonal articles' ) ); ?>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block <?= $this->site()->readonly( $this->get( 'itemData/coupon.siteid' ) ); ?>">
					<table class="item-config table table-striped">
						<thead>
							<tr>
								<th>
									<span class="help"><?= $enc->html( $this->translate( 'admin', 'Option' ) ); ?></span>
									<div class="form-text text-muted help-text">
										<?= $enc->html( $this->translate( 'admin', 'Coupon provider or coupon decorator configuration name' ) ); ?>
									</div>
								</th>
								<th>
									<?= $enc->html( $this->translate( 'admin', 'Value' ) ); ?>
								</th>
								<th class="actions">
									<?php if( !$this->site()->readonly( $this->get( 'itemData/coupon.siteid' ) ) ) : ?>
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
											<?= $this->site()->readonly( $this->get( 'itemData/coupon.siteid' ) ); ?> />
									</td>
									<td>
										<?php $cfgval = $this->get( 'itemData/config/val/' . $idx ); ?>
										<input type="text" class="config-value form-control" tabindex="1"
											name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'val', '' ) ) ); ?>"
											value="<?= $enc->attr( is_array( $cfgval ) ? json_encode( $cfgval ) : $cfgval ); ?>"
											<?= $this->site()->readonly( $this->get( 'itemData/coupon.siteid' ) ); ?> />
									</td>
									<td class="actions">
										<?php if( !$this->site()->readonly( $this->get( 'itemData/coupon.siteid' ) ) ) : ?>
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
									<?php if( !$this->site()->readonly( $this->get( 'itemData/coupon.siteid' ) ) ) : ?>
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
</form>

<?php $this->block()->stop(); ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-default.php' ) ); ?>
