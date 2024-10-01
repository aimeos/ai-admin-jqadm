<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */


/** admin/jqadm/partial/productref
 * Relative path to the partial template for displaying the list of associated products
 *
 * The template file contains the HTML code and processing instructions
 * to generate the result shown in the administration interface. The
 * configuration string is the path to the template file relative
 * to the templates directory (usually in templates/admin/jqadm).
 *
 * You can overwrite the template file configuration in extensions and
 * provide alternative templates. These alternative templates should be
 * named like the default one but with an unique name. You may use the
 * name of your project for this. If you've implemented an alternative
 * client class as well, use the name of the new class.
 *
 * @param string Relative path to the partial creating the HTML code
 * @since 2023.04
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
	<?= $this->partial( $this->config( 'admin/jqadm/partial/productref', 'productref' ), [
		'types' => $this->get( 'productListTypes', map() )->col( null, 'customer.lists.type.code' )->getName()->toArray(),
		'data' => $this->get( 'productData', [] ),
		'tabindex' => $this->get( 'tabindex', 1 ),
		'siteid' => $this->site()->siteid(),
		'parentid' => $this->param( 'id' ),
		'resource' => 'customer/lists',
		'fields' => $fields,
	] ) ?>
</div>
<?= $this->get( 'productBody' ) ?>
