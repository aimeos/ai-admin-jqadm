<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */

$enc = $this->encoder();


/** admin/jqadm/url/search/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.04
 * @see admin/jqadm/url/search/controller
 * @see admin/jqadm/url/search/action
 * @see admin/jqadm/url/search/config
 * @see admin/jqadm/url/search/filter
 */

/** admin/jqadm/url/search/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @see admin/jqadm/url/search/target
 * @see admin/jqadm/url/search/action
 * @see admin/jqadm/url/search/config
 * @see admin/jqadm/url/search/filter
 */

/** admin/jqadm/url/search/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @see admin/jqadm/url/search/target
 * @see admin/jqadm/url/search/controller
 * @see admin/jqadm/url/search/config
 * @see admin/jqadm/url/search/filter
 */

/** admin/jqadm/url/search/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  admin/jqadm/url/search/config = ['absoluteUri' => true )
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2016.04
 * @see admin/jqadm/url/search/target
 * @see admin/jqadm/url/search/controller
 * @see admin/jqadm/url/search/action
 * @see admin/jqadm/url/search/filter
 */

/** admin/jqadm/url/search/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2016.04
 * @see admin/jqadm/url/search/target
 * @see admin/jqadm/url/search/controller
 * @see admin/jqadm/url/search/action
 * @see admin/jqadm/url/search/config
 */


/** admin/jqadm/url/create/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.04
 * @see admin/jqadm/url/create/controller
 * @see admin/jqadm/url/create/action
 * @see admin/jqadm/url/create/config
 * @see admin/jqadm/url/create/filter
 */

/** admin/jqadm/url/create/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @see admin/jqadm/url/create/target
 * @see admin/jqadm/url/create/action
 * @see admin/jqadm/url/create/config
 * @see admin/jqadm/url/create/filter
 */

/** admin/jqadm/url/create/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @see admin/jqadm/url/create/target
 * @see admin/jqadm/url/create/controller
 * @see admin/jqadm/url/create/config
 * @see admin/jqadm/url/create/filter
 */

/** admin/jqadm/url/create/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  admin/jqadm/url/create/config = ['absoluteUri' => true]
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2016.04
 * @see admin/jqadm/url/create/target
 * @see admin/jqadm/url/create/controller
 * @see admin/jqadm/url/create/action
 * @see admin/jqadm/url/create/filter
 */

/** admin/jqadm/url/create/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2016.04
 * @see admin/jqadm/url/create/target
 * @see admin/jqadm/url/create/controller
 * @see admin/jqadm/url/create/action
 * @see admin/jqadm/url/create/config
 */


/** admin/jqadm/url/get/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.04
 * @see admin/jqadm/url/get/controller
 * @see admin/jqadm/url/get/action
 * @see admin/jqadm/url/get/config
 * @see admin/jqadm/url/get/filter
 */

/** admin/jqadm/url/get/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @see admin/jqadm/url/get/target
 * @see admin/jqadm/url/get/action
 * @see admin/jqadm/url/get/config
 * @see admin/jqadm/url/get/filter
 */

/** admin/jqadm/url/get/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @see admin/jqadm/url/get/target
 * @see admin/jqadm/url/get/controller
 * @see admin/jqadm/url/get/config
 * @see admin/jqadm/url/get/filter
 */

/** admin/jqadm/url/get/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  admin/jqadm/url/get/config = ['absoluteUri' => true]
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2016.04
 * @see admin/jqadm/url/get/target
 * @see admin/jqadm/url/get/controller
 * @see admin/jqadm/url/get/action
 * @see admin/jqadm/url/get/filter
 */

/** admin/jqadm/url/get/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2016.04
 * @see admin/jqadm/url/get/target
 * @see admin/jqadm/url/get/controller
 * @see admin/jqadm/url/get/action
 * @see admin/jqadm/url/get/config
 */


/** admin/jqadm/url/copy/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.04
 * @see admin/jqadm/url/copy/controller
 * @see admin/jqadm/url/copy/action
 * @see admin/jqadm/url/copy/config
 * @see admin/jqadm/url/copy/filter
 */

/** admin/jqadm/url/copy/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @see admin/jqadm/url/copy/target
 * @see admin/jqadm/url/copy/action
 * @see admin/jqadm/url/copy/config
 * @see admin/jqadm/url/copy/filter
 */

