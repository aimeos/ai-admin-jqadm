<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */

$enc = $this->encoder();


/** admin/jqadm/locale/site/fields
 * List of locale columns that should be displayed in the list view
 *
 * Changes the list of locale columns shown by default in the locale list view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "locale.site.id" for the locale ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.10
 */
$default = ['locale.site.status', 'locale.site.code', 'locale.site.label', 'locale.site.config'];
$default = $this->config( 'admin/jqadm/locale/site/fields', $default );
$fields = $this->session( 'aimeos/admin/jqadm/locale/site/fields', $default );

$searchParams = $params = $this->get( 'pageParams', [] );
$searchParams['page']['start'] = 0;

$searchAttributes = map( $this->get( 'filterAttributes', [] ) )->filter( function( $item ) {
	return $item->isPublic();
} )->call( 'toArray' )->each( function( &$val ) {
	$val = $this->translate( 'admin/ext', $val['label'] ?? ' ' );
} )->all();

$operators = map( $this->get( 'filterOperators/compare', [] ) )->flip()->map( function( $val, $key ) {
	return $this->translate( 'admin/ext', $key );
} )->all();

$columnList = [
	'locale.site.id' => $this->translate( 'admin', 'ID' ),
	'locale.site.status' => $this->translate( 'admin', 'Status' ),
	'locale.site.code' => $this->translate( 'admin', 'Code' ),
	'locale.site.label' => $this->translate( 'admin', 'Label' ),
	'locale.site.config' => $this->translate( 'admin', 'Config' ),
	'locale.site.ctime' => $this->translate( 'admin', 'Created' ),
	'locale.site.mtime' => $this->translate( 'admin', 'Modified' ),
	'locale.site.editor' => $this->translate( 'admin', 'Editor' ),
];


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<?= $this->partial( $this->config( 'admin/jqadm/partial/navsearch', 'navsearch' ) ) ?>
<?= $this->partial( $this->config( 'admin/jqadm/partial/columns', 'columns' ) ) ?>


