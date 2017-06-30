<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */


$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$jsonTarget = $this->config( 'admin/jsonadm/url/target' );
$jsonCntl = $this->config( 'admin/jsonadm/url/controller', 'Jsonadm' );
$jsonAction = $this->config( 'admin/jsonadm/url/action', 'index' );
$jsonConfig = $this->config( 'admin/jsonadm/url/config', [] );

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
	<table class="list-items table table-hover">
		<thead class="list-header">
			<tr>
				<?= $this->partial(
						$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-default.php' ),
						['fields' => $fields, 'params' => $params, 'tabindex' => $this->get( 'tabindex' ), 'data' => $columnList]
					);
				?>

				<th class="actions">
					<a class="btn fa act-add" href="#" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+A)') ); ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ); ?>">
					</a>

					<?= $this->partial(
							$this->config( 'admin/jqadm/partial/columns', 'common/partials/columns-default.php' ),
							['fields' => $fields, 'group' => 'vc', 'tabindex' => $this->get( 'tabindex' ), 'data' => $columnList]
						);
					?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?= $this->partial(
				$this->config( 'admin/jqadm/partial/listsearch', 'common/partials/listsearch-default.php' ), [
					'fields' => $fields, 'tabindex' => $this->get( 'tabindex' ),
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

			<tr class="list-item-new prototype">
				<td colspan="<?= count( $fields ); ?>">
					<div class="content-block row">
						<div class="col-xl-6">
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Voucher' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control coupon-code-code" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>" required="required"
										name="<?= $enc->attr( $this->formparam( array( 'code', 'coupon.code.code', '' ) ) ); ?>" disabled="disabled" />
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Count' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control coupon-code-count" type="number" min="0" step="1" tabindex="<?= $this->get( 'tabindex' ); ?>" required="required"
										name="<?= $enc->attr( $this->formparam( array( 'code', 'coupon.code.count', '' ) ) ); ?>" disabled="disabled" />
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'Start date' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control coupon-code-dateend" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
										name="<?= $enc->attr( $this->formparam( array( 'code', 'coupon.code.dateend', '' ) ) ); ?>" disabled="disabled" />
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label"><?= $enc->html( $this->translate( 'admin', 'End date' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control coupon-code-dateend" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
										name="<?= $enc->attr( $this->formparam( array( 'code', 'coupon.code.dateend', '' ) ) ); ?>" disabled="disabled" />
								</div>
							</div>
						</div>
					</div>
				</td>
				<td class="actions">
					<input type="hidden" name="<?= $enc->attr( $this->formparam( array( 'code', 'coupon.code.id', '' ) ) ); ?>" disabled="disabled" />

					<a class="btn fa act-close" href="#" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Close') ); ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Close' ) ); ?>">
					</a>
				</td>
			</tr>

			<?php foreach( $this->get( 'codeData/coupon.code.id', [] ) as $idx => $id ) : ?>
				<tr class="<?= $this->site()->readonly( $this->get( 'codeData/coupon.code.siteid/' . $idx ) ); ?>">
					<?php if( in_array( 'coupon.code.code', $fields ) ) : ?>
						<td class="coupon-code">
							<input class="form-control coupon-code-code" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'code', 'coupon.code.code', '' ) ) ); ?>"
								value="<?= $enc->attr( $this->get( 'codeData/coupon.code.code/' . $idx ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'codeData/coupon.code.siteid/' . $idx ) ); ?> />
						</td>
					<?php endif; ?>
					<?php if( in_array( 'coupon.code.count', $fields ) ) : ?>
						<td class="coupon-count">
							<input class="form-control coupon-code-count" type="number" min="0" step="1" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'code', 'coupon.code.count', '' ) ) ); ?>"
								value="<?= $enc->attr( $this->get( 'codeData/coupon.code.count/' . $idx ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'codeData/coupon.code.siteid/' . $idx ) ); ?> />
						</td>
					<?php endif; ?>
					<?php if( in_array( 'coupon.code.datestart', $fields ) ) : ?>
						<td class="coupon-datestart">
							<input class="form-control coupon-code-datestart" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'code', 'coupon.code.datestart', '' ) ) ); ?>"
								value="<?= $enc->attr( str_replace( ' ', 'T', $this->get( 'codeData/coupon.code.datestart/' . $idx ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'codeData/coupon.code.siteid/' . $idx ) ); ?> />
						</td>
					<?php endif; ?>
					<?php if( in_array( 'coupon.code.dateend', $fields ) ) : ?>
						<td class="coupon-dateend">
							<input class="form-control coupon-code-dateend" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'code', 'coupon.code.dateend', '' ) ) ); ?>"
								value="<?= $enc->attr( str_replace( ' ', 'T', $this->get( 'codeData/coupon.code.dateend/' . $idx ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'codeData/coupon.code.siteid/' . $idx ) ); ?> />
						</td>
					<?php endif; ?>
					<?php if( in_array( 'coupon.code.ctime', $fields ) ) : ?>
						<td class="coupon-ctime">
							<span class="form-control coupon-code-ctime">
								<?= $enc->attr( $this->get( 'codeData/coupon.code.ctime/' . $idx ) ); ?>
							</span>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'coupon.code.mtime', $fields ) ) : ?>
						<td class="coupon-mtime">
							<span class="form-control coupon-code-mtime">
								<?= $enc->attr( $this->get( 'codeData/coupon.code.mtime/' . $idx ) ); ?>
							</span>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'coupon.code.editor', $fields ) ) : ?>
						<td class="coupon-editor">
							<span class="form-control coupon-code-editor">
								<?= $enc->attr( $this->get( 'codeData/coupon.code.editor/' . $idx ) ); ?>
							</span>
						</td>
					<?php endif; ?>
					<td class="actions">
						<input type="hidden" name="<?= $enc->attr( $this->formparam( array( 'code', 'coupon.code.id', '' ) ) ); ?>" value="<?= $enc->attr( $id ); ?>" />

						<a class="btn fa act-delete" tabindex="<?= $this->get( 'tabindex' ); ?>"
							href="<?= $this->url( $jsonTarget, $jsonCntl, $jsonAction, ['resource' => 'coupon/code', 'id' => $id], [], $jsonConfig ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete') ); ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ); ?>">
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if( $this->get( 'codeData/coupon.code.siteid', [] ) === [] ) : ?>
		<?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ); ?>
	<?php endif; ?>
</div>
<?= $this->get( 'codeBody' ); ?>