/** admin/jqadm/url/copy/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @see admin/jqadm/url/copy/target
 * @see admin/jqadm/url/copy/controller
 * @see admin/jqadm/url/copy/config
 * @see admin/jqadm/url/copy/filter
 */

/** admin/jqadm/url/copy/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  admin/jqadm/url/copy/config = ['absoluteUri' => true]
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2016.04
 * @see admin/jqadm/url/copy/target
 * @see admin/jqadm/url/copy/controller
 * @see admin/jqadm/url/copy/action
 * @see admin/jqadm/url/copy/filter
 */

/** admin/jqadm/url/copy/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2016.04
 * @see admin/jqadm/url/copy/target
 * @see admin/jqadm/url/copy/controller
 * @see admin/jqadm/url/copy/action
 * @see admin/jqadm/url/copy/config
 */


/** admin/jqadm/url/delete/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.04
 * @see admin/jqadm/url/delete/controller
 * @see admin/jqadm/url/delete/action
 * @see admin/jqadm/url/delete/config
 * @see admin/jqadm/url/delete/filter
 */

/** admin/jqadm/url/delete/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @see admin/jqadm/url/delete/target
 * @see admin/jqadm/url/delete/action
 * @see admin/jqadm/url/delete/config
 * @see admin/jqadm/url/delete/filter
 */

/** admin/jqadm/url/delete/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @see admin/jqadm/url/delete/target
 * @see admin/jqadm/url/delete/controller
 * @see admin/jqadm/url/delete/config
 * @see admin/jqadm/url/delete/filter
 */

/** admin/jqadm/url/delete/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  admin/jqadm/url/delete/config = ['absoluteUri' => true]
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2016.04
 * @see admin/jqadm/url/delete/target
 * @see admin/jqadm/url/delete/controller
 * @see admin/jqadm/url/delete/action
 * @see admin/jqadm/url/delete/filter
 */

/** admin/jqadm/url/delete/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2016.04
 * @see admin/jqadm/url/delete/target
 * @see admin/jqadm/url/delete/controller
 * @see admin/jqadm/url/delete/action
 * @see admin/jqadm/url/delete/config
 */


/** admin/jqadm/url/batch/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2022.10
 * @see admin/jqadm/url/batch/controller
 * @see admin/jqadm/url/batch/action
 * @see admin/jqadm/url/batch/config
 * @see admin/jqadm/url/batch/filter
 */

/** admin/jqadm/url/batch/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2022.10
 * @see admin/jqadm/url/batch/target
 * @see admin/jqadm/url/batch/action
 * @see admin/jqadm/url/batch/config
 * @see admin/jqadm/url/batch/filter
 */

/** admin/jqadm/url/batch/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2022.10
 * @see admin/jqadm/url/batch/target
 * @see admin/jqadm/url/batch/controller
 * @see admin/jqadm/url/batch/config
 * @see admin/jqadm/url/batch/filter
 */

/** admin/jqadm/url/batch/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  admin/jqadm/url/batch/config = ['absoluteUri' => true]
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2022.10
 * @see admin/jqadm/url/batch/target
 * @see admin/jqadm/url/batch/controller
 * @see admin/jqadm/url/batch/action
 * @see admin/jqadm/url/batch/filter
 */

/** admin/jqadm/url/batch/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2022.10
 * @see admin/jqadm/url/batch/target
 * @see admin/jqadm/url/batch/controller
 * @see admin/jqadm/url/batch/action
 * @see admin/jqadm/url/batch/config
 */


/** admin/jqadm/partial/columns
 * Relative path to the partial template for displaying the column selector in the list views
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
 * @since 2017.07
 * @see admin/jqadm/partial/confirm
 * @see admin/jqadm/partial/error
 */

/** admin/jqadm/partial/listhead
 * Relative path to the partial template for displaying the table header in the list views
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
 * @since 2017.07
 * @see admin/jqadm/partial/confirm
 * @see admin/jqadm/partial/error
 */

/** admin/jqadm/partial/listsearch
 * Relative path to the partial template for displaying the table search row in the list views
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
 * @since 2017.07
 * @see admin/jqadm/partial/confirm
 * @see admin/jqadm/partial/error
 */

/** admin/jqadm/partial/navsearch
 * Relative path to the partial template for displaying the search filter in the navigation bar
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
 * @since 2016.04
 * @see admin/jqadm/partial/confirm
 * @see admin/jqadm/partial/error
 */

