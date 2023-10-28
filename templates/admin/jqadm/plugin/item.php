<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */

$attr = function( $list, $key, $code ) {
	$map = ( isset( $list[$key] ) ? $list[$key]->toArray() : [] );
	return ( isset( $map[$code] ) ? $map[$code] : '' );
};

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};
$attributes = $this->get( 'itemAttributes', [] );
$params = $this->get( 'pageParams', [] );

$enc = $this->encoder();


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<form class="item item-plugin form-horizontal container-fluid" method="POST" enctype="multipart/form-data"
	action="<?= $enc->attr( $this->link( 'admin/jqadm/url/save', $params ) ) ?>">
	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'plugin.id' ) ) ) ?>"
		value="<?= $enc->attr( $this->get( 'itemData/plugin.id' ) ) ?>">
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ) ?>" value="get">
	<?= $this->csrf()->formfield() ?>

	<nav class="main-navbar">
		<h1 class="navbar-brand">
			<span class="navbar-title"><?= $enc->html( $this->translate( 'admin', 'Plugin' ) ) ?></span>
			<span class="navbar-id"><?= $enc->html( $this->get( 'itemData/plugin.id' ) ) ?></span>
			<span class="navbar-label"><?= $enc->html( $this->get( 'itemData/plugin.label' ) ?: $this->translate( 'admin', 'New' ) ) ?></span>
			<span class="navbar-site"><?= $enc->html( $this->site()->match( $this->get( 'itemData/plugin.siteid' ) ) ) ?></span>
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

			<div id="basic" class="item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic"
				data-decorators="<?= $enc->attr( $this->get( 'itemDecorators', [] ) ) ?>"
				data-providers="<?= $enc->attr( $this->get( 'itemProviders', [] ) ) ?>"
				data-item="<?= $enc->attr( $this->get( 'itemData', [] ) ) ?>"
				data-siteid="<?= $enc->attr( $this->site()->siteid() ) ?>">

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
									value="<?= $enc->attr( $types->getCode()->first() ) ?>">
							<?php endif ?>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-label" type="text" required="required" tabindex="1"
										name="<?= $this->formparam( array( 'item', 'plugin.label' ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ) ?>"
										value="<?= $enc->attr( $this->get( 'itemData/plugin.label' ) ) ?>"
										<?= $this->site()->readonly( $this->get( 'itemData/plugin.siteid' ) ) ?>>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Plugin label for describing the configured plugin' ) ) ?>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Provider' ) ) ?></label>
								<div class="col-sm-8">
									<div class="input-group">
										<Multiselect class="item-provider"
											name="<?= $this->formparam( array( 'item', 'plugin.provider' ) ) ?>"
											v-model="item['plugin.provider']"
											:title="item['plugin.provider']"
											:disabled="!can('change')"
											:native-support="true"
											:can-deselect="false"
											:options="providers[item['plugin.type']] || []"
											:can-clear="false"
											:allow-absent="true"
											:required="true"
										><input class="form-control">
										</Multiselect>
										<div v-if="can('change')" class="dropdown input-group-end">
											<div class="btn act-add fa" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
											<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="decoratorButton">
												<li v-for="(name, idx) in decorators" :key="idx" class="dropdown-item">
													<a class="decorator-name" href="#" @click="decorate(name)">{{ name }}</a>
												</li>
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
										<?= $this->site()->readonly( $this->get( 'itemData/plugin.siteid' ) ) ?>>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Execution order of the plugins' ) ) ?>
								</div>
							</div>
						</div><!--

						--><div class="col-xl-6 block <?= $this->site()->readonly( $this->get( 'itemData/plugin.siteid' ) ) ?>">
							<config-table tabindex="1"
								@update:items="item['config'] = $event"
								v-bind:items="item['config'] || []"
								v-bind:readonly="!can('change')"
								v-bind:keys="config(item['plugin.provider'], item['plugin.type'])"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'item', 'config', '_pos_', '_key_' ) ) ) ?>`"
								v-bind:i18n="{
									value: `<?= $enc->js( $this->translate( 'admin', 'Value' ) ) ?>`,
									option: `<?= $enc->js( $this->translate( 'admin', 'Option' ) ) ?>`,
									help: `<?= $enc->js( $this->translate( 'admin', 'Plugin provider or plugin decorator configuration name' ) ) ?>`,
									insert: `<?= $enc->js( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>`,
									delete: `<?= $enc->js( $this->translate( 'admin', 'Delete this entry' ) ) ?>`,
								}">
								<table class="item-config table">
									<thead>
										<tr>
											<th class="config-row-key"><span class="help"><?= $enc->html( $this->translate( 'admin', 'Option' ) ) ?></span></th>
											<th class="config-row-value"><?= $enc->html( $this->translate( 'admin', 'Value' ) ) ?></th>
											<th class="actions"><div class="btn act-add fa"></div></th>
										</tr>
									</thead>
								</table>
							</config-table>
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
