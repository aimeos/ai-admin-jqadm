<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
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
$fields = $this->param( 'fields/oi', $this->config( 'admin/jqadm/order/invoice/fields', $default ) );


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


?>
<div id="invoice" class="item-invoice content-block tab-pane fade" role="tabpanel" aria-labelledby="invoice">
	<table class="list-items table table-striped table-hover">
		<thead class="list-header">
			<tr>
				<?= $this->partial(
					$this->config( 'admin/jqadm/partial/listhead', 'common/partials/listhead-default.php' ), [
						'fields' => $fields, 'params' => $params, 'tabindex' => $this->get( 'tabindex' ),
						'data' => [
							'order.id' => $this->translate( 'admin', 'Invoice' ),
							'order.datepayment' => $this->translate( 'admin', 'Purchased' ),
							'order.statuspayment' => $this->translate( 'admin', 'Payment status' ),
							'order.type' => $this->translate( 'admin', 'Type' ),
							'order.datedelivery' => $this->translate( 'admin', 'Delivery' ),
							'order.statusdelivery' => $this->translate( 'admin', 'Delivery status' ),
							'order.relatedid' => $this->translate( 'admin', 'Related ID' ),
						]
					] );
				?>

				<th class="actions">
					<a class="btn fa act-add" href="#" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+A)') ); ?>"
						aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ); ?>">
					</a>

					<?= $this->partial(
						$this->config( 'admin/jqadm/partial/columns', 'common/partials/columns-default.php' ), [
							'fields' => $fields, 'group' => 'oi', 'tabindex' => $this->get( 'tabindex' ),
							'data' => [
								'order.id' => $this->translate( 'admin', 'Invoice' ),
								'order.datepayment' => $this->translate( 'admin', 'Purchased' ),
								'order.statuspayment' => $this->translate( 'admin', 'Payment status' ),
								'order.type' => $this->translate( 'admin', 'Type' ),
								'order.datedelivery' => $this->translate( 'admin', 'Delivery' ),
								'order.statusdelivery' => $this->translate( 'admin', 'Delivery status' ),
								'order.relatedid' => $this->translate( 'admin', 'Related ID' ),
							]
						] );
					?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?= $this->partial(
				$this->config( 'admin/jqadm/partial/listsearch', 'common/partials/listsearch-default.php' ), [
					'fields' => $fields, 'tabindex' => $this->get( 'tabindex' ),
					'data' => [
						'order.id' => ['oi' => '==', 'type' => 'number'],
						'order.datepayment' => ['oi' => '>=', 'type' => 'datetime-local'],
						'order.statuspayment' => ['oi' => '==', 'type' => 'select', 'val' => [
							'-1' => $this->translate( 'client/code', 'pay:-1' ),
							'0' => $this->translate( 'client/code', 'pay:0' ),
							'1' => $this->translate( 'client/code', 'pay:1' ),
							'2' => $this->translate( 'client/code', 'pay:2' ),
							'3' => $this->translate( 'client/code', 'pay:3' ),
							'4' => $this->translate( 'client/code', 'pay:4' ),
							'5' => $this->translate( 'client/code', 'pay:5' ),
							'6' => $this->translate( 'client/code', 'pay:6' ),
						]],
						'order.type' => ['oi' => '~='],
						'order.datedelivery' => ['oi' => '>=', 'type' => 'datetime-local'],
						'order.statusdelivery' => ['oi' => '==', 'type' => 'select', 'val' => [
							'-1' => $this->translate( 'client/code', 'stat:-1' ),
							'0' => $this->translate( 'client/code', 'stat:0' ),
							'1' => $this->translate( 'client/code', 'stat:1' ),
							'2' => $this->translate( 'client/code', 'stat:2' ),
							'3' => $this->translate( 'client/code', 'stat:3' ),
							'4' => $this->translate( 'client/code', 'stat:4' ),
							'5' => $this->translate( 'client/code', 'stat:5' ),
							'6' => $this->translate( 'client/code', 'stat:6' ),
							'7' => $this->translate( 'client/code', 'stat:7' ),
						]],
						'order.relatedid' => ['oi' => '=='],
					]
				] );
			?>

			<tr class="list-item-new prototype">
				<td colspan="<?= count( $fields ); ?>">
					<div class="content-block row">
						<div class="col-xl-6">
							<div class="form-group row mandatory">
								<label class="col-sm-4 form-control-label help"><?= $enc->html( $this->translate( 'admin', 'Purchased' ) ); ?></label>
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
										<option value="-1"><?= $enc->html( $this->translate( 'client/code', 'pay:-1' ) ); ?></option>
										<option value="0"><?= $enc->html( $this->translate( 'client/code', 'pay:0' ) ); ?></option>
										<option value="1"><?= $enc->html( $this->translate( 'client/code', 'pay:1' ) ); ?></option>
										<option value="2"><?= $enc->html( $this->translate( 'client/code', 'pay:2' ) ); ?></option>
										<option value="3"><?= $enc->html( $this->translate( 'client/code', 'pay:3' ) ); ?></option>
										<option value="4"><?= $enc->html( $this->translate( 'client/code', 'pay:4' ) ); ?></option>
										<option value="5"><?= $enc->html( $this->translate( 'client/code', 'pay:5' ) ); ?></option>
										<option value="6"><?= $enc->html( $this->translate( 'client/code', 'pay:6' ) ); ?></option>
									</select>
								</div>
								<div class="col-sm-12 form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Last payment status of the order' ) ); ?>
								</div>
							</div>
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
										<option value="-1"><?= $enc->html( $this->translate( 'client/code', 'stat:-1' ) ); ?></option>
										<option value="0"><?= $enc->html( $this->translate( 'client/code', 'stat:0' ) ); ?></option>
										<option value="1"><?= $enc->html( $this->translate( 'client/code', 'stat:1' ) ); ?></option>
										<option value="2"><?= $enc->html( $this->translate( 'client/code', 'stat:2' ) ); ?></option>
										<option value="3"><?= $enc->html( $this->translate( 'client/code', 'stat:3' ) ); ?></option>
										<option value="4"><?= $enc->html( $this->translate( 'client/code', 'stat:4' ) ); ?></option>
										<option value="5"><?= $enc->html( $this->translate( 'client/code', 'stat:5' ) ); ?></option>
										<option value="6"><?= $enc->html( $this->translate( 'client/code', 'stat:6' ) ); ?></option>
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
								value="<?= $enc->attr( $orderId ); ?>" />
							<?= $enc->html( $orderId ); ?>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.datepayment', $fields ) ) : ?>
						<td class="order-datepayment">
							<input class="form-control order-datepayment" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.datepayment', '' ) ) ); ?>"
								value="<?= $enc->attr( str_replace( ' ', 'T', $this->get( 'invoiceData/order.datepayment/' . $idx ) ) ); ?>"
								<?= $this->site()->readonly( $siteId ); ?> />
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.statuspayment', $fields ) ) : ?>
						<td class="order-statuspayment">
							<select class="form-control custom-select order-statuspayment" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.statuspayment', '' ) ) ); ?>"
								<?= $this->site()->readonly( $siteId ); ?> >
								<option value="">
									<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
								</option>
								<option value="-1" <?= $selected( $this->get( 'invoiceData/order.statuspayment/' . $idx ), '-1' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'pay:-1' ) ); ?>
								</option>
								<option value="0" <?= $selected( $this->get( 'invoiceData/order.statuspayment/' . $idx ), '0' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'pay:0' ) ); ?>
								</option>
								<option value="1" <?= $selected( $this->get( 'invoiceData/order.statuspayment/' . $idx ), '1' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'pay:1' ) ); ?>
								</option>
								<option value="2" <?= $selected( $this->get( 'invoiceData/order.statuspayment/' . $idx ), '2' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'pay:2' ) ); ?>
								</option>
								<option value="3" <?= $selected( $this->get( 'invoiceData/order.statuspayment/' . $idx ), '3' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'pay:3' ) ); ?>
								</option>
								<option value="4" <?= $selected( $this->get( 'invoiceData/order.statuspayment/' . $idx ), '4' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'pay:4' ) ); ?>
								</option>
								<option value="5" <?= $selected( $this->get( 'invoiceData/order.statuspayment/' . $idx ), '5' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'pay:5' ) ); ?>
								</option>
								<option value="6" <?= $selected( $this->get( 'invoiceData/order.statuspayment/' . $idx ), '6' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'pay:6' ) ); ?>
								</option>
							</select>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.type', $fields ) ) : ?>
						<td class="order-type">
							<select class="form-control custom-select order-type" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.type', '' ) ) ); ?>"
								value="<?= $enc->attr( $this->get( 'invoiceData/order.type/' . $idx ) ); ?>" />
								<option value=""><?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?></option>

								<?php foreach( $types as $type ) : ?>
									<option value="<?= $enc->attr( $type ); ?>" <?= $selected( $this->get( 'invoiceData/order.type/' . $idx ), $type ); ?> >
										<?= $enc->html( $type ); ?>
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
								<?= $this->site()->readonly( $siteId ); ?> />
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.statusdelivery', $fields ) ) : ?>
						<td class="order-statusdelivery">
							<select class="form-control custom-select order-statusdelivery" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.statusdelivery', '' ) ) ); ?>"
								<?= $this->site()->readonly( $siteId ); ?> >
								<option value="">
									<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
								</option>
								<option value="-1" <?= $selected( $this->get( 'invoiceData/order.statusdelivery/' . $idx ), '-1' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'stat:-1' ) ); ?>
								</option>
								<option value="0" <?= $selected( $this->get( 'invoiceData/order.statusdelivery/' . $idx ), '0' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'stat:0' ) ); ?>
								</option>
								<option value="1" <?= $selected( $this->get( 'invoiceData/order.statusdelivery/' . $idx ), '1' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'stat:1' ) ); ?>
								</option>
								<option value="2" <?= $selected( $this->get( 'invoiceData/order.statusdelivery/' . $idx ), '2' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'stat:2' ) ); ?>
								</option>
								<option value="3" <?= $selected( $this->get( 'invoiceData/order.statusdelivery/' . $idx ), '3' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'stat:3' ) ); ?>
								</option>
								<option value="4" <?= $selected( $this->get( 'invoiceData/order.statusdelivery/' . $idx ), '4' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'stat:4' ) ); ?>
								</option>
								<option value="5" <?= $selected( $this->get( 'invoiceData/order.statusdelivery/' . $idx ), '5' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'stat:5' ) ); ?>
								</option>
								<option value="6" <?= $selected( $this->get( 'invoiceData/order.statusdelivery/' . $idx ), '6' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'stat:6' ) ); ?>
								</option>
								<option value="7" <?= $selected( $this->get( 'invoiceData/order.statusdelivery/' . $idx ), '7' ); ?> >
									<?= $enc->html( $this->translate( 'client/code', 'stat:7' ) ); ?>
								</option>
							</select>
						</td>
					<?php endif; ?>
					<?php if( in_array( 'order.relatedid', $fields ) ) : ?>
						<td class="order-relatedid">
							<input class="form-control order-relatedid" type="text" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.relatedid', '' ) ) ); ?>"
								value="<?= $enc->attr( $this->get( 'invoiceData/order.relatedid/' . $idx ) ); ?>"
								<?= $this->site()->readonly( $siteId ); ?> />
						</td>
					<?php endif; ?>
					<td class="actions"></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if( $this->get( 'invoiceData/order.id', [] ) === [] ) : ?>
		<?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ); ?>
	<?php endif; ?>
</div>
<?= $this->get( 'invoiceIBody' ); ?>
