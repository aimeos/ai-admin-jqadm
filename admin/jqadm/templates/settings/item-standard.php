<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};


$enc = $this->encoder();

$target = $this->config( 'admin/jqadm/url/save/target' );
$cntl = $this->config( 'admin/jqadm/url/save/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/save/action', 'save' );
$config = $this->config( 'admin/jqadm/url/save/config', [] );

$params = $this->get( 'pageParams', [] );


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<form class="item item-settings form-horizontal container-fluid" method="POST" enctype="multipart/form-data"
	action="<?= $enc->attr( $this->url( $target, $cntl, $action, $params, [], $config ) ) ?>"
	data-data="<?= $enc->attr( $this->get( 'data', [] ) ) ?>" >

	<?= $this->csrf()->formfield() ?>

	<nav class="main-navbar">
		<h1 class="navbar-brand">
			<span class="navbar-title"><?= $enc->html( $this->translate( 'admin', 'Settings' ) ) ?></span>
			<span class="navbar-label"><?= $enc->html( $this->get( 'itemData/locale.site.label' ) ) ?></span>
		</h1>
		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard' ), ['params' => $params, 'actions' => []] ) ?>
		</div>
	</nav>

	<div class="row item-container">

		<div class="col-xl-3 item-navbar">
			<div class="navbar-content">
				<ul class="nav nav-tabs flex-row flex-wrap d-flex box" role="tablist">
					<li class="nav-item basic">
						<a class="nav-link active" href="#basic" data-bs-toggle="tab" role="tab" aria-expanded="true" aria-controls="basic" tabindex="1">
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
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/locale.site.mtime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/locale.site.ctime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/locale.site.editor' ) ) ?></span>
					</small>
				</div>

				<div class="more"></div>
			</div>
		</div>

		<div class="col-xl-9 item-content tab-content">

			<div id="basic" class="item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic">

				<div class="row item-media">
					<div class="col-xl-6 item-logo">
						<div class="box">

							<div class="form-group media-preview">
								<input class="fileupload" type="file" tabindex="<?= $this->get( 'tabindex', 1 ); ?>"
									name="media[logo]" />
								<?php if( $logos = $this->get( 'itemData/locale.site.logo' ) ) : ?>
									<img class="item-preview"
										src="<?= $enc->attr( $this->content( $this->config( 'resource/fs/baseurl' ) . '/' . end( $logos ) ) ) ?>" />
								<?php else : ?>
									<p class="item-preview">
										<?= $enc->html( $this->translate( 'admin', 'Upload shop logo' ) ) ?>
									</p>
								<?php endif ?>
							</div>

						</div>
					</div>

					<div class="col-xl-6 item-icon">
						<div class="box">

							<div class="form-group media-preview">
								<input class="fileupload" type="file" tabindex="<?= $this->get( 'tabindex', 1 ); ?>"
									name="media[icon]" />
								<?php if( $icon = $this->get( 'itemData/locale.site.icon' ) ) : ?>
									<img class="item-preview"
										src="<?= $enc->attr( $this->content( $this->config( 'resource/fs/baseurl' ) . '/' . $icon ) ) ?>" />
								<?php else : ?>
									<p class="item-preview">
										<?= $enc->html( $this->translate( 'admin', 'Upload shop icon' ) ) ?>
									</p>
								<?php endif ?>
							</div>

						</div>
					</div>

					<div class="col-xl-12">
						<div class="box">
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Shop name' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-email-address" type="text" required="required" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'locale.site.label' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Shop name (required)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/locale.site.label' ) ) ?>" />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Name of your shop shown to your customers' ) ) ?>
										</div>
									</div>
									<div class="form-group row mandatory">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Shop e-mail' ) ) ?></label>
										<div class="col-sm-8">
											<input class="form-control item-email-address" type="email" required="required" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'locale.site.config', 'resource', 'email', 'from-email' ) ) ) ?>"
												placeholder="<?= $enc->attr( $this->translate( 'admin', 'Shop e-mail address (required)' ) ) ?>"
												value="<?= $enc->attr( $this->get( 'itemData/locale.site.config/resource/email/from-email' ) ) ?>" />
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'E-Mail address used for sending shop related e-mails' ) ) ?>
										</div>
									</div>
								</div>

								<div class="col-xl-6">
									<div class="form-group row optional">
										<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Shop theme' ) ) ?></label>
										<div class="col-sm-8">
											<select class="form-select item-theme" tabindex="1"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'locale.site.theme' ) ) ) ?>" >
												<option value="">
													<?= $enc->html( $this->translate( 'admin', 'Default' ) ) ?>
												</option>
												<?php foreach( $this->get( 'themes', [] ) as $theme => $name ) : ?>
													<option value="<?= $enc->attr( $theme ) ?>" <?= $selected( $this->get( 'itemData/locale.site.theme' ), $theme ) ?> >
														<?= $enc->html( $name ) ?>
													</option>
												<?php endforeach ?>
											</select>
										</div>
										<div class="col-sm-12 form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Theme to change the layout of your shop' ) ) ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="box">
					<div class="row">
						<div class="col-xl-6">
							<div class="form-group row mandatory warning">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Shop domain' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-code" type="text" required="required" tabindex="1"
										pattern="^[a-z0-9\-]+(\.[a-z0-9\-]+)*$"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'locale.site.code' ) ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Shop domain (required)' ) ) ?>"
										value="<?= $enc->attr( $this->get( 'itemData/locale.site.code' ) ) ?>" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Custom domain or unique code of your shop' ) ) ?>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>

			<?= $this->get( 'itemBody' ) ?>

		</div>

		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard' ), ['params' => $params, 'actions' => []] ) ?>
		</div>

	</div>
</form>

<?php $this->block()->stop() ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard' ) ) ?>
