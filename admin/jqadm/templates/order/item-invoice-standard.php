<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */


$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$enc = $this->encoder();
$params = $this->get( 'pageParams', [] );


/** admin/jqadm/order/invoice/fields
 * List of invoice columns that should be displayed in the order invoice view
 *
 * Changes the list of invoice columns shown by default in the order invoice view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "order.id" for the order ID value.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.07
 * @category Developer
 */
$default = ['order.id', 'order.datepayment', 'order.statuspayment', 'order.statusdelivery'];
$default = $this->config( 'admin/jqadm/order/invoice/fields', $default );
$fields = $this->session( 'aimeos/admin/jqadm/orderinvoice/fields', $default );


/** admin/jqadm/order/invoice/types
 * List of invoice source types that can be selected in the order invoice view
 *
 * Changes the list of available source types for invoices. Sources types can be
 * e.g. "web", "phone" or any other custom source. Each source string must not
 * be longer than eight characters.
 *
 * @param array List invoice source types
 * @since 2017.07
 * @category Developer
 */
$types = $this->config( 'admin/jqadm/order/invoice/fields', ['web', 'phone'] );


$paymentStatusList = [
	'-1' => $this->translate( 'mshop/code', 'pay:-1' ),
	'0' => $this->translate( 'mshop/code', 'pay:0' ),
	'1' => $this->translate( 'mshop/code', 'pay:1' ),
	'2' => $this->translate( 'mshop/code', 'pay:2' ),
	'3' => $this->translate( 'mshop/code', 'pay:3' ),
	'4' => $this->translate( 'mshop/code', 'pay:4' ),
	'5' => $this->translate( 'mshop/code', 'pay:5' ),
	'6' => $this->translate( 'mshop/code', 'pay:6' ),
];

$statusList = [
	'-1' => $this->translate( 'mshop/code', 'stat:-1' ),
	'0' => $this->translate( 'mshop/code', 'stat:0' ),
	'1' => $this->translate( 'mshop/code', 'stat:1' ),
	'2' => $this->translate( 'mshop/code', 'stat:2' ),
	'3' => $this->translate( 'mshop/code', 'stat:3' ),
	'4' => $this->translate( 'mshop/code', 'stat:4' ),
	'5' => $this->translate( 'mshop/code', 'stat:5' ),
	'6' => $this->translate( 'mshop/code', 'stat:6' ),
	'7' => $this->translate( 'mshop/code', 'stat:7' ),
];


