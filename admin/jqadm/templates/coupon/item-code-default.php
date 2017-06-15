<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */


$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$enc = $this->encoder();
$params = $searchParam = $this->get( 'pageParams', [] );
unset( $searchParam['filter'] );

$default = $this->config( 'admin/jqadm/coupon/code/fields', ['coupon.code.code', 'coupon.code.count'] );
$fields = $this->param( 'fields/vc', $default );

$columnList = [
	'coupon.code.code' => $this->translate( 'admin', 'Code' ),
	'coupon.code.count' => $this->translate( 'admin', 'Count' ),
	'coupon.code.datestart' => $this->translate( 'admin', 'Start date' ),
	'coupon.code.dateend' => $this->translate( 'admin', 'End date' ),
	'coupon.code.ctime' => $this->translate( 'admin', 'Created' ),
	'coupon.code.mtime' => $this->translate( 'admin', 'Modified' ),
	'coupon.code.editor' => $this->translate( 'admin', 'Editor' ),
];


?>
<div id="code" class="item-code content-block tab-pane fade" role="tabpanel" aria-labelledby="code">
	<div class="table-responsive">
		<table class="list-items table table-hover">
			<thead class="list-header">
				<tr>
					<?= $this->partial(
							$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-default.php' ),
							['fields' => $fields, 'params' => $params, 'data' => $columnList]
						);
					?>

					<th class="actions">
						<a class="btn fa act-add" href="#"
							title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+a)') ); ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ); ?>">
						</a>

						<?= $this->partial(
								$this->config( 'admin/jqadm/partial/columns', 'common/partials/columns-default.php' ),
								['fields' => $fields, 'group' => 'vc', 'data' => $columnList]
							);
						?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?= $this->partial(
					$this->config( 'admin/jqadm/partial/listsearch', 'common/partials/listsearch-default.php' ), [
						'fields' => $fields, 'params' => $searchParam,
						'data' => [
							'coupon.code.code' => [],
							'coupon.code.count' => ['op' => '=='],
							'coupon.code.datestart' => ['op' => '>=', 'type' => 'datetime-local'],
							'coupon.code.dateend' => ['op' => '>=', 'type' => 'datetime-local'],
							'coupon.code.ctime' => ['op' => '>=', 'type' => 'datetime-local'],
							'coupon.code.mtime' => ['op' => '>=', 'type' => 'datetime-local'],
							'coupon.code.editor' => [],
						]
					] );
				?>

				<?php foreach( $this->get( 'codeItems', [] ) as $id => $item ) : ?>
					<tr>
						<?php if( in_array( 'coupon.code.code', $fields ) ) : ?>
							<td class="coupon-code">
								<input class="form-control coupon-code-code" type="text"
									name="<?= $enc->attr( $this->formparam( array( 'coupon.code.code' ) ) ); ?>"
									value="<?= $enc->attr( $item->getCode() ); ?>"
									<?= $this->site()->readonly( $item->getSiteId() ); ?> />
							</td>
						<?php endif; ?>
						<?php if( in_array( 'coupon.code.count', $fields ) ) : ?>
							<td class="coupon-count">
								<input class="form-control coupon-code-count" type="text"
									name="<?= $enc->attr( $this->formparam( array( 'coupon.code.count' ) ) ); ?>"
									value="<?= $enc->attr( $item->getCount() ); ?>"
									<?= $this->site()->readonly( $item->getSiteId() ); ?> />
							</td>
						<?php endif; ?>
						<?php if( in_array( 'coupon.code.datestart', $fields ) ) : ?>
							<td class="coupon-datestart">
								<input class="form-control coupon-code-datestart" type="datetime-local" tabindex="<?= $this->get( "tabindex" ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'coupon.code.datestart' ) ) ); ?>"
									value="<?= $enc->attr( str_replace( ' ', 'T', $item->getDateStart() ) ); ?>"
									<?= $this->site()->readonly( $item->getSiteId() ); ?> />
							</td>
						<?php endif; ?>
						<?php if( in_array( 'coupon.code.dateend', $fields ) ) : ?>
							<td class="coupon-dateend">
								<input class="form-control coupon-code-dateend" type="datetime-local" tabindex="<?= $this->get( "tabindex" ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'coupon.code.dateend' ) ) ); ?>"
									value="<?= $enc->attr( str_replace( ' ', 'T', $item->getDateEnd() ) ); ?>"
									<?= $this->site()->readonly( $item->getSiteId() ); ?> />
							</td>
						<?php endif; ?>
						<?php if( in_array( 'coupon.code.ctime', $fields ) ) : ?>
							<td class="coupon-ctime">
								<span class="form-control coupon-code-ctime" <?= $this->site()->readonly( $item->getSiteId() ); ?> >
									<?= $enc->attr( $item->getTimeCreated() ); ?>
								</span>
							</td>
						<?php endif; ?>
						<?php if( in_array( 'coupon.code.mtime', $fields ) ) : ?>
							<td class="coupon-mtime">
								<span class="form-control coupon-code-mtime" <?= $this->site()->readonly( $item->getSiteId() ); ?> >
									<?= $enc->attr( $item->getTimeModified() ); ?>
								</span>
							</td>
						<?php endif; ?>
						<?php if( in_array( 'coupon.code.editor', $fields ) ) : ?>
							<td class="coupon-editor">
								<span class="form-control coupon-code-editor" <?= $this->site()->readonly( $item->getSiteId() ); ?> >
									<?= $enc->attr( $item->getEditor() ); ?>
								</span>
							</td>
						<?php endif; ?>
						<td class="actions">
							<input type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'coupon.code.id' ) ) ); ?>"
								value="<?= $enc->attr( $id ); ?>" />
							<input type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'coupon.code.parentid' ) ) ); ?>"
								value="<?= $enc->attr( $item->getParentId() ); ?>" />

							<a class="btn fa act-delete" href="#"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ); ?>">
							</a>
							<a class="btn fa act-save" href="#"
								title="<?= $enc->attr( $this->translate( 'admin', 'Save entry') ); ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Save' ) ); ?>">
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<?php if( $this->get( 'codeItems', [] ) === [] ) : ?>
			<?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ); ?>
		<?php endif; ?>
	</div>
</div>
<?= $this->get( 'codeBody' ); ?>
