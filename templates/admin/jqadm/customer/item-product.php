<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


/** admin/jqadm/customer/product/fields
 * List of customer list and product columns that should be displayed in the customer product view
 *
 * Changes the list of customer list and product columns shown by default in the
 * customer product view. The columns can be changed by the editor as required
 * within the administraiton interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "customer.lists.status" for the status value.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.10
 */
$fields = ['customer.lists.status', 'customer.lists.type', 'customer.lists.position', 'customer.lists.refid'];
$fields = $this->config( 'admin/jqadm/customer/product/fields', $fields );


?>
<div id="product" class="item-product tab-pane fade box" role="tabpanel" aria-labelledby="product">
	<?= $this->partial( $this->config( 'admin/jqadm/partial/productlist', 'productlist' ), [
		'types' => $this->get( 'productListTypes', map() )->col( 'customer.lists.type.label', 'customer.lists.type.code' )->toArray(),
		'siteid' => $this->site()->siteid(),
		'refid' => $this->param( 'id' ),
		'resource' => 'customer/lists',
		'domain' => 'product',
		'fields' => $fields,
	] ) ?>
</div>
<?= $this->get( 'productBody' ) ?>
