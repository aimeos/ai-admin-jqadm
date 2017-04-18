<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

$operators = $this->get( 'operators', [] );
$operators = ( isset( $operators['compare'] ) ? $operators['compare'] : [] );

$operatorMap = array(
	'=~' => array( 'string' ),
	'~=' => array( 'string' ),
	'>' => array( 'date', 'datetime', 'integer', 'float' ),
	'>=' => array( 'date', 'datetime', 'integer', 'float' ),
	'<' => array( 'date', 'datetime', 'integer', 'float' ),
	'<=' => array( 'date', 'datetime', 'integer', 'float' ),
	'==' => array( 'boolean', 'date', 'datetime', 'integer', 'float', 'string' ),
	'!=' => array( 'boolean', 'date', 'datetime', 'integer', 'float', 'string' ),
);

$filter = $this->param( 'filter' );

if( !isset( $filter['key'][0] ) ) {
	$filter['key'][0] = $this->get( 'default', '' );
}

$cnt = count( (array) $filter['key'] );

?>
<table class="filter-items search-item">
	<?php for( $pos = 0; $pos < $cnt; $pos++ ) : ?>
		<tr class="input-group filter-item">
			<td>
				<?php if( $pos < $cnt - 1 ) : ?>
					<div class="fa fa-minus" aria-label="<?= $enc->attr( $this->translate( 'admin', 'Remove filter' ) ); ?>"></div>
				<?php else : ?>
					<div class="fa fa-plus" aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add filter' ) ); ?>"></div>
				<?php endif; ?>
			</td>
			<td>
				<fieldset>
					<select name="<?= $this->formparam( array( 'filter', 'key', '' ) ); ?>" class="filter-key form-control" data-selected="<?= $filter['key'][$pos]; ?>">
					</select><!--
					--><select name="<?= $this->formparam( array( 'filter', 'op', '' ) ); ?>" class="filter-operator form-control c-select">
						<?php foreach( $operators as $code ) : ?>
							<option value="<?= $enc->attr( $code ); ?>"
								class="<?= ( isset( $operatorMap[$code] ) ? implode( ' ', $operatorMap[$code] ) : '' ); ?>"
								<?= ( isset( $filter['op'][$pos] ) && $filter['op'][$pos] === $code ? 'selected' : '' ); ?>
							><?= $enc->html( $this->translate( 'admin/ext', $code ) ); ?></option>
						<?php endforeach; ?>
					</select><!--
					--><input name="<?= $this->formparam( array( 'filter', 'val', '' ) ); ?>" class="filter-value form-control" type="text" value="<?= $enc->attr( ( isset( $filter['val'][$pos] ) ? $filter['val'][$pos] : '' ) ); ?>" />
				</fieldset>
			</td>
		</tr>
	<?php endfor; ?>

	<tr class="input-group prototype">
		<td>
			<div class="fa fa-plus" aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add filter' ) ); ?>"></div>
		</td>
		<td>
			<fieldset>
				<select name="<?= $this->formparam( array( 'filter', 'key', '' ) ); ?>" class="filter-key form-control" data-selected="<?= $this->get( 'default' ); ?>" disabled="disabled">
				</select><!--
				--><select name="<?= $this->formparam( array( 'filter', 'op', '' ) ); ?>" class="filter-operator form-control c-select" disabled="disabled">
					<?php foreach( $operators as $code ) : ?>
						<option value="<?= $enc->attr( $code ); ?>"
							class="<?= ( isset( $operatorMap[$code] ) ? implode( ' ', $operatorMap[$code] ) : '' ); ?>"
						><?= $enc->html( $this->translate( 'admin/ext', $code ) ); ?></option>
					<?php endforeach; ?>
				</select><!--
				--><input name="<?= $this->formparam( array( 'filter', 'val', '' ) ); ?>" class="filter-value form-control" type="text" disabled="disabled" />
			</fieldset>
		</td>
	</tr>
</table>
