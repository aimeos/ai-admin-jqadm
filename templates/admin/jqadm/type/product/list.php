<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */

$enc = $this->encoder();


/** admin/jqadm/type/product/fields
 * List of product type columns that should be displayed in the list view
 *
 * Changes the list of product type columns shown by default in the product type
 * list view. The columns can be changed by the editor as required within the
 * administraiton interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "product.type.id" for the product type ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.10
 */
$default = ['product.type.domain', 'product.type.status', 'product.type.code', 'product.type.label'];
$default = $this->config( 'admin/jqadm/type/product/fields', $default );
$fields = $this->session( 'aimeos/admin/jqadm/type/product/fields', $default );

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
	'product.type.id' => $this->translate( 'admin', 'ID' ),
	'product.type.domain' => $this->translate( 'admin', 'Domain' ),
	'product.type.status' => $this->translate( 'admin', 'Status' ),
	'product.type.code' => $this->translate( 'admin', 'Code' ),
	'product.type.label' => $this->translate( 'admin', 'Label' ),
	'product.type.position' => $this->translate( 'admin', 'Position' ),
	'product.type.ctime' => $this->translate( 'admin', 'Created' ),
	'product.type.mtime' => $this->translate( 'admin', 'Modified' ),
	'product.type.editor' => $this->translate( 'admin', 'Editor' ),
];

$domains = [
	'attribute' => $this->translate( 'admin', 'attribute' ),
	'catalog' => $this->translate( 'admin', 'catalog' ),
	'customer' => $this->translate( 'admin', 'customer' ),
	'media' => $this->translate( 'admin', 'media' ),
	'price' => $this->translate( 'admin', 'price' ),
	'product' => $this->translate( 'admin', 'product' ),
	'service' => $this->translate( 'admin', 'service' ),
	'supplier' => $this->translate( 'admin', 'supplier' ),
	'text' => $this->translate( 'admin', 'text' ),
];


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<?= $this->partial( $this->config( 'admin/jqadm/partial/navsearch', 'navsearch' ) ) ?>
<?= $this->partial( $this->config( 'admin/jqadm/partial/columns', 'columns' ) ) ?>


