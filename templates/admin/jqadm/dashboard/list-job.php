<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */

$enc = $this->encoder();
$params = $this->param();
$items = $this->get( 'jobItems', map() );


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
					<table class="list-items table table-hover">
						<tbody>
							<?php foreach( $items as $id => $item ) : ?>
								<tr>
									<td class="job-label"><?= $enc->html( $item->getLabel() ) ?></td>
									<td class="job-mtime"><?= $enc->html( $item->getTimeModified() ) ?></td>
									<td class="actions">
										<?php if( !$this->site()->readonly( $item->getSiteId() ) ) : ?>
											<form method="POST" action="<?= $enc->attr( $this->link( 'admin/jqadm/url/delete', ['resource' => 'dashboard', 'id' => $id] + $params ) ) ?>">
												<?= $this->csrf()->formfield() ?>
												<button class="btn act-delete fa" tabindex="1"
													title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
													aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ) ?>"
												></button>
											</form>
										<?php endif ?>
										<a class="btn act-download fa" tabindex="1"
											href="<?= $enc->attr( $this->link( 'admin/jqadm/url/get', ['resource' => 'dashboard', 'id' => $id] + $params ) ) ?>"
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
<?php endif ?>