/** admin/jqadm/partial/pagination
 * Relative path to the partial template for displaying the pagination
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
 * @since 2016.04
 * @see admin/jqadm/partial/navsearch
 * @see admin/jqadm/partial/confirm
 * @see admin/jqadm/partial/error
 */

/** admin/jqadm/template/page
 * Relative path to the template for the base page template
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
 * @since 2016.04
 */


/** admin/jqadm/product/fields
 * List of product columns that should be displayed in the list view
 *
 * Changes the list of product columns shown by default in the product list view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "product.id" for the product ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2016.04
 */
$default = ['image', 'product.id', 'product.status', 'product.type', 'product.code', 'product.label'];
$default = $this->config( 'admin/jqadm/product/fields', $default );
$fields = $this->session( 'aimeos/admin/jqadm/product/fields', $default );

$searchParams = $params = $this->get( 'pageParams', [] );
$searchParams['page']['start'] = 0;

$searchAttributes = map( $this->get( 'filterAttributes', [] ) )->filter( function( $item ) {
	return $item->isPublic();
} )->call( 'toArray' )->each( function( &$val ) {
	$val = $this->translate( 'admin/ext', $val['label'] ?? ' ' );
} )->all();

$operators = map( $this->get( 'filterOperators/compare', [] ) )->flip()->map( function( $val, $key ) {
	return $this->translate( 'admin/ext', $key );
} )->all();

$typeList = $this->get( 'itemTypes', map() )->col( 'product.type.code', 'product.type.code' )->all();

$columnList = [
	'image' => $this->translate( 'admin', 'Image' ),
	'product.id' => $this->translate( 'admin', 'ID' ),
	'product.status' => $this->translate( 'admin', 'Status' ),
	'product.type' => $this->translate( 'admin', 'Type' ),
	'product.code' => $this->translate( 'admin', 'Code' ),
	'product.label' => $this->translate( 'admin', 'Label' ),
	'product.datestart' => $this->translate( 'admin', 'Start date' ),
	'product.dateend' => $this->translate( 'admin', 'End date' ),
	'product.dataset' => $this->translate( 'admin', 'Dataset' ),
	'product.url' => $this->translate( 'admin', 'URL segment' ),
	'product.scale' => $this->translate( 'admin', 'Quantity scale' ),
	'product.target' => $this->translate( 'admin', 'URL target' ),
	'product.ctime' => $this->translate( 'admin', 'Created' ),
	'product.mtime' => $this->translate( 'admin', 'Modified' ),
	'product.editor' => $this->translate( 'admin', 'Editor' ),
	'product.rating' => $this->translate( 'admin', 'Rating' ),
	'product.ratings' => $this->translate( 'admin', 'Total ratings' ),
];


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<?= $this->partial( $this->config( 'admin/jqadm/partial/navsearch', 'navsearch' ) ) ?>
<?= $this->partial( $this->config( 'admin/jqadm/partial/columns', 'columns' ) ) ?>


