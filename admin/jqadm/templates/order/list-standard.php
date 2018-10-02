<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */

$enc = $this->encoder();


$target = $this->config( 'admin/jqadm/url/search/target' );
$controller = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/search/action', 'search' );
$config = $this->config( 'admin/jqadm/url/search/config', [] );

$getTarget = $this->config( 'admin/jqadm/url/get/target' );
$getCntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$getAction = $this->config( 'admin/jqadm/url/get/action', 'get' );
$getConfig = $this->config( 'admin/jqadm/url/get/config', [] );

$copyTarget = $this->config( 'admin/jqadm/url/copy/target' );
$copyCntl = $this->config( 'admin/jqadm/url/copy/controller', 'Jqadm' );
$copyAction = $this->config( 'admin/jqadm/url/copy/action', 'copy' );
$copyConfig = $this->config( 'admin/jqadm/url/copy/config', [] );

/** admin/jqadm/url/export/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2017.10
 * @category Developer
 * @see admin/jqadm/url/export/controller
 * @see admin/jqadm/url/export/action
 * @see admin/jqadm/url/export/config
 */
$expTarget = $this->config( 'admin/jqadm/url/export/target' );

/** admin/jqadm/url/export/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/export/target
 * @see admin/jqadm/url/export/action
 * @see admin/jqadm/url/export/config
 */
$expCntl = $this->config( 'admin/jqadm/url/export/controller', 'Jqadm' );

/** admin/jqadm/url/export/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/export/target
 * @see admin/jqadm/url/export/controller
 * @see admin/jqadm/url/export/config
 */
$expAction = $this->config( 'admin/jqadm/url/export/action', 'export' );

/** admin/jqadm/url/export/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  admin/jqadm/url/export/config = ['absoluteUri' => true )
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2016.04
 * @category Developer
 * @see admin/jqadm/url/export/target
 * @see admin/jqadm/url/export/controller
 * @see admin/jqadm/url/export/action
 */
$expConfig = $this->config( 'admin/jqadm/url/export/config', [] );


/** admin/jqadm/order/fields
 * List of order columns that should be displayed in the list view
 *
 * Changes the list of order columns shown by default in the order list view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "order.id" for the order ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.07
 * @category Developer
 */
$default = $this->config( 'admin/jqadm/order/fields', ['order.id', 'order.ctime', 'order.statuspayment', 'order.base.address.lastname'] );
$fields = $this->session( 'aimeos/admin/jqadm/order/fields', $default );

$searchParams = $params = $this->get( 'pageParams', [] );
$searchParams['page']['start'] = 0;

$baseItems = $this->get( 'baseItems', [] );


