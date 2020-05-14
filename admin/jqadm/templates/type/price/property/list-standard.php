<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2020
 */

$enc = $this->encoder();


$target = $this->config( 'admin/jqadm/url/search/target' );
$controller = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/search/action', 'search' );
$config = $this->config( 'admin/jqadm/url/search/config', [] );


$newTarget = $this->config( 'admin/jqadm/url/create/target' );
$newCntl = $this->config( 'admin/jqadm/url/create/controller', 'Jqadm' );
$newAction = $this->config( 'admin/jqadm/url/create/action', 'create' );
$newConfig = $this->config( 'admin/jqadm/url/create/config', [] );


$getTarget = $this->config( 'admin/jqadm/url/get/target' );
$getCntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$getAction = $this->config( 'admin/jqadm/url/get/action', 'get' );
$getConfig = $this->config( 'admin/jqadm/url/get/config', [] );


$copyTarget = $this->config( 'admin/jqadm/url/copy/target' );
$copyCntl = $this->config( 'admin/jqadm/url/copy/controller', 'Jqadm' );
$copyAction = $this->config( 'admin/jqadm/url/copy/action', 'copy' );
$copyConfig = $this->config( 'admin/jqadm/url/copy/config', [] );


$delTarget = $this->config( 'admin/jqadm/url/delete/target' );
$delCntl = $this->config( 'admin/jqadm/url/delete/controller', 'Jqadm' );
$delAction = $this->config( 'admin/jqadm/url/delete/action', 'delete' );
$delConfig = $this->config( 'admin/jqadm/url/delete/config', [] );


/** admin/jqadm/type/price/property/fields
 * List of price list type columns that should be displayed in the list view
 *
 * Changes the list of price list type columns shown by default in the price
 * list type list view. The columns can be changed by the editor as required within the
 * administraiton interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "price.property.type.id" for the price type ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.10
 * @category Developer
 */
$default = ['price.property.type.domain', 'price.property.type.status', 'price.property.type.code', 'price.property.type.label'];
$default = $this->config( 'admin/jqadm/type/price/property/fields', $default );
$fields = $this->session( 'aimeos/admin/jqadm/type/price/property/fields', $default );

$searchParams = $params = $this->get( 'pageParams', [] );
$searchParams['page']['start'] = 0;

$typeList = [];
foreach( $this->get( 'itemTypes', [] ) as $typeItem ) {
	$typeList[$typeItem->getCode()] = $typeItem->getCode();
}

$columnList = [
	'price.property.type.id' => $this->translate( 'admin', 'ID' ),
	'price.property.type.domain' => $this->translate( 'admin', 'Domain' ),
	'price.property.type.status' => $this->translate( 'admin', 'Status' ),
	'price.property.type.code' => $this->translate( 'admin', 'Code' ),
	'price.property.type.label' => $this->translate( 'admin', 'Label' ),
	'price.property.type.position' => $this->translate( 'admin', 'Position' ),
	'price.property.type.ctime' => $this->translate( 'admin', 'Created' ),
	'price.property.type.mtime' => $this->translate( 'admin', 'Modified' ),
	'price.property.type.editor' => $this->translate( 'admin', 'Editor' ),
];

?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<nav class="main-navbar">

	<span class="navbar-brand">
		<?= $enc->html( $this->translate( 'admin', 'Price Property Types' ) ); ?>
		<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ); ?>)</span>
	</span>

	<?= $this->partial(
		$this->config( 'admin/jqadm/partial/navsearch', 'common/partials/navsearch-standard' ), [
			'filter' => $this->session( 'aimeos/admin/jqadm/type/price/property/filter', [] ),
			'filterAttributes' => $this->get( 'filterAttributes', [] ),
			'filterOperators' => $this->get( 'filterOperators', [] ),
			'params' => $params,
		]
	); ?>
</nav>


<?= $this->partial(
		$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard' ),
		['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
		'page' => $this->session( 'aimeos/admin/jqadm/type/price/property/page', [] )]
	);
?>

