<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


/** admin/jqadm/partial/productlist
 * Relative path to the partial template for displaying the list of related products
 *
 * The template file contains the HTML code and processing instructions
 * to generate the result shown in the administration interface. The
 * configuration string is the path to the template file relative
 * to the templates directory (usually in templates/admin/jqadm).
 *
 * You can overwrite the template file configuration in extensions and
 * provide alternative templates. These alternative templates should be
 * named like the default one but with the string "default" replaced by
 * an unique name. You may use the name of your project for this. If
 * you've implemented an alternative client class as well, "default"
 * should be replaced by the name of the new class.
 *
 * @param string Relative path to the partial creating the HTML code
 * @since 2023.04
 */

/** admin/jqadm/catalog/product/fields
 * List of catalog list and product columns that should be displayed in the catalog product view
 *
 * Changes the list of catalog list and product columns shown by default in the
 * catalog product view. The columns can be changed by the editor as required
 * within the administraiton interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "product.lists.status" for the status value.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.10
 */
$fields = ['product.lists.status', 'product.lists.type', 'product.lists.position', 'product.lists.parentid'];
$fields = $this->config( 'admin/jqadm/catalog/product/fields', $fields );


?>
<div id="product" class="item-product tab-pane fade box" role="tabpanel" aria-labelledby="product">
	<?= $this->partial( $this->config( 'admin/jqadm/partial/productlist', 'productlist' ), [
		'types' => $this->get( 'productListTypes', map() )->col( 'product.lists.type.label', 'product.lists.type.code' )->toArray(),
		'siteid' => $this->site()->siteid(),
		'refid' => $this->param( 'id' ),
		'resource' => 'product/lists',
		'domain' => 'catalog',
		'fields' => $fields,
	] ) ?>
</div>
<?= $this->get( 'productBody' ) ?>
