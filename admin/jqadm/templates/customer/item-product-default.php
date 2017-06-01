<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$enc = $this->encoder();
$searchParam = $params = $this->get( 'pageParams', [] );

$newTarget = $this->config( 'admin/jqadm/url/get/target' );
$newCntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$newAction = $this->config( 'admin/jqadm/url/get/action', 'new' );
$newConfig = $this->config( 'admin/jqadm/url/get/config', [] );

$deleteTarget = $this->config( 'admin/jqadm/url/get/target' );
$deleteCntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$deleteAction = $this->config( 'admin/jqadm/url/get/action', 'delete' );
$deleteConfig = $this->config( 'admin/jqadm/url/get/config', [] );

$saveTarget = $this->config( 'admin/jqadm/url/save/target' );
$saveCntl = $this->config( 'admin/jqadm/url/save/controller', 'Jqadm' );
$saveAction = $this->config( 'admin/jqadm/url/save/action', 'save' );
$saveConfig = $this->config( 'admin/jqadm/url/save/config', [] );


/** admin/jqadm/customer/product/fields
 * List of list and product columns that should be displayed in the customer product view
 *
 * Changes the list of list and product columns shown by default in the customer product view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "customer.lists.status" for the status value.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.07
 * @category Developer
 */
$default = $this->config( 'admin/jqadm/product/fields', [
	'customer.lists.status', 'customer.lists.type', 'customer.lists.config', 'customer.lists.datestart', 'customer.lists.dateend',
	'product.status', 'product.type', 'product.label', 'product.datestart', 'product.dateend'
] );
$fields = $this->param( 'fields', $default );


?>
<div id="product" class="item-product content-block tab-pane fade" role="tabpanel" aria-labelledby="product">
	<div class="table-responsive">
		<table class="list-items table table-hover">
			<thead class="list-header">
				<tr>
					<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-default.php' ), [
							'fields' => $fields, 'params' => $params,
							'data' => [
								'customer.lists.status' => $this->translate( 'admin', 'Status' ),
								'customer.lists.type' => $this->translate( 'admin', 'Type' ),
								'customer.lists.config' => $this->translate( 'admin', 'Config' ),
								'customer.lists.datestart' => $this->translate( 'admin', 'Start date' ),
								'customer.lists.dateend' => $this->translate( 'admin', 'End date' ),
								'product.status' => $this->translate( 'admin', 'Product Status' ),
								'product.type' => $this->translate( 'admin', 'Product type' ),
								'product.label' => $this->translate( 'admin', 'Product label' ),
								'product.datestart' => $this->translate( 'admin', 'Product start date' ),
								'product.dateend' => $this->translate( 'admin', 'Product end date' ),
							]
						] );
					?>

					<th class="actions">
						<a class="btn fa act-add"
							href="<?= $enc->attr( $this->url( $newTarget, $newCntl, $newAction, ['resource' => 'customer/lists'], [], $newConfig ) ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+a)') ); ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ); ?>">
						</a>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listsearch', 'common/partials/listsearch-default.php' ), [
							'fields' => $fields, 'params' => $searchParam,
							'data' => [
								'customer.lists.status' => ['op' => '==', 'type' => 'select', 'val' => [
									'1' => $this->translate( 'admin', 'status:enabled' ),
									'0' => $this->translate( 'admin', 'status:disabled' ),
									'-1' => $this->translate( 'admin', 'status:review' ),
									'-2' => $this->translate( 'admin', 'status:archive' ),
								]],
								'customer.lists.type' => ['op' => '=='],
								'customer.lists.config' => [],
								'customer.lists.datestart' => ['op' => '>=', 'type' => 'datetime-local'],
								'customer.lists.dateend' => ['op' => '>=', 'type' => 'datetime-local'],
								'product.status' => ['op' => '==', 'type' => 'select', 'val' => [
									'1' => $this->translate( 'admin', 'status:enabled' ),
									'0' => $this->translate( 'admin', 'status:disabled' ),
									'-1' => $this->translate( 'admin', 'status:review' ),
									'-2' => $this->translate( 'admin', 'status:archive' ),
								]],
								'product.type' => [],
								'product.label' => [],
								'product.datestart' => ['op' => '>=', 'type' => 'datetime-local'],
								'product.dateend' => ['op' => '>=', 'type' => 'datetime-local'],
							]
						] );
					?>
				<tr>

				<?php foreach( $this->item->getListItems( 'product', 'default' ) as $listId => $listItem ) : ?>
					<tr class="<?= $this->site()->readonly( $item->getSiteId() ); ?>">
						<td class="customer-lists-status"><div class="fa status-<?= $enc->attr( $listItem->getStatus() ); ?>"></div></td>
						<td class="customer-lists-type"><?= $enc->html( $listItem->getType() ); ?></td>
						<td class="customer-lists-config"><?= $enc->html( json_encode( $listItem->getConfig() ) ); ?></td>
						<td class="customer-lists-datestart"><?= $enc->html( $listItem->getStartDate() ); ?></td>
						<td class="customer-lists-dateend"><?= $enc->html( $listItem->getEndDate() ); ?></td>

						<?php if( ( $refItem = $listItem->getRefItem() ) !== false ) : ?>
							<td class="product-status"><div class="fa status-<?= $enc->attr( $refItem->getStatus() ); ?>"></div></td>
							<td class="product-type"><?= $enc->html( $refItem->getType() ); ?></td>
							<td class="product-label"><?= $enc->html( $refItem->getLabel() ); ?></td>
							<td class="product-datestart"><?= $enc->html( $refItem->getStartDate() ); ?></td>
							<td class="product-dateend"><?= $enc->html( $refItem->getEndDate() ); ?></td>
						<?php else : ?>
							<td class="product-status"></td>
							<td class="product-type"></td>
							<td class="product-label"></td>
							<td class="product-datestart"></td>
							<td class="product-dateend"></td>
						<?php endif; ?>

						<td class="actions">
							<a class="btn act-edit fa" target="_blank"
								href="<?= $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['resource' => 'customer/lists', 'id' => $listId] + $params, [], $getConfig ) ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Edit entry') ); ?>"></a>
							<a class="btn act-delete fa"
								href="<?= $enc->attr( $this->url( $deleteTarget, $deleteCntl, $deleteAction, ['resource' => 'customer/lists', 'id' => $listId] + $params, [], $deleteConfig ) ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ); ?>"></a>
						</td>
					</tr>
				<?php endforeach; ?>

			</tbody>
		</table>

		<?php if( $this->item->getListItems( 'product', 'default' ) === [] ) : ?>
			<?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ); ?>
		<?php endif; ?>
	</div>
</div>
<?= $this->get( 'productBody' ); ?>
