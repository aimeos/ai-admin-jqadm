<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
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


$default = $this->config( 'admin/jqadm/coupon/fields', ['coupon.status', 'coupon.label', 'coupon.provider'] );
$fields = $this->param( 'fields/v', $default );

$params = $this->get( 'pageParams', [] );
$pageParams = ['total' => $this->get( 'total', 0 ), 'pageParams' => $params];
$sortcode = $this->param( 'sort' );

$typeList = [];
foreach( $this->get( 'itemTypes', [] ) as $id => $typeItem ) {
	$typeList[$id] = $typeItem->getCode();
}

$columnList = [
	'coupon.id' => $this->translate( 'admin', 'ID' ),
	'coupon.status' => $this->translate( 'admin', 'Status' ),
	'coupon.label' => $this->translate( 'admin', 'Label' ),
	'coupon.provider' => $this->translate( 'admin', 'Provider' ),
	'coupon.datestart' => $this->translate( 'admin', 'Start date' ),
	'coupon.dateend' => $this->translate( 'admin', 'End date' ),
	'coupon.ctime' => $this->translate( 'admin', 'Created' ),
	'coupon.mtime' => $this->translate( 'admin', 'Modified' ),
	'coupon.editor' => $this->translate( 'admin', 'Editor' ),
];

?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<nav class="main-navbar">

	<span class="navbar-brand">
		<?= $enc->html( $this->translate( 'admin', 'Voucher' ) ); ?>
		<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ); ?>)</span>
	</span>

	<?= $this->partial(
		$this->config( 'admin/jqadm/partial/navsearch', 'common/partials/navsearch-default.php' ), [
			'filterAttributes' => $this->get( 'filterAttributes', [] ),
			'filterOperators' => $this->get( 'filterOperators', [] ),
			'filterData' => $this->param( 'filter', [] ),
			'params' => $params,
		]
	); ?>
</nav>


<?= $this->partial( $this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-default.php' ), $pageParams + ['pos' => 'top'] ); ?>

<form class="list-product" method="POST" action="<?= $enc->attr( $this->url( $target, $controller, $action, $params, [], $config ) ); ?>">
	<?= $this->csrf()->formfield(); ?>

	<table class="list-items table table-hover table-striped">
		<thead class="list-header">
			<tr>
				<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-default.php' ),
						['fields' => $fields, 'params' => $params, 'tabindex' => 1, 'data' => $columnList]
					);
				?>

				<th class="actions">
					<a class="btn fa act-add" tabindex="1"
						href="<?= $enc->attr( $this->url( $newTarget, $newCntl, $newAction, $params, [], $newConfig ) ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+A)') ); ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ); ?>">
					</a>

					<?= $this->partial(
							$this->config( 'admin/jqadm/partial/columns', 'common/partials/columns-default.php' ),
							['fields' => $fields, 'group' => 'v', 'tabindex' => 1, 'data' => $columnList]
						);
					?>
				</th>
			</tr>
		</thead>
		<tbody>

			<?= $this->partial(
				$this->config( 'admin/jqadm/partial/listsearch', 'common/partials/listsearch-default.php' ), [
					'fields' => $fields, 'tabindex' => 1,
					'data' => [
						'coupon.id' => ['op' => '=='],
						'coupon.status' => ['op' => '==', 'type' => 'select', 'val' => [
							'1' => $this->translate( 'admin', 'status:enabled' ),
							'0' => $this->translate( 'admin', 'status:disabled' ),
							'-1' => $this->translate( 'admin', 'status:review' ),
							'-2' => $this->translate( 'admin', 'status:archive' ),
						]],
						'coupon.label' => [],
						'coupon.provider' => [],
						'coupon.datestart' => ['op' => '>=', 'type' => 'datetime-local'],
						'coupon.dateend' => ['op' => '>=', 'type' => 'datetime-local'],
						'coupon.ctime' => ['op' => '>=', 'type' => 'datetime-local'],
						'coupon.mtime' => ['op' => '>=', 'type' => 'datetime-local'],
						'coupon.editor' => [],
					]
				] );
			?>

			<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
				<?php $url = $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['id' => $id] + $params, [], $getConfig ) ); ?>
				<tr class="<?= $this->site()->readonly( $item->getSiteId() ); ?>">
					<?php if( in_array( 'coupon.id', $fields ) ) : ?>
						<td class="coupon-id"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getId() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'coupon.status', $fields ) ) : ?>
						<td class="coupon-status"><a class="items-field" href="<?= $url; ?>"><div class="fa status-<?= $enc->attr( $item->getStatus() ); ?>"></div></a></td>
					<?php endif; ?>
					<?php if( in_array( 'coupon.label', $fields ) ) : ?>
						<td class="coupon-label"><a class="items-field" href="<?= $url; ?>" tabindex="1"><?= $enc->html( $item->getLabel() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'coupon.provider', $fields ) ) : ?>
						<td class="coupon-provider"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getProvider() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'coupon.datestart', $fields ) ) : ?>
						<td class="coupon-datestart"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDateStart() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'coupon.dateend', $fields ) ) : ?>
						<td class="coupon-dateend"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDateEnd() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'coupon.ctime', $fields ) ) : ?>
						<td class="coupon-ctime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeCreated() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'coupon.mtime', $fields ) ) : ?>
						<td class="coupon-mtime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeModified() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'coupon.editor', $fields ) ) : ?>
						<td class="coupon-editor"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getEditor() ); ?></a></td>
					<?php endif; ?>

					<td class="actions">
						<?php if( !$this->site()->readonly( $item->getSiteId() ) ) : ?>
							<a class="btn act-delete fa" tabindex="1"
								href="<?= $enc->attr( $this->url( $delTarget, $delCntl, $delAction, ['resource' => 'coupon', 'id' => $id] + $params, [], $delConfig ) ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ); ?>"></a>
						<?php endif; ?>
						<a class="btn act-copy fa" tabindex="1"
							href="<?= $enc->attr( $this->url( $copyTarget, $copyCntl, $copyAction, ['id' => $id] + $params, [], $copyConfig ) ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry') ); ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ); ?>"></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if( $this->get( 'items', [] ) === [] ) : ?>
		<?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ); ?>
	<?php endif; ?>
</form>

<?= $this->partial( $this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-default.php' ), $pageParams + ['pos' => 'bottom'] ); ?>

<?php $this->block()->stop(); ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-default.php' ) ); ?>