?>
<div id="invoice" class="item-invoice content-block tab-pane fade" role="tabpanel" aria-labelledby="invoice">

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard.php' ),
			['pageParams' => $params, 'pos' => 'top', 'total' => $this->get( 'invoiceTotal' ),
			'group' => 'oi', 'action' => 'get', 'fragment' => 'invoice',
			'page' => $this->session( 'aimeos/admin/jqadm/orderinvoice/page', [] )]
		);
	?>

	<table class="list-items table table-striped table-hover">
		<thead class="list-header">
			<tr>
				<?= $this->partial(
					$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-standard.php' ), [
						'fields' => $fields, 'params' => $params, 'tabindex' => $this->get( 'tabindex' ),
						'group' => 'oi', 'action' => 'get', 'fragment' => 'invoice',
						'sort' => $this->session( 'aimeos/admin/jqadm/product/sort' ),
						'data' => [
							'order.id' => $this->translate( 'admin', 'Invoice' ),
							'order.type' => $this->translate( 'admin', 'Type' ),
							'order.datepayment' => $this->translate( 'admin', 'Payment' ),
							'order.statuspayment' => $this->translate( 'admin', 'Payment status' ),
							'order.datedelivery' => $this->translate( 'admin', 'Delivery' ),
							'order.statusdelivery' => $this->translate( 'admin', 'Delivery status' ),
							'order.relatedid' => $this->translate( 'admin', 'Related ID' ),
							'order.ctime' => $this->translate( 'admin', 'Created' ),
							'order.mtime' => $this->translate( 'admin', 'Modified' ),
							'order.editor' => $this->translate( 'admin', 'Editor' ),
						]
					] );
				?>

				<th class="actions">
					<a class="btn fa act-add" href="#" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ); ?>">
					</a>

					<?= $this->partial(
						$this->config( 'admin/jqadm/partial/columns', 'common/partials/columns-standard.php' ), [
							'fields' => $fields, 'group' => 'oi', 'tabindex' => $this->get( 'tabindex' ),
							'data' => [
								'order.id' => $this->translate( 'admin', 'Invoice' ),
								'order.type' => $this->translate( 'admin', 'Type' ),
								'order.datepayment' => $this->translate( 'admin', 'Payment' ),
								'order.statuspayment' => $this->translate( 'admin', 'Payment status' ),
								'order.datedelivery' => $this->translate( 'admin', 'Delivery' ),
								'order.statusdelivery' => $this->translate( 'admin', 'Delivery status' ),
								'order.relatedid' => $this->translate( 'admin', 'Related ID' ),
								'order.ctime' => $this->translate( 'admin', 'Created' ),
								'order.mtime' => $this->translate( 'admin', 'Modified' ),
								'order.editor' => $this->translate( 'admin', 'Editor' ),
							]
						] );
					?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?= $this->partial(
				$this->config( 'admin/jqadm/partial/listsearch', 'common/partials/listsearch-standard.php' ), [
					'filter' => $this->session( 'aimeos/admin/jqadm/orderinvoice/filter', [] ),
					'fields' => $fields, 'group' => 'oi', 'tabindex' => $this->get( 'tabindex' ),
					'data' => [
						'order.id' => ['oi' => '==', 'type' => 'number'],
						'order.type' => ['oi' => '~=', 'type' => 'select', 'val' => [
							'web' => $this->translate( 'mshop/code', 'order:web' ),
							'phone' => $this->translate( 'mshop/code', 'order:phone' ),
						]],
						'order.datepayment' => ['oi' => '>=', 'type' => 'datetime-local'],
						'order.statuspayment' => ['oi' => '==', 'type' => 'select', 'val' => $paymentStatusList],
						'order.datedelivery' => ['oi' => '>=', 'type' => 'datetime-local'],
						'order.statusdelivery' => ['oi' => '==', 'type' => 'select', 'val' => $statusList],
						'order.relatedid' => ['oi' => '=='],
						'order.ctime' => ['op' => '>=', 'type' => 'date'],
						'order.mtime' => ['op' => '>=', 'type' => 'date'],
						'order.editor' => [],
					]
				] );
			?>

			<tr class="list-item-new prototype">
				<td colspan="<?= count( $fields ); ?>">
					<div class="content-block row">
						<div class="col-xl-6">
							<input class="order-id" type="hidden" value="" disabled="disabled"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.id', '' ) ) ); ?>" />

							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></label>
								<div class="col-sm-8">
									<select class="form-control custom-select order-type" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
										name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.type', '' ) ) ); ?>" disabled="disabled">
										<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?></option>

										<?php foreach( $types as $type ) : ?>
											<option value="<?= $enc->attr( $type ); ?>" ><?= $enc->html( $type ); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Source of the invoice, e.g. by web or phone' ) ); ?>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Payment' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control order-datepayment" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
										name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.datepayment', '' ) ) ); ?>" disabled="disabled" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Date of the last payment status change' ) ); ?>
								</div>
							</div>
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Payment status' ) ); ?></label>
								<div class="col-sm-8">
									<select class="form-control custom-select order-statuspayment" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
										name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.statuspayment', '' ) ) ); ?>" disabled="disabled">
										<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?></option>
										<?php foreach( $paymentStatusList as $code => $label ) : ?>
											<option value="<?= $code ?>"><?= $enc->html( $label ); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Last payment status of the order' ) ); ?>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Delivery' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control order-datedelivery" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
										name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.datedelivery', '' ) ) ); ?>" disabled="disabled" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Date of the last delivery status change' ) ); ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Delivery status' ) ); ?></label>
								<div class="col-sm-8">
									<select class="form-control custom-select order-statusdelivery" tabindex="<?= $this->get( 'tabindex' ); ?>"
										name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.statusdelivery', '' ) ) ); ?>" disabled="disabled">
										<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?></option>
										<?php foreach( $statusList as $code => $label ) : ?>
											<option value="<?= $code ?>"><?= $enc->html( $label ); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Last delivery status of the order' ) ); ?>
								</div>
							</div>
							<div class="form-group row optional">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Related ID' ) ); ?></label>
								<div class="col-sm-8">
									<input class="form-control order-relatedid" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
										name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.relatedid', '' ) ) ); ?>" disabled="disabled" />
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'ID of a related invoice, e.g. of a changed or refunded invoice' ) ); ?>
								</div>
							</div>
						</div>
					</div>
				</td>
				<td class="actions">
					<a class="btn fa act-close" href="#" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Close') ); ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Close' ) ); ?>">
					</a>
				</td>
			</tr>

			<?php foreach( $this->get( 'invoiceData/order.id', [] ) as $idx => $orderId ) : ?>
				<?php $siteId = $this->get( 'invoiceData/order.siteid/' . $idx ); ?>

				<tr class="list-item <?= $this->site()->readonly( $siteId ); ?>">
					<?php if( in_array( 'order.id', $fields ) ) : ?>
						<td class="order-id">
							<input class="order-id" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.id', '' ) ) ); ?>"
								value="<?= $enc->attr( $orderId ); ?>" disabled="disabled" />
							<?= $enc->html( $orderId ); ?>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.type', $fields ) ) : ?>
						<td class="order-type">
							<select class="form-control custom-select order-type" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.type', '' ) ) ); ?>"
								value="<?= $enc->attr( $this->get( 'invoiceData/order.type/' . $idx ) ); ?>" disabled="disabled" />
								<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?></option>

								<?php foreach( $types as $type ) : ?>
									<option value="<?= $enc->attr( $type ); ?>" <?= $selected( $this->get( 'invoiceData/order.type/' . $idx ), $type ); ?> >
										<?= $enc->html( $type ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.datepayment', $fields ) ) : ?>
						<td class="order-datepayment">
							<input class="form-control order-datepayment" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.datepayment', '' ) ) ); ?>"
								value="<?= $enc->attr( str_replace( ' ', 'T', $this->get( 'invoiceData/order.datepayment/' . $idx ) ) ); ?>"
								<?= $this->site()->readonly( $siteId ); ?> disabled="disabled" />
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.statuspayment', $fields ) ) : ?>
						<td class="order-statuspayment">
							<select class="form-control custom-select order-statuspayment" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.statuspayment', '' ) ) ); ?>"
								<?= $this->site()->readonly( $siteId ); ?> disabled="disabled" >
								<option value="">
									<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
								</option>
								<?php foreach( $paymentStatusList as $code => $label ) : ?>
									<option value="<?= $code ?>" <?= $selected( $this->get( 'invoiceData/order.statuspayment/' . $idx ), $code ); ?> >
										<?= $enc->html( $label ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.datedelivery', $fields ) ) : ?>
						<td class="order-datedelivery">
							<input class="form-control order-datedelivery" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.datedelivery', '' ) ) ); ?>"
								value="<?= $enc->attr( str_replace( ' ', 'T', $this->get( 'invoiceData/order.datedelivery/' . $idx ) ) ); ?>"
								<?= $this->site()->readonly( $siteId ); ?> disabled="disabled" />
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.statusdelivery', $fields ) ) : ?>
						<td class="order-statusdelivery">
							<select class="form-control custom-select order-statusdelivery" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.statusdelivery', '' ) ) ); ?>"
								<?= $this->site()->readonly( $siteId ); ?> disabled="disabled" >
								<option value="">
									<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
								</option>
								<?php foreach( $statusList as $code => $label ) : ?>
									<option value="<?= $code ?>" <?= $selected( $this->get( 'invoiceData/order.statusdelivery/' . $idx ), $code ); ?> >
										<?= $enc->html( $label ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.relatedid', $fields ) ) : ?>
						<td class="order-relatedid">
							<input class="form-control order-relatedid" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.relatedid', '' ) ) ); ?>"
								value="<?= $enc->attr( $this->get( 'invoiceData/order.relatedid/' . $idx ) ); ?>"
								<?= $this->site()->readonly( $siteId ); ?> disabled="disabled" />
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.ctime', $fields ) ) : ?>
						<td class="order-ctime"><?= $enc->html( $this->get( 'invoiceData/order.ctime/' . $idx ) ); ?></td>
					<?php endif; ?>
					<?php if( in_array( 'order.mtime', $fields ) ) : ?>
						<td class="order-mtime"><?= $enc->html( $this->get( 'invoiceData/order.mtime/' . $idx ) ); ?></td>
					<?php endif; ?>
					<?php if( in_array( 'order.editor', $fields ) ) : ?>
						<td class="order-editor"><?= $enc->html( $this->get( 'invoiceData/order.editor/' . $idx ) ); ?></td>
					<?php endif; ?>
					<td class="actions">
						<?php if( !$this->site()->readonly( $siteId ) ) : ?>
							<a class="btn act-edit fa" tabindex="<?= $this->get( 'tabindex' ); ?>" href="#"
								title="<?= $enc->attr( $this->translate( 'admin', 'Edit this entry') ); ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Edit' ) ); ?>"></a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if( $this->get( 'invoiceData/order.id', [] ) === [] ) : ?>
		<div class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ); ?></div>
	<?php endif; ?>

	<?= $this->partial(
			$this->config( 'admin/jqadm/partial/pagination', 'common/partials/pagination-standard.php' ),
			['pageParams' => $params, 'pos' => 'bottom', 'total' => $this->get( 'invoiceTotal' ),
			'group' => 'oi', 'action' => 'get', 'fragment' => 'invoice',
			'page' =>$this->session( 'aimeos/admin/jqadm/orderinvoice/page', [] )]
		);
	?>

</div>
<?= $this->get( 'invoiceIBody' ); ?>
