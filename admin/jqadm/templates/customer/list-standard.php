<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
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


/** admin/jqadm/customer/fields
 * List of customer columns that should be displayed in the list view
 *
 * Changes the list of customer columns shown by default in the customer list view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "customer.id" for the customer ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.07
 * @category Developer
 */
$default = ['customer.code', 'customer.lastname', 'customer.postal', 'customer.city'];
$default = $this->config( 'admin/jqadm/customer/fields', $default );
$fields = $this->session( 'aimeos/admin/jqadm/customer/fields', $default );

$searchParams = $params = $this->get( 'pageParams', [] );
$searchParams['page']['start'] = 0;

$langList = [];
foreach( $this->get( 'pageLanguages', [] ) as $langId => $langItem ) {
	$langList[$langId] = $this->translate( 'language', $langId );
}

$columnList = [
	'customer.id' => $this->translate( 'admin', 'ID' ),
	'customer.status' => $this->translate( 'admin', 'Status' ),
	'customer.code' => $this->translate( 'admin', 'Code' ),
	'customer.label' => $this->translate( 'admin', 'Label' ),
	'customer.salutation' => $this->translate( 'admin', 'Salutation' ),
	'customer.company' => $this->translate( 'admin', 'Company' ),
	'customer.vatid' => $this->translate( 'admin', 'VAT ID' ),
	'customer.title' => $this->translate( 'admin', 'Title' ),
	'customer.firstname' => $this->translate( 'admin', 'First name' ),
	'customer.lastname' => $this->translate( 'admin', 'Last name' ),
	'customer.address1' => $this->translate( 'admin', 'Address 1' ),
	'customer.address2' => $this->translate( 'admin', 'Address 2' ),
	'customer.address3' => $this->translate( 'admin', 'Address 3' ),
	'customer.postal' => $this->translate( 'admin', 'Postal' ),
	'customer.city' => $this->translate( 'admin', 'City' ),
	'customer.state' => $this->translate( 'admin', 'State' ),
	'customer.languageid' => $this->translate( 'admin', 'Language ID' ),
	'customer.countryid' => $this->translate( 'admin', 'Country ID' ),
	'customer.telephone' => $this->translate( 'admin', 'State' ),
	'customer.telefax' => $this->translate( 'admin', 'Facsimile' ),
	'customer.email' => $this->translate( 'admin', 'Email' ),
	'customer.website' => $this->translate( 'admin', 'Web site' ),
	'customer.birthday' => $this->translate( 'admin', 'Birthday' ),
	'customer.ctime' => $this->translate( 'admin', 'Created' ),
	'customer.mtime' => $this->translate( 'admin', 'Modified' ),
	'customer.editor' => $this->translate( 'admin', 'Editor' ),
];


?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<nav class="main-navbar">

	<span class="navbar-brand">
		<?= $enc->html( $this->translate( 'admin', 'Customer' ) ); ?>
		<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ); ?>)</span>
	</span>

	<?= $this->partial(
		$this->config( 'admin/jqadm/partial/navsearch', 'common/partials/navsearch-standard.php' ), [
			'filter' => $this->session( 'aimeos/admin/jqadm/customer/filter', [] ),
			'filterAttributes' => $this->get( 'filterAttributes', [] ),
			'filterOperators' => $this->get( 'filterOperators', [] ),
			'params' => $params,
		]
	); ?>
</nav>


<?= $this->partial(
		$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard.php' ),
		['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
		'page' => $this->session( 'aimeos/admin/jqadm/customer/page', [] )]
	);
?>

