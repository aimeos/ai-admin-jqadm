<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


/** admin/jqadm/supplier/product/fields
 * List of supplier list and product columns that should be displayed in the supplier product view
 *
 * Changes the list of supplier list and product columns shown by default in the
 * supplier product view. The columns can be changed by the editor as required
 * within the administraiton interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "product.lists.status" for the status value.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.10
 */
$fields = ['product.lists.status', 'product.lists.type', 'product.lists.position', 'product.lists.parentid'];
$fields = $this->config( 'admin/jqadm/supplier/product/fields', $fields );


?>
<div id="product" class="item-product tab-pane fade box" role="tabpanel" aria-labelledby="product">
	<?= $this->partial( $this->config( 'admin/jqadm/partial/productlist', 'productlist' ), [
		'types' => $this->get( 'productListTypes', map() )->col( 'product.lists.type.label', 'product.lists.type.code' )->toArray(),
		'siteid' => $this->site()->siteid(),
		'refid' => $this->param( 'id' ),
		'resource' => 'product/lists',
		'domain' => 'supplier',
		'fields' => $fields,
	] ) ?>
</div>
<?= $this->get( 'productBody' ) ?>
