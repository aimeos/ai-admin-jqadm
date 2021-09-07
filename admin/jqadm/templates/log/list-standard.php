<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2021
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
$default = ['log.timestamp', 'log.facility', 'log.priority', 'log.message'];
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
<?php $this->block()->start( 'jqadm_content' ) ?>

<?= $this->partial( $this->config( 'admin/jqadm/partial/columns', 'common/partials/columns-standard' ) ) ?>


<div class="list-view">

	<nav class="main-navbar log">
		<span class="navbar-brand">
			<?= $enc->html( $this->translate( 'admin', 'Log' ) ) ?>
			<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ) ?>)</span>
		</span>
	</nav>


	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard' ),
			['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/log/page', [] )]
		);
	?>

	<form class="list list-log" method="POST" action="<?= $enc->attr( $this->url( $target, $controller, $action, $searchParams, [], $config ) ) ?>">
		<?= $this->csrf()->formfield() ?>

		<column-select tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
			name="<?= $enc->attr( $this->formparam( ['fields', ''] ) ) ?>"
			v-bind:titles="<?= $enc->attr( $columnList ) ?>"
			v-bind:fields="<?= $enc->attr( $fields ) ?>"
			v-bind:show="columns"
			v-on:close="columns = false">
		</column-select>

		<div class="table-responsive">
			<table class="list-items table table-hover">
				<thead class="list-header">
					<tr>

						<?= $this->partial(
								$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-standard' ),
								['fields' => $fields, 'params' => $params, 'data' => $columnList,
								'sort' => $this->session( 'aimeos/admin/jqadm/log/sort', '-log.timestamp' )]
							);
						?>

						<th class="actions">
							<a class="btn act-columns fa" href="#" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Columns' ) ) ?>"
								v-on:click.prevent.stop="columns = true">
							</a>
						</th>
					</tr>
				</thead>
				<tbody>

					<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
						<tr class="<?= $this->site()->readonly( $item->getSiteId() ) ?>">
							<?php if( in_array( 'log.timestamp', $fields ) ) : ?>
								<td class="log-timestamp"><?= $enc->attr( $item->getTimestamp() ) ?></td>
							<?php endif ?>
							<?php if( in_array( 'log.facility', $fields ) ) : ?>
								<td class="log-facility"><?= $enc->html( $item->getFacility() ) ?></td>
							<?php endif ?>
							<?php if( in_array( 'log.priority', $fields ) ) : ?>
								<td class="log-priority"><?= $enc->html( $item->getPriority() ) ?></td>
							<?php endif ?>
							<?php if( in_array( 'log.request', $fields ) ) : ?>
								<td class="log-request"><?= $enc->html( $item->getRequest() ) ?></td>
							<?php endif ?>
							<?php if( in_array( 'log.message', $fields ) ) : ?>
								<td class="log-message"><span class="content"><?= nl2br( $enc->html( $item->getMessage() ) ) ?></span></td>
							<?php endif ?>

							<td class="actions"></td>
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
			$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard' ),
			['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/log/page', [] )]
		);
	?>

</div>
<?php $this->block()->stop() ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard' ) ) ?>
