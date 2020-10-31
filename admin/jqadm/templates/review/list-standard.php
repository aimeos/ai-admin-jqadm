<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020
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

$delTarget = $this->config( 'admin/jqadm/url/delete/target' );
$delCntl = $this->config( 'admin/jqadm/url/delete/controller', 'Jqadm' );
$delAction = $this->config( 'admin/jqadm/url/delete/action', 'delete' );
$delConfig = $this->config( 'admin/jqadm/url/delete/config', [] );


/** admin/jqadm/review/domains
 * List of domain names reviews can be assigend to
 *
 * Changes the list of domain names shown in the review list view which can be
 * filtered for.
 *
 * @param array List of domains names
 * @since 2020.10
 */
$domains = $this->config( 'admin/jqadm/review/domains', ['product'] );
$domains = array_combine( $domains, $domains );

/** admin/jqadm/review/fields
 * List of review columns that should be displayed in the list view
 *
 * Changes the list of review columns shown by default in the review list view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "review.id" for the customer ID.
 *
 * @param array List of field names, i.e. search keys
 * @since 2020.10
 * @category Developer
 */
$fields = ['review.status', 'review.rating', 'review.comment', 'review.response', 'review.ctime'];
$fields = $this->config( 'admin/jqadm/review/fields', $fields );
$fields = $this->session( 'aimeos/admin/jqadm/review/fields', $fields );

$searchParams = $params = $this->get( 'pageParams', [] );
$searchParams['page']['start'] = 0;

$columnList = [
	'review.id' => $this->translate( 'admin', 'ID' ),
	'review.status' => $this->translate( 'admin', 'Status' ),
	'review.domain' => $this->translate( 'admin', 'Domain' ),
	'review.refid' => $this->translate( 'admin', 'Reference ID' ),
	'review.name' => $this->translate( 'admin', 'Name' ),
	'review.rating' => $this->translate( 'admin', 'Rating' ),
	'review.comment' => $this->translate( 'admin', 'Comment' ),
	'review.response' => $this->translate( 'admin', 'Response' ),
	'review.ctime' => $this->translate( 'admin', 'Created' ),
	'review.mtime' => $this->translate( 'admin', 'Modified' ),
	'review.editor' => $this->translate( 'admin', 'Editor' ),
];

?>
<?php $this->block()->start( 'jqadm_content' ); ?>
<div class="vue-block" data-data="<?= $enc->attr( $this->get( 'items', map() )->getId()->toArray() ) ?>">

<nav class="main-navbar">

	<span class="navbar-brand">
		<?= $enc->html( $this->translate( 'admin', 'Review' ) ); ?>
		<span class="navbar-secondary">(<?= $enc->html( $this->site()->label() ); ?>)</span>
	</span>

	<?= $this->partial(
		$this->config( 'admin/jqadm/partial/navsearch', 'common/partials/navsearch-standard' ), [
			'filter' => $this->session( 'aimeos/admin/jqadm/review/filter', [] ),
			'filterAttributes' => $this->get( 'filterAttributes', [] ),
			'filterOperators' => $this->get( 'filterOperators', [] ),
			'params' => $params,
		]
	); ?>
</nav>


<div is="list-view" inline-template v-bind:items="data"><div>

<?= $this->partial(
		$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard' ),
		['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'total' ),
		'page' => $this->session( 'aimeos/admin/jqadm/review/page', [] )]
	);
?>

