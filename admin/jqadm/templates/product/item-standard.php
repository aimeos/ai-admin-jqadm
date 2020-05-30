<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2020
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$enc = $this->encoder();


/** admin/jqadm/url/save/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/save/controller
 * @see admin/jqadm/url/save/action
 * @see admin/jqadm/url/save/config
 */
$target = $this->config( 'admin/jqadm/url/save/target' );

/** admin/jqadm/url/save/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/save/target
 * @see admin/jqadm/url/save/action
 * @see admin/jqadm/url/save/config
 */
$cntl = $this->config( 'admin/jqadm/url/save/controller', 'Jqadm' );

/** admin/jqadm/url/save/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/save/target
 * @see admin/jqadm/url/save/controller
 * @see admin/jqadm/url/save/config
 */
$action = $this->config( 'admin/jqadm/url/save/action', 'save' );

/** admin/jqadm/url/save/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  admin/jqadm/url/save/config = array( 'absoluteUri' => true )
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/save/target
 * @see admin/jqadm/url/save/controller
 * @see admin/jqadm/url/save/action
 */
$config = $this->config( 'admin/jqadm/url/save/config', [] );


/** admin/jqadm/product/item/config/suggest
 * List of suggested configuration keys in product item panel
 *
 * Product items can store arbitrary key value pairs. This setting gives editors
 * a hint which config keys are available and are used in the templates.
 *
 * @param string List of suggested config keys
 * @since 2017.10
 * @category Developer
 * @see admin/jqadm/catalog/item/config/suggest
 */
$cfgSuggest = $this->config( 'admin/jqadm/product/item/config/suggest', ['css-class'] );


/** admin/jqadm/navbar-limit
 * Number of JQAdm client links in the navigation bar shown by default
 *
 * The navigation bar is divided into the basic and advanced section.
 * All admin client links in the basic section are always shown
 * while the links in the advanced section are hidden by default. The
 * entries in the navigation bar are defined by admin/jqadm/navbar. Using
 * this setting you can change how many links are always shown.
 *
 * @param integer Number of client resource links
 * @since 2017.10
 * @category Developer
 * @see admin/jqadm/navbar
 */
$navlimit = $this->config( 'admin/jqadm/product/item/navbar-limit', 7 );


/** admin/jqadm/partial/itemactions
 * Relative path to the partial template for displaying the available actions for the item
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
 * @since 2017.10
 * @category Developer
 */


/** admin/jqadm/dataset/product
 * Predefined data sets for products
 *
 * To simplify creating new products by editors, Aimeos supports the defintion
 * of arbitrary product templates. Each template consists of a name and the
 * associated configuration template for that product type (e.g. a T-Shirt).
 * The template names will be listed in a select box in the product detail view.
 *
 * A template definition may look like this in the PHP configuration:
 *
 *  [
 *    'T-Shirt' => [
 *      'characteristic' => [
 *        'variant' => [['attribute.type' => 'color'], ['attribute.type' => 'size']],
 *        'attribute' => [['attribute.type' => 'material']],
 *      ],
 *      'option' => [
 *        'config' => [['attribute.type' => 'sticker']],
 *        'custom' => [['attribute.type' => 'print']],
 *      ],
 *      'price' => [['price.currencyid' => 'EUR', 'price.taxrates' => ['' => '19.00']]],
 *      'stock' => [['stock.type' => 'default']],
 *    ],
 *  ]
 *
 * That definition would add "T-Shirt" to the data set selection and if the editor
 * selects this data set defintion the following will appear:
 *
 * * two variant attribute select boxes (type "color" and "size") in the "Characteristics" tab
 * * one attribute select box (type "material") in the "Characteristics" tab
 * * one select box for configurable attributes (type: "sticker")
 * * one select box for customizable attributes (type: "print")
 * * a price item with currency "EUR" and tax rate pre-filled
 * * a stock item with of type "default" pre-filled
 *
 * You can also define a template for a book with a totally different data set:
 *
 *  [
 *    'Book' => [
 *      'characteristic' => [
 *        'attribute' => [['attribute.type' => 'binding']],
 *        'property' => [['product.property.type' => 'isbn']],
 *      ],
 *      'related' => [
 *        'suggest' => [[], []],
 *      ],
 *      'catalog' => [
 *        'default' => [[]],
 *        'promotion' => [[]],
 *      ],
 *      'media' => [['media.type' => 'default'], ['media.type' => 'download']],
 *      'text' => [
 *        ['text.type' => 'name', 'text.languageid' => 'en'],
 *        ['text.type' => 'short', 'text.languageid' => 'en'],
 *        ['text.type' => 'long', 'text.languageid' => 'en'],
 *      ],
 *      'price' => [['price.currencyid' => 'EUR', 'price.taxrates' => ['' => '7.00']]],
 *      'stock' => [['stock.type' => 'default']],
 *    ],
 *  ]
 *
 * If an editor select the book dataset defintion, the following will appear:
 *
 * * one attribute select box (type "binding") in the "Characteristics" tab
 * * one property line (type "isbn") in the "Characteristics" tab
 * * two select boxes for suggested products in the "Related" tab
 * * one select box for a default category in the "Categories" tab
 * * one select box for a promotion category in the "Categories" tab
 * * two media items ("default" image and "download" file)
 * * three text items for name, short and long texts and "English" language pre-selected
 * * a price item with currency "EUR" and tax rate pre-filled
 * * a stock item with of type "default" pre-filled
 *
 * All types in a template that should be pre-selected must already exist. Make
 * sure you've created them first in the appropriate type panels.
 *
 * Also, the data set template can contain definitions for other tabs as well,
 * e.g. for product variants and bundles.
 *
 * @param array Associative list of name/configuration pairs
 * @category Developer
 * @since 2019.10
 */


