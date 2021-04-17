<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */


/** admin/jqadm/catalog/product/fields
 * List of catalog list and product columns that should be displayed in the catalog product view
 *
 * Changes the list of catalog list and product columns shown by default in the
 * catalog product view. The columns can be changed by the editor as required
 * within the administraiton interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "catalog.lists.status" for the status value.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.10
 * @category Developer
 */
$fields = ['catalog.lists.status', 'catalog.lists.type', 'catalog.lists.position', 'catalog.lists.refid'];
$fields = $this->config( 'admin/jqadm/catalog/product/fields', $fields );


?>
<div id="product" class="item-product tab-pane fade box" role="tabpanel" aria-labelledby="product">
	<?= $this->partial( $this->config( 'admin/jqadm/partial/productref', 'common/partials/productref-standard' ), [
		'types' => $this->get( 'productListTypes', map() )->col( 'catalog.lists.type.label', 'catalog.lists.type.code' )->toArray(),
		'siteid' => $this->site()->siteid(),
		'parentid' => $this->param( 'id' ),
		'resource' => 'catalog/lists',
		'fields' => $fields,
	] ) ?>
</div>
<?= $this->get( 'productBody' ) ?>