<form class="list list-review" method="POST" action="<?= $enc->attr( $this->url( $target, $controller, $action, $searchParams, [], $config ) ); ?>">
	<?= $this->csrf()->formfield(); ?>

	<table class="list-items table table-hover table-striped">
		<thead class="list-header">
			<tr>
				<th class="select">
					<a href="#" class="btn act-delete fa" tabindex="1" data-multi="1"
						v-on:click.prevent.stop="removeAll('<?= $enc->attr( $this->url( $delTarget, $delCntl, $delAction, ['id' => ''] + $params, [], $delConfig ) ) ?>','<?= $enc->attr( $this->translate( 'admin', 'Selected entries' ) ) ?>')"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete selected entries' ) ); ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ); ?>">
					</a>
				</th>

				<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-standard' ),
						['fields' => $fields, 'params' => $params, 'data' => $columnList, 'sort' => $this->session( 'aimeos/admin/jqadm/review/sort' )]
					);
				?>

				<th class="actions">
					<?= $this->partial(
							$this->config( 'admin/jqadm/partial/columns', 'common/partials/columns-standard' ),
							['fields' => $fields, 'data' => $columnList]
						);
					?>
				</th>
			</tr>
		</thead>
		<tbody>

			<?= $this->partial(
				$this->config( 'admin/jqadm/partial/listsearch', 'common/partials/listsearch-standard' ), [
					'fields' => array_merge( $fields, ['select'] ), 'filter' => $this->session( 'aimeos/admin/jqadm/review/filter', [] ),
					'data' => [
						'review.id' => ['op' => '=='],
						'review.status' => ['op' => '==', 'type' => 'select', 'val' => [
							'1' => $this->translate( 'mshop/code', 'status:1' ),
							'0' => $this->translate( 'mshop/code', 'status:0' ),
							'-1' => $this->translate( 'mshop/code', 'status:-1' ),
							'-2' => $this->translate( 'mshop/code', 'status:-2' ),
						]],
						'review.domain' => ['op' => '==', 'type' => 'select', 'val' => $domains],
						'review.refid' => ['op' => '=='],
						'review.name' => [],
						'review.rating' => [],
						'review.comment' => ['op' => '~='],
						'review.response' => ['op' => '~='],
						'review.ctime' => ['op' => '-', 'type' => 'datetime-local'],
						'review.mtime' => ['op' => '-', 'type' => 'datetime-local'],
						'review.editor' => [],
					]
				] );
			?>

			<?php foreach( $this->get( 'items', [] ) as $id => $item ) : ?>
				<?php $url = $enc->attr( $this->url( $getTarget, $getCntl, $getAction, ['id' => $id] + $params, [], $getConfig ) ); ?>
				<tr class="list-item <?= $this->site()->readonly( $item->getSiteId() ); ?>" data-label="<?= $enc->attr( $item->getName() ) ?>">
					<td class="select"><input v-on:click="toggle('<?= $id ?>')" v-bind:checked="!items['<?= $id ?>']" class="form-control" type="checkbox" tabindex="1" name="<?= $enc->attr( $this->formparam( ['id', ''] ) ) ?>" value="<?= $enc->attr( $item->getId() ) ?>" /></td>
					<?php if( in_array( 'review.id', $fields ) ) : ?>
						<td class="review-id"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getId() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'review.status', $fields ) ) : ?>
						<td class="review-status"><a class="items-field" href="<?= $url; ?>"><div class="fa status-<?= $enc->attr( $item->getStatus() ); ?>"></div></a></td>
					<?php endif; ?>
					<?php if( in_array( 'review.domain', $fields ) ) : ?>
						<td class="review-domain"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getDomain() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'review.refid', $fields ) ) : ?>
						<td class="review-refid"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getRefId() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'review.name', $fields ) ) : ?>
						<td class="review-name"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getName() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'review.rating', $fields ) ) : ?>
						<td class="review-rating"><a class="items-field" href="<?= $url; ?>" tabindex="1"><?= $enc->html( $item->getRating() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'review.comment', $fields ) ) : ?>
						<td class="review-comment"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getComment() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'review.response', $fields ) ) : ?>
						<td class="review-response"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getResponse() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'review.ctime', $fields ) ) : ?>
						<td class="review-ctime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeCreated() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'review.mtime', $fields ) ) : ?>
						<td class="review-mtime"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getTimeModified() ); ?></a></td>
					<?php endif; ?>
					<?php if( in_array( 'review.editor', $fields ) ) : ?>
						<td class="review-editor"><a class="items-field" href="<?= $url; ?>"><?= $enc->html( $item->getEditor() ); ?></a></td>
					<?php endif; ?>

					<td class="actions">
						<?php if( $this->access( ['super', 'admin'] ) && !$this->site()->readonly( $item->getSiteId() ) ) : ?>
							<a class="btn act-delete fa" tabindex="1" href="#"
								v-on:click.prevent.stop="remove('<?= $enc->attr( $this->url( $delTarget, $delCntl, $delAction, ['id' => $id] + $params, [], $delConfig ) ) ?>','<?= $enc->attr( mb_strcut( $item->getComment(), 0, 30 ) ) ?>')"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ); ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ); ?>">
							</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if( $this->get( 'items', map() )->isEmpty() ) : ?>
		<?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ); ?>
	<?php endif; ?>
</form>

<?= $this->partial(
		$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard' ),
		['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get( 'total' ),
		'page' => $this->session( 'aimeos/admin/jqadm/review/page', [] )]
	);
?>

</div></div>

</div>
<?php $this->block()->stop(); ?>

<?= $this->render( $this->config( 'admin/jqadm/template/page', 'common/page-standard' ) ); ?>
