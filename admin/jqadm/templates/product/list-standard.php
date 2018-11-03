<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
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
 * @category Developer
 * @see admin/jqadm/url/search/controller
 * @see admin/jqadm/url/search/action
 * @see admin/jqadm/url/search/config
 */
$target = $this->config( 'admin/jqadm/url/search/target' );

/** admin/jqadm/url/search/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/search/target
 * @see admin/jqadm/url/search/action
 * @see admin/jqadm/url/search/config
 */
$controller = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );

/** admin/jqadm/url/search/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/search/target
 * @see admin/jqadm/url/search/controller
 * @see admin/jqadm/url/search/config
 */
$action = $this->config( 'admin/jqadm/url/search/action', 'search' );

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
 * @category Developer
 * @see admin/jqadm/url/search/target
 * @see admin/jqadm/url/search/controller
 * @see admin/jqadm/url/search/action
 */
$config = $this->config( 'admin/jqadm/url/search/config', [] );


/** admin/jqadm/url/create/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/create/controller
 * @see admin/jqadm/url/create/action
 * @see admin/jqadm/url/create/config
 */
$newTarget = $this->config( 'admin/jqadm/url/create/target' );

/** admin/jqadm/url/create/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/create/target
 * @see admin/jqadm/url/create/action
 * @see admin/jqadm/url/create/config
 */
$newCntl = $this->config( 'admin/jqadm/url/create/controller', 'Jqadm' );

/** admin/jqadm/url/create/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/create/target
 * @see admin/jqadm/url/create/controller
 * @see admin/jqadm/url/create/config
 */
$newAction = $this->config( 'admin/jqadm/url/create/action', 'create' );

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
 * @category Developer
 * @see admin/jqadm/url/create/target
 * @see admin/jqadm/url/create/controller
 * @see admin/jqadm/url/create/action
 */
$newConfig = $this->config( 'admin/jqadm/url/create/config', [] );


/** admin/jqadm/url/get/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/get/controller
 * @see admin/jqadm/url/get/action
 * @see admin/jqadm/url/get/config
 */
$getTarget = $this->config( 'admin/jqadm/url/get/target' );

/** admin/jqadm/url/get/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/get/target
 * @see admin/jqadm/url/get/action
 * @see admin/jqadm/url/get/config
 */
$getCntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );

/** admin/jqadm/url/get/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/get/target
 * @see admin/jqadm/url/get/controller
 * @see admin/jqadm/url/get/config
 */
$getAction = $this->config( 'admin/jqadm/url/get/action', 'get' );

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
 * @category Developer
 * @see admin/jqadm/url/get/target
 * @see admin/jqadm/url/get/controller
 * @see admin/jqadm/url/get/action
 */
$getConfig = $this->config( 'admin/jqadm/url/get/config', [] );


/** admin/jqadm/url/copy/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/copy/controller
 * @see admin/jqadm/url/copy/action
 * @see admin/jqadm/url/copy/config
 */
$copyTarget = $this->config( 'admin/jqadm/url/copy/target' );

/** admin/jqadm/url/copy/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/copy/target
 * @see admin/jqadm/url/copy/action
 * @see admin/jqadm/url/copy/config
 */
$copyCntl = $this->config( 'admin/jqadm/url/copy/controller', 'Jqadm' );

/** admin/jqadm/url/copy/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/copy/target
 * @see admin/jqadm/url/copy/controller
 * @see admin/jqadm/url/copy/config
 */
$copyAction = $this->config( 'admin/jqadm/url/copy/action', 'copy' );

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
 * @category Developer
 * @see admin/jqadm/url/copy/target
 * @see admin/jqadm/url/copy/controller
 * @see admin/jqadm/url/copy/action
 */
$copyConfig = $this->config( 'admin/jqadm/url/copy/config', [] );


/** admin/jqadm/url/delete/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/delete/controller
 * @see admin/jqadm/url/delete/action
 * @see admin/jqadm/url/delete/config
 */
$delTarget = $this->config( 'admin/jqadm/url/delete/target' );

/** admin/jqadm/url/delete/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/delete/target
 * @see admin/jqadm/url/delete/action
 * @see admin/jqadm/url/delete/config
 */
$delCntl = $this->config( 'admin/jqadm/url/delete/controller', 'Jqadm' );

/** admin/jqadm/url/delete/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/delete/target
 * @see admin/jqadm/url/delete/controller
 * @see admin/jqadm/url/delete/config
 */
$delAction = $this->config( 'admin/jqadm/url/delete/action', 'delete' );

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
 * @category Developer
 * @see admin/jqadm/url/delete/target
 * @see admin/jqadm/url/delete/controller
 * @see admin/jqadm/url/delete/action
 */
