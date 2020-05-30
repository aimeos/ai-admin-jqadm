<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2020
 */


/** admin/jqadm/locale/site/item/config/suggest
 * List of suggested configuration keys in locale site item panel
 *
 * Locale site items can store arbitrary key value pairs. This setting gives editors
 * a hint which config keys are available and are used in the templates.
 *
 * @param string List of suggested config keys
 * @since 2017.10
 * @category Developer
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
<?php $this->block()->start( 'jqadm_content' ); ?>

<form class="item item-locale-site form-horizontal" method="POST" enctype="multipart/form-data" action="<?= $enc->attr( $this->url( $target, $cntl, $action, $params, [], $config ) ); ?>">
	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'locale.site.id' ) ) ); ?>" value="<?= $enc->attr( $this->get( 'itemData/locale.site.id' ) ); ?>" />
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ); ?>" value="get" />
	<?= $this->csrf()->formfield(); ?>

	<nav class="main-navbar">
		<h1 class="navbar-brand">
			<span class="navbar-title"><?= $enc->html( $this->translate( 'admin', 'Site' ) ); ?></span>
			<span class="navbar-id"><?= $enc->html( $this->get( 'itemData/locale.site.id' ) ); ?></span>
			<span class="navbar-label"><?= $enc->html( $this->get( 'itemData/locale.site.label' ) ?: $this->translate( 'admin', 'New' ) ); ?></span>
		</h1>
		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard' ), ['params' => $params] ); ?>
		</div>
	</nav>

	<div class="row item-container">

		<div class="col-md-3 item-navbar">
			<div class="navbar-content">
				<ul class="nav nav-tabs flex-md-column flex-wrap d-flex justify-content-between" role="tablist">

					<li class="nav-item basic">
						<a class="nav-link active" href="#basic" data-toggle="tab" role="tab" aria-expanded="true" aria-controls="basic">
							<?= $enc->html( $this->translate( 'admin', 'Basic' ) ); ?>
						</a>
					</li>

					<?php foreach( array_values( $this->get( 'itemSubparts', [] ) ) as $idx => $subpart ) : ?>
						<li class="nav-item <?= $enc->attr( $subpart ); ?>">
							<a class="nav-link" href="#<?= $enc->attr( $subpart ); ?>" data-toggle="tab" role="tab" tabindex="<?= ++$idx + 1; ?>">
								<?= $enc->html( $this->translate( 'admin', $subpart ) ); ?>
							</a>
						</li>
					<?php endforeach; ?>

				</ul>

				<div class="item-meta text-muted">
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Modified' ) ); ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/locale.site.mtime' ) ); ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Created' ) ); ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/locale.site.ctime' ) ); ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Editor' ) ); ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/locale.site.editor' ) ); ?></span>
					</small>
				</div>
			</div>
		</div>

		<div class="col-md-9 item-content tab-content">
			<div id="basic" class="row item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic">

				<div class="col-xl-6 content-block">
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ); ?></label>
						<div class="col-sm-8">
							<select class="form-control custom-select item-status" required="required" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'locale.site.status' ) ) ); ?>" >
								<option value="">
									<?= $enc->attr( $this->translate( 'admin', 'Please select' ) ); ?>
								</option>
								<option value="1" <?= $selected( $this->get( 'itemData/locale.site.status', 1 ), 1 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ); ?>
								</option>
								<option value="0" <?= $selected( $this->get( 'itemData/locale.site.status', 1 ), 0 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ); ?>
								</option>
								<option value="-1" <?= $selected( $this->get( 'itemData/locale.site.status', 1 ), -1 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ); ?>
								</option>
								<option value="-2" <?= $selected( $this->get( 'itemData/locale.site.status', 1 ), -2 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ); ?>
								</option>
							</select>
						</div>
					</div>
					<div class="form-group row mandatory warning">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Code' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-code" required="required" tabindex="1" autocomplete="off"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'locale.site.code' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Unique site code (required)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/locale.site.code' ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Code to uniquely identify the site, renaming is dangerous!' ) ); ?>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-label" required="required" tabindex="1" autocomplete="off"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'locale.site.label' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Label (required)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/locale.site.label' ) ); ?>" />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Descritive name of the site' ) ); ?>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block vue-block"
					data-data="<?= $enc->attr( $this->get( 'itemData', new stdClass() ) ) ?>">

					<config-table inline-template v-bind:readonly="false"
						v-bind:items="data['config']" v-on:change="data['config'] = $event">

						<table class="item-config table table-striped">
							<thead>
								<tr>
									<th class="config-row-key">
										<span class="help"><?= $enc->html( $this->translate( 'admin', 'Option' ) ); ?></span>
										<div class="form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Site specific configuration options, will be available as key/value pairs' ) ); ?>
										</div>
									</th>
									<th class="config-row-value"><?= $enc->html( $this->translate( 'admin', 'Value' ) ); ?></th>
									<th class="actions">
										<div v-if="!readonly" class="btn act-add fa" tabindex="1" v-on:click="add()"
											title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ); ?>"></div>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(entry, pos) in items" v-bind:key="pos" class="config-item">
									<td class="config-row-key">
										<input is="auto-complete" required class="form-control" v-bind:readonly="readonly" tabindex="1"
											v-bind:name="'<?= $enc->attr( $this->formparam( array( 'item', 'config', '_pos_', 'key' ) ) ); ?>'.replace('_pos_', pos)"
											v-bind:keys="JSON.parse('<?= $enc->attr( $this->config( 'admin/jqadm/locale/site/item/config/suggest', [] ) ) ?>')"
											v-model="entry.key" />
									</td>
									<td class="config-row-value">
										<input class="form-control" v-bind:readonly="readonly" tabindex="1"
											v-bind:name="'<?= $enc->attr( $this->formparam( array( 'item', 'config', '_pos_', 'val' ) ) ); ?>'.replace('_pos_', pos)"
											v-model="entry.val" />
									</td>
									<td class="actions">
										<div v-if="!readonly" class="btn act-delete fa" tabindex="1" v-on:click="remove(pos)"
											title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ); ?>"></div>
									</td>
								</tr>
							</tbody>
						</table>
					</config-table>
				</div>

			</div>

			<?= $this->get( 'itemBody' ); ?>

		</div>

		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard' ), ['params' => $params] ); ?>
		</div>
	</div>
</form>

<?php $this->block()->stop(); ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard' ) ); ?>
