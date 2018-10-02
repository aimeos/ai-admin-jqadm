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


/** admin/jqadm/locale/site/fields
 * List of locale columns that should be displayed in the list view
 *
 * Changes the list of locale columns shown by default in the locale list view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "locale.site.id" for the locale ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.10
 * @category Developer
 */
$default = ['locale.site.status', 'locale.site.code', 'locale.site.label', 'locale.site.config'];
$default = $this->config( 'admin/jqadm/locale/site/fields', $default );
$fields = $this->session( 'aimeos/admin/jqadm/locale/site/fields', $default );

$searchParams = $params = $this->get( 'pageParams', [] );
$searchParams['page']['start'] = 0;

$columnList = [
	'locale.site.id' => $this->translate( 'admin', 'ID' ),
	'locale.site.status' => $this->translate( 'admin', 'Status' ),
	'locale.site.code' => $this->translate( 'admin', 'Code' ),
	'locale.site.label' => $this->translate( 'admin', 'Label' ),
	'locale.site.config' => $this->translate( 'admin', 'Config' ),
	'locale.site.ctime' => $this->translate( 'admin', 'Created' ),
	'locale.site.mtime' => $this->translate( 'admin', 'Modified' ),
	'locale.site.editor' => $this->translate( 'admin', 'Editor' ),
];


?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<nav class="main-navbar">

	<span class="navbar-brand">
		<?= $enc->html( $this->translate( 'admin', 'Sites' ) ); ?>
	</span>

	<?= $this->partial(
		$this->config( 'admin/jqadm/partial/navsearch', 'common/partials/navsearch-standard.php' ), [
			'filter' => $this->session( 'aimeos/admin/jqadm/locale/site/filter', [] ),
			'filterAttributes' => $this->get( 'filterAttributes', [] ),
			'filterOperators' => $this->get( 'filterOperators', [] ),
			'params' => $params,
		]
	); ?>
</nav>


<?= $this->partial(
		$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard.php' ),
		['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
		'page' => $this->session( 'aimeos/admin/jqadm/locale/site/page', [] )]
	);
?>

<form class="list list-locale-site" method="POST" action="<?= $enc->attr( $this->url( $target, $controller, $action, $searchParams, [], $config ) ); ?>">
	<?= $this->csrf()->formfield(); ?>

	<table class="list-items table table-hover table-striped">
		<thead class="list-header">
			<tr>

				<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-standard.php' ),
						['fields' => $fields, 'params' => $params, 'data' => $columnList, 'sort' => $this->session( 'aimeos/admin/jqadm/locale/site/sort' )]
					);
				?>

				<th class="actions">
					<?php if( $this->access( 'super' ) ) : ?>
						<a class="btn fa act-add" tabindex="1"
							href="<?= $enc->attr( $this->url( $newTarget, $newCntl, $newAction, $params, [], $newConfig ) ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ); ?>">
						</a>
					<?php endif; ?>

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
					'fields' => $fields, 'filter' => $this->session( 'aimeos/admin/jqadm/locale/site/filter', [] ),
					'data' => [
						'locale.site.id' => ['op' => '=='],
						'locale.site.status' => ['op' => '==', 'type' => 'select', 'val' => [
							'1' => $this->translate( 'mshop/code', 'status:1' ),
							'0' => $this->translate( 'mshop/code', 'status:0' ),
							'-1' => $this->translate( 'mshop/code', 'status:-1' ),
							'-2' => $this->translate( 'mshop/code', 'status:-2' ),
						]],
						'locale.site.code' => [],
						'locale.site.label' => [],
						'locale.site.config' => ['op' => '~='],
						'locale.site.ctime' => ['op' => '>=', 'type' => 'datetime-local'],
						'locale.site.mtime' => ['op' => '>=', 'type' => 'datetime-local'],
						'locale.site.editor' => [],
					]
				] );
			?>

			<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
				<?php $url = $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['id' => $id] + $params, [], $getConfig ) ); ?>
				<tr>
					<?php if( in_array( 'locale.site.id', $fields ) ) : ?>
						<td class="locale-site-id"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getId() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'locale.site.status', $fields ) ) : ?>
						<td class="locale-site-status"><a class="items-field" href="<?= $url; ?>"><div class="fa status-<?= $enc->attr( $item->getStatus() ); ?>"></div></a></td>
					<?php endif; ?>
					<?php if( in_array( 'locale.site.code', $fields ) ) : ?>
						<td class="locale-site-code"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getCode() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'locale.site.label', $fields ) ) : ?>
						<td class="locale-site-label"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getLabel() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'locale.site.config', $fields ) ) : ?>
						<td class="locale.site-config config-item">
							<a class="items-field" href="<?= $url; ?>">
								<?php foreach( $item->getConfig() as $key => $value ) : ?>
									<span class="config-key"><?= $enc->html( $key ); ?></span>
									<span class="config-value"><?= $enc->html( !is_scalar( $value ) ? json_encode( $value ) : $value ); ?></span>
									<br/>
								<?php endforeach; ?>
							</a>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'locale.site.ctime', $fields ) ) : ?>
						<td class="locale-site-ctime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeCreated() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'locale.site.mtime', $fields ) ) : ?>
						<td class="locale-site-mtime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeModified() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'locale.site.editor', $fields ) ) : ?>
						<td class="locale-site-editor"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getEditor() ); ?></a></td>
					<?php endif; ?>

					<td class="actions">
						<?php if( $this->access( 'super' ) ) : ?>
							<a class="btn act-copy fa" tabindex="1"
								href="<?= $enc->attr( $this->url( $copyTarget, $copyCntl, $copyAction, ['id' => $id] + $params, [], $copyConfig ) ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry') ); ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ); ?>"></a>
							<a class="btn act-delete fa" tabindex="1"
								href="<?= $enc->attr( $this->url( $delTarget, $delCntl, $delAction, ['id' => $id] + $params, [], $delConfig ) ); ?>"
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
		'page' => $this->session( 'aimeos/admin/jqadm/locale/site/page', [] )]
	);
?>

<?php $this->block()->stop(); ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard.php' ) ); ?>