<form class="list list-price-property-type" method="POST" action="<?= $enc->attr( $this->url( $target, $controller, $action, $searchParams, [], $config ) ); ?>">
	<?= $this->csrf()->formfield(); ?>

	<table class="list-items table table-hover table-striped">
		<thead class="list-header">
			<tr>
				<th class="select">
					<a class="btn act-delete fa" tabindex="1" data-multi="1"
						href="<?= $enc->attr( $this->url( $delTarget, $delCntl, $delAction, ['id' => ''] + $params, [], $delConfig ) ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ); ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ); ?>">
					</a>
				</th>

				<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-standard' ),
						['fields' => $fields, 'params' => $params, 'data' => $columnList, 'sort' => $this->session( 'aimeos/admin/jqadm/type/price/property/sort' )]
					);
				?>

				<th class="actions">
					<a class="btn fa act-add" tabindex="1"
						href="<?= $enc->attr( $this->url( $newTarget, $newCntl, $newAction, $params, [], $newConfig ) ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ); ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ); ?>">
					</a>

					<?= $this->partial(
							$this->config( 'admin/jqadm/partial/columns', 'common/partials/columns-standard' ),
							['fields' => $fields, 'data' => $columnList]
						);
					?>
				</th>
			</tr>
		</thead>
		<tbody>

			<?= $this->partial(
				$this->config( 'admin/jqadm/partial/listsearch', 'common/partials/listsearch-standard' ), [
					'fields' => array_merge( $fields, ['select'] ), 'filter' => $this->session( 'aimeos/admin/jqadm/type/price/property/filter', [] ),
					'data' => [
						'select' => ['type' => 'checkbox'],
						'price.property.type.id' => ['op' => '=='],
						'price.property.type.domain' => ['op' => '==', 'type' => 'select', 'val' => [
							'attribute' => $this->translate( 'admin', 'attribute' ),
							'catalog' => $this->translate( 'admin', 'catalog' ),
							'customer' => $this->translate( 'admin', 'customer' ),
							'price' => $this->translate( 'admin', 'price' ),
							'price' => $this->translate( 'admin', 'price' ),
							'product' => $this->translate( 'admin', 'product' ),
							'service' => $this->translate( 'admin', 'service' ),
							'supplier' => $this->translate( 'admin', 'supplier' ),
							'text' => $this->translate( 'admin', 'text' ),
						]],
						'price.property.type.status' => ['op' => '==', 'type' => 'select', 'val' => [
							'1' => $this->translate( 'mshop/code', 'status:1' ),
							'0' => $this->translate( 'mshop/code', 'status:0' ),
							'-1' => $this->translate( 'mshop/code', 'status:-1' ),
							'-2' => $this->translate( 'mshop/code', 'status:-2' ),
						]],
						'price.property.type.code' => [],
						'price.property.type.label' => [],
						'price.property.type.position' => ['op' => '>=', 'type' => 'number'],
						'price.property.type.ctime' => ['op' => '>=', 'type' => 'datetime-local'],
						'price.property.type.mtime' => ['op' => '>=', 'type' => 'datetime-local'],
						'price.property.type.editor' => [],
					]
				] );
			?>

			<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
				<?php $url = $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['id' => $id] + $params, [], $getConfig ) ); ?>
				<tr class="list-item <?= $this->site()->readonly( $item->getSiteId() ); ?>" data-label="<?= $enc->attr( $item->getLabel() ) ?>">
					<td class="select"><input class="form-control" type="checkbox" tabindex="1" name="<?= $enc->attr( $this->formparam( ['id', ''] ) ) ?>" value="<?= $enc->attr( $item->getId() ) ?>" /></td>
					<?php if( in_array( 'price.property.type.id', $fields ) ) : ?>
						<td class="price-type-id"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getId() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'price.property.type.domain', $fields ) ) : ?>
						<td class="price-type-domain"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDomain() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'price.property.type.status', $fields ) ) : ?>
						<td class="price-type-status"><a class="items-field" href="<?= $url; ?>"><div class="fa status-<?= $enc->attr( $item->getStatus() ); ?>"></div></a></td>
					<?php endif; ?>
					<?php if( in_array( 'price.property.type.code', $fields ) ) : ?>
						<td class="price-type-code"><a class="items-field" href="<?= $url; ?>" tabindex="1"><?= $enc->html( $item->getCode() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'price.property.type.label', $fields ) ) : ?>
						<td class="price-type-label"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getLabel() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'price.property.type.position', $fields ) ) : ?>
						<td class="price-type-position"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getPosition() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'price.property.type.ctime', $fields ) ) : ?>
						<td class="price-type-ctime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeCreated() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'price.property.type.mtime', $fields ) ) : ?>
						<td class="price-type-mtime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeModified() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'price.property.type.editor', $fields ) ) : ?>
						<td class="price-type-editor"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getEditor() ); ?></a></td>
					<?php endif; ?>

					<td class="actions">
						<a class="btn act-copy fa" tabindex="1"
							href="<?= $enc->attr( $this->url( $copyTarget, $copyCntl, $copyAction, ['id' => $id] + $params, [], $copyConfig ) ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry' ) ); ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ); ?>">
						</a>
						<?php if( !$this->site()->readonly( $item->getSiteId() ) ) : ?>
							<a class="btn act-delete fa" tabindex="1"
								href="<?= $enc->attr( $this->url( $delTarget, $delCntl, $delAction, ['id' => $id] + $params, [], $delConfig ) ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ); ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ); ?>">
							</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if( $this->get( 'items', map() )->isEmpty() ) : ?>
		<div class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ); ?></div>
	<?php endif; ?>
</form>

<?= $this->partial(
		$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard' ),
		['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get( 'total' ),
		'page' => $this->session( 'aimeos/admin/jqadm/type/price/property/page', [] )]
	);
?>

<?php $this->block()->stop(); ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard' ) ); ?>
