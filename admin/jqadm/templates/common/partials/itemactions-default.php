<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

/**
 * Renders action buttons in the item view
 *
 * Available data:
 * - params: Associative list of current parameters
 */

$listTarget = $this->config( 'admin/jqadm/url/search/target' );
$listCntl = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$listAction = $this->config( 'admin/jqadm/url/search/action', 'search' );
$listConfig = $this->config( 'admin/jqadm/url/search/config', [] );

$params = $this->get( 'params', [] );
unset( $params['id'] );

$enc = $this->encoder();


?>
<a class="btn btn-secondary act-cancel"
	title="<?= $enc->attr( $this->translate( 'admin', 'Cancel and return to list') ); ?>"
	href="<?= $enc->attr( $this->url( $listTarget, $listCntl, $listAction, $params, [], $listConfig ) ); ?>">
	<?php if( $this->access( ['admin', 'editor'] ) ) : ?>
		<?= $enc->html( $this->translate( 'admin', 'Cancel' ) ); ?>
	<?php else : ?>
		<?= $enc->html( $this->translate( 'admin', 'Back' ) ); ?>
	<?php endif; ?>
</a>

<?php if( $this->access( ['admin', 'editor'] ) ) : ?>
	<div class="btn-group">
		<button type="submit" class="btn btn-primary act-save"
			title="<?= $enc->attr( $this->translate( 'admin', 'Save entry (Ctrl+S)') ); ?>">
			<?= $enc->html( $this->translate( 'admin', 'Save' ) ); ?>
		</button>
		<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"
			aria-haspopup="true" aria-expanded="false">
			<span class="sr-only"><?= $enc->html( $this->translate( 'admin', 'Toggle dropdown' ) ); ?></span>
		</button>
		<div class="dropdown-menu dropdown-menu-right">
			<a class="dropdown-item next-action" href="#" data-next="search"><?= $enc->html( $this->translate( 'admin', 'Save & Close' ) ); ?></a>
			<a class="dropdown-item next-action" href="#" data-next="copy"><?= $enc->html( $this->translate( 'admin', 'Save & Copy' ) ); ?></a>
			<a class="dropdown-item next-action" href="#" data-next="create"><?= $enc->html( $this->translate( 'admin', 'Save & New' ) ); ?></a>
		</div>
	</div>
<?php endif; ?>
