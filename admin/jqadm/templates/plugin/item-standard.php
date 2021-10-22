<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */

$attr = function( $list, $key, $code ) {
	$map = ( isset( $list[$key] ) ? $list[$key]->toArray() : [] );
	return ( isset( $map[$code] ) ? $map[$code] : '' );
};

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};


$target = $this->config( 'admin/jqadm/url/save/target' );
$cntl = $this->config( 'admin/jqadm/url/save/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/save/action', 'save' );
$config = $this->config( 'admin/jqadm/url/save/config', [] );

$attributes = $this->get( 'itemAttributes', [] );
$params = $this->get( 'pageParams', [] );

$enc = $this->encoder();


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<form class="item item-plugin form-horizontal container-fluid" method="POST" enctype="multipart/form-data"
	action="<?= $enc->attr( $this->url( $target, $cntl, $action, $params, [], $config ) ) ?>">
	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'plugin.id' ) ) ) ?>"
		value="<?= $enc->attr( $this->get( 'itemData/plugin.id' ) ) ?>" />
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ) ?>" value="get" />
	<?= $this->csrf()->formfield() ?>

	<nav class="main-navbar">
		<h1 class="navbar-brand">
			<span class="navbar-title"><?= $enc->html( $this->translate( 'admin', 'Plugin' ) ) ?></span>
			<span class="navbar-id"><?= $enc->html( $this->get( 'itemData/plugin.id' ) ) ?></span>
			<span class="navbar-label"><?= $enc->html( $this->get( 'itemData/plugin.label' ) ?: $this->translate( 'admin', 'New' ) ) ?></span>
			<span class="navbar-site"><?= $enc->html( $this->site()->match( $this->get( 'itemData/plugin.siteid' ) ) ) ?></span>
		</h1>
		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard' ), ['params' => $params] ) ?>
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
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/plugin.mtime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/plugin.ctime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/plugin.editor' ) ) ?></span>
					</small>
				</div>

				<div class="more"></div>
			</div>
		</div>

		<div class="col-xl-9 item-content tab-content">

			<div id="basic" class="item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic">

				<div class="box">
					<div class="row">
						<div class="col-xl-6 block <?= $this->site()->readonly( $this->get( 'itemData/plugin.siteid' ) ) ?>">
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></label>
								<div class="col-sm-8">
									<select class="form-select item-status" required="required" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'plugin.status' ) ) ) ?>"
										<?= $this->site()->readonly( $this->get( 'itemData/plugin.siteid' ) ) ?> >
										<option value="">
											<?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?>
										</option>
										<option value="1" <?= $selected( $this->get( 'itemData/plugin.status', 1 ), 1 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?>
										</option>
										<option value="0" <?= $selected( $this->get( 'itemData/plugin.status', 1 ), 0 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?>
										</option>
										<option value="-1" <?= $selected( $this->get( 'itemData/plugin.status', 1 ), -1 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?>
										</option>
										<option value="-2" <?= $selected( $this->get( 'itemData/plugin.status', 1 ), -2 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?>
										</option>
									</select>
								</div>
							</div>
							<?php if( ( $types = $this->get( 'itemTypes', map() ) )->count() !== 1 ) : ?>
								<div class="form-group row mandatory">
									<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?></label>
									<div class="col-sm-8">
										<select class="form-select item-type" required="required" tabindex="1"
											name="<?= $enc->attr( $this->formparam( array( 'item', 'plugin.type' ) ) ) ?>"
											<?= $this->site()->readonly( $this->get( 'itemData/plugin.siteid' ) ) ?> >
											<option value="">
												<?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?>
											</option>

											<?php foreach( $types as $item ) : ?>
												<option value="<?= $enc->attr( $item->getCode() ) ?>" <?= $selected( $this->get( 'itemData/plugin.type', 'order' ), $item->getCode() ) ?> >
													<?= $enc->html( $item->getLabel() ) ?>
												</option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
							<?php else : ?>
								<input class="item-type" type="hidden"
									name="<?= $enc->attr( $this->formparam( array( 'item', 'plugin.type' ) ) ) ?>"
									value="<?= $enc->attr( $types->getCode()->first() ) ?>" />
							<?php endif ?>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-label" type="text" required="required" tabindex="1"
										name="<?= $this->formparam( array( 'item', 'plugin.label' ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ) ?>"
										value="<?= $enc->attr( $this->get( 'itemData/plugin.label' ) ) ?>"
										<?= $this->site()->readonly( $this->get( 'itemData/plugin.siteid' ) ) ?> />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Plugin label for describing the configured plugin' ) ) ?>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Provider' ) ) ?></label>
								<div class="col-sm-8">
									<div class="input-group">
										<input class="form-control combobox select item-provider noedit" type="text" required="required" tabindex="1"
											name="<?= $enc->attr( $this->formparam( array( 'item', 'plugin.provider' ) ) ) ?>"
											placeholder="<?= $enc->attr( $this->translate( 'admin', 'Provider/decorator class names (required)' ) ) ?>"
											value="<?= $enc->attr( $this->get( 'itemData/plugin.provider' ) ) ?>"
											data-order="<?= implode( ',', $this->get( 'itemProviders/order', [] ) ) ?>"
											<?= $this->site()->readonly( $this->get( 'itemData/plugin.siteid' ) ) ?> />
										<div class="dropdown">
											<div class="btn act-add fa" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
											<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="decoratorButton">
												<?php foreach( $this->get( 'itemDecorators', [] ) as $name ) : ?>
													<li class="dropdown-item"><a class="decorator-name" href="#" data-name="<?= $enc->attr( $name ) ?>"><?= $enc->html( $name ) ?></a></li>
												<?php endforeach ?>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'One provider and zero or more decorator class names separated by commas' ) ) ?>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Position' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-position" type="number" required="required" tabindex="1"
										name="<?= $this->formparam( array( 'item', 'plugin.position' ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Plugin position (required)' ) ) ?>"
										value="<?= $enc->attr( $this->get( 'itemData/plugin.position' ) ) ?>"
										<?= $this->site()->readonly( $this->get( 'itemData/plugin.siteid' ) ) ?> />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Execution order of the plugins' ) ) ?>
								</div>
							</div>
						</div><!--

						--><div class="col-xl-6 block <?= $this->site()->readonly( $this->get( 'itemData/plugin.siteid' ) ) ?>">
							<table class="item-config table">
								<thead>
									<tr>
										<th class="config-row-key">
											<span class="help"><?= $enc->html( $this->translate( 'admin', 'Option' ) ) ?></span>
											<div class="form-text text-muted help-text">
												<?= $enc->html( $this->translate( 'admin', 'Plugin provider or plugin decorator configuration name' ) ) ?>
											</div>
										</th>
										<th>
											<?= $enc->html( $this->translate( 'admin', 'Value' ) ) ?>
										</th class="config-row-value">
										<th class="actions">
											<?php if( !$this->site()->readonly( $this->get( 'itemData/plugin.siteid' ) ) ) : ?>
												<div class="btn act-add fa" tabindex="1"
													title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>">
												</div>
											<?php endif ?>
										</th>
									</tr>
								</thead>
								<tbody>

									<?php foreach( (array) $this->get( 'itemData/config/key', [] ) as $idx => $key ) : ?>
										<tr class="config-item">
											<td class="config-row-key">
												<input type="text" class="config-key form-control" tabindex="1"
													name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'key', '' ) ) ) ?>"
													value="<?= $enc->attr( $this->get( 'itemData/config/key/' . $idx, $key ) ) ?>"
													<?= $this->site()->readonly( $this->get( 'itemData/plugin.siteid' ) ) ?> />
												<div class="form-text text-muted help-text"></div>
											</td>
											<td class="config-row-value">
												<?php $cfgval = $this->get( 'itemData/config/val/' . $idx ) ?>
												<input type="text" class="config-value form-control config-type" tabindex="1"
													name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'val', '' ) ) ) ?>"
													value="<?= $enc->attr( $cfgval ) ?>"
													<?= $this->site()->readonly( $this->get( 'itemData/plugin.siteid' ) ) ?> />
											</td>
											<td class="actions">
												<?php if( !$this->site()->readonly( $this->get( 'itemData/plugin.siteid' ) ) ) : ?>
													<div class="btn act-delete fa" tabindex="1"
														title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>">
													</div>
												<?php endif ?>
											</td>
										</tr>
									<?php endforeach ?>

									<tr class="config-item prototype">
										<td class="config-row-key">
											<input type="text" class="config-key form-control" tabindex="1" disabled="disabled"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'key', '' ) ) ) ?>" />
											<div class="form-text text-muted help-text"></div>
										</td>
										<td class="config-row-value">

											<div class="config-type config-type-map">
												<input type="text" class="config-value form-control" tabindex="1" disabled="disabled"
													name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'val', '' ) ) ) ?>" />

												<table class="table config-map-table">
													<tr class="config-map-row prototype-map">
														<td class="config-map-actions">
															<div class="btn act-delete fa" tabindex="1"
																title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>">
															</div>
														</td>
														<td class="config-map-row-key">
															<input type="text" class="config-map-key form-control" tabindex="1" disabled="disabled" />
														</td>
														<td class="config-map-row-value">
															<input type="text" class="config-map-value form-control" tabindex="1" disabled="disabled" />
														</td>
													</tr>
													<tr class="config-map-actions">
														<td class="config-map-action-add">
															<div class="btn act-add fa" tabindex="1"
																title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry' ) ) ?>">
															</div>
														</td>
														<td class="config-map-action-update" colspan="2">
															<div class="btn btn-primary act-update" tabindex="1">
																<?= $enc->attr( $this->translate( 'admin', 'OK' ) ) ?>
															</div>
														</td>
													</tr>
												</table>
											</div>

											<div class="config-type config-type-list">
												<input type="text" class="config-value form-control" tabindex="1" disabled="disabled"
													name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'val', '' ) ) ) ?>" />

												<table class="table config-list-table">
													<tr class="config-list-row prototype-list">
														<td class="config-list-actions">
															<div class="btn act-delete fa" tabindex="1"
																title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>">
															</div>
														</td>
														<td class="config-list-row-value">
															<input type="text" class="config-list-value form-control" tabindex="1" disabled="disabled" />
														</td>
													</tr>
													<tr class="config-list-actions">
														<td class="config-list-action-add">
															<div class="btn act-add fa" tabindex="1"
																title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry' ) ) ?>">
															</div>
														</td>
														<td class="config-list-action-update">
															<div class="btn btn-primary act-update" tabindex="1">
																<?= $enc->attr( $this->translate( 'admin', 'OK' ) ) ?>
															</div>
														</td>
													</tr>
												</table>
											</div>

											<select class="config-value form-select config-type config-type-select" tabindex="1" disabled="disabled"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'val', '' ) ) ) ?>" >
											</select>

											<select class="config-value form-select config-type config-type-boolean" tabindex="1" disabled="disabled"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'val', '' ) ) ) ?>" >
												<option value=""></option>
												<option value="0"><?= $enc->html( $this->translate( 'client', 'no' ) ) ?></option>
												<option value="1"><?= $enc->html( $this->translate( 'client', 'yes' ) ) ?></option>
											</select>

											<input type="text" class="config-value form-control config-type config-type-string" tabindex="1" disabled="disabled"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'val', '' ) ) ) ?>" />

											<input type="number" class="config-value form-control config-type config-type-number" tabindex="1" disabled="disabled"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'val', '' ) ) ) ?>" step="0.01" />

											<input type="number" class="config-value form-control config-type config-type-integer" tabindex="1" disabled="disabled"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'val', '' ) ) ) ?>" />

											<input type="date" class="config-value form-control config-type config-type-date" tabindex="1" disabled="disabled"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'val', '' ) ) ) ?>" />

											<input type="datetime-local" class="config-value form-control config-type config-type-datetime" tabindex="1" disabled="disabled"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'val', '' ) ) ) ?>" />

											<input type="time" class="config-value form-control config-type config-type-time" tabindex="1" disabled="disabled"
												name="<?= $enc->attr( $this->formparam( array( 'item', 'config', 'val', '' ) ) ) ?>" />
										</td>
										<td class="actions">
											<div class="btn act-delete fa" tabindex="1"
												title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>">
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>

					</div>
				</div>
			</div>

			<?= $this->get( 'itemBody' ) ?>

		</div>

		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard' ), ['params' => $params] ) ?>
		</div>
	</div>
</form>

<?php $this->block()->stop() ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard' ) ) ?>
