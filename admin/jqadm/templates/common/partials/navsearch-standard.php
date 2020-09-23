<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2020
 */


/**
 * Renders the navbar search fields in the list views
 *
 * Available data:
 * - filterAttributes: Associative list of keys (e.g. "product.id") and translated names (e.g. "ID")
 * - filterOperators: List of columns that are currently shown
 * - filter: Associative list of filter parameters
 * - params: Associative list of current parameters
 */


$selected = function( array $filter, $key, $code ) {
	return ( isset( $filter[$key][0] ) && $filter[$key][0] == $code ? 'selected="selected"' : '' );
};


$target = $this->config( 'admin/jqadm/url/search/target' );
$controller = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/search/action', 'search' );
$config = $this->config( 'admin/jqadm/url/search/config', [] );

$filter = $this->get( 'filter', [] );
$params = $this->get( 'params', [] );
$params['page']['start'] = 0;
unset( $params['filter'] );

$enc = $this->encoder();


?>
<div id="js--toggle-search" class="toggle-search" @click="toggleFilterSearchMobile">
	<span class="icon search"></span>
	<span class="hidden">Button to show/hide the filter search in mobile state only.</span>
</div>

<form class="form-inline" method="POST" action="<?= $enc->attr( $this->url( $target, $controller, $action, $params, [], $config ) ); ?>">
	<?= $this->csrf()->formfield(); ?>

	<i class="fa" :class="toggleFilter ? 'more' : 'less'" @click="toggleFilterSearch"></i>

	<div class="input-group">
		<select class="custom-select filter-key" :class="toggleFilter ? '' : 'expanded'" name="<?= $this->formparam( ['filter', 'key', '0'] ); ?>">
			<?php foreach( $this->get( 'filterAttributes', [] ) as $code => $attrItem ) : ?>
				<?php if( $attrItem->isPublic() ) : ?>
					<option value="<?= $enc->attr( $code ); ?>" data-type="<?= $enc->attr( $attrItem->getType() ); ?>" <?= $selected( $filter, 'key', $code ); ?> >
						<?= $enc->html( $this->translate( 'admin/ext', $attrItem->getLabel() ) ); ?>
					</option>
				<?php endif; ?>
			<?php endforeach; ?>
		</select>
		<select class="custom-select filter-operator" :class="toggleFilter ? '' : 'expanded'" name="<?= $this->formparam( ['filter', 'op', '0'] ); ?>">
			<?php foreach( $this->get( 'filterOperators/compare', [] ) as $code ) : ?>
				<option value="<?= $enc->attr( $code ); ?>" <?= $selected( $filter, 'op', $code ); ?> >
					<?= $enc->html( $code ) . ( strlen( $code ) === 1 ? '&nbsp;' : '' ); ?>&nbsp;&nbsp;<?= $enc->html( $this->translate( 'admin/ext', $code ) ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<input type="text" class="form-control filter-value" ref="input-filter-value" name="<?= $this->formparam( ['filter', 'val', '0'] ); ?>"
			 value="<?= $enc->attr( ( isset( $filter['val'][0] ) ? $filter['val'][0] : '' ) ); ?>" >
		<div class="input-group-append">
			<button class="btn btn-primary fa fa-search"></button>
		</div>
	</div>

</form>
