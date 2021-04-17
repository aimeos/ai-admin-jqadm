<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */


/** admin/jqadm/supplier/product/fields
 * List of supplier list and product columns that should be displayed in the supplier product view
 *
 * Changes the list of supplier list and product columns shown by default in the
 * supplier product view. The columns can be changed by the editor as required
 * within the administraiton interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "supplier.lists.status" for the status value.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.10
 * @category Developer
 */
$fields = ['supplier.lists.status', 'supplier.lists.type', 'supplier.lists.position', 'supplier.lists.refid'];
$fields = $this->config( 'admin/jqadm/supplier/product/fields', $fields );


?>
<div id="product" class="item-product tab-pane fade" role="tabpanel" aria-labelledby="product">
	<div class="box">
		<?= $this->partial( $this->config( 'admin/jqadm/partial/productref', 'common/partials/productref-standard' ), [
			'types' => $this->get( 'productListTypes', map() )->col( 'supplier.lists.type.label', 'supplier.lists.type.code' )->toArray(),
			'siteid' => $this->site()->siteid(),
			'parentid' => $this->param( 'id' ),
			'resource' => 'supplier/lists',
			'fields' => $fields,
		] ) ?>
	</div>
</div>
<?= $this->get( 'productBody' ) ?>