<div class="list-view"
	data-domain="product/type"
	data-siteid="<?= $enc->attr( $this->site()->siteid() ) ?>"
	data-filter="<?= $enc->attr( $this->session( 'aimeos/admin/jqadm/type/product/filter', new \stdClass ) ) ?>"
	data-items="<?= $enc->attr( $this->get( 'items', map() )->call( 'toArray', [true] )->all() ) ?>">

	<nav class="main-navbar">

		<span class="navbar-brand">
			<?= $enc->html( $this->translate( 'admin', 'Product Types' ) ) ?>
			<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ) ?>)</span>
		</span>

		<div class="btn fa act-search" v-on:click="search = true"
			title="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>"
			aria-label="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>">
		</div>
	</nav>

	<nav-search v-bind:show="search" v-on:close="search = false"
		v-bind:url="`<?= $enc->js( $this->link( 'admin/jqadm/url/search', map( $searchParams )->except( 'filter' )->all() ) ) ?>`"
		v-bind:filter="<?= $enc->attr( (object) $this->session( 'aimeos/admin/jqadm/type/product/filter', new \stdClass ) ) ?>"
		v-bind:operators="<?= $enc->attr( $operators ) ?>"
		v-bind:name="`<?= $enc->js( $this->formparam( ['filter', '_key_', '0'] ) ) ?>`"
		v-bind:attributes="<?= $enc->attr( $searchAttributes ) ?>">
	</nav-search>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
			['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/type/product/page', [] )]
		);
	?>

	<form ref="form" class="list list-product-type" method="POST"
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

						<?= $this->partial(
								$this->config( 'admin/jqadm/partial/listhead', 'listhead' ),
								['fields' => $fields, 'params' => $params, 'data' => $columnList, 'sort' => $this->session( 'aimeos/admin/jqadm/type/product/sort' )]
							);
						?>

						<th class="actions">
							<a class="btn fa act-add" tabindex="1"
								href="<?= $enc->attr( $this->link( 'admin/jqadm/url/create', $params ) ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ) ?>">
							</a>

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
							'fields' => array_merge( $fields, ['select'] ), 'filter' => $this->session( 'aimeos/admin/jqadm/type/product/filter', [] ),
							'data' => [
								'product.type.id' => ['op' => '=='],
								'product.type.domain' => ['op' => '==', 'type' => 'select', 'val' => $domains],
								'product.type.status' => ['op' => '==', 'type' => 'select', 'val' => [
									'1' => $this->translate( 'mshop/code', 'status:1' ),
									'0' => $this->translate( 'mshop/code', 'status:0' ),
									'-1' => $this->translate( 'mshop/code', 'status:-1' ),
									'-2' => $this->translate( 'mshop/code', 'status:-2' ),
								]],
								'product.type.code' => [],
								'product.type.label' => [],
								'product.type.position' => ['op' => '>=', 'type' => 'number'],
								'product.type.ctime' => ['op' => '-', 'type' => 'datetime-local'],
								'product.type.mtime' => ['op' => '-', 'type' => 'datetime-local'],
								'product.type.editor' => [],
							]
						] );
					?>

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
									<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'type/product'] ) ) ?>">
										<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
									</button>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-product-type-status" class="form-check-input" type="checkbox" v-on:click="setState('item/product.type.status')">
												</div>
												<label class="col-4 form-control-label" for="batch-product-type-status">
													<?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select" v-bind:disabled="state('item/product.type.status')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'product.type.status' ) ) ) ?>">
														<option value=""></option>
														<option value="1"><?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?></option>
														<option value="0"><?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?></option>
														<option value="-1"><?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?></option>
														<option value="-2"><?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?></option>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-product-type-domain" class="form-check-input" type="checkbox" v-on:click="setState('item/product.type.domain')">
												</div>
												<label class="col-4 form-control-label" for="batch-product-type-domain">
													<?= $enc->html( $this->translate( 'admin', 'Domain' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select" v-bind:disabled="state('item/product.type.domain')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'product.type.domain' ) ) ) ?>">
														<option value=""></option>
														<?php foreach( $domains as $domain => $label ) : ?>
															<option value="<?= $enc->attr( $domain ) ?>">
																<?= $enc->html( $label ) ?>
															</option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-1">
													<input id="batch-product-type-position" class="form-check-input" type="checkbox" v-on:click="setState('item/product.type.position')">
												</div>
												<label class="col-4 form-control-label" for="batch-product-type-position">
													<?= $enc->html( $this->translate( 'admin', 'Position' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="number" v-bind:disabled="state('item/product.type.position')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'product.type.position' ) ) ) ?>">
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
								<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'type/product'] ) ) ?>">
									<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
								</button>
							</div>
						</td>
					</tr>

					<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
						<?php $url = $enc->attr( $this->link( 'admin/jqadm/url/get', ['id' => $id] + $params ) ) ?>
						<tr class="list-item <?= $this->site()->readonly( $item->getSiteId() ) ?>" data-label="<?= $enc->attr( $item->getLabel() ) ?>">
							<td class="select">
								<input class="form-check-input" type="checkbox" tabindex="1"
									name="<?= $enc->attr( $this->formparam( ['id', ''] ) ) ?>"
									value="<?= $enc->attr( $item->getId() ) ?>"
									v-on:click="toggle(`<?= $enc->js( $id ) ?>`)"
									v-bind:checked="checked(`<?= $enc->js( $id ) ?>`)"
									v-bind:disabled="readonly(`<?= $enc->js( $id ) ?>`)">
							</td>
							<?php if( in_array( 'product.type.id', $fields ) ) : ?>
								<td class="product-type-id"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.type.status', $fields ) ) : ?>
								<td class="product-type-status"><a class="items-field" href="<?= $url ?>"><div class="fa status-<?= $enc->attr( $item->getStatus() ) ?>"></div></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.type.domain', $fields ) ) : ?>
								<td class="product-type-domain"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getDomain() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.type.code', $fields ) ) : ?>
								<td class="product-type-code"><a class="items-field" href="<?= $url ?>" tabindex="1"><?= $enc->html( $item->getCode() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.type.label', $fields ) ) : ?>
								<td class="product-type-label"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getLabel() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.type.position', $fields ) ) : ?>
								<td class="product-type-position"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getPosition() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.type.ctime', $fields ) ) : ?>
								<td class="product-type-ctime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeCreated() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.type.mtime', $fields ) ) : ?>
								<td class="product-type-mtime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeModified() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.type.editor', $fields ) ) : ?>
								<td class="product-type-editor"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->editor() ) ?></a></td>
							<?php endif ?>

							<td class="actions">
								<a class="btn act-copy fa" tabindex="1"
									href="<?= $enc->attr( $this->link( 'admin/jqadm/url/copy', ['id' => $id] + $params ) ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry' ) ) ?>"
									aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ) ?>">
								</a>
								<?php if( !$this->site()->readonly( $item->getSiteId() ) ) : ?>
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
			'page' => $this->session( 'aimeos/admin/jqadm/type/product/page', [] )]
		);
	?>

	<confirm-delete v-bind:items="unconfirmed" v-bind:show="dialog"
		v-on:close="confirmDelete(false)" v-on:confirm="confirmDelete(true)">
	</confirm-delete>

</div>
<?php $this->block()->stop() ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'page' ) ) ?>