<div class="list-view"
	data-domain="product"
	data-siteid="<?= $enc->attr( $this->site()->siteid() ) ?>"
	data-filter="<?= $enc->attr( $this->session( 'aimeos/admin/jqadm/product/filter', new \stdClass ) ) ?>"
	data-items="<?= $enc->attr( $this->get( 'items', map() )->call( 'toArray', [true] )->all() ) ?>">

	<nav class="main-navbar">

		<span class="navbar-brand">
			<?= $enc->html( $this->translate( 'admin', 'Product' ) ) ?>
			<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ) ?>)</span>
		</span>

		<div class="btn fa act-search" v-on:click="search = true"
			title="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>"
			aria-label="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>">
		</div>
	</nav>

	<nav-search v-bind:show="search" v-on:close="search = false"
		v-bind:url="`<?= $enc->js( $this->link( 'admin/jqadm/url/search', map( $searchParams )->except( 'filter' )->all() ) ) ?>`"
		v-bind:filter="<?= $enc->attr( (object) $this->session( 'aimeos/admin/jqadm/product/filter', new \stdClass ) ) ?>"
		v-bind:operators="<?= $enc->attr( $operators ) ?>"
		v-bind:name="`<?= $enc->js( $this->formparam( ['filter', '_key_', '0'] ) ) ?>`"
		v-bind:attributes="<?= $enc->attr( $searchAttributes ) ?>">
	</nav-search>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
			['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
			'page' =>$this->session( 'aimeos/admin/jqadm/product/page', [] )]
		);
	?>

	<form ref="form" class="list list-product" method="POST"
		action="<?= $enc->attr( $this->link( 'admin/jqadm/url/search', $searchParams ) ) ?>">

		<?= $this->csrf()->formfield() ?>

		<column-select tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
			name="<?= $enc->attr( $this->formparam( ['fields', ''] ) ) ?>"
			v-bind:titles="<?= $enc->attr( $columnList ) ?>"
			v-bind:fields="<?= $enc->attr( $fields ) ?>"
			v-bind:show="columns"
			v-on:close="columns = false">
		</column-select>

		<div class="table-responsive">
			<table class="list-items table table-hover table-striped">
				<thead class="list-header">
					<tr>
						<th class="select">
							<button class="btn icon-menu" type="button" data-bs-toggle="dropdown"
								aria-expanded="false" title="<?= $enc->attr( $this->translate( 'admin', 'Menu' ) ) ?>">
							</button>
							<ul class="dropdown-menu">
								<li>
									<a class="btn" v-on:click.prevent="batch = true" href="#" tabindex="1">
										<?= $enc->html( $this->translate( 'admin', 'Edit' ) ) ?>
									</a>
								</li>
								<li>
									<a class="btn" v-on:click.prevent="askDelete(null, $event)" tabindex="1"
										href="<?= $enc->attr( $this->link( 'admin/jqadm/url/delete', $params ) ) ?>">
										<?= $enc->html( $this->translate( 'admin', 'Delete' ) ) ?>
									</a>
								</li>
							</ul>
						</th>

						<?= $this->partial(
								$this->config( 'admin/jqadm/partial/listhead', 'listhead' ),
								['fields' => $fields, 'params' => $params, 'data' => $columnList,
								'sort' => $this->session( 'aimeos/admin/jqadm/product/sort', 'product.id' )]
							);
						?>

						<th class="actions">
							<a class="btn fa act-add" tabindex="1"
								href="<?= $enc->attr( $this->link( 'admin/jqadm/url/create', $params ) ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ) ?>">
							</a>

							<a class="btn act-columns fa" href="#" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Columns' ) ) ?>"
								v-on:click.prevent.stop="columns = true">
							</a>
						</th>
					</tr>
				</thead>
				<tbody>

					<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listsearch', 'listsearch' ), [
							'fields' => array_merge( $fields, ['select'] ), 'filter' => $this->session( 'aimeos/admin/jqadm/product/filter', [] ),
							'data' => [
								'image' => null,
								'product.id' => ['op' => '=='],
								'product.status' => ['op' => '==', 'type' => 'select', 'val' => [
									'1' => $this->translate( 'mshop/code', 'status:1' ),
									'0' => $this->translate( 'mshop/code', 'status:0' ),
									'-1' => $this->translate( 'mshop/code', 'status:-1' ),
									'-2' => $this->translate( 'mshop/code', 'status:-2' ),
								]],
								'product.type' => ['op' => '==', 'type' => 'select', 'val' => $typeList],
								'product.code' => [],
								'product.label' => [],
								'product.datestart' => ['op' => '-', 'type' => 'datetime-local'],
								'product.dateend' => ['op' => '-', 'type' => 'datetime-local'],
								'product.dataset' => ['op' => '=~'],
								'product.url' => ['op' => '=~'],
								'product.scale' => [],
								'product.target' => ['op' => '=~'],
								'product.config' => ['op' => '~='],
								'product.ctime' => ['op' => '-', 'type' => 'datetime-local'],
								'product.mtime' => ['op' => '-', 'type' => 'datetime-local'],
								'product.editor' => [],
								'product.rating' => [],
								'product.ratings' => [],
							]
						] );
					?>

					<tr class="batch" style="display: none" v-show="batch">
						<td colspan="<?= count( $fields ) + 2 ?>">
							<div class="batch-header">
								<div class="intro">
									<span class="name"><?= $enc->html( $this->translate( 'admin', 'Bulk edit' ) ) ?></span>
									<span class="count">{{ selected }} <?= $enc->html( $this->translate( 'admin', 'selected' ) ) ?></span>
								</div>
								<a class="btn btn-secondary" href="#" v-on:click.prevent="batch = false">
									<?= $enc->html( $this->translate( 'admin', 'Close' ) ) ?>
								</a>
							</div>
							<div class="card">
								<div class="card-header">
									<span><?= $enc->html( $this->translate( 'admin', 'Basic' ) ) ?></span>
									<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'product'] ) ) ?>">
										<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
									</button>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-6">

											<?php if( !empty( $datasets = $this->config( 'admin/jqadm/dataset/product', [] ) ) ) : ?>
												<div class="row">
													<div class="col-1">
														<input id="batch-product-dataset" class="form-check-input" type="checkbox" v-on:click="setState('item/product.dataset')">
													</div>
													<label class="col-4 form-control-label" for="batch-product-dataset">
														<?= $enc->html( $this->translate( 'admin', 'Data set' ) ) ?>
													</label>
													<div class="col-7">
														<select class="form-select" v-bind:disabled="state('item/product.dataset')"
															name="<?= $enc->attr( $this->formparam( array( 'item', 'product.dataset' ) ) ) ?>">
															<option value=""></option>
															<?php foreach( $datasets as $name ) : ?>
																<option value="<?= $enc->attr( $name ) ?>">
																	<?= $enc->html( $name ) ?>
																</option>
															<?php endforeach ?>
														</select>
													</div>
												</div>
											<?php endif ?>

											<div class="row">
												<div class="col-1">
													<input id="batch-product-status" class="form-check-input" type="checkbox" v-on:click="setState('item/product.status')">
												</div>
												<label class="col-4 form-control-label" for="batch-product-status">
													<?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select" v-bind:disabled="state('item/product.status')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'product.status' ) ) ) ?>">
														<option value=""></option>
														<option value="1"><?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ) ?></option>
														<option value="0"><?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ) ?></option>
														<option value="-1"><?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ) ?></option>
														<option value="-2"><?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ) ?></option>
													</select>
												</div>
											</div>

											<div class="row">
												<div class="col-1">
													<input id="batch-product-type" class="form-check-input" type="checkbox" v-on:click="setState('item/product.type')">
												</div>
												<label class="col-4 form-control-label" for="batch-product-type">
													<?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?>
												</label>
												<div class="col-7">
													<select class="form-select" v-bind:disabled="state('item/product.type')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'product.type' ) ) ) ?>">
														<option value=""></option>
														<?php foreach( $this->get( 'itemTypes', [] ) as $item ) : ?>
															<option value="<?= $enc->attr( $item->getCode() ) ?>">
																<?= $enc->html( $item->getLabel() ) ?>
															</option>
														<?php endforeach ?>
													</select>
												</div>
											</div>

											<div class="row">
												<div class="col-1">
													<input id="batch-product-scale" class="form-check-input" type="checkbox" v-on:click="setState('item/product.scale')">
												</div>
												<label class="col-4 form-control-label" for="batch-product-scale">
													<?= $enc->html( $this->translate( 'admin', 'Quantity scale' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="number" min="0.001" step="0.001" v-bind:disabled="state('item/product.scale')"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'product.scale' ) ) ) ?>">
												</div>
											</div>
										</div>

										<div class="col-lg-6">
											<div class="row">
												<div class="col-1">
													<input id="batch-product-datestart" class="form-check-input" type="checkbox" v-on:click="setState('item/product.datestart')">
												</div>
												<label class="col-4 form-control-label" for="batch-product-datestart">
													<?= $enc->html( $this->translate( 'admin', 'Start date' ) ) ?>
												</label>
												<div class="col-7">
													<input is="flat-pickr" class="form-control" type="datetime-local" tabindex="1"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'product.datestart' ) ) ) ?>"
														v-bind:disabled="state('item/product.datestart')"
														v-bind:config="Aimeos.flatpickr.datetime"
														value="">
												</div>
											</div>

											<div class="row">
												<div class="col-1">
													<input id="batch-product-dateend" class="form-check-input" type="checkbox" v-on:click="setState('item/product.dateend')">
												</div>
												<label class="col-4 form-control-label" for="batch-product-dateend">
													<?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?>
												</label>
												<div class="col-7">
													<input is="flat-pickr" class="form-control" type="datetime-local" tabindex="1"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'product.dateend' ) ) ) ?>"
														v-bind:disabled="state('item/product.dateend')"
														v-bind:config="Aimeos.flatpickr.datetime"
														value="">
												</div>
											</div>

											<div class="row">
												<div class="col-1">
													<input id="batch-product-ctime" class="form-check-input" type="checkbox" v-on:click="setState('item/product.ctime')">
												</div>
												<label class="col-4 form-control-label" for="batch-product-ctime">
													<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>
												</label>
												<div class="col-7">
													<input is="flat-pickr" class="form-control" type="datetime-local" tabindex="1"
														name="<?= $enc->attr( $this->formparam( array( 'item', 'product.ctime' ) ) ) ?>"
														v-bind:disabled="state('item/product.ctime')"
														v-bind:config="Aimeos.flatpickr.datetime"
														value="">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<span><?= $enc->html( $this->translate( 'admin', 'Price' ) ) ?></span>
									<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'product'] ) ) ?>">
										<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
									</button>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-6">

											<div class="row">
												<div class="col-1">
													<input id="batch-price-valuepercent" class="form-check-input" type="checkbox" v-on:click="setState('price/valuepercent')">
												</div>
												<label class="col-4 form-control-label" for="batch-price-valuepercent">
													<?= $enc->html( $this->translate( 'admin', 'Change value in %' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="number" min="0.01" step="0.01" v-bind:disabled="state('price/valuepercent')"
														name="<?= $enc->attr( $this->formparam( array( 'price', 'valuepercent' ) ) ) ?>">
												</div>
											</div>

											<div class="row">
												<div class="col-1">
													<input id="batch-price-rebatepercent" class="form-check-input" type="checkbox" v-on:click="setState('price/rebatepercent')">
												</div>
												<label class="col-4 form-control-label" for="batch-price-rebatepercent">
													<?= $enc->html( $this->translate( 'admin', 'Change rebate in %' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="number" min="0.01" step="0.01" v-bind:disabled="state('price/rebatepercent')"
														name="<?= $enc->attr( $this->formparam( array( 'price', 'rebatepercent' ) ) ) ?>">
												</div>
											</div>

											<div class="row">
												<div class="col-1">
													<input id="batch-price-costspercent" class="form-check-input" type="checkbox" v-on:click="setState('price/costspercent')">
												</div>
												<label class="col-4 form-control-label" for="batch-price-costspercent">
													<?= $enc->html( $this->translate( 'admin', 'Change costs/item in %' ) ) ?>
												</label>
												<div class="col-7">
													<input class="form-control" type="number" min="0.01" step="0.01" v-bind:disabled="state('price/costspercent')"
														name="<?= $enc->attr( $this->formparam( array( 'price', 'costspercent' ) ) ) ?>">
												</div>
											</div>

										</div>
										<div class="col-lg-6">

											<div class="row">
												<div class="col-1">
													<input id="batch-price-taxrates" class="form-check-input" type="checkbox" v-on:click="setState('price/price.taxrates')">
												</div>
												<label class="col-4 form-control-label" for="batch-price-taxrates">
													<?= $enc->html( $this->translate( 'admin', 'Tax rate' ) ) ?>
												</label>
												<div class="col-7">
													<div is="taxrates" tabindex="1"
														name="<?= $enc->attr( $this->formparam( array( 'price', 'price.taxrates' ) ) ) ?>"
														placeholder="<?= $enc->attr( $this->translate( 'admin', 'Tax rate in %' ) ) ?>"
														v-bind:types="<?= $enc->attr( $this->config( 'admin/tax', [] ) ) ?>"
														v-bind:disabled="state('price/price.taxrates')"
														v-bind:taxrates='{"tax": 0}'
														v-bind:readonly="false"
													></div>
												</div>
											</div>

										</div>
									</div>
								</div>
							</div>
							<div class="batch-footer">
								<a class="btn btn-secondary" href="#" v-on:click.prevent="batch = false">
									<?= $enc->html( $this->translate( 'admin', 'Close' ) ) ?>
								</a>
								<button class="btn btn-primary" formaction="<?= $enc->attr( $this->link( 'admin/jqadm/url/batch', ['resource' => 'product'] ) ) ?>">
									<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
								</button>
							</div>
						</td>
					</tr>

					<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
						<?php $url = $enc->attr( $this->link( 'admin/jqadm/url/get', ['id' => $id] + $params ) ) ?>
						<tr class="list-item <?= $this->site()->readonly( $item->getSiteId() ) ?>" data-label="<?= $enc->attr( $item->getLabel() ) ?>">
							<td class="select">
								<input class="form-check-input" type="checkbox" tabindex="1"
									name="<?= $enc->attr( $this->formparam( ['id', ''] ) ) ?>"
									value="<?= $enc->attr( $item->getId() ) ?>"
									v-on:click="toggle(`<?= $enc->js( $id ) ?>`)"
									v-bind:checked="checked(`<?= $enc->js( $id ) ?>`)"
									v-bind:disabled="readonly(`<?= $enc->js( $id ) ?>`)">
							</td>
							<?php if( in_array( 'image', $fields ) ) : $mediaItem = $item->getRefItems( 'media', 'default', 'default' )->first() ?>
								<td class="image"><a class="items-field" href="<?= $url ?>" tabindex="1"><img class="image" src="<?= $mediaItem ? $enc->attr( $this->content( $mediaItem->getPreview(), $mediaItem->getFileSystem() ) ) : '' ?>"></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.id', $fields ) ) : ?>
								<td class="product-id"><a class="items-field" href="<?= $url ?>" tabindex="1"><?= $enc->html( $item->getId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.status', $fields ) ) : ?>
								<td class="product-status"><a class="items-field" href="<?= $url ?>"><div class="fa status-<?= $enc->attr( $item->getStatus() ) ?>"></div></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.type', $fields ) ) : ?>
								<td class="product-type"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getType() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.code', $fields ) ) : ?>
								<td class="product-code"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getCode() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.label', $fields ) ) : ?>
								<td class="product-label"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getLabel() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.datestart', $fields ) ) : ?>
								<td class="product-datestart"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getDateStart() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.dateend', $fields ) ) : ?>
								<td class="product-dateend"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getDateEnd() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.dataset', $fields ) ) : ?>
								<td class="product-dataset"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getDataset() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.url', $fields ) ) : ?>
								<td class="product-url"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getUrl() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.scale', $fields ) ) : ?>
								<td class="product-scale"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getScale() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.target', $fields ) ) : ?>
								<td class="product-target"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTarget() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.config', $fields ) ) : ?>
								<td class="product-config config-item">
									<a class="items-field" href="<?= $url ?>">
										<?php foreach( $item->getConfig() as $key => $value ) : ?>
											<span class="config-key"><?= $enc->html( $key ) ?></span>
											<span class="config-value"><?= $enc->html( $value ) ?></span>
											<br>
										<?php endforeach ?>
									</a>
								</td>
							<?php endif ?>
							<?php if( in_array( 'product.ctime', $fields ) ) : ?>
								<td class="product-ctime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeCreated() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.mtime', $fields ) ) : ?>
								<td class="product-mtime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeModified() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.editor', $fields ) ) : ?>
								<td class="product-editor"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->editor() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.rating', $fields ) ) : ?>
								<td class="product-rating"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getRating() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'product.ratings', $fields ) ) : ?>
								<td class="product-ratings"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getRatings() ) ?></a></td>
							<?php endif ?>

							<td class="actions">
								<a class="btn act-copy fa" tabindex="1"
									href="<?= $enc->attr( $this->link( 'admin/jqadm/url/copy', ['id' => $id] + $params ) ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry' ) ) ?>"
									aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ) ?>">
								</a>
								<?php if( !$this->site()->readonly( $item->getSiteId() ) ) : ?>
									<a class="btn act-delete fa" tabindex="1"
										v-on:click.prevent.stop="askDelete(`<?= $enc->js( $id ) ?>`, $event)"
										href="<?= $enc->attr( $this->link( 'admin/jqadm/url/delete', $params ) ) ?>"
										title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
										aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ) ?>">
									</a>
								<?php endif ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>

		<?php if( $this->get( 'items', map() )->isEmpty() ) : ?>
			<div class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ) ?></div>
		<?php endif ?>
	</form>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
			['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/product/page', [] )]
		);
	?>

	<confirm-delete v-bind:items="unconfirmed" v-bind:show="dialog"
		v-on:close="confirmDelete(false)" v-on:confirm="confirmDelete(true)">
	</confirm-delete>

</div>
<?php $this->block()->stop() ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'page' ) ) ?>