$columnList = [
	'order.id' => $this->translate( 'admin', 'Invoice' ),
	'order.type' => $this->translate( 'admin', 'Type' ),
	'order.statuspayment' => $this->translate( 'admin', 'Pay status' ),
	'order.datepayment' => $this->translate( 'admin', 'Pay date' ),
	'order.statusdelivery' => $this->translate( 'admin', 'Ship status' ),
	'order.datedelivery' => $this->translate( 'admin', 'Ship date' ),
	'order.relatedid' => $this->translate( 'admin', 'Related order' ),
	'order.ctime' => $this->translate( 'admin', 'Created' ),
	'order.mtime' => $this->translate( 'admin', 'Modified' ),
	'order.editor' => $this->translate( 'admin', 'Editor' ),
	'order.base.customerid' => $this->translate( 'admin', 'Customer ID' ),
	'order.base.sitecode' => $this->translate( 'admin', 'Site' ),
	'order.base.languageid' => $this->translate( 'admin', 'Language' ),
	'order.base.currencyid' => $this->translate( 'admin', 'Currency' ),
	'order.base.price' => $this->translate( 'admin', 'Price' ),
	'order.base.costs' => $this->translate( 'admin', 'Costs' ),
	'order.base.rebate' => $this->translate( 'admin', 'Rebate' ),
	'order.base.taxvalue' => $this->translate( 'admin', 'Tax' ),
	'order.base.taxflag' => $this->translate( 'admin', 'Incl. tax' ),
	'order.base.comment' => $this->translate( 'admin', 'Comment' ),
	'order.base.address.salutation' => $this->translate( 'admin', 'Salutation' ),
	'order.base.address.company' => $this->translate( 'admin', 'Company' ),
	'order.base.address.vatid' => $this->translate( 'admin', 'VAT ID' ),
	'order.base.address.title' => $this->translate( 'admin', 'Title' ),
	'order.base.address.firstname' => $this->translate( 'admin', 'First name' ),
	'order.base.address.lastname' => $this->translate( 'admin', 'Last name' ),
	'order.base.address.address1' => $this->translate( 'admin', 'Street' ),
	'order.base.address.address2' => $this->translate( 'admin', 'House number' ),
	'order.base.address.address3' => $this->translate( 'admin', 'Floor' ),
	'order.base.address.postal' => $this->translate( 'admin', 'Zip code' ),
	'order.base.address.city' => $this->translate( 'admin', 'City' ),
	'order.base.address.state' => $this->translate( 'admin', 'State' ),
	'order.base.address.countryid' => $this->translate( 'admin', 'Country' ),
	'order.base.address.languageid' => $this->translate( 'admin', 'Language' ),
	'order.base.address.telephone' => $this->translate( 'admin', 'Telephone' ),
	'order.base.address.telefax' => $this->translate( 'admin', 'Facsimile' ),
	'order.base.address.email' => $this->translate( 'admin', 'E-Mail' ),
	'order.base.address.website' => $this->translate( 'admin', 'Web site' ),
	'order.base.service.code' => $this->translate( 'admin', 'Service' ),
	'order.base.service.name' => $this->translate( 'admin', 'Service name' ),
	'order.base.service.price' => $this->translate( 'admin', 'Service price' ),
	'order.base.service.costs' => $this->translate( 'admin', 'Service costs' ),
	'order.base.service.rebate' => $this->translate( 'admin', 'Service rebate' ),
];

$paymentStatusList = [
	'-1' => $this->translate( 'mshop/code', 'pay:-1' ),
	'0' => $this->translate( 'mshop/code', 'pay:0' ),
	'1' => $this->translate( 'mshop/code', 'pay:1' ),
	'2' => $this->translate( 'mshop/code', 'pay:2' ),
	'3' => $this->translate( 'mshop/code', 'pay:3' ),
	'4' => $this->translate( 'mshop/code', 'pay:4' ),
	'5' => $this->translate( 'mshop/code', 'pay:5' ),
	'6' => $this->translate( 'mshop/code', 'pay:6' ),
];

$deliveryStatusList = [
	'-1' => $this->translate( 'mshop/code', 'stat:-1' ),
	'0' => $this->translate( 'mshop/code', 'stat:0' ),
	'1' => $this->translate( 'mshop/code', 'stat:1' ),
	'2' => $this->translate( 'mshop/code', 'stat:2' ),
	'3' => $this->translate( 'mshop/code', 'stat:3' ),
	'4' => $this->translate( 'mshop/code', 'stat:4' ),
	'5' => $this->translate( 'mshop/code', 'stat:5' ),
	'6' => $this->translate( 'mshop/code', 'stat:6' ),
	'7' => $this->translate( 'mshop/code', 'stat:7' ),
];

$statusList = [
	0 => $this->translate( 'admin', 'no' ),
	1 => $this->translate( 'admin', 'yes' ),
];


?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<nav class="main-navbar">

	<span class="navbar-brand">
		<?= $enc->html( $this->translate( 'admin', 'Order' ) ); ?>
		<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ); ?>)</span>
	</span>

	<?= $this->partial(
		$this->config( 'admin/jqadm/partial/navsearch', 'common/partials/navsearch-standard.php' ), [
			'filter' => $this->session( 'aimeos/admin/jqadm/order/filter', [] ),
			'filterAttributes' => $this->get( 'filterAttributes', [] ),
			'filterOperators' => $this->get( 'filterOperators', [] ),
			'params' => $params,
		]
	); ?>
</nav>


<?= $this->partial(
		$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard.php' ),
		['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
		'page' => $this->session( 'aimeos/admin/jqadm/order/page', [] )]
	);
