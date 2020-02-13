<?php

$enc = $this->encoder();

$settings = [
	'file_uploads' => ini_get( 'file_uploads' ) != 1 ? ['danger', $this->translate( 'admin', 'PHP setting "%1$s" is disabled' )] : null,
	'allow_url_fopen' => ini_get( 'allow_url_fopen' ) != 1 ? ['warning', $this->translate( 'admin', 'PHP setting "%1$s" is disabled' )] : null,
	'opcache.enable' => ini_get( 'opcache.enable' ) != 1 ? ['warning', $this->translate( 'admin', 'PHP setting "%1$s" is disabled' )] : null,
	'open_basedir' => ini_get( 'open_basedir' ) != null ? ['warning', $this->translate( 'admin', 'PHP setting "%1$s" is enabled' )] : null,
	'xdebug.default_enable' => ini_get( 'xdebug.default_enable' ) != 0 ? ['warning', $this->translate( 'admin', 'PHP setting "%1$s" is enabled' )] : null,
	'madmin/cache/manager/name' => $this->get( 'madmin/cache/manager/name' ) === 'None' ? ['warning', $this->translate( 'admin', 'Aimeos caching is disabled by "%1$s"' )] : null,
	'madmin/log/manager/standard/loglevel' => $this->get( 'madmin/log/manager/standard/loglevel', 0 ) > 6 ? ['warning', $this->translate( 'admin', 'Aimeos debug logging is enabled by "%1$s"' )] : null,
];

?>
<?php if( ( $settings = array_filter( $settings ) ) !== [] ) : ?>
	<div class="dashboard-setting row">
		<div class="setting-list card col-lg-12">
			<div id="setting-list-head" class="card-header header" role="tab"
				data-toggle="collapse" data-target="#setting-list-data"
				aria-expanded="true" aria-controls="setting-list-data">
				<div class="card-tools-left">
					<div class="btn btn-card-header act-show fa"></div>
				</div>
				<span class="item-label header-label">
					<?= $enc->html( $this->translate( 'admin', 'Potential problems' ) ); ?>
				</span>
			</div>
			<div id="setting-list-data" class="card-block content collapse show" role="tabpanel" aria-labelledby="setting-list-head">
				<?php foreach( $settings as $setting => $entry ) : list( $type, $problem ) = $entry; ?>
					<p class="alert alert-<?= $enc->attr( $type ) ?>">
						<?= $enc->html( sprintf( $problem, $setting ) ) ?>
					</p>
				<?php endforeach ?>
			</div>
		</div>
	</div>
<?php endif; ?>