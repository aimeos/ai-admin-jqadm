<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */

$attr = function( $list, $key, $code ) {
	$map = ( isset( $list[$key] ) ? $list[$key]->toArray() : [] );
	return ( isset( $map[$code] ) ? $map[$code] : '' );
};

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$enc = $this->encoder();
$attributes = $this->get( 'itemAttributes', [] );
$params = $this->get( 'pageParams', [] );


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<form class="item item-coupon form-horizontal container-fluid" method="POST" enctype="multipart/form-data" action="<?= $enc->attr( $this->link( 'admin/jqadm/url/save', $params ) ) ?>">
	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'coupon.id' ) ) ) ?>" value="<?= $enc->attr( $this->get( 'itemData/coupon.id' ) ) ?>">
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ) ?>" value="get">
	<?= $this->csrf()->formfield() ?>

	<nav class="main-navbar">
		<h1 class="navbar-brand">
			<span class="navbar-title"><?= $enc->html( $this->translate( 'admin', 'Discount' ) ) ?></span>
			<span class="navbar-id"><?= $enc->html( $this->get( 'itemData/coupon.id' ) ) ?></span>
			<span class="navbar-label"><?= $enc->html( $this->get( 'itemData/coupon.label' ) ?: $this->translate( 'admin', 'New' ) ) ?></span>
			<span class="navbar-site"><?= $enc->html( $this->site()->match( $this->get( 'itemData/coupon.siteid' ) ) ) ?></span>
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
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/coupon.mtime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/coupon.ctime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/coupon.editor' ) ) ?></span>
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

				<div class="box <?= $this->site()->mismatch( $this->get( 'itemData/coupon.siteid' ) ) ?>">
					<div class="row">
						<div class="col-xl-6 block">
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></label>
								<div class="col-sm-8">
									<select class="form-select item-status" required="required" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'coupon.status' ) ) ) ?>"
										:readonly="!can('change')">
										<option value="">
											<?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?>
										</option>
										<option value="1" <?= $selected( $this->get( 'itemData/coupon.status', 1 ), 1 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?>
										</option>
										<option value="0" <?= $selected( $this->get( 'itemData/coupon.status', 1 ), 0 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?>
										</option>
										<option value="-1" <?= $selected( $this->get( 'itemData/coupon.status', 1 ), -1 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?>
										</option>
										<option value="-2" <?= $selected( $this->get( 'itemData/coupon.status', 1 ), -2 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?>
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-label" type="text" required="required" tabindex="1"
										name="<?= $this->formparam( array( 'item', 'coupon.label' ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ) ?>"
										value="<?= $enc->attr( $this->get( 'itemData/coupon.label' ) ) ?>"
										:readonly="!can('change')">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Internal article name, will be used on the web site if no product name for the language is available' ) ) ?>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Provider' ) ) ?></label>
								<div class="col-sm-8">
									<div class="input-group provider">
										<Multiselect class="item-provider form-control"
											name="<?= $this->formparam( array( 'item', 'coupon.provider' ) ) ?>"
											v-model="item['coupon.provider']"
											:title="item['coupon.provider']"
											:disabled="!can('change')"
											:native-support="true"
											:can-deselect="false"
											:options="providers"
											:can-clear="false"
											:allow-absent="true"
											:required="true"
										>
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
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ) ?></label>
								<div class="col-sm-8">
									<input is="flat-pickr" class="form-control item-datestart select" type="datetime-local" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'coupon.datestart' ) ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
										:value="item['coupon.datestart']"
										:disabled="!can('change')"
										:config="Aimeos.flatpickr.datetime">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The article is only shown on the web site after that date and time, useful or seasonal articles' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?></label>
								<div class="col-sm-8">
									<input is="flat-pickr" class="form-control item-dateend select" type="datetime-local" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'coupon.dateend' ) ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
										:value="item['coupon.dateend']"
										:disabled="!can('change')"
										:config="Aimeos.flatpickr.datetime">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'The article is only shown on the web site until that date and time, useful or seasonal articles' ) ) ?>
								</div>
							</div>
						</div><!--

						--><div class="col-xl-6 block">

							<config-table tabindex="1"
								@update:items="item['config'] = $event"
								v-bind:items="item['config'] || []"
								v-bind:readonly="!can('change')"
								v-bind:keys="config(item['coupon.provider'])"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'item', 'config', '_pos_', '_key_' ) ) ) ?>`"
								v-bind:i18n="{
									value: `<?= $enc->js( $this->translate( 'admin', 'Value' ) ) ?>`,
									option: `<?= $enc->js( $this->translate( 'admin', 'Option' ) ) ?>`,
									help: `<?= $enc->js( $this->translate( 'admin', 'Coupon provider or coupon decorator configuration name' ) ) ?>`,
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
