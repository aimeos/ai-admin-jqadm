<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$enc = $this->encoder();
$searchParam = $params = $this->get( 'pageParams', [] );

$newTarget = $this->config( 'admin/jqadm/url/create/target' );
$newCntl = $this->config( 'admin/jqadm/url/create/controller', 'Jqadm' );
$newAction = $this->config( 'admin/jqadm/url/create/action', 'create' );
$newConfig = $this->config( 'admin/jqadm/url/create/config', [] );

$getTarget = $this->config( 'admin/jqadm/url/get/target' );
$getCntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$getAction = $this->config( 'admin/jqadm/url/get/action', 'get' );
$getConfig = $this->config( 'admin/jqadm/url/get/config', [] );

$delTarget = $this->config( 'admin/jsonadm/url/target' );
$delCntl = $this->config( 'admin/jsonadm/url/controller', 'Jsonadm' );
$delAction = $this->config( 'admin/jsonadm/url/action', 'delete' );
$delConfig = $this->config( 'admin/jsonadm/url/config', [] );


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
$default = ['customer.lists.status', 'customer.lists.typeid', 'customer.lists.config', 'product.label'];
$fields = $this->param( 'fields/up', $this->config( 'admin/jqadm/customer/product/fields', $default ) );

$listItems = $this->get( 'productListItems', [] );
$refItems = $this->get( 'productItems', [] );


