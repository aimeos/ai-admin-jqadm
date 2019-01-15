<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018
 */

$enc = $this->encoder();


$target = $this->config( 'admin/jqadm/url/search/target' );
$controller = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/search/action', 'search' );
$config = $this->config( 'admin/jqadm/url/search/config', [] );

$newTarget = $this->config( 'admin/jqadm/url/create/target' );
$newCntl = $this->config( 'admin/jqadm/url/create/controller', 'Jqadm' );
$newAction = $this->config( 'admin/jqadm/url/create/action', 'create' );
$newConfig = $this->config( 'admin/jqadm/url/create/config', [] );

$getTarget = $this->config( 'admin/jqadm/url/get/target' );
$getCntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$getAction = $this->config( 'admin/jqadm/url/get/action', 'get' );
$getConfig = $this->config( 'admin/jqadm/url/get/config', [] );

$copyTarget = $this->config( 'admin/jqadm/url/copy/target' );
$copyCntl = $this->config( 'admin/jqadm/url/copy/controller', 'Jqadm' );
$copyAction = $this->config( 'admin/jqadm/url/copy/action', 'copy' );
$copyConfig = $this->config( 'admin/jqadm/url/copy/config', [] );

$delTarget = $this->config( 'admin/jqadm/url/delete/target' );
$delCntl = $this->config( 'admin/jqadm/url/delete/controller', 'Jqadm' );
$delAction = $this->config( 'admin/jqadm/url/delete/action', 'delete' );
$delConfig = $this->config( 'admin/jqadm/url/delete/config', [] );

$expTarget = $this->config( 'admin/jqadm/url/export/target' );
$expCntl = $this->config( 'admin/jqadm/url/export/controller', 'Jqadm' );
$expAction = $this->config( 'admin/jqadm/url/export/action', 'export' );
$expConfig = $this->config( 'admin/jqadm/url/export/config', [] );


/** admin/jqadm/subscription/fields
 * List of subscription columns that should be displayed in the list view
 *
 * Changes the list of subscription columns shown by default in the subscription list view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "subscription.id" for the subscription ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2018.04
 * @category Developer
 */
$default = ['subscription.status', 'subscription.datenext', 'subscription.dateend', 'subscription.interval'];
$default = $this->config( 'admin/jqadm/subscription/fields', $default );
$fields = $this->session( 'aimeos/admin/jqadm/subscription/fields', $default );

$searchParams = $params = $this->get( 'pageParams', [] );
$searchParams['page']['start'] = 0;

$baseItems = $this->get( 'baseItems', [] );

$columnList = [
	'subscription.id' => $this->translate( 'admin', 'ID' ),
	'subscription.status' => $this->translate( 'admin', 'Status' ),
	'subscription.interval' => $this->translate( 'admin', 'Interval' ),
	'subscription.datenext' => $this->translate( 'admin', 'Next date' ),
	'subscription.dateend' => $this->translate( 'admin', 'End date' ),
	'subscription.reason' => $this->translate( 'admin', 'Reason' ),
	'subscription.ctime' => $this->translate( 'admin', 'Created' ),
	'subscription.mtime' => $this->translate( 'admin', 'Modified' ),
	'subscription.editor' => $this->translate( 'admin', 'Editor' ),
	'order.base.customerid' => $this->translate( 'admin', 'Customer ID' ),
	'order.base.sitecode' => $this->translate( 'admin', 'Site' ),
	'order.base.languageid' => $this->translate( 'admin', 'Language' ),
	'order.base.currencyid' => $this->translate( 'admin', 'Currency' ),
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

$reasonList = [
	null => '',
	-1 => $this->translate( 'mshop/code', 'reason:-1' ),
	0 => $this->translate( 'mshop/code', 'reason:0' ),
	1 => $this->translate( 'mshop/code', 'reason:1' ),
];


?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<nav class="main-navbar">

	<span class="navbar-brand">
		<?= $enc->html( $this->translate( 'admin', 'Subscription' ) ); ?>
		<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ); ?>)</span>
	</span>

	<?= $this->partial(
		$this->config( 'admin/jqadm/partial/navsearch', 'common/partials/navsearch-standard.php' ), [
			'filter' => $this->session( 'aimeos/admin/jqadm/subscription/filter', [] ),
			'filterAttributes' => $this->get( 'filterAttributes', [] ),
			'filterOperators' => $this->get( 'filterOperators', [] ),
			'params' => $params,
		]
	); ?>