<form class="list list-customer" method="POST" action="<?= $enc->attr( $this->url( $target, $controller, $action, $searchParams, [], $config ) ); ?>">
	<?= $this->csrf()->formfield(); ?>

	<table class="list-items table table-hover table-striped">
		<thead class="list-header">
			<tr>

				<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-standard.php' ),
						['fields' => $fields, 'params' => $params, 'data' => $columnList, 'sort' => $this->session( 'aimeos/admin/jqadm/customer/sort' )]
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
					'fields' => $fields, 'filter' => $this->session( 'aimeos/admin/jqadm/customer/filter', [] ),
					'data' => [
						'customer.id' => ['op' => '=='],
						'customer.status' => ['op' => '==', 'type' => 'select', 'val' => [
							'1' => $this->translate( 'mshop/code', 'status:1' ),
							'0' => $this->translate( 'mshop/code', 'status:0' ),
							'-1' => $this->translate( 'mshop/code', 'status:-1' ),
							'-2' => $this->translate( 'mshop/code', 'status:-2' ),
						]],
						'customer.code' => [],
						'customer.label' => [],
						'customer.salutation' => ['op' => '==', 'type' => 'select', 'val' => [
							'company' => 'company', 'mr' => 'mr', 'mrs' => 'mrs', 'miss' => 'miss'
						]],
						'customer.company' => [],
						'customer.vatid' => [],
						'customer.title' => [],
						'customer.firstname' => [],
						'customer.lastname' => [],
						'customer.address1' => [],
						'customer.address2' => [],
						'customer.address3' => [],
						'customer.postal' => [],
						'customer.city' => [],
						'customer.state' => [],
						'customer.languageid' => ['op' => '==', 'type' => 'select', 'val' => $langList],
						'customer.countryid' => ['op' => '=='],
						'customer.telephone' => [],
						'customer.telefax' => [],
						'customer.email' => [],
						'customer.website' => [],
						'customer.birthday' => ['op' => '==', 'type' => 'date'],
						'customer.ctime' => ['op' => '>=', 'type' => 'datetime-local'],
						'customer.mtime' => ['op' => '>=', 'type' => 'datetime-local'],
						'customer.editor' => [],
					]
				] );
			?>

			<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
				<?php $address = $item->getPaymentAddress(); ?>
				<?php $url = $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['id' => $id] + $params, [], $getConfig ) ); ?>
				<tr class="<?= $this->site()->readonly( $item->getSiteId() ); ?>">
					<?php if( in_array( 'customer.id', $fields ) ) : ?>
						<td class="customer-id"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getId() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.status', $fields ) ) : ?>
						<td class="customer-status"><a class="items-field" href="<?= $url; ?>"><div class="fa status-<?= $enc->attr( $item->getStatus() ); ?>"></div></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.code', $fields ) ) : ?>
						<td class="customer-code"><a class="items-field" href="<?= $url; ?>" tabindex="1"><?= $enc->html( $item->getCode() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.label', $fields ) ) : ?>
						<td class="customer-label"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getLabel() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.salutation', $fields ) ) : ?>
						<td class="customer-salutation"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getSalutation() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.company', $fields ) ) : ?>
						<td class="customer-company"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getCompany() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.vatid', $fields ) ) : ?>
						<td class="customer-vatid"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getVatID() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.title', $fields ) ) : ?>
						<td class="customer-title"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getTitle() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.firstname', $fields ) ) : ?>
						<td class="customer-firstname"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getFirstname() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.lastname', $fields ) ) : ?>
						<td class="customer-lastname"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getLastname() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.address1', $fields ) ) : ?>
						<td class="customer-address1"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getAddress1() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.address2', $fields ) ) : ?>
						<td class="customer-address2"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getAddress2() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.address3', $fields ) ) : ?>
						<td class="customer-address3"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getAddress3() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.postal', $fields ) ) : ?>
						<td class="customer-postal"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getPostal() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.city', $fields ) ) : ?>
						<td class="customer-city"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getCity() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.state', $fields ) ) : ?>
						<td class="customer-state"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getState() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.languageid', $fields ) ) : ?>
						<td class="customer-languageid"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getLanguageId() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.countryid', $fields ) ) : ?>
						<td class="customer-countryid"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getCountryId() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.telephone', $fields ) ) : ?>
						<td class="customer-telephone"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getTelephone() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.telefax', $fields ) ) : ?>
						<td class="customer-telefax"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getTelefax() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.email', $fields ) ) : ?>
						<td class="customer-email"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getEmail() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.website', $fields ) ) : ?>
						<td class="customer-website"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $address->getWebsite() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.birthday', $fields ) ) : ?>
						<td class="customer-birthday"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getBirthday() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.ctime', $fields ) ) : ?>
						<td class="customer-ctime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeCreated() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.mtime', $fields ) ) : ?>
						<td class="customer-mtime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeModified() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'customer.editor', $fields ) ) : ?>
						<td class="customer-editor"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getEditor() ); ?></a></td>
					<?php endif; ?>

					<td class="actions">
						<a class="btn act-copy fa" tabindex="1"
							href="<?= $enc->attr( $this->url( $copyTarget, $copyCntl, $copyAction, ['id' => $id] + $params, [], $copyConfig ) ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry') ); ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ); ?>"></a>
						<?php if( !$this->site()->readonly( $item->getSiteId() ) ) : ?>
							<a class="btn act-delete fa" tabindex="1"
								href="<?= $enc->attr( $this->url( $delTarget, $delCntl, $delAction, ['resource' => 'customer', 'id' => $id] + $params, [], $delConfig ) ); ?>"
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
		'page' => $this->session( 'aimeos/admin/jqadm/customer/page', [] )]
	);
?>

<?php $this->block()->stop(); ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard.php' ) ); ?>