?>

<?php $searchParam = $params; unset( $searchParam['filter'] ); ?>
<form class="list list-order" method="POST" action="<?= $enc->attr( $this->url( $target, $controller, $action, $searchParams, [], $config ) ); ?>">
	<?= $this->csrf()->formfield(); ?>

	<table class="list-items table table-hover table-striped">
		<thead class="list-header">
			<tr>
				<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-standard.php' ),
						['fields' => $fields, 'params' => $params, 'data' => $columnList, 'sort' => $this->session( 'aimeos/admin/jqadm/order/sort' )]
					);
				?>

				<th class="actions">
					<a class="btn fa act-download" tabindex="1"
						href="<?= $enc->attr( $this->url( $expTarget, $expCntl, $expAction, $params, [], $expConfig ) ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Download') ); ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Download' ) ); ?>">
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
					'fields' => $fields, 'filter' => $this->session( 'aimeos/admin/jqadm/order/filter', [] ),
					'data' => [
						'order.id' => ['op' => '=='],
						'order.type' => [],
						'order.statuspayment' => ['op' => '==', 'type' => 'select', 'val' => $paymentStatusList],
						'order.datepayment' => ['op' => '>=', 'type' => 'date'],
						'order.statusdelivery' => ['op' => '==', 'type' => 'select', 'val' => $deliveryStatusList],
						'order.datedelivery' => ['op' => '>=', 'type' => 'date'],
						'order.relatedid' => ['op' => '=='],
						'order.ctime' => ['op' => '>=', 'type' => 'date'],
						'order.mtime' => ['op' => '>=', 'type' => 'date'],
						'order.editor' => [],
						'order.base.customerid' => ['op' => '=='],
						'order.base.sitecode' => ['op' => '=='],
						'order.base.languageid' => ['op' => '=='],
						'order.base.currencyid' => ['op' => '=='],
						'order.base.price' => ['op' => '==', 'type' => 'number'],
						'order.base.costs' => ['op' => '==', 'type' => 'number'],
						'order.base.rebate' => ['op' => '==', 'type' => 'number'],
						'order.base.taxvalue' => ['op' => '==', 'type' => 'number'],
						'order.base.taxflag' => ['op' => '==', 'type' => 'select', 'val' => $statusList],
						'order.base.comment' => [],
						'order.base.address.salutation' => ['op' => '==', 'type' => 'select', 'val' => [
							'company' => 'company', 'mr' => 'mr', 'mrs' => 'mrs', 'miss' => 'miss'
						]],
						'order.base.address.company' => [],
						'order.base.address.vatid' => [],
						'order.base.address.title' => [],
						'order.base.address.firstname' => [],
						'order.base.address.lastname' => [],
						'order.base.address.address1' => [],
						'order.base.address.address2' => [],
						'order.base.address.address3' => [],
						'order.base.address.postal' => [],
						'order.base.address.city' => [],
						'order.base.address.state' => [],
						'order.base.address.countryid' => ['op' => '=='],
						'order.base.address.languageid' => ['op' => '=='],
						'order.base.address.telephone' => [],
						'order.base.address.telefax' => [],
						'order.base.address.email' => [],
						'order.base.address.website' => [],
						'order.base.service.code' => [],
						'order.base.service.name' => [],
						'order.base.service.price' => ['op' => '==', 'type' => 'number'],
						'order.base.service.costs' => ['op' => '==', 'type' => 'number'],
						'order.base.service.rebate' => ['op' => '==', 'type' => 'number'],
					]
				] );
			?>

			<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
				<?php $url = $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['id' => $item->getBaseId()] + $params, [], $getConfig ) ); ?>
				<tr class="list-item <?= $this->site()->readonly( $item->getSiteId() ); ?>">
					<?php if( in_array( 'order.id', $fields ) ) : ?>
						<td class="order-id"><a class="items-field" href="<?= $url; ?>" tabindex="1"><?= $enc->html( $item->getId() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.type', $fields ) ) : ?>
						<td class="order-type"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getType() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.statuspayment', $fields ) ) : ?>
						<td class="order-statuspayment"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $paymentStatusList[$item->getPaymentStatus()] ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.datepayment', $fields ) ) : ?>
						<td class="order-datepayment"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDatePayment() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.statusdelivery', $fields ) ) : ?>
						<td class="order-statusdelivery"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $deliveryStatusList[$item->getDeliveryStatus()] ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.datedelivery', $fields ) ) : ?>
						<td class="order-datedelivery"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDateDelivery() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.relatedid', $fields ) ) : ?>
						<td class="order-relatedid"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getRelatedId() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.ctime', $fields ) ) : ?>
						<td class="order-ctime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeCreated() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.mtime', $fields ) ) : ?>
						<td class="order-mtime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeModified() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.editor', $fields ) ) : ?>
						<td class="order-editor"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getEditor() ); ?></a></td>
					<?php endif; ?>

					<?php $baseItem = ( isset( $baseItems[$item->getBaseId()] ) ? $baseItems[$item->getBaseId()] : null ); ?>

					<?php if( in_array( 'order.base.customerid', $fields ) ) : ?>
						<td class="order-base-customerid"><a class="items-field" href="<?= $url; ?>"><?= $baseItem ? $enc->html( $baseItem->getCustomerId() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.sitecode', $fields ) ) : ?>
						<td class="order-base-sitecode"><a class="items-field" href="<?= $url; ?>"><?= $baseItem ? $enc->html( $baseItem->getSiteCode() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.languageid', $fields ) ) : ?>
						<td class="order-base-languageid"><a class="items-field" href="<?= $url; ?>"><?= $baseItem ? $enc->html( $baseItem->getLocale()->getLanguageId() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.currencyid', $fields ) ) : ?>
						<td class="order-base-currencyid"><a class="items-field" href="<?= $url; ?>"><?= $baseItem ? $enc->html( $baseItem->getLocale()->getCurrencyId() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.price', $fields ) ) : ?>
						<td class="order-base-price"><a class="items-field" href="<?= $url; ?>"><?= $baseItem ? $enc->html( $baseItem->getPrice()->getValue() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.costs', $fields ) ) : ?>
						<td class="order-base-costs"><a class="items-field" href="<?= $url; ?>"><?= $baseItem ? $enc->html( $baseItem->getPrice()->getCosts() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.rebate', $fields ) ) : ?>
						<td class="order-base-rebate"><a class="items-field" href="<?= $url; ?>"><?= $baseItem ? $enc->html( $baseItem->getPrice()->getRebate() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.taxvalue', $fields ) ) : ?>
						<td class="order-base-taxvalue"><a class="items-field" href="<?= $url; ?>"><?= $baseItem ? $enc->html( $baseItem->getPrice()->getTaxValue() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.taxflag', $fields ) ) : ?>
						<td class="order-base-taxflag"><a class="items-field" href="<?= $url; ?>"><?= $baseItem ? $enc->html( $statusList[$baseItem->getPrice()->getTaxFlag()] ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.comment', $fields ) ) : ?>
						<td class="order-base-comment"><a class="items-field" href="<?= $url; ?>"><?= $baseItem ? $enc->html( $baseItem->getComment() ) : ''; ?></a></td>
					<?php endif; ?>

					<?php $addrItem = null;
						if( $baseItem && ( $addresses = $baseItem->getAddresses() ) && isset( $addresses[\Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT] ) ) {
							$addrItem = $addresses[\Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT];
						}
					?>

					<?php if( in_array( 'order.base.address.salutation', $fields ) ) : ?>
						<td class="order-base-address-salutation"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getSalutation() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.company', $fields ) ) : ?>
						<td class="order-base-address-company"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getCompany() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.vatid', $fields ) ) : ?>
						<td class="order-base-address-vatid"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getVatID() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.title', $fields ) ) : ?>
						<td class="order-base-address-title"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getTitle() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.firstname', $fields ) ) : ?>
						<td class="order-base-address-firstname"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getFirstname() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.lastname', $fields ) ) : ?>
						<td class="order-base-address-lastname"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getLastname() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.address1', $fields ) ) : ?>
						<td class="order-base-address-address1"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getAddress1() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.address2', $fields ) ) : ?>
						<td class="order-base-address-address2"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getAddress2() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.address3', $fields ) ) : ?>
						<td class="order-base-address-address3"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getAddress3() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.postal', $fields ) ) : ?>
						<td class="order-base-address-postal"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getPostal() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.city', $fields ) ) : ?>
						<td class="order-base-address-city"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getCity() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.state', $fields ) ) : ?>
						<td class="order-base-address-state"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getState() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.countryid', $fields ) ) : ?>
						<td class="order-base-address-countryid"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getCountryId() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.languageid', $fields ) ) : ?>
						<td class="order-base-address-languageid"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getLanguageId() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.telephone', $fields ) ) : ?>
						<td class="order-base-address-telephone"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getTelephone() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.telefax', $fields ) ) : ?>
						<td class="order-base-address-telefax"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getTelefax() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.email', $fields ) ) : ?>
						<td class="order-base-address-email"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getEmail() ) : ''; ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.address.website', $fields ) ) : ?>
						<td class="order-base-address-website"><a class="items-field" href="<?= $url; ?>"><?= $addrItem ? $enc->html( $addrItem->getWebsite() ) : ''; ?></a></td>
					<?php endif; ?>

					<?php $services = ( $baseItem ? $baseItem->getServices() : [] ); ?>

					<?php if( in_array( 'order.base.service.code', $fields ) ) : ?>
						<td class="order-base-service-code">
							<a class="items-field" href="<?= $url; ?>">
								<?php foreach( $services as $type => $list ) : ?>
									<?php foreach( $list as $serviceItem ) : ?>
										<span class="line"><?= $enc->html( $serviceItem->getCode() ); ?></span>
									<?php endforeach; ?>
								<?php endforeach; ?>
							</a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.service.name', $fields ) ) : ?>
						<td class="order-base-service-name">
							<a class="items-field" href="<?= $url; ?>">
								<?php foreach( $services as $type => $list ) : ?>
									<?php foreach( $list as $serviceItem ) : ?>
										<span class="line"><?= $enc->html( $serviceItem->getName() ); ?></span>
									<?php endforeach; ?>
								<?php endforeach; ?>
							</a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.service.price', $fields ) ) : ?>
						<td class="order-base-service-price">
							<a class="items-field" href="<?= $url; ?>">
								<?php foreach( $services as $type => $list ) : ?>
									<?php foreach( $list as $serviceItem ) : ?>
										<span class="line"><?= $enc->html( $serviceItem->getPrice()->getValue() ); ?></span>
									<?php endforeach; ?>
								<?php endforeach; ?>
							</a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.service.costs', $fields ) ) : ?>
						<td class="order-base-service-costs">
							<a class="items-field" href="<?= $url; ?>">
								<?php foreach( $services as $type => $list ) : ?>
									<?php foreach( $list as $serviceItem ) : ?>
										<span class="line"><?= $enc->html( $serviceItem->getPrice()->getCosts() ); ?></span>
									<?php endforeach; ?>
								<?php endforeach; ?>
							</a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.base.service.rebate', $fields ) ) : ?>
						<td class="order-base-service-rebate">
							<a class="items-field" href="<?= $url; ?>">
								<?php foreach( $services as $type => $list ) : ?>
									<?php foreach( $list as $serviceItem ) : ?>
										<span class="line"><?= $enc->html( $serviceItem->getPrice()->getRebate() ); ?></span>
									<?php endforeach; ?>
								<?php endforeach; ?>
							</a>
						</td>
					<?php endif; ?>

					<td class="actions">
						<a class="btn act-copy fa"
							href="<?= $enc->attr( $this->url( $copyTarget, $copyCntl, $copyAction, ['id' => $item->getBaseId()] + $params, [], $copyConfig ) ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry') ); ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ); ?>"></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if( $this->get( 'items', [] ) === [] ) : ?>
		<?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ); ?>
	<?php endif; ?>
</form>

<?= $this->partial(
		$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard.php' ),
		['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get( 'total' ),
		'page' => $this->session( 'aimeos/admin/jqadm/order/page', [] )]
	);
?>

<?php $this->block()->stop(); ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard.php' ) ); ?>
