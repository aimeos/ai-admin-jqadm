<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2024
 */


/**
 * Renders the pagination in the list view
 *
 * Available data:
 * - action: Action to use for generating URLs
 * - fragment: Name of the subpanel that should be shown by default
 * - group: Parameter group if several lists are on one page
 * - pageParams: Associative list of page parameters
 * - page: Current pagination parameters
 * - pos: Drop-down direction (top/bottom)
 * - tabindex: Numerical index for tabbing through the fields and buttons
 * - total: Total number available items
 */


$pgroup = function( array $params, $group )
{
	if( $group != null ) {
		return [$group => ['page' => $params]];
	}

	return ['page' => $params];
};

$fragment = (array) $this->get( 'fragment', [] );
$total = min( $this->get( 'total', 0 ), 10000 );
$pOffset = $pLimit = $this->get( 'page', [] );
$params = $this->get( 'pageParams', [] );
$group = $this->get( 'group' );

$offset = max( $this->get( 'page/offset', 0 ), 0 );
$limit = max( $this->get( 'page/limit', 25 ), 1 );

$first = ( $offset > 0 ? 0 : null );
$prev = ( $offset - $limit >= 0 ? $offset - $limit : null );
$next = ( $offset + $limit < $total ? $offset + $limit : null );
$last = ( floor( ( $total - 1 ) / $limit ) * $limit > $offset ? floor( ( $total - 1 ) / $limit ) * $limit : null );

$pageCurrent = floor( $offset / $limit ) + 1;
$pageTotal = ( $total != 0 ? ceil( $total / $limit ) : 1 );


if( $this->get( 'action' ) === 'get' )
{
	if( isset( $params['id'] ) ) {
		$key = 'admin/jqadm/url/get';
	} else {
		$key = 'admin/jqadm/url/create';
	}
}
else
{
	$key = 'admin/jqadm/url/search';
}


$enc = $this->encoder();


?>
<?php if( $total > $limit || $offset > 0 || $this->get( 'pos', 'top' ) === 'bottom' ) : ?>
	<nav class="list-page">
		<ul class="page-offset pagination">
			<li class="page-item <?= ( $first === null ? 'disabled' : '' ) ?>">
				<a class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
					href="<?php $pOffset['offset'] = $first; echo $enc->attr( $this->link( $key, $pgroup( $pOffset, $group ) + $params, [], $fragment ) ) ?>"
					aria-label="<?= $enc->attr( $this->translate( 'admin', 'First' ) ) ?>">
					<span class="icon icon-first" aria-hidden="true"></span>
				</a>
			</li><!--
			--><li class="page-item <?= ( $prev === null ? 'disabled' : '' ) ?>">
				<a class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
					href="<?php $pOffset['offset'] = $prev; echo $enc->attr( $this->link( $key, $pgroup( $pOffset, $group ) + $params, [], $fragment ) ) ?>"
					aria-label="<?= $enc->attr( $this->translate( 'admin', 'Previous' ) ) ?>">
					<span class="icon icon-prev" aria-hidden="true"></span>
				</a>
			</li><!--
			--><li class="page-item disabled">
				<a class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#">
					<span class="d-none d-lg-block"><?= $enc->html( sprintf( $this->translate( 'admin', 'Page %1$d of %2$d' ), $pageCurrent, $pageTotal ) ) ?></span>
					<span class="d-lg-none"><?= $enc->html( sprintf( '%1$d/%2$d', $pageCurrent, $pageTotal ) ) ?></span>
				</a>
			</li><!--
			--><li class="page-item <?= ( $next === null ? 'disabled' : '' ) ?>">
				<a class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
					href="<?php $pOffset['offset'] = $next; echo $enc->attr( $this->link( $key, $pgroup( $pOffset, $group ) + $params, [], $fragment ) ) ?>"
					aria-label="<?= $enc->attr( $this->translate( 'admin', 'Next' ) ) ?>">
					<span class="icon icon-next" aria-hidden="true"></span>
				</a>
			</li><!--
			--><li class="page-item <?= ( $last === null ? 'disabled' : '' ) ?>">
				<a class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
					href="<?php $pOffset['offset'] = $last; echo $enc->attr( $this->link( $key, $pgroup( $pOffset, $group ) + $params, [], $fragment ) ) ?>"
					aria-label="<?= $enc->attr( $this->translate( 'admin', 'Last' ) ) ?>">
					<span class="icon icon-last" aria-hidden="true"></span>
				</a>
			</li>
		</ul>
		<div class="page-limit btn-group <?= ( $this->get( 'pos', 'top' ) === 'bottom' ? 'dropup' : '' ) ?>" role="group">
			<button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}'
				 tabindex="<?= $this->get( 'tabindex', 1 ) ?>" aria-haspopup="true" aria-expanded="false">
				<?= $limit ?> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu dropdown-menu-end">
				<li class="dropdown-item">
					<a href="<?php $pLimit['limit'] = 25; $pLimit['offset'] = 0; echo $enc->attr( $this->link( $key, $pgroup( $pLimit, $group ) + $params, [], $fragment ) ) ?>"
						tabindex="<?= $this->get( 'tabindex', 1 ) ?>">25</a>
				</li>
				<li class="dropdown-item">
					<a href="<?php $pLimit['limit'] = 50; $pLimit['offset'] = 0; echo $enc->attr( $this->link( $key, $pgroup( $pLimit, $group ) + $params, [], $fragment ) ) ?>"
						tabindex="<?= $this->get( 'tabindex', 1 ) ?>">50</a>
				</li>
				<li class="dropdown-item">
					<a href="<?php $pLimit['limit'] = 100; $pLimit['offset'] = 0; echo $enc->attr( $this->link( $key, $pgroup( $pLimit, $group ) + $params, [], $fragment ) ) ?>"
						tabindex="<?= $this->get( 'tabindex', 1 ) ?>">100</a>
				</li>
				<li class="dropdown-item">
					<a href="<?php $pLimit['limit'] = 200; $pLimit['offset'] = 0; echo $enc->attr( $this->link( $key, $pgroup( $pLimit, $group ) + $params, [], $fragment ) ) ?>"
						tabindex="<?= $this->get( 'tabindex', 1 ) ?>">200</a>
				</li>
				<li class="dropdown-item">
					<a href="<?php $pLimit['limit'] = 500; $pLimit['offset'] = 0; echo $enc->attr( $this->link( $key, $pgroup( $pLimit, $group ) + $params, [], $fragment ) ) ?>"
						tabindex="<?= $this->get( 'tabindex', 1 ) ?>">500</a>
				</li>
			</ul>
		</div>
	</nav>
<?php endif ?>