</nav>


<?= $this->partial(
		$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard.php' ),
		['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
		'page' => $this->session( 'aimeos/admin/jqadm/subscription/page', [] )]
	);
?>

<form class="list list-subscription" method="POST" action="<?= $enc->attr( $this->url( $target, $controller, $action, $searchParams, [], $config ) ); ?>">
	<?= $this->csrf()->formfield(); ?>

	<table class="list-items table table-hover table-striped">
		<thead class="list-header">
			<tr>

				<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-standard.php' ),
						['fields' => $fields, 'params' => $params, 'data' => $columnList, 'sort' => $this->session( 'aimeos/admin/jqadm/subscription/sort' )]
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
					'fields' => $fields, 'filter' => $this->session( 'aimeos/admin/jqadm/subscription/filter', [] ),
					'data' => [
						'subscription.id' => ['op' => '=='],
						'subscription.status' => ['op' => '==', 'type' => 'select', 'val' => [
							'1' => $this->translate( 'mshop/code', 'status:1' ),
							'0' => $this->translate( 'mshop/code', 'status:0' ),
							'-1' => $this->translate( 'mshop/code', 'status:-1' ),
							'-2' => $this->translate( 'mshop/code', 'status:-2' ),
						]],
						'subscription.interval' => ['op' => '=~', 'type' => 'string'],
						'subscription.datenext' => ['op' => '>=', 'type' => 'date'],
						'subscription.dateend' => ['op' => '>=', 'type' => 'date'],
						'subscription.reason' => ['op' => '==', 'type' => 'select', 'val' => $reasonList],
						'subscription.ctime' => ['op' => '>=', 'type' => 'datetime-local'],
						'subscription.mtime' => ['op' => '>=', 'type' => 'datetime-local'],
						'subscription.editor' => [],
						'order.base.customerid' => ['op' => '=='],
						'order.base.sitecode' => ['op' => '=='],
						'order.base.languageid' => ['op' => '=='],
						'order.base.currencyid' => ['op' => '=='],
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
					]
				] );
			?>

			<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
				<?php $url = $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['id' => $id] + $params, [], $getConfig ) ); ?>
				<tr class="<?= $this->site()->readonly( $item->getSiteId() ); ?>">
					<?php if( in_array( 'subscription.id', $fields ) ) : ?>
						<td class="subscription-id"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getId() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'subscription.status', $fields ) ) : ?>
						<td class="subscription-status"><a class="items-field" href="<?= $url; ?>"><div class="fa status-<?= $enc->attr( $item->getStatus() ); ?>"></div></a></td>
					<?php endif; ?>
					<?php if( in_array( 'subscription.interval', $fields ) ) : ?>
						<td class="subscription-interval"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getInterval() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'subscription.datenext', $fields ) ) : ?>
						<td class="subscription-datenext"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDateNext() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'subscription.dateend', $fields ) ) : ?>
						<td class="subscription-dateend"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDateEnd() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'subscription.reason', $fields ) ) : ?>
						<td class="subscription-reason"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $reasonList[$item->getReason()] ); ?></td>
					<?php endif; ?>
					<?php if( in_array( 'subscription.ctime', $fields ) ) : ?>
						<td class="subscription-ctime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeCreated() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'subscription.mtime', $fields ) ) : ?>
						<td class="subscription-mtime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeModified() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'subscription.editor', $fields ) ) : ?>
						<td class="subscription-editor"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getEditor() ); ?></a></td>
					<?php endif; ?>

					<?php $baseItem = ( isset( $baseItems[$item->getOrderBaseId()] ) ? $baseItems[$item->getOrderBaseId()] : null ); ?>

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

					<td class="actions">
						<a class="btn act-copy fa" tabindex="1"
							href="<?= $enc->attr( $this->url( $copyTarget, $copyCntl, $copyAction, ['id' => $id] + $params, [], $copyConfig ) ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry') ); ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ); ?>"></a>
						<?php if( !$this->site()->readonly( $item->getSiteId() ) ) : ?>
							<a class="btn act-delete fa" tabindex="1"
								href="<?= $enc->attr( $this->url( $delTarget, $delCntl, $delAction, ['resource' => 'subscription', 'id' => $id] + $params, [], $delConfig ) ); ?>"
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
		'page' => $this->session( 'aimeos/admin/jqadm/subscription/page', [] )]
	);
?>

<?php $this->block()->stop(); ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard.php' ) ); ?>