$delConfig = $this->config( 'admin/jqadm/url/delete/config', [] );


/** admin/jqadm/partial/columns
 * Relative path to the partial template for displaying the column selector in the list views
 *
 * The template file contains the HTML code and processing instructions
 * to generate the result shown in the administration interface. The
 * configuration string is the path to the template file relative
 * to the templates directory (usually in admin/jqadm/templates).
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
 * @category Developer
 * @see admin/jqadm/partial/confirm
 * @see admin/jqadm/partial/error
 */

/** admin/jqadm/partial/listhead
 * Relative path to the partial template for displaying the table header in the list views
 *
 * The template file contains the HTML code and processing instructions
 * to generate the result shown in the administration interface. The
 * configuration string is the path to the template file relative
 * to the templates directory (usually in admin/jqadm/templates).
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
 * @category Developer
 * @see admin/jqadm/partial/confirm
 * @see admin/jqadm/partial/error
 */

/** admin/jqadm/partial/listsearch
 * Relative path to the partial template for displaying the table search row in the list views
 *
 * The template file contains the HTML code and processing instructions
 * to generate the result shown in the administration interface. The
 * configuration string is the path to the template file relative
 * to the templates directory (usually in admin/jqadm/templates).
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
 * @category Developer
 * @see admin/jqadm/partial/confirm
 * @see admin/jqadm/partial/error
 */

/** admin/jqadm/partial/navsearch
 * Relative path to the partial template for displaying the search filter in the navigation bar
 *
 * The template file contains the HTML code and processing instructions
 * to generate the result shown in the administration interface. The
 * configuration string is the path to the template file relative
 * to the templates directory (usually in admin/jqadm/templates).
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
 * @category Developer
 * @see admin/jqadm/partial/confirm
 * @see admin/jqadm/partial/error
 */

/** admin/jqadm/partial/pagination
 * Relative path to the partial template for displaying the pagination
 *
 * The template file contains the HTML code and processing instructions
 * to generate the result shown in the administration interface. The
 * configuration string is the path to the template file relative
 * to the templates directory (usually in admin/jqadm/templates).
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
 * @category Developer
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
 * to the templates directory (usually in admin/jqadm/templates).
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
 * @category Developer
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
 * @category Developer
 */
$default = $this->config( 'admin/jqadm/product/fields', ['product.id', 'product.status', 'product.typeid', 'product.code', 'product.label'] );
$fields = $this->session( 'aimeos/admin/jqadm/product/fields', $default );

$searchParams = $params = $this->get( 'pageParams', [] );
$searchParams['page']['start'] = 0;

$typeList = [];
foreach( $this->get( 'itemTypes', [] ) as $id => $typeItem ) {
	$typeList[$id] = $typeItem->getCode();
}

$columnList = [
	'product.id' => $this->translate( 'admin', 'ID' ),
	'product.status' => $this->translate( 'admin', 'Status' ),
	'product.typeid' => $this->translate( 'admin', 'Type' ),
	'product.code' => $this->translate( 'admin', 'Code' ),
	'product.label' => $this->translate( 'admin', 'Label' ),
	'product.datestart' => $this->translate( 'admin', 'Start date' ),
	'product.dateend' => $this->translate( 'admin', 'End date' ),
	'product.config' => $this->translate( 'admin', 'Config' ),
	'product.ctime' => $this->translate( 'admin', 'Created' ),
	'product.mtime' => $this->translate( 'admin', 'Modified' ),
	'product.editor' => $this->translate( 'admin', 'Editor' ),
];


?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<nav class="main-navbar">

	<span class="navbar-brand">
		<?= $enc->html( $this->translate( 'admin', 'Product' ) ); ?>
		<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ); ?>)</span>
	</span>

	<?= $this->partial(
		$this->config( 'admin/jqadm/partial/navsearch', 'common/partials/navsearch-standard.php' ), [
			'filter' => $this->session( 'aimeos/admin/jqadm/product/filter', [] ),
			'filterAttributes' => $this->get( 'filterAttributes', [] ),
			'filterOperators' => $this->get( 'filterOperators', [] ),
			'params' => $params,
		]
	); ?>
</nav>


<?= $this->partial(
		$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard.php' ),
		['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
		'page' =>$this->session( 'aimeos/admin/jqadm/product/page', [] )]
	);
?>