<div class="list-view"
	data-domain="locale/site"
	data-siteid="<?= $enc->attr( $this->site()->siteid() ) ?>"
	data-filter="<?= $enc->attr( $this->session( 'aimeos/admin/jqadm/locale/site/filter', new \stdClass ) ) ?>"
	data-items="<?= $enc->attr( $this->get( 'items', map() )->call( 'toArray', [true] )->all() ) ?>">

	<nav class="main-navbar">

		<span class="navbar-brand">
			<?= $enc->html( $this->translate( 'admin', 'Sites' ) ) ?>
		</span>

		<div class="btn fa act-search" v-on:click="search = true"
			title="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>"
			aria-label="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>">
		</div>
	</nav>

	<nav-search v-bind:show="search" v-on:close="search = false"
		v-bind:url="`<?= $enc->js( $this->link( 'admin/jqadm/url/search', map( $searchParams )->except( 'filter' )->all() ) ) ?>`"
		v-bind:filter="<?= $enc->attr( (object) $this->session( 'aimeos/admin/jqadm/locale/site/filter', new \stdClass ) ) ?>"
		v-bind:operators="<?= $enc->attr( $operators ) ?>"
		v-bind:name="`<?= $enc->js( $this->formparam( ['filter', '_key_', '0'] ) ) ?>`"
		v-bind:attributes="<?= $enc->attr( $searchAttributes ) ?>">
	</nav-search>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
			['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/locale/site/page', [] )]
		);
	?>

	<form ref="form" class="list list-locale-site" method="POST"
		action="<?= $enc->attr( $this->link( 'admin/jqadm/url/search', $searchParams ) ) ?>">

		<?= $this->csrf()->formfield() ?>

		<column-select tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
			name="<?= $enc->attr( $this->formparam( ['fields', ''] ) ) ?>"
			v-bind:titles="<?= $enc->attr( $columnList ) ?>"
			v-bind:fields="<?= $enc->attr( $fields ) ?>"
			v-bind:show="columns"
			v-on:close="columns = false">
		</column-select>

		<div class="table-responsive">
			<table class="list-items table table-hover table-striped">
				<thead class="list-header">
					<tr>
						<?php if( $this->access( 'super' ) ) : ?>
							<th class="select">
								<button class="btn icon-menu" type="button" data-bs-toggle="dropdown"
									aria-expanded="false" title="<?= $enc->attr( $this->translate( 'admin', 'Menu' ) ) ?>">
								</button>
								<ul class="dropdown-menu">
									<li>
										<a class="btn" v-on:click.prevent="batch = true" href="#" tabindex="1">
											<?= $enc->html( $this->translate( 'admin', 'Edit' ) ) ?>
										</a>
									</li>
									<li>
										<a class="btn" v-on:click.prevent="askDelete(null, $event)" tabindex="1"
											href="<?= $enc->attr( $this->link( 'admin/jqadm/url/delete', $params ) ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Delete' ) ) ?>
										</a>
									</li>
								</ul>
							</th>
						<?php endif ?>

						<?= $this->partial(
								$this->config( 'admin/jqadm/partial/listhead', 'listhead' ),
								['fields' => $fields, 'params' => $params, 'data' => $columnList, 'sort' => $this->session( 'aimeos/admin/jqadm/locale/site/sort' )]
							);
						?>

						<th class="actions">
							<?php if( $this->access( 'super' ) ) : ?>
								<a class="btn fa act-add" tabindex="1"
									href="<?= $enc->attr( $this->link( 'admin/jqadm/url/create', $params ) ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
									aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ) ?>">
								</a>
							<?php endif ?>

							<a class="btn act-columns fa" href="#" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Columns' ) ) ?>"
								v-on:click.prevent.stop="columns = true">
							</a>
						</th>
					</tr>
				</thead>
				<tbody>
					<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listsearch', 'listsearch' ), [
							'fields' => $this->access( 'super' ) ? array_merge( $fields, ['select'] ) : $fields,
							'filter' => $this->session( 'aimeos/admin/jqadm/locale/site/filter', [] ),
							'data' => [
								'locale.site.id' => ['op' => '=='],
								'locale.site.status' => ['op' => '==', 'type' => 'select', 'val' => [
									'1' => $this->translate( 'mshop/code', 'status:1' ),
									'0' => $this->translate( 'mshop/code', 'status:0' ),
									'-1' => $this->translate( 'mshop/code', 'status:-1' ),
									'-2' => $this->translate( 'mshop/code', 'status:-2' ),
								]],
								'locale.site.code' => [],
								'locale.site.label' => [],
								'locale.site.config' => ['op' => '~='],
								'locale.site.ctime' => ['op' => '-', 'type' => 'datetime-local'],
								'locale.site.mtime' => ['op' => '-', 'type' => 'datetime-local'],
								'locale.site.editor' => [],
							]
						] );
					?>

					<?php if( $this->access( 'super' ) ) : ?>
						<tr class="batch" style="display: none" v-show="batch">
							<td colspan="<?= count( $fields ) + 2 ?>">
								<div class="batch-header">
									<div class="intro">
										<span class="name"><?= $enc->html( $this->translate( 'admin', 'Bulk edit' ) ) ?></span>
										<span class="count">{{ selected }} <?= $enc->html( $this->translate( 'admin', 'selected' ) ) ?></span>
									</div>
									<a class="btn btn-secondary" href="#" v-on:click.prevent="batch = false">
										<?= $enc->html( $this->translate( 'admin', 'Close' ) ) ?>
									</a>
								</div>
								<div class="card">
									<div class="card-header">
										<span><?= $enc->html( $this->translate( 'admin', 'Basic' ) ) ?></span>
										<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'locale/site'] ) ) ?>">
											<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
										</button>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-lg-6">
												<div class="row">
													<div class="col-1">
														<input id="batch-locale-site-status" class="form-check-input" type="checkbox" v-on:click="setState('item/locale.site.status')">
													</div>
													<label class="col-4 form-control-label" for="batch-locale-site-status">
														<?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?>
													</label>
													<div class="col-7">
														<select class="form-select" v-bind:disabled="state('item/locale.site.status')"
															name="<?= $enc->attr( $this->formparam( array( 'item', 'locale.site.status' ) ) ) ?>">
															<option value=""></option>
															<option value="1"><?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?></option>
															<option value="0"><?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?></option>
															<option value="-1"><?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?></option>
															<option value="-2"><?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?></option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-lg-6">
											</div>
										</div>
									</div>
								</div>
								<div class="batch-footer">
									<a class="btn btn-secondary" href="#" v-on:click.prevent="batch = false">
										<?= $enc->html( $this->translate( 'admin', 'Close' ) ) ?>
									</a>
									<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'locale/site'] ) ) ?>">
										<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
									</button>
								</div>
							</td>
						</tr>
					<?php endif ?>

					<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
						<?php $url = $enc->attr( $this->link( 'admin/jqadm/url/get', ['id' => $id] + $params ) ) ?>
						<tr class="list-item" data-label="<?= $enc->attr( $item->getLabel() ) ?>">
							<?php if( $this->access( 'super' ) ) : ?>
								<td class="select">
								<input class="form-check-input" type="checkbox" tabindex="1"
									name="<?= $enc->attr( $this->formparam( ['id', ''] ) ) ?>"
									value="<?= $enc->attr( $item->getId() ) ?>"
									v-on:click="toggle(`<?= $enc->js( $id ) ?>`)"
									v-bind:checked="checked(`<?= $enc->js( $id ) ?>`)">
							</td>
							<?php endif ?>
							<?php if( in_array( 'locale.site.id', $fields ) ) : ?>
								<td class="locale-site-id"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'locale.site.status', $fields ) ) : ?>
								<td class="locale-site-status"><a class="items-field" href="<?= $url ?>"><div class="fa status-<?= $enc->attr( $item->getStatus() ) ?>"></div></a></td>
							<?php endif ?>
							<?php if( in_array( 'locale.site.code', $fields ) ) : ?>
								<td class="locale-site-code"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getCode() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'locale.site.label', $fields ) ) : ?>
								<td class="locale-site-label"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getLabel() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'locale.site.config', $fields ) ) : ?>
								<td class="locale.site-config config-item">
									<a class="items-field" href="<?= $url ?>">
										<?php foreach( $item->getConfig() as $key => $value ) : ?>
											<span class="config-key"><?= $enc->html( $key ) ?></span>
											<span class="config-value"><?= $enc->html( $value ) ?></span>
											<br>
										<?php endforeach ?>
									</a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'locale.site.ctime', $fields ) ) : ?>
								<td class="locale-site-ctime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeCreated() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'locale.site.mtime', $fields ) ) : ?>
								<td class="locale-site-mtime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeModified() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'locale.site.editor', $fields ) ) : ?>
								<td class="locale-site-editor"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->editor() ) ?></a></td>
							<?php endif ?>

							<td class="actions">
								<?php if( $this->access( 'super' ) ) : ?>
									<a class="btn act-copy fa" tabindex="1"
										href="<?= $enc->attr( $this->link( 'admin/jqadm/url/copy', ['id' => $id] + $params ) ) ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry' ) ) ?>"
										aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ) ?>">
									</a>
									<a class="btn act-delete fa" tabindex="1"
										v-on:click.prevent.stop="askDelete(`<?= $enc->js( $id ) ?>`, $event)"
										href="<?= $enc->attr( $this->link( 'admin/jqadm/url/delete', $params ) ) ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
										aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ) ?>">
									</a>
								<?php endif ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>

		<?php if( $this->get( 'items', map() )->isEmpty() ) : ?>
			<div class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ) ?></div>
		<?php endif ?>
	</form>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
			['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/locale/site/page', [] )]
		);
	?>

	<confirm-delete v-bind:items="unconfirmed" v-bind:show="dialog"
		v-on:close="confirmDelete(false)" v-on:confirm="confirmDelete(true)">
	</confirm-delete>

</div>
<?php $this->block()->stop() ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'page' ) ) ?>
