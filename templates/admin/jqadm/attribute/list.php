<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2024
 */

$enc = $this->encoder();


/** admin/jqadm/attribute/fields
 * List of attribute columns that should be displayed in the list view
 *
 * Changes the list of attribute columns shown by default in the attribute list view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "attribute.id" for the attribute ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.07
 */
$default = ['attribute.status', 'attribute.type', 'attribute.code', 'attribute.label'];
$default = $this->config( 'admin/jqadm/attribute/fields', $default );
$fields = $this->session( 'aimeos/admin/jqadm/attribute/fields', $default );

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

$typeList = $this->get( 'itemTypes', map() )->col( 'attribute.type.code', 'attribute.type.code' )->all();

$columnList = [
	'attribute.id' => $this->translate( 'admin', 'ID' ),
	'attribute.domain' => $this->translate( 'admin', 'Domain' ),
	'attribute.status' => $this->translate( 'admin', 'Status' ),
	'attribute.type' => $this->translate( 'admin', 'Type' ),
	'attribute.code' => $this->translate( 'admin', 'Code' ),
	'attribute.label' => $this->translate( 'admin', 'Label' ),
	'attribute.position' => $this->translate( 'admin', 'Position' ),
	'attribute.ctime' => $this->translate( 'admin', 'Created' ),
	'attribute.mtime' => $this->translate( 'admin', 'Modified' ),
	'attribute.editor' => $this->translate( 'admin', 'Editor' ),
];

?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<?= $this->partial( $this->config( 'admin/jqadm/partial/navsearch', 'navsearch' ) ) ?>
<?= $this->partial( $this->config( 'admin/jqadm/partial/columns', 'columns' ) ) ?>


