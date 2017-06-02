<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
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


$formparams = $params = $this->get( 'pageParams', [] );
unset( $formparams['fields'], $formparams['filter'], $formparams['page'] );


/** admin/jqadm/order/fields
 * List of order columns that should be displayed in the list view
 *
 * Changes the list of order columns shown by default in the order list view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "order.id" for the order ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.07
 * @category Developer
 */
$default = $this->config( 'admin/jqadm/order/fields', [
	'order.id', 'order.ctime', 'order.statuspayment', 'order.base.price',
	'order.base.address.lastname', 'order.base.service.code'
] );
$fields = $this->param( 'fields/o', $default );

$pageParams = ['total' => $this->get( 'total', 0 ), 'pageParams' => $params];
$sortcode = $this->param( 'sort' );

$baseItems = $this->get( 'baseItems', [] );


?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<nav class="main-navbar">

	<span class="navbar-brand">
		<?= $enc->html( $this->translate( 'admin', 'Product' ) ); ?>
		<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ); ?>)</span>
	</span>

	<form class="form-inline" method="POST" action="<?= $enc->attr( $this->url( $target, $controller, $action, $formparams, [], $config ) ); ?>">
		<?= $this->csrf()->formfield(); ?>

		<i class="fa more"></i>

		<div class="input-group">
			<select class="custom-select filter-key" name="<?= $this->formparam( ['filter', 'key', ''] ); ?>">
				<?php foreach( $this->get( 'filterAttributes', [] ) as $code => $attrItem ) : ?>
					<?php if( $attrItem->isPublic() ) : ?>
						<option value="<?= $enc->attr( $code ); ?>" data-type="<?= $enc->attr( $attrItem->getType() ); ?>" <?= ( isset( $filter['key'] ) && $filter['key'] === $code ? 'selected' : '' ); ?> >
							<?= $enc->html( $this->translate( 'admin/ext', $attrItem->getLabel() ) ); ?>
						</option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
			<select class="custom-select filter-operator" name="<?= $this->formparam( ['filter', 'op', ''] ); ?>">
				<?php foreach( $this->get( 'filterOperators/compare', [] ) as $code ) : ?>
					<option value="<?= $enc->attr( $code ); ?>" <?= ( isset( $filter['op'] ) && $filter['op'] === $code ? 'selected' : '' ); ?> >
						<?= $enc->html( $code ) . ( strlen( $code ) === 1 ? '&nbsp;' : '' ); ?>&nbsp;&nbsp;<?= $enc->html( $this->translate( 'admin/ext', $code ) ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<input type="text" class="form-control filter-value" name="<?= $this->formparam( ['filter', 'val', ''] ); ?>"
				 value="<?= $enc->attr( ( isset( $filter['val'] ) ? $filter['val'] : '' ) ); ?>" >
			<button class="input-group-addon btn btn-primary fa fa-search"></button>
		</div>

	</form>

</nav>


<?= $this->partial( $this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-default.php' ), $pageParams + ['pos' => 'top'] ); ?>

<?php $searchParam = $params; unset( $searchParam['filter'] ); ?>
<form class="list-order" method="POST" action="<?= $enc->attr( $this->url( $target, $controller, $action, $params, [], $config ) ); ?>">
	<?= $this->csrf()->formfield(); ?>

	<table class="list-items table table-hover table-striped">
		<thead class="list-header">
			<tr>
				<?= $this->partial(
					$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-default.php' ), [
						'fields' => $fields, 'params' => $params,
						'data' => [
							'order.id' => $this->translate( 'admin', 'ID' ),
							'order.type' => $this->translate( 'admin', 'Type' ),
							'order.statuspayment' => $this->translate( 'admin', 'Payment status' ),
							'order.datepayment' => $this->translate( 'admin', 'Payment date' ),
							'order.statusdelivery' => $this->translate( 'admin', 'Delivery status' ),
							'order.datedelivery' => $this->translate( 'admin', 'Delivery date' ),
							'order.relatedid' => $this->translate( 'admin', 'Related order' ),
							'order.ctime' => $this->translate( 'admin', 'Created' ),
							'order.mtime' => $this->translate( 'admin', 'Modified' ),
							'order.editor' => $this->translate( 'admin', 'Editor' ),
							'order.base.comment' => $this->translate( 'admin', 'Comment' ),
						]
					] );
				?>

				<th class="actions">
					<a class="btn fa act-add"
						href="<?= $enc->attr( $this->url( $newTarget, $newCntl, $newAction, $params, [], $newConfig ) ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+a)') ); ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ); ?>">
					</a>

					<?= $this->partial(
						$this->config( 'admin/jqadm/partial/columns', 'common/partials/columns-default.php' ), [
							'fields' => $fields, 'group' => 'p',
							'data' => [
								'order.id' => $this->translate( 'admin', 'ID' ),
								'order.type' => $this->translate( 'admin', 'Type' ),
								'order.statuspayment' => $this->translate( 'admin', 'Payment status' ),
								'order.datepayment' => $this->translate( 'admin', 'Payment date' ),
								'order.statusdelivery' => $this->translate( 'admin', 'Delivery status' ),
								'order.datedelivery' => $this->translate( 'admin', 'Delivery date' ),
								'order.relatedid' => $this->translate( 'admin', 'Related order' ),
								'order.ctime' => $this->translate( 'admin', 'Created' ),
								'order.mtime' => $this->translate( 'admin', 'Modified' ),
								'order.editor' => $this->translate( 'admin', 'Editor' ),
								'order.base.comment' => $this->translate( 'admin', 'Comment' ),
							]
						] );
					?>
				</th>
			</tr>
		</thead>
		<tbody>

			<?= $this->partial(
				$this->config( 'admin/jqadm/partial/listsearch', 'common/partials/listsearch-default.php' ), [
					'fields' => $fields, 'params' => $searchParam,
					'data' => [
						'order.id' => ['op' => '=='],
						'order.type' => [],
						'order.statuspayment' => ['op' => '==', 'type' => 'select', 'val' => [
							'1' => $this->translate( 'admin', 'status:enabled' ),
						]],
						'order.datepayment' => ['op' => '>=', 'type' => 'datetime-local'],
						'order.statusdelivery' => ['op' => '==', 'type' => 'select', 'val' => [
							'1' => $this->translate( 'admin', 'status:enabled' ),
						]],
						'order.datedelivery' => ['op' => '>=', 'type' => 'datetime-local'],
						'order.relatedid' => ['op' => '=='],
						'order.ctime' => ['op' => '>=', 'type' => 'datetime-local'],
						'order.mtime' => ['op' => '>=', 'type' => 'datetime-local'],
						'order.editor' => [],
						'order.base.comment' => [],
					]
				] );
			?>

			<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
				<?php $url = $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['id' => $item->getBaseId()] + $params, [], $getConfig ) ); ?>
				<tr class="<?= $this->site()->readonly( $item->getSiteId() ); ?>">
					<?php if( in_array( 'order.id', $fields ) ) : ?>
						<td class="order-id"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getId() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.type', $fields ) ) : ?>
						<td class="order-type"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getType() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.statuspayment', $fields ) ) : ?>
						<td class="order-statuspayment"><a class="items-field" href="<?= $url; ?>"><?= $enc->attr( $item->getPaymentStatus() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.datepayment', $fields ) ) : ?>
						<td class="order-datepayment"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDatePayment() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.statusdelivery', $fields ) ) : ?>
						<td class="order-statusdelivery"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDeliveryStatus() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.datedelivery', $fields ) ) : ?>
						<td class="order-datedelivery"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDateDelivery() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.relatedid', $fields ) ) : ?>
						<td class="order-relatedid"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getRelatedId() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.ctime', $fields ) ) : ?>
						<td class="order-ctime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeCreated() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.mtime', $fields ) ) : ?>
						<td class="order-mtime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeModified() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.editor', $fields ) ) : ?>
						<td class="order-editor"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getEditor() ); ?></a></td>
					<?php endif; ?>

					<?php $baseItem = ( isset( $baseItems[$item->getBaseId()] ) ? $baseItems[$item->getBaseId()] : null ); ?>

					<?php if( in_array( 'order.base.comment', $fields ) ) : ?>
						<td class="order-base-comment"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $baseItem->getComment() ); ?></a></td>
					<?php endif; ?>

					<td class="actions">
						<a class="btn act-copy fa"
							href="<?= $enc->attr( $this->url( $copyTarget, $copyCntl, $copyAction, ['id' => $item->getBaseId()] + $params, [], $copyConfig ) ); ?>"
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
