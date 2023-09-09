<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2023
 */

$enc = $this->encoder();


/** admin/jqadm/order/actions
 * Actions available in the list view of the order panel
 *
 * List of actions, the editor can select from in the list header of the order
 * panel. You can dynamically extend the available actions like exporting the
 * selected orders in CSV format and translate the action names using the
 * "admin/ext" translation domain.
 *
 * The action names will be passed as "queue" parameter to the export method
 * of the JQADM order class, which will create an entry for the message queue
 * from the selected filter criteria. You have to implement a suitable controller
 * which must fetch the entries from the message queue and generate the appropriate
 * files. If files should be offered for download in the dashboard, a new job
 * entry must be created using the MAdmin Job manager.
 *
 * @param array List of action queue names
 * @since 2023.10
 */

/** admin/jqadm/url/export/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2023.10
 * @see admin/jqadm/url/export/controller
 * @see admin/jqadm/url/export/action
 * @see admin/jqadm/url/export/config
 */

/** admin/jqadm/url/export/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2023.10
 * @see admin/jqadm/url/export/target
 * @see admin/jqadm/url/export/action
 * @see admin/jqadm/url/export/config
 */

/** admin/jqadm/url/export/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2023.10
 * @see admin/jqadm/url/export/target
 * @see admin/jqadm/url/export/controller
 * @see admin/jqadm/url/export/config
 */

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
 * @since 2023.10
 * @see admin/jqadm/url/export/target
 * @see admin/jqadm/url/export/controller
 * @see admin/jqadm/url/export/action
 */

/** admin/jqadm/url/export/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2023.10
 * @see admin/jqadm/url/export/target
 * @see admin/jqadm/url/export/controller
 * @see admin/jqadm/url/export/action
 * @see admin/jqadm/url/export/config
 */


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
 * @since 2023.10
 */
$default = $this->config( 'admin/jqadm/order/fields', ['order.basket.id', 'order.basket.customerid', 'order.basket.name', 'order.basket.ctime'] );
$fields = $this->session( 'aimeos/admin/jqadm/orderbasket/fields', $default );

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


$columnList = [
	'order.basket.id' => $this->translate( 'admin', 'ID' ),
	'order.basket.customerid' => $this->translate( 'admin', 'Customer ID' ),
	'order.basket.name' => $this->translate( 'admin', 'Name' ),
	'order.basket.ctime' => $this->translate( 'admin', 'Created' ),
	'order.basket.mtime' => $this->translate( 'admin', 'Modified' ),
	'order.basket.editor' => $this->translate( 'admin', 'Editor' ),

];


?>
<?php $this->block()->start( 'jqadm_content' ) ?>

<?= $this->partial( $this->config( 'admin/jqadm/partial/navsearch', 'navsearch' ) ) ?>
<?= $this->partial( $this->config( 'admin/jqadm/partial/columns', 'columns' ) ) ?>


