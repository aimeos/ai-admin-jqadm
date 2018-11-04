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


/** admin/jqadm/log/fields
 * List of log columns that should be displayed in the list view
 *
 * Changes the list of log columns shown by default in the log list view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "log.facility" for the log facility.
 *
 * @param array List of field names, i.e. search keys
 * @since 2018.04
 * @category Developer
 */
$default = ['log.timestamp', 'log.facility', 'log.priority', 'log.request', 'log.message'];
$default = $this->config( 'admin/jqadm/log/fields', $default );
$fields = $this->session( 'aimeos/admin/jqadm/log/fields', $default );

$searchParams = $params = $this->get( 'pageParams', [] );
$searchParams['page']['start'] = 0;

$columnList = [
	'log.timestamp' => $this->translate( 'admin', 'Timestamp' ),
	'log.facility' => $this->translate( 'admin', 'Facility' ),
	'log.priority' => $this->translate( 'admin', 'Priority' ),
	'log.request' => $this->translate( 'admin', 'Request' ),
	'log.message' => $this->translate( 'admin', 'Message' ),
];


?>
<?php $this->block()->start( 'jqadm_content' ); ?>

<nav class="main-navbar log">
	<span class="navbar-brand">
		<?= $enc->html( $this->translate( 'admin', 'Log' ) ); ?>
		<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ); ?>)</span>
	</span>
	<span class="placeholder">&nbsp;</span>
</nav>


<?= $this->partial(
		$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard.php' ),
		['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
		'page' => $this->session( 'aimeos/admin/jqadm/log/page', [] )]
	);
?>

<form class="list list-log" method="POST" action="<?= $enc->attr( $this->url( $target, $controller, $action, $searchParams, [], $config ) ); ?>">
	<?= $this->csrf()->formfield(); ?>

	<table class="list-items table table-hover table-striped">
		<thead class="list-header">
			<tr>

				<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-standard.php' ),
						['fields' => $fields, 'params' => $params, 'data' => $columnList,
						'sort' => $this->session( 'aimeos/admin/jqadm/log/sort', '-log.timestamp' )]
					);
				?>

				<th class="actions">
					<?= $this->partial(
							$this->config( 'admin/jqadm/partial/columns', 'common/partials/columns-standard.php' ),
							['fields' => $fields, 'data' => $columnList]
						);
					?>
					<button type="submit" class="btn act-search fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Search') ); ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Search' ) ); ?>">
					</button>
				</th>
			</tr>
		</thead>
		<tbody>

			<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
				<tr class="<?= $this->site()->readonly( $item->getSiteId() ); ?>">
					<?php if( in_array( 'log.timestamp', $fields ) ) : ?>
						<td class="log-timestamp"><?= $enc->attr( $item->getTimestamp() ); ?></td>
					<?php endif; ?>
					<?php if( in_array( 'log.facility', $fields ) ) : ?>
						<td class="log-facility"><?= $enc->html( $item->getFacility() ); ?></td>
					<?php endif; ?>
					<?php if( in_array( 'log.priority', $fields ) ) : ?>
						<td class="log-priority"><?= $enc->html( $item->getPriority() ); ?></td>
					<?php endif; ?>
					<?php if( in_array( 'log.request', $fields ) ) : ?>
						<td class="log-request"><?= $enc->html( $item->getRequest() ); ?></td>
					<?php endif; ?>
					<?php if( in_array( 'log.message', $fields ) ) : ?>
						<td class="log-message"><a class="items-field" href="#" tabindex="1"><?= nl2br( $enc->html( $item->getMessage() ) ); ?></a></td>
					<?php endif; ?>

					<td class="actions"></td>
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
		'page' => $this->session( 'aimeos/admin/jqadm/log/page', [] )]
	);
?>

<?php $this->block()->stop(); ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard.php' ) ); ?>