$params = $this->get( 'pageParams', [] );
$navlist = array_values( $this->get( 'itemSubparts', [] ) );


?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<form class="item item-product form-horizontal" method="POST" enctype="multipart/form-data" action="<?= $enc->attr( $this->url( $target, $cntl, $action, $params, [], $config ) ); ?>">
	<input id="item-id" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'item', 'product.id' ) ) ); ?>" value="<?= $enc->attr( $this->get( 'itemData/product.id' ) ); ?>" />
	<input id="item-next" type="hidden" name="<?= $enc->attr( $this->formparam( array( 'next' ) ) ); ?>" value="get" />
	<?= $this->csrf()->formfield(); ?>

	<nav class="main-navbar">
		<h1 class="navbar-brand">
			<span class="navbar-title"><?= $enc->html( $this->translate( 'admin', 'Product' ) ); ?></span>
			<span class="navbar-id"><?= $enc->html( $this->get( 'itemData/product.id' ) ); ?></span>
			<span class="navbar-label"><?= $enc->html( $this->get( 'itemData/product.label' ) ?: $this->translate( 'admin', 'New' ) ); ?></span>
			<span class="navbar-site"><?= $enc->html( $this->site()->match( $this->get( 'itemData/product.siteid' ) ) ); ?></span>
		</h1>
		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard' ), ['params' => $params] ); ?>
		</div>
	</nav>

	<div class="row item-container">

		<div class="col-md-3 item-navbar">
			<div class="navbar-content">
				<ul class="nav nav-tabs flex-md-column flex-wrap d-flex justify-content-between" role="tablist">
					<li class="nav-item basic">
						<a class="nav-link active" href="#basic" data-toggle="tab" role="tab" aria-expanded="true" aria-controls="basic">
							<?= $enc->html( $this->translate( 'admin', 'Basic' ) ); ?>
						</a>
					</li>

					<?php foreach( array_splice( $navlist, 0, $navlimit ) as $idx => $subpart ) : ?>
						<li class="nav-item <?= $enc->attr( $subpart ); ?>">
							<a class="nav-link" href="#<?= $enc->attr( $subpart ); ?>" data-toggle="tab" role="tab" tabindex="<?= ++$idx + 1; ?>">
								<?= $enc->html( $this->translate( 'admin', $subpart ) ); ?>
							</a>
						</li>
					<?php endforeach; ?>

					<li class="separator"><i class="nav-link icon more"></i></li>

					<?php foreach( $navlist as $idx => $subpart ) : ?>
						<li class="nav-item advanced <?= $enc->attr( $subpart ); ?>">
							<a class="nav-link" href="#<?= $enc->attr( $subpart ); ?>" data-toggle="tab" role="tab" tabindex="<?= ++$idx + $navlimit + 1; ?>">
								<?= $enc->html( $this->translate( 'admin', $subpart ) ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>

				<div class="item-meta text-muted">
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Modified' ) ); ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/product.mtime' ) ); ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Created' ) ); ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/product.ctime' ) ); ?></span>
					</small>
					<small>
						<?= $enc->html( $this->translate( 'admin', 'Editor' ) ); ?>:
						<span class="meta-value"><?= $enc->html( $this->get( 'itemData/product.editor' ) ); ?></span>
					</small>
				</div>
			</div>
		</div>

		<div class="col-md-9 item-content tab-content">

			<div id="basic" class="row item-basic tab-pane fade show active" role="tabpanel" aria-labelledby="basic">

				<div class="col-xl-6 content-block vue-block <?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ); ?>"
					data-data="<?= $enc->attr( $this->get( 'itemData', new stdClass() ) ) ?>">

					<?php if( $this->config( 'admin/jqadm/dataset/product', [] ) !== [] ) : ?>
						<div class="form-group row optional">
							<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Data set' ) ); ?></label>
							<div class="col-sm-8">
								<select class="form-control custom-select item-set" tabindex="1"
									name="<?= $enc->attr( $this->formparam( array( 'item', 'product.dataset' ) ) ); ?>"
									<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ); ?> >
									<option value="">
										<?= $enc->html( $this->translate( 'admin', 'None' ) ); ?>
									</option>

									<?php foreach( $this->config( 'admin/jqadm/dataset/product', [] ) as $name => $config ) : ?>
										<option value="<?= $enc->attr( $name ); ?>" <?= $selected( $this->get( 'itemData/product.dataset' ), $name ); ?>
											data-config="<?= $enc->attr( $config ) ?>" >
											<?= $enc->html( $name ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-sm-12 form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Depending on the selected data set, the list of shown fields for the product will be different' ) ); ?>
							</div>
						</div>
					<?php endif ?>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Status' ) ); ?></label>
						<div class="col-sm-8">
							<select class="form-control custom-select item-status" required="required" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'product.status' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ); ?> >
								<option value="">
									<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
								</option>
								<option value="1" <?= $selected( $this->get( 'itemData/product.status', 1 ), 1 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:1' ) ); ?>
								</option>
								<option value="0" <?= $selected( $this->get( 'itemData/product.status', 1 ), 0 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:0' ) ); ?>
								</option>
								<option value="-1" <?= $selected( $this->get( 'itemData/product.status', 1 ), -1 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:-1' ) ); ?>
								</option>
								<option value="-2" <?= $selected( $this->get( 'itemData/product.status', 1 ), -2 ); ?> >
									<?= $enc->html( $this->translate( 'mshop/code', 'status:-2' ) ); ?>
								</option>
							</select>
						</div>
					</div>
					<?php if( ( $types = $this->get( 'itemTypes', map() )->col( 'product.type.label', 'product.type.code' ) )->count() !== 1 ) : ?>
						<div class="form-group row mandatory">
							<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
							<div class="col-sm-8">
								<select is="select-component" class="form-control custom-select item-type" required v-bind:tabindex="'1'"
									v-bind:readonly="'<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ); ?>' ? true : false"
									v-bind:name="'<?= $enc->attr( $this->formparam( ['item', 'product.type'] ) ); ?>'"
									v-bind:text="'<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>'"
									v-bind:items="JSON.parse('<?= $enc->attr( $types->toArray() ) ?>')"
									v-model="data['product.type']" >
									<option value="<?= $enc->attr( $this->get( 'itemData/product.type' ) ) ?>">
										<?= $enc->html( $types[$this->get( 'itemData/product.type', '' )] ?? $this->translate( 'admin', 'Please select' ) ) ?>
									</option>
								</select>
							</div>
						</div>
					<?php else : ?>
						<input class="item-type" type="hidden"
							name="<?= $enc->attr( $this->formparam( array( 'item', 'product.type' ) ) ); ?>"
							value="<?= $enc->attr( $types->firstKey() ) ?>" />
					<?php endif; ?>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'SKU' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-code" type="text" required="required" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'product.code' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'EAN, SKU or article number (required)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/product.code' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Unique article code related to stock levels, e.g. from the ERP system, an EAN/GTIN number or self invented' ) ); ?>
						</div>
					</div>
					<div class="form-group row mandatory">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-label" type="text" required="required" tabindex="1"
								name="<?= $this->formparam( array( 'item', 'product.label' ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Internal name (required)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/product.label' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Internal article name, will be used on the web site and for searching only if no other product names in any language exist' ) ); ?>
						</div>
					</div>

					<div class="separator"><i class="icon more"></i></div>

					<div class="form-group row optional advanced">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'URL segment' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-label" type="text" tabindex="1"
								name="<?= $this->formparam( array( 'item', 'product.url' ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Name in URL (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/product.url' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'The name of the product shown in the URL, will be used if no language specific URL segment exists' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional advanced">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Quantity scale' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-scale" type="number" tabindex="1" min="0.001" step="0.001"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'product.scale' ) ) ); ?>"
								value="<?= $enc->attr( $this->datetime( $this->get( 'itemData/product.scale', 1 ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'The step value allowed for quantities in the basket, e.g. "0.1" for fractional quantities or "5" for multiple of five articles' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional advanced">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-datestart" type="datetime-local" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'product.datestart' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->datetime( $this->get( 'itemData/product.datestart' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'The article is only shown on the web site after that date and time, useful or seasonal articles' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional advanced">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'End date' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-dateend" type="datetime-local" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'product.dateend' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->datetime( $this->get( 'itemData/product.dateend' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'The article is only shown on the web site until that date and time, useful or seasonal articles' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional advanced">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Created' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-ctime" type="datetime-local" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'product.ctime' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->datetime( $this->get( 'itemData/product.ctime' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Since when the product is available, used for sorting in the front-end' ) ); ?>
						</div>
					</div>
					<div class="form-group row optional advanced warning">
						<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'URL target' ) ); ?></label>
						<div class="col-sm-8">
							<input class="form-control item-target" type="text" tabindex="1"
								name="<?= $enc->attr( $this->formparam( array( 'item', 'product.target' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Route or page ID (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'itemData/product.target' ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ); ?> />
						</div>
						<div class="col-sm-12 form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Route name or page ID of the product detail page if this product should shown on a different page' ) ); ?>
						</div>
					</div>
				</div><!--

				--><div class="col-xl-6 content-block vue-block <?= $this->site()->readonly( $this->get( 'itemData/product.siteid' ) ); ?>"
					data-data="<?= $enc->attr( $this->get( 'itemData', new stdClass() ) ) ?>">

					<config-table inline-template
						v-bind:readonly="data['product.siteid'] != '<?= $this->site()->siteid() ?>'"
						v-bind:items="data['config']" v-on:change="data['config'] = $event">

						<table class="item-config table table-striped">
							<thead>
								<tr>
									<th class="config-row-key">
										<span class="help"><?= $enc->html( $this->translate( 'admin', 'Option' ) ); ?></span>
										<div class="form-text text-muted help-text">
											<?= $enc->html( $this->translate( 'admin', 'Article specific configuration options, will be available as key/value pairs in the templates' ) ); ?>
										</div>
									</th>
									<th class="config-row-value"><?= $enc->html( $this->translate( 'admin', 'Value' ) ); ?></th>
									<th class="actions">
										<div v-if="!readonly" class="btn act-add fa" tabindex="1" v-on:click="add()"
											title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ); ?>"></div>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(entry, pos) in items" v-bind:key="pos" class="config-item">
									<td class="config-row-key">
										<input is="auto-complete" required class="form-control" v-bind:readonly="readonly" tabindex="1"
											v-bind:name="'<?= $enc->attr( $this->formparam( array( 'item', 'config', '_pos_', 'key' ) ) ); ?>'.replace('_pos_', pos)"
											v-bind:keys="JSON.parse('<?= $enc->attr( $this->config( 'admin/jqadm/product/item/config/suggest', ['css-class'] ) ) ?>')"
											v-model="entry.key" />
									</td>
									<td class="config-row-value">
										<input class="form-control" v-bind:readonly="readonly" v-bind:tabindex="1"
											v-bind:name="'<?= $enc->attr( $this->formparam( array( 'item', 'config', '_pos_', 'val' ) ) ); ?>'.replace('_pos_', pos)"
											v-model="entry.val" />
									</td>
									<td class="actions">
										<div v-if="!readonly" class="btn act-delete fa" tabindex="1" v-on:click="remove(pos)"
											title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ); ?>"></div>
									</td>
								</tr>
							</tbody>
						</table>
					</config-table>
				</div>

			</div>

			<?= $this->get( 'itemBody' ); ?>

		</div>

		<div class="item-actions">
			<?= $this->partial( $this->config( 'admin/jqadm/partial/itemactions', 'common/partials/itemactions-standard' ), ['params' => $params] ); ?>
		</div>
	</div>
</form>

<?php $this->block()->stop(); ?>


<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard' ) ); ?>