<div class="list-view"
	data-domain="order/basket"
	data-siteid="<?= $enc->attr( $this->site()->siteid() ) ?>"
	data-filter="<?= $enc->attr( $this->session( 'aimeos/admin/jqadm/orderbasket/filter', new \stdClass ) ) ?>"
	data-items="<?= $enc->attr( $this->get( 'items', map() )->call( 'toArray', [true] )->all() ) ?>">

	<nav class="main-navbar">

		<span class="navbar-brand">
			<?= $enc->html( $this->translate( 'admin', 'Basket' ) ) ?>
			<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ) ?>)</span>
		</span>

		<div class="btn fa act-search" v-on:click="search = true"
			title="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>"
			aria-label="<?= $enc->attr( $this->translate( 'admin', 'Show search form' ) ) ?>">
		</div>
	</nav>

	<nav-search v-bind:show="search" v-on:close="search = false"
		v-bind:url="`<?= $enc->js( $this->link( 'admin/jqadm/url/search', map( $searchParams )->except( 'filter' )->all() ) ) ?>`"
		v-bind:filter="<?= $enc->attr( (object) $this->session( 'aimeos/admin/jqadm/orderbasket/filter', new \stdClass ) ) ?>"
		v-bind:operators="<?= $enc->attr( $operators ) ?>"
		v-bind:name="`<?= $enc->js( $this->formparam( ['filter', '_key_', '0'] ) ) ?>`"
		v-bind:attributes="<?= $enc->attr( $searchAttributes ) ?>">
	</nav-search>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
			['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/orderbasket/page', [] )]
		);
	?>

	<form ref="form" class="list list-order" method="POST"
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
							</ul>
						</th>

						<?= $this->partial(
								$this->config( 'admin/jqadm/partial/listhead', 'listhead' ),
								['fields' => $fields, 'params' => $params, 'data' => $columnList, 'sort' => $this->session( 'aimeos/admin/jqadm/orderbasket/sort' )]
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

					<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listsearch', 'listsearch' ), [
							'fields' => array_merge( $fields, ['select'] ), 'filter' => $this->session( 'aimeos/admin/jqadm/orderbasket/filter', [] ),
							'data' => [
								'order.basket.id' => ['op' => '=='],
								'order.basket.customerid' => ['op' => '=='],
								'order.basket.name' => [],
								'order.basket.ctime' => ['op' => '-', 'type' => 'date'],
								'order.basket.mtime' => ['op' => '-', 'type' => 'date'],
								'order.basket.editor' => [],

							]
						] );
					?>


					<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
						<?php $url = $enc->attr( $this->link( 'admin/jqadm/url/get', ['id' => $item->getId()] + $params ) ) ?>
						<tr class="list-item <?= $this->site()->readonly( $item->getSiteId() ) ?>">
							<td class="select">
								<input class="form-check-input" type="checkbox" tabindex="1"
									name="<?= $enc->attr( $this->formparam( ['id', ''] ) ) ?>"
									value="<?= $enc->attr( $item->getId() ) ?>"
									v-on:click="toggle(`<?= $enc->js( $id ) ?>`)"
									v-bind:checked="checked(`<?= $enc->js( $id ) ?>`)"
									v-bind:disabled="readonly(`<?= $enc->js( $id ) ?>`)">
							</td>
							<?php if( in_array( 'order.basket.id', $fields ) ) : ?>
								<td class="order-basket-id"><a class="items-field" href="<?= $url ?>" tabindex="1"><?= $enc->html( $item->getId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.basket.customerid', $fields ) ) : ?>
								<td class="order-basket-customerid"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getCustomerId() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.basket.name', $fields ) ) : ?>
								<td class="order-basket-name"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getName() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.ctime', $fields ) ) : ?>
								<td class="order-basket-ctime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeCreated() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.mtime', $fields ) ) : ?>
								<td class="order-basket-mtime"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->getTimeModified() ) ?></a></td>
							<?php endif ?>
							<?php if( in_array( 'order.editor', $fields ) ) : ?>
								<td class="order-basket-editor"><a class="items-field" href="<?= $url ?>"><?= $enc->html( $item->editor() ) ?></a></td>
							<?php endif ?>

							<td class="actions">
								<a class="btn act-copy fa"
									href="<?= $enc->attr( $this->link( 'admin/jqadm/url/copy', ['id' => $item->getId()] + $params ) ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Copy this entry' ) ) ?>"
									aria-label="<?= $enc->attr( $this->translate( 'admin', 'Copy' ) ) ?>">
								</a>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>

		<?php if( $this->get( 'items', map() )->isEmpty() ) : ?>
			<?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ) ?>
		<?php endif ?>
	</form>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'pagination' ),
			['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get( 'total' ),
			'page' => $this->session( 'aimeos/admin/jqadm/orderbasket/page', [] )]
		);
	?>

</div>
<?php $this->block()->stop() ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'page' ) ) ?>