?>
<div id="product" class="item-product content-block tab-pane fade" role="tabpanel" aria-labelledby="product">
	<table class="list-items table table-hover">
		<thead class="list-header">
			<tr>
				<?= $this->partial(
					$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-default.php' ), [
						'fields' => $fields, 'params' => $params,
						'data' => [
							'customer.lists.status' => $this->translate( 'admin', 'Status' ),
							'customer.lists.typeid' => $this->translate( 'admin', 'Type' ),
							'customer.lists.config' => $this->translate( 'admin', 'Config' ),
							'customer.lists.datestart' => $this->translate( 'admin', 'Start date' ),
							'customer.lists.dateend' => $this->translate( 'admin', 'End date' ),
							'product.status' => $this->translate( 'admin', 'Product Status' ),
							'product.typeid' => $this->translate( 'admin', 'Product type' ),
							'product.code' => $this->translate( 'admin', 'Product SKU' ),
							'product.label' => $this->translate( 'admin', 'Product label' ),
							'product.datestart' => $this->translate( 'admin', 'Product start date' ),
							'product.dateend' => $this->translate( 'admin', 'Product end date' ),
						]
					] );
				?>

				<th class="actions">
					<?= $this->partial(
						$this->config( 'admin/jqadm/partial/columns', 'common/partials/columns-default.php' ), [
							'fields' => $fields, 'group' => 'up',
							'data' => [
								'customer.lists.status' => $this->translate( 'admin', 'Status' ),
								'customer.lists.typeid' => $this->translate( 'admin', 'Type' ),
								'customer.lists.config' => $this->translate( 'admin', 'Config' ),
								'customer.lists.datestart' => $this->translate( 'admin', 'Start date' ),
								'customer.lists.dateend' => $this->translate( 'admin', 'End date' ),
								'product.status' => $this->translate( 'admin', 'Product Status' ),
								'product.typeid' => $this->translate( 'admin', 'Product type' ),
								'product.code' => $this->translate( 'admin', 'Product SKU' ),
								'product.label' => $this->translate( 'admin', 'Product label' ),
								'product.datestart' => $this->translate( 'admin', 'Product start date' ),
								'product.dateend' => $this->translate( 'admin', 'Product end date' ),
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
						'customer.lists.status' => ['op' => '==', 'type' => 'select', 'val' => [
							'1' => $this->translate( 'admin', 'status:enabled' ),
							'0' => $this->translate( 'admin', 'status:disabled' ),
							'-1' => $this->translate( 'admin', 'status:review' ),
							'-2' => $this->translate( 'admin', 'status:archive' ),
						]],
						'customer.lists.typeid' => ['op' => '==', 'type' => 'select', 'val' => $this->get( 'productListTypes', [])],
						'customer.lists.config' => ['op' => '~='],
						'customer.lists.datestart' => ['op' => '>=', 'type' => 'datetime-local'],
						'customer.lists.dateend' => ['op' => '>=', 'type' => 'datetime-local'],
						'product.status' => ['op' => '==', 'type' => 'select', 'val' => [
							'1' => $this->translate( 'admin', 'status:enabled' ),
							'0' => $this->translate( 'admin', 'status:disabled' ),
							'-1' => $this->translate( 'admin', 'status:review' ),
							'-2' => $this->translate( 'admin', 'status:archive' ),
						]],
						'product.typeid' => ['op' => '==', 'type' => 'select', 'val' => $this->get( 'productTypes', [])],
						'product.code' => [],
						'product.label' => [],
						'product.datestart' => ['op' => '>=', 'type' => 'datetime-local'],
						'product.dateend' => ['op' => '>=', 'type' => 'datetime-local'],
					]
				] );
			?>

			<?php foreach( $listItems as $listId => $listItem ) : ?>
				<tr class="<?= $this->site()->readonly( $listItem->getSiteId() ); ?>">
					<?php if( in_array( 'customer.lists.status', $fields ) ) : ?>
						<td class="customer-lists-status"><div class="fa status-<?= $enc->attr( $listItem->getStatus() ); ?>"></div></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.lists.typeid', $fields ) ) : ?>
						<td class="customer-lists-typeid"><?= $enc->html( $listItem->getType() ); ?></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.lists.config', $fields ) ) : ?>
						<td class="customer-lists-config"><?= $enc->html( json_encode( $listItem->getConfig() ) ); ?></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.lists.datestart', $fields ) ) : ?>
						<td class="customer-lists-datestart"><?= $enc->html( $listItem->getDateStart() ); ?></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.lists.dateend', $fields ) ) : ?>
						<td class="customer-lists-dateend"><?= $enc->html( $listItem->getDateEnd() ); ?></td>
					<?php endif; ?>

					<?php $refItem = ( isset( $refItems[$listItem->getRefId()] ) ? $refItems[$listItem->getRefId()] : null ); ?>

					<?php if( $refItem && in_array( 'product.status', $fields ) ) : ?>
						<td class="product-status"><div class="fa status-<?= $enc->attr( $refItem->getStatus() ); ?>"></div></td>
					<?php endif; ?>
					<?php if( $refItem && in_array( 'product.typeid', $fields ) ) : ?>
						<td class="product-typeid"><?= $enc->html( $refItem->getType() ); ?></td>
					<?php endif; ?>
					<?php if( $refItem && in_array( 'product.code', $fields ) ) : ?>
						<td class="product-code"><?= $enc->html( $refItem->getCode() ); ?></td>
					<?php endif; ?>
					<?php if( $refItem && in_array( 'product.label', $fields ) ) : ?>
						<td class="product-label"><?= $enc->html( $refItem->getLabel() ); ?></td>
					<?php endif; ?>
					<?php if( $refItem && in_array( 'product.datestart', $fields ) ) : ?>
						<td class="product-datestart"><?= $enc->html( $refItem->getDateStart() ); ?></td>
					<?php endif; ?>
					<?php if( $refItem && in_array( 'product.dateend', $fields ) ) : ?>
						<td class="product-dateend"><?= $enc->html( $refItem->getDateEnd() ); ?></td>
					<?php endif; ?>

					<td class="actions">
						<a class="btn act-delete fa"
							href="<?= $enc->attr( $this->url( $delTarget, $delCntl, $delAction, ['resource' => 'customer/lists', 'id' => $listItem->getId()] + $params, [], $delConfig ) ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ); ?>"></a>
						<a class="btn act-view fa" target="_blank"
							href="<?= $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['resource' => 'product', 'id' => $listItem->getRefId()] + $params, [], $getConfig ) ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Show entry') ); ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Show' ) ); ?>"></a>
					</td>
				</tr>
			<?php endforeach; ?>

		</tbody>
	</table>

	<?php if( $listItems === [] ) : ?>
		<?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ); ?>
	<?php endif; ?>
</div>
<?= $this->get( 'productBody' ); ?>
