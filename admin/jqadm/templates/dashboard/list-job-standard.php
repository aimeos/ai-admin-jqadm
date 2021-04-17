<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */

$enc = $this->encoder();
$params = $this->param();
$items = $this->get( 'jobItems', map() );

$getTarget = $this->config( 'admin/jqadm/url/get/target' );
$getCntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$getAction = $this->config( 'admin/jqadm/url/get/action', 'get' );
$getConfig = $this->config( 'admin/jqadm/url/get/config', [] );

$delTarget = $this->config( 'admin/jqadm/url/delete/target' );
$delCntl = $this->config( 'admin/jqadm/url/delete/controller', 'Jqadm' );
$delAction = $this->config( 'admin/jqadm/url/delete/action', 'delete' );
$delConfig = $this->config( 'admin/jqadm/url/delete/config', [] );


?>
<?php if( !$items->isEmpty() ) : ?>
	<div class="dashboard-job row">
		<div class="job-list col-xl-12">
			<div class="box">
				<div class="header"
					data-bs-toggle="collapse" data-bs-target="#job-list-data"
					aria-expanded="true" aria-controls="job-list-data">
					<div class="card-tools-start">
						<div class="btn act-show fa"></div>
					</div>
					<h2 class="header-label">
						<?= $enc->html( $this->translate( 'admin', 'Import/Export jobs' ) ) ?>
					</h2>
				</div>
				<div class="content collapse show">
					<div class="table-responsive">
						<table class="list-items table table-hover">
							<tbody>
								<?php foreach( $items as $id => $item ) : ?>
									<tr>
										<td class="job-label"><?= $enc->html( $item->getLabel() ) ?></td>
										<td class="job-mtime"><?= $enc->html( $item->getTimeModified() ) ?></td>
										<td class="actions">
											<?php if( !$this->site()->readonly( $item->getSiteId() ) ) : ?>
												<a class="btn act-delete fa" tabindex="1"
													href="<?= $enc->attr( $this->url( $delTarget, $delCntl, $delAction, ['resource' => 'dashboard', 'id' => $id] + $params, [], $delConfig ) ) ?>"
													title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
													aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ) ?>"></a>
											<?php endif ?>
											<a class="btn act-download fa" tabindex="1"
												href="<?= $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['resource' => 'dashboard', 'id' => $id] + $params, [], $getConfig ) ) ?>"
												title="<?= $enc->attr( $this->translate( 'admin', 'Download this file' ) ) ?>"
												aria-label="<?= $enc->attr( $this->translate( 'admin', 'Download' ) ) ?>"></a>
										</td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>