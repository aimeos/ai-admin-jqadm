<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$enc = $this->encoder();

?>
<div class="product-item-option card panel">
	<div id="product-item-option" class="header card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordion" data-target="#product-item-option-data" aria-expanded="false" aria-controls="product-item-option-data">
		<?php echo $enc->html( $this->translate( 'admin', 'Options' ) ); ?>
	</div>
	<div id="product-item-option-data" class="item-option card-block panel-collapse collapse" role="tabpanel" aria-labelledby="product-item-option">
<?php echo $this->get( 'optionBody' ); ?>
	</div>
</div>
