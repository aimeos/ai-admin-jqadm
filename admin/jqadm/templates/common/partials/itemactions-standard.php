<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2021
 */

/**
 * Renders action buttons in the item view
 *
 * Available data:
 * - params: Associative list of current parameters
 * - actions: List of possible save actions (e.g. "search", "copy", "create")
 */

$listTarget = $this->config( 'admin/jqadm/url/search/target' );
$listCntl = $this->config( 'admin/jqadm/url/search/controller', 'Jqadm' );
$listAction = $this->config( 'admin/jqadm/url/search/action', 'search' );
$listConfig = $this->config( 'admin/jqadm/url/search/config', [] );

$actions = $this->get( 'actions', ['search', 'copy', 'create'] );
$params = $this->get( 'params', [] );
unset( $params['id'] );

$enc = $this->encoder();


?>
<div class="btn btn-secondary act-help" title="<?= $enc->attr( $this->translate( 'admin', 'Display help texts' ) ) ?>">
	<?= $enc->html( $this->translate( 'admin', '?' ) ) ?>
</div>

<a class="btn btn-secondary act-cancel"
	title="<?= $enc->attr( $this->translate( 'admin', 'Cancel and return to list' ) ) ?>"
	href="<?= $enc->attr( $this->url( $listTarget, $listCntl, $listAction, $params, [], $listConfig ) ) ?>">
	<?= $enc->html( $this->translate( 'admin', 'Cancel' ) ) ?>
</a>

<div class="btn-group">
	<button type="submit" class="btn btn-primary act-save"
		title="<?= $enc->attr( $this->translate( 'admin', 'Save entry (Ctrl+S)' ) ) ?>">
		<?= $enc->html( $this->translate( 'admin', 'Save' ) ) ?>
	</button>
	<?php if( !empty( $actions ) ) : ?>
		<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"
			aria-haspopup="true" aria-expanded="false">
			<span class="sr-only"><?= $enc->html( $this->translate( 'admin', 'Toggle dropdown' ) ) ?></span>
		</button>
		<ul class="dropdown-menu dropdown-menu-end">
			<?php if( in_array( 'search', $actions ) ) : ?>
				<li class="dropdown-item"><a class="next-action" href="#" data-next="search"><?= $enc->html( $this->translate( 'admin', 'Save & Close' ) ) ?></a></li>
			<?php endif ?>
			<?php if( in_array( 'copy', $actions ) ) : ?>
				<li class="dropdown-item"><a class="next-action" href="#" data-next="copy"><?= $enc->html( $this->translate( 'admin', 'Save & Copy' ) ) ?></a></li>
			<?php endif ?>
			<?php if( in_array( 'create', $actions ) ) : ?>
				<li class="dropdown-item"><a class="next-action" href="#" data-next="create"><?= $enc->html( $this->translate( 'admin', 'Save & New' ) ) ?></a></li>
			<?php endif ?>
		</ul>
	<?php endif ?>
</div>
