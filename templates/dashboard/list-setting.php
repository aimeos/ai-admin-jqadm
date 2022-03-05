<?php

$enc = $this->encoder();

$settings = [
	'file_uploads' => ini_get( 'file_uploads' ) != 1 ? ['danger', $this->translate( 'admin', 'PHP setting "%1$s" is disabled' )] : null,
	'opcache.enable' => ini_get( 'opcache.enable' ) != 1 ? ['warning', $this->translate( 'admin', 'PHP setting "%1$s" is disabled' )] : null,
	'open_basedir' => ini_get( 'open_basedir' ) != null ? ['warning', $this->translate( 'admin', 'PHP setting "%1$s" is enabled' )] : null,
	'xdebug.default_enable' => ini_get( 'xdebug.default_enable' ) != 0 ? ['warning', $this->translate( 'admin', 'PHP setting "%1$s" is enabled' )] : null,
	'madmin/cache/manager/name' => $this->get( 'madmin/cache/manager/name' ) === 'None' ? ['warning', $this->translate( 'admin', 'Aimeos caching is disabled by "%1$s"' )] : null,
	'madmin/log/manager/loglevel' => $this->get( 'madmin/log/manager/loglevel', 0 ) > 6 ? ['warning', $this->translate( 'admin', 'Aimeos debug logging is enabled by "%1$s"' )] : null,
];

?>
<?php if( ( $settings = array_filter( $settings ) ) !== [] ) : ?>
	<div class="dashboard-setting row">
		<div class="setting-list col-xl-12">
			<div class="box">
				<div class="header"
					data-bs-toggle="collapse" data-bs-target="#setting-list-data"
					aria-expanded="true" aria-controls="setting-list-data">
					<div class="card-tools-start">
						<div class="btn act-show fa"></div>
					</div>
					<h2 class="header-label">
						<?= $enc->html( $this->translate( 'admin', 'Potential problems' ) ) ?>
					</h2>
				</div>
				<div id="setting-list-data" class="content collapse show">
					<div class="problem-list">
						<?php foreach( $settings as $setting => $entry ) : list( $type, $problem ) = $entry ?>
							<p class="alert alert-<?= $enc->attr( $type ) ?>">
								<?= $enc->html( sprintf( $problem, $setting ) ) ?>
							</p>
						<?php endforeach ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>