<form class="list list-product" method="POST" action="<?= $enc->attr( $this->url( $target, $controller, $action, $searchParams, [], $config ) ); ?>">
	<?= $this->csrf()->formfield(); ?>

	<table class="list-items table table-hover table-striped">
		<thead class="list-header">
			<tr>
				<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-standard.php' ),
						['fields' => $fields, 'params' => $params, 'data' => $columnList,
						'sort' => $this->session( 'aimeos/admin/jqadm/product/sort', 'product.id' )]
					);
				?>

				<th class="actions">
					<a class="btn fa act-add" tabindex="1"
						href="<?= $enc->attr( $this->url( $newTarget, $newCntl, $newAction, $params, [], $newConfig ) ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ); ?>">
					</a>

					<?= $this->partial(
							$this->config( 'admin/jqadm/partial/columns', 'common/partials/columns-standard.php' ),
							['fields' => $fields, 'data' => $columnList]
						);
					?>
				</th>
			</tr>
		</thead>
		<tbody>

			<?= $this->partial(
				$this->config( 'admin/jqadm/partial/listsearch', 'common/partials/listsearch-standard.php' ), [
					'fields' => $fields, 'filter' => $this->session( 'aimeos/admin/jqadm/product/filter', [] ),
					'data' => [
						'product.id' => ['op' => '=='],
						'product.status' => ['op' => '==', 'type' => 'select', 'val' => [
							'1' => $this->translate( 'mshop/code', 'status:1' ),
							'0' => $this->translate( 'mshop/code', 'status:0' ),
							'-1' => $this->translate( 'mshop/code', 'status:-1' ),
							'-2' => $this->translate( 'mshop/code', 'status:-2' ),
						]],
						'product.typeid' => ['op' => '==', 'type' => 'select', 'val' => $typeList],
						'product.code' => [],
						'product.label' => [],
						'product.datestart' => ['op' => '>=', 'type' => 'datetime-local'],
						'product.dateend' => ['op' => '>=', 'type' => 'datetime-local'],
						'product.config' => ['op' => '~='],
						'product.ctime' => ['op' => '>=', 'type' => 'datetime-local'],
						'product.mtime' => ['op' => '>=', 'type' => 'datetime-local'],
						'product.editor' => [],
					]
				] );
			?>

			<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
				<?php $url = $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['id' => $id] + $params, [], $getConfig ) ); ?>
				<tr class="<?= $this->site()->readonly( $item->getSiteId() ); ?>">
					<?php if( in_array( 'product.id', $fields ) ) : ?>
						<td class="product-id"><a class="items-field" href="<?= $url; ?>" tabindex="1"><?= $enc->html( $item->getId() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'product.status', $fields ) ) : ?>
						<td class="product-status"><a class="items-field" href="<?= $url; ?>"><div class="fa status-<?= $enc->attr( $item->getStatus() ); ?>"></div></a></td>
					<?php endif; ?>
					<?php if( in_array( 'product.typeid', $fields ) ) : ?>
						<td class="product-type"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getType() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'product.code', $fields ) ) : ?>
						<td class="product-code"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getCode() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'product.label', $fields ) ) : ?>
						<td class="product-label"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getLabel() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'product.datestart', $fields ) ) : ?>
						<td class="product-datestart"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDateStart() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'product.dateend', $fields ) ) : ?>
						<td class="product-dateend"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDateEnd() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'product.config', $fields ) ) : ?>
						<td class="product-config config-item">
							<a class="items-field" href="<?= $url; ?>">
								<?php foreach( $item->getConfig() as $key => $value ) : ?>
									<span class="config-key"><?= $enc->html( $key ); ?></span>
									<span class="config-value"><?= $enc->html( !is_scalar( $value ) ? json_encode( $value ) : $value ); ?></span>
									<br/>
								<?php endforeach; ?>
							</a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'product.ctime', $fields ) ) : ?>
						<td class="product-ctime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeCreated() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'product.mtime', $fields ) ) : ?>
						<td class="product-mtime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeModified() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'product.editor', $fields ) ) : ?>
						<td class="product-editor"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getEditor() ); ?></a></td>
					<?php endif; ?>

					<td class="actions">
						<a class="btn act-copy fa" tabindex="1"
							href="<?= $enc->attr( $this->url( $copyTarget, $copyCntl, $copyAction, ['id' => $id] + $params, [], $copyConfig ) ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry') ); ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ); ?>"></a>
						<?php if( !$this->site()->readonly( $item->getSiteId() ) ) : ?>
							<a class="btn act-delete fa" tabindex="1"
								href="<?= $enc->attr( $this->url( $delTarget, $delCntl, $delAction, ['resource' => 'product', 'id' => $id] + $params, [], $delConfig ) ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ); ?>"></a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if( $this->get( 'items', [] ) === [] ) : ?>
		<div class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ); ?></div>
	<?php endif; ?>
</form>

<?= $this->partial(
		$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard.php' ),
		['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get( 'total' ),
		'page' => $this->session( 'aimeos/admin/jqadm/product/page', [] )]
	);
?>

<?php $this->block()->stop(); ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard.php' ) ); ?>