<div class="list-view"
	data-domain="attribute"
	data-siteid="<?= $enc->attr( $this->site()->siteid() ) ?>"
	data-filter="<?= $enc->attr( $this->session( 'aimeos/admin/jqadm/attribute/filter', new \stdClass ) ) ?>"
	data-items="<?= $enc->attr( $this->get( 'items', map() )->call( 'toArray', [true] )->all() ) ?>">

	<nav class="main-navbar">

		<span class="navbar-brand">
			<?= $enc->html( $this->translate( 'admin', 'Attribute' ) ) ?>
			<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ) ?>)</span>
		</span>

		<div class="btn icon act-search" v-on:click="search = true"
			title="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>"
			aria-label="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>">
		</div>
	</nav>

	<nav-search v-bind:show="search" v-on:close="search = false"
		v-bind:url="`<?= $enc->js( $this->link( 'admin/jqadm/url/search', map( $searchParams )->except( 'filter' )->all() ) ) ?>`"
		v-bind:filter="<?= $enc->attr( (object) $this->session( 'aimeos/admin/jqadm/attribute/filter', new \stdClass ) ) ?>"
		v-bind:operators="<?= $enc->attr( $operators ) ?>"
		v-bind:name="`<?= $enc->js( $this->formparam( ['filter', '_key_', '0'] ) ) ?>`"
		v-bind:attributes="<?= $enc->attr( $searchAttributes ) ?>">
	</nav-search>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
			['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/attribute/page', [] )]
		);
	?>

	<form ref="form" class="list list-attribute" method="POST"
		action="<?= $enc->attr( $this->link( 'admin/jqadm/url/search', $searchParams ) ) ?>">

		<?= $this->csrf()->formfield() ?>

		<column-select tabindex="1"
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
						<th class="select">
							<button class="btn icon-menu" type="button" data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}'
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

						<?= $this->partial(
								$this->config( 'admin/jqadm/partial/listhead', 'listhead' ),
								['fields' => $fields, 'params' => $params, 'data' => $columnList, 'sort' => $this->session( 'aimeos/admin/jqadm/attribute/sort' )]
							);
						?>

						<th class="actions">
							<a class="btn icon act-add" tabindex="1"
								href="<?= $enc->attr( $this->link( 'admin/jqadm/url/create', $params ) ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ) ?>">
							</a>

							<a class="btn act-columns icon" href="#" tabindex="1"
								title="<?= $enc->attr( $this->translate( 'admin', 'Columns' ) ) ?>"
								v-on:click.prevent="columns = true">
							</a>
						</th>
					</tr>
				</thead>
				<tbody>

					<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listsearch', 'listsearch' ), [
							'fields' => array_merge( $fields, ['select'] ), 'filter' => $this->session( 'aimeos/admin/jqadm/attribute/filter', [] ),
							'data' => [
								'attribute.id' => ['op' => '=='],
								'attribute.domain' => ['op' => '==', 'type' => 'select', 'val' => [
									'product' => $this->translate( 'admin', 'product' ),
									'catalog' => $this->translate( 'admin', 'catalog' ),
									'media' => $this->translate( 'admin', 'media' ),
									'text' => $this->translate( 'admin', 'text' ),
								]],
								'attribute.status' => ['op' => '==', 'type' => 'select', 'val' => [
									'1' => $this->translate( 'mshop/code', 'status:1' ),
									'0' => $this->translate( 'mshop/code', 'status:0' ),
									'-1' => $this->translate( 'mshop/code', 'status:-1' ),
									'-2' => $this->translate( 'mshop/code', 'status:-2' ),
								]],
								'attribute.type' => ['op' => '==', 'type' => 'select', 'val' => $typeList],
								'attribute.code' => [],
								'attribute.label' => [],
								'attribute.position' => ['op' => '=='],
								'attribute.ctime' => ['op' => '-', 'type' => 'datetime-local'],
								'attribute.mtime' => ['op' => '-', 'type' => 'datetime-local'],
								'attribute.editor' => [],
							]
						] );
					?>

					<tr class="batch" v-bind:class="{show: batch}" v-show="batch">
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
									<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'attribute'] ) ) ?>">
										<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
									</button>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-xl-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-attribute-domain" class="form-check-input" type="checkbox" v-on:click="setState('item/attribute.domain')">
												</div>
												<label class="col-4 form-control-label" for="batch-attribute-domain">
													<?= $enc->html( $this->translate( 'admin', 'Domain' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select" v-bind:disabled="state('item/attribute.domain')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'attribute.domain' ) ) ) ?>">
														<option value=""></option>
														<?php foreach( ['product', 'media', 'text', 'catalog'] as $domain ) : ?>
															<option value="<?= $enc->attr( $domain ) ?>">
																<?= $enc->html( $this->translate( 'admin', $domain ) ) ?>
															</option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-attribute-status" class="form-check-input" type="checkbox" v-on:click="setState('item/attribute.status')">
												</div>
												<label class="col-4 form-control-label" for="batch-attribute-status">
													<?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select" v-bind:disabled="state('item/attribute.status')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'attribute.status' ) ) ) ?>">
														<option value=""></option>
														<option value="1"><?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?></option>
														<option value="0"><?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?></option>
														<option value="-1"><?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?></option>
														<option value="-2"><?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?></option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-xl-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-attribute-type" class="form-check-input" type="checkbox" v-on:click="setState('item/attribute.type')">
												</div>
												<label class="col-4 form-control-label" for="batch-attribute-type">
													<?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select" v-bind:disabled="state('item/attribute.type')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'attribute.type' ) ) ) ?>">
														<option value=""></option>
														<?php foreach( $this->get( 'itemTypes', [] ) as $item ) : ?>
															<option value="<?= $enc->attr( $item->getCode() ) ?>">
																<?= $enc->html( $item->getLabel() ) ?>
															</option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-attribute-position" class="form-check-input" type="checkbox" v-on:click="setState('item/attribute.position')">
												</div>
												<label class="col-4 form-control-label" for="batch-attribute-position">
													<?= $enc->html( $this->translate( 'admin', 'Position' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="number" v-bind:disabled="state('item/attribute.position')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'attribute.position' ) ) ) ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="batch-footer">
								<a class="btn btn-secondary" href="#" v-on:click.prevent="batch = false">
									<?= $enc->html( $this->translate( 'admin', 'Close' ) ) ?>
								</a>
								<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'attribute'] ) ) ?>">
									<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
								</button>
							</div>
						</td>
					</tr>

					<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
						<?php $url = $enc->attr( $this->link( 'admin/jqadm/url/get', ['id' => $id] + $params ) ) ?>
						<tr class="list-item <?= $this->site()->mismatch( $item->getSiteId() ) ?>" data-label="<?= $enc->attr( $item->getLabel() ) ?>">
							<td class="select">
								<input class="form-check-input" type="checkbox" tabindex="1"
									name="<?= $enc->attr( $this->formparam( ['id', ''] ) ) ?>"
									value="<?= $enc->attr( $item->getId() ) ?>"
									v-on:click="toggle(`<?= $enc->js( $id ) ?>`)"
									v-bind:checked="checked(`<?= $enc->js( $id ) ?>`)"
									v-bind:disabled="readonly(`<?= $enc->js( $id ) ?>`)">
							</td>
							<?php if( in_array( 'attribute.id', $fields ) ) : ?>
								<td class="attribute-id"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'attribute.domain', $fields ) ) : ?>
								<td class="attribute-domain"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getDomain() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'attribute.status', $fields ) ) : ?>
								<td class="attribute-status"><a class="items-field" href="<?= $url ?>"><div class="icon status-<?= $enc->attr( $item->getStatus() ) ?>"></div></a></td>
							<?php endif ?>
							<?php if( in_array( 'attribute.type', $fields ) ) : ?>
								<td class="attribute-type"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getType() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'attribute.code', $fields ) ) : ?>
								<td class="attribute-code"><a class="items-field" href="<?= $url ?>" tabindex="1"><?= $enc->html( $item->getCode() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'attribute.label', $fields ) ) : ?>
								<td class="attribute-label"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getLabel() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'attribute.position', $fields ) ) : ?>
								<td class="attribute-position"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getPosition() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'attribute.ctime', $fields ) ) : ?>
								<td class="attribute-ctime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeCreated() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'attribute.mtime', $fields ) ) : ?>
								<td class="attribute-mtime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeModified() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'attribute.editor', $fields ) ) : ?>
								<td class="attribute-editor"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->editor() ) ?></a></td>
							<?php endif ?>

							<td class="actions">
								<a class="btn act-copy icon" tabindex="1"
									href="<?= $enc->attr( $this->link( 'admin/jqadm/url/copy', ['id' => $id] + $params ) ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry' ) ) ?>"
									aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ) ?>">
								</a>
								<?php if( !$this->site()->readonly( $item->getSiteId() ) ) : ?>
									<a class="btn act-delete icon" tabindex="1"
										v-on:click.prevent.stop="askDelete(`<?= $enc->js( $id ) ?>`, $event)"
										href="<?= $enc->attr( $this->link( 'admin/jqadm/url/delete', ['id' => $id] + $params ) ) ?>"
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
			<div class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No entries found' ) ) ) ?></div>
		<?php endif ?>
	</form>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
			['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/attribute/page', [] )]
		);
	?>

	<confirm-delete v-bind:items="unconfirmed" v-bind:show="dialog"
		v-on:close="confirmDelete(false)" v-on:confirm="confirmDelete(true)">
	</confirm-delete>

</div>
<?php $this->block()->stop() ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'page' ) ) ?>
