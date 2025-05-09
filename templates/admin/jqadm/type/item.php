<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2025
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$enc = $this->encoder();
$params = $this->get( 'pageParams', [] );


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<form class="item item-type form-horizontal container-fluid" method="POST" enctype="multipart/form-data" action="<?= $enc->attr( $this->link( 'admin/jqadm/url/save', $params ) ) ?>">
	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'type.id' ) ) ) ?>" value="<?= $enc->attr( $this->get( 'itemData/type.id' ) ) ?>">
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ) ?>" value="get">
	<?= $this->csrf()->formfield() ?>

	<nav class="main-navbar">
		<h1 class="navbar-brand">
			<span class="navbar-title"><?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?></span>
			<span class="navbar-id"><?= $enc->html( $this->get( 'itemData/type.id' ) ) ?></span>
			<span class="navbar-label"><?= $enc->html( $this->get( 'itemData/type.label' ) ?: $this->translate( 'admin', 'New' ) ) ?></span>
			<span class="navbar-site"><?= $enc->html( $this->site()->match( $this->get( 'itemData/type.siteid' ) ) ) ?></span>
		</h1>
		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'itemactions' ), ['params' => $params] ) ?>
		</div>
	</nav>

	<div class="row item-container">

		<div class="col-xl-3 item-navbar">
			<div class="navbar-content" v-bind:class="{show: show}">
				<ul class="nav nav-tabs flex-xl-column flex-wrap d-flex box" role="tablist">
					<li class="nav-item basic">
						<a class="nav-link active" href="#basic" v-on:click="url(`basic`)"
							data-bs-toggle="tab" role="tab" aria-expanded="true" aria-controls="basic">
							<?= $enc->html( $this->translate( 'admin', 'Basic' ) ) ?>
						</a>
					</li>

					<?php foreach( array_values( $this->get( 'itemSubparts', [] ) ) as $idx => $subpart ) : ?>
						<li class="nav-item <?= $enc->attr( $subpart ) ?>">
							<a class="nav-link" href="#<?= $enc->attr( $subpart ) ?>" v-on:click="url(`<?= $enc->js( $subpart ) ?>`)"
								data-bs-toggle="tab" role="tab" tabindex="<?= ++$idx + 1 ?>">
								<?= $enc->html( $this->translate( 'admin', $subpart ) ) ?>
							</a>
						</li>
					<?php endforeach ?>
				</ul>

				<div class="item-meta text-muted">
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Modified' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/type.mtime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/type.ctime' ) ) ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/type.editor' ) ) ?></span>
					</small>
				</div>

				<div class="icon more" v-bind:class="{less: show}" v-on:click="toggle()"></div>
			</div>
		</div>

		<div class="col-xl-9 item-content tab-content">

			<div id="basic" class="item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic">

				<div class="vue box" v-bind:class="{mismatch: !can('match')}"
					data-data="<?= $enc->attr( $this->get( 'itemData' ) ) ?>"
					data-siteid="<?= $enc->attr( $this->site()->siteid() ) ?>"
					data-domain="type">

					<div class="row">

						<div class="col-xl-6 block">
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Domain' ) ) ?></label>
								<div class="col-sm-8">
									<select class="form-select item-domain" required="required" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'type.domain' ) ) ) ?>"
										:readonly="!can('change')" >
										<option value="">
											<?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?>
										</option>

										<?php foreach( $this->get( 'itemDomains', [] ) as $domain => $label ) : ?>
											<option value="<?= $enc->attr( $domain ) ?>" <?= $selected( $this->get( 'itemData/type.domain', $domain ), $domain ) ?> >
												<?= $enc->html( $label ) ?>
											</option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?></label>
								<div class="col-sm-8">
									<select class="form-select item-status" required="required" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'type.status' ) ) ) ?>"
										:readonly="!can('change')" >
										<option value="">
											<?= $enc->html( $this->translate( 'admin', 'Please select' ) ) ?>
										</option>
										<option value="1" <?= $selected( $this->get( 'itemData/type.status', 1 ), 1 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?>
										</option>
										<option value="0" <?= $selected( $this->get( 'itemData/type.status', 1 ), 0 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?>
										</option>
										<option value="-1" <?= $selected( $this->get( 'itemData/type.status', 1 ), -1 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?>
										</option>
										<option value="-2" <?= $selected( $this->get( 'itemData/type.status', 1 ), -2 ) ?> >
											<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?>
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Code' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-code" type="text" required="required" tabindex="1"
										name="<?= $enc->attr( $this->formparam( array( 'item', 'type.code' ) ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Unique type code (required)' ) ) ?>"
										value="<?= $enc->attr( $this->get( 'itemData/type.code' ) ) ?>"
										:readonly="!can('change')">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Unique type code' ) ) ?>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-label" type="text" required="required" tabindex="1"
										name="<?= $this->formparam( array( 'item', 'type.label' ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ) ?>"
										value="<?= $enc->attr( $this->get( 'itemData/type.label' ) ) ?>"
										:readonly="!can('change')">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Internal type name shown in the administration interface' ) ) ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Position' ) ) ?></label>
								<div class="col-sm-8">
									<input class="form-control item-position" type="number" step="1" tabindex="1"
										name="<?= $this->formparam( array( 'item', 'type.position' ) ) ?>"
										value="<?= $enc->attr( $this->get( 'itemData/type.position' ) ) ?>"
										placeholder="<?= $enc->attr( $this->translate( 'admin', 'Type position (optional)' ) ) ?>"
										:readonly="!can('change')">
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Order of the types in the frontend' ) ) ?>
								</div>
							</div>
						</div>

						<div class="col-xl-6 block">
							<translations tabindex="1"
								v-model="dataset['type.i18n']"
								:name="`<?= $enc->js( $this->formparam( array( 'item', 'type.i18n' ) ) ) ?>`"
								:readonly="!can('change')"
								:langs="<?= $enc->attr( $this->get( 'pageLangItems', map() )->col( 'locale.language.label', 'locale.language.id' ) ) ?>"
								:i18n="{
									header: `<?= $enc->js( $this->translate( 'admin', 'Translations' ) ) ?>`,
									select: `<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`,
									insert: `<?= $enc->js( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>`,
									delete: `<?= $enc->js( $this->translate( 'admin', 'Delete this entry' ) ) ?>`,
								}">
								<table class="table translations">
									<tr>
										<th colspan="2" class="head"><?= $enc->html( $this->translate( 'admin', 'Translations' ) ) ?></th>
										<th class="action"><div class="btn icon"><div></th>
									</tr>
								</table>
							</translations>
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
