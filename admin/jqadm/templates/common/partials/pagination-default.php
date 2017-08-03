<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */


/**
 * Renders the pagination in the list view
 *
 * Available data:
 * - pageParams: Associative list of page parameters
 * - page: Current pagination parameters
 * - total: Total number available items
 * - tabindex: Numerical index for tabbing through the fields and buttons
 */


$enc = $this->encoder();

$target = $this->config( 'admin/jqadm/url/search/target' );
$controller = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/search/action', 'search' );
$config = $this->config( 'admin/jqadm/url/search/config', [] );

$params = $this->get( 'pageParams', [] );
$total = $this->get( 'total', 0 );

$offset = max( $this->get( 'page/offset', 0 ), 0 );
$limit = max( $this->get( 'page/limit', 100 ), 1 );

$first = ( $offset > 0 ? 0 : null );
$prev = ( $offset - $limit >= 0 ? $offset - $limit : null );
$next = ( $offset + $limit < $total ? $offset + $limit : null );
$last = ( ((int) ($total / $limit)) * $limit > $offset ? ((int) ($total / $limit)) * $limit : null );

$pageCurrent = floor( $offset / $limit ) + 1;
$pageTotal = ( $total != 0 ? ceil( $total / $limit ) : 1 );


?>
<?php if( $total > $limit ) : ?>
	<nav class="list-page">
		<ul class="page-offset pagination">
			<li class="page-item">
				<a class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ); ?>"
					href="<?php $params['page']['offset'] = $first; echo $enc->attr( $this->url( $target, $controller, $action, $params, [], $config ) ); ?>"
					aria-label="<?= $enc->attr( $this->translate( 'admin', 'First' ) ); ?>">
					<span class="fa fa-fast-backward" aria-hidden="true"></span>
				</a>
			</li><!--
			--><li class="page-item">
				<a class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ); ?>"
					href="<?php $params['page']['offset'] = $prev; echo $enc->attr( $this->url( $target, $controller, $action, $params, [], $config ) ); ?>"
					aria-label="<?= $enc->attr( $this->translate( 'admin', 'Previous' ) ); ?>">
					<span class="fa fa-step-backward" aria-hidden="true"></span>
				</a>
			</li><!--
			--><li class="page-item disabled">
				<a class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ); ?>" href="#">
					<?= $enc->html( sprintf( $this->translate( 'admin', 'Page %1$d of %2$d' ), $pageCurrent, $pageTotal ) ); ?>
				</a>
			</li><!--
			--><li class="page-item">
				<a class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ); ?>"
					href="<?php $params['page']['offset'] = $next; echo $enc->attr( $this->url( $target, $controller, $action, $params, [], $config ) ); ?>"
					aria-label="<?= $enc->attr( $this->translate( 'admin', 'Next' ) ); ?>">
					<span class="fa fa-step-forward" aria-hidden="true"></span>
				</a>
			</li><!--
			--><li class="page-item">
				<a class="page-link" tabindex="<?= $this->get( 'tabindex', 1 ); ?>"
					href="<?php $params['page']['offset'] = $last; echo $enc->attr( $this->url( $target, $controller, $action, $params, [], $config ) ); ?>"
					aria-label="<?= $enc->attr( $this->translate( 'admin', 'Last' ) ); ?>">
					<span class="fa fa-fast-forward" aria-hidden="true"></span>
				</a>
			</li>
		</ul>
		<div class="page-limit btn-group <?= ( $this->get( 'pos', 'top' ) === 'bottom' ? 'dropup' : '' ); ?>" role="group">
			<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
				 tabindex="<?= $this->get( 'tabindex', 1 ); ?>" aria-haspopup="true" aria-expanded="false">
				<?= $limit; ?> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li class="dropdown-item">
					<a href="<?php $params['page']['limit'] = 25; echo $enc->attr( $this->url( $target, $controller, $action, $params, [], $config ) ); ?>" tabindex="<?= $this->get( 'tabindex', 1 ); ?>">25</a>
				</li>
				<li class="dropdown-item">
					<a href="<?php $params['page']['limit'] = 50; echo $enc->attr( $this->url( $target, $controller, $action, $params, [], $config ) ); ?>" tabindex="<?= $this->get( 'tabindex', 1 ); ?>">50</a>
				</li>
				<li class="dropdown-item">
					<a href="<?php $params['page']['limit'] = 100; echo $enc->attr( $this->url( $target, $controller, $action, $params, [], $config ) ); ?>" tabindex="<?= $this->get( 'tabindex', 1 ); ?>">100</a>
				</li>
				<li class="dropdown-item">
					<a href="<?php $params['page']['limit'] = 200; echo $enc->attr( $this->url( $target, $controller, $action, $params, [], $config ) ); ?>" tabindex="<?= $this->get( 'tabindex', 1 ); ?>">200</a>
				</li>
				<li class="dropdown-item">
					<a href="<?php $params['page']['limit'] = 500; echo $enc->attr( $this->url( $target, $controller, $action, $params, [], $config ) ); ?>" tabindex="<?= $this->get( 'tabindex', 1 ); ?>">500</a>
				</li>
			</ul>
		</div>
	</nav>
<?php endif; ?>
