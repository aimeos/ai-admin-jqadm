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


?>
<div id="invoice" class="item-invoice content-block tab-pane fade" role="tabpanel" aria-labelledby="invoice">
	<div class="table-responsive">
		<table class="list-items table table-hover">
			<thead class="list-header">
				<tr>
					<th class="order-id">
						<?= $enc->html( $this->translate( 'admin', 'Invoice' ) ); ?>
					</th>
					<th class="order-type">
						<?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?>
					</th>
					<th class="order-datepayment">
						<?= $enc->html( $this->translate( 'admin', 'Purchased' ) ); ?>
					</th>
					<th class="order-statuspayment">
						<?= $enc->html( $this->translate( 'admin', 'Payment status' ) ); ?>
					</th>
					<th class="order-datedelivery">
						<?= $enc->html( $this->translate( 'admin', 'Delivery' ) ); ?>
					</th>
					<th class="order-statusdelivery">
						<?= $enc->html( $this->translate( 'admin', 'Delivery status' ) ); ?>
					</th>
					<th class="order-relatedid">
						<?= $enc->html( $this->translate( 'admin', 'Related' ) ); ?>
					</th>
					<th class="actions"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $this->get( 'invoiceData/order.id', [] ) as $idx => $orderId ) : ?>
					<tr>
						<td class="order-id">
							<input class="order-id" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.id', '' ) ) ); ?>"
								value="<?= $enc->attr( $orderId ); ?>" />
							<?= $enc->html( $orderId ); ?>
						</td>
						<td class="order-type">
							<input class="order-type" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.type', '' ) ) ); ?>"
								value="<?= $enc->attr( $this->get( 'invoiceData/order.type/' . $idx ) ); ?>" />
							<?= $enc->html( $this->get( 'invoiceData/order.type/' . $idx ) ); ?>
						</td>
						<td class="order-datepayment">
							<input class="form-control order-datepayment" type="datetime-local" tabindex="<?= $this->get( "tabindex" ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.datepayment', '' ) ) ); ?>"
								value="<?= $enc->attr( $this->get( 'invoiceData/order.datepayment/' . $idx ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'invoiceData/order.siteid/' . $idx ) ); ?> />
						</td>
						<td class="order-statuspayment">
							<select class="form-control c-select order-statuspayment" required="required" tabindex="<?= $this->get( "tabindex" ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.statuspayment', '' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'invoiceData/order.siteid/' . $idx ) ); ?> >
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
						<td class="order-datedelivery">
							<input class="form-control order-datedelivery" type="datetime-local" tabindex="<?= $this->get( "tabindex" ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.datedelivery', '' ) ) ); ?>"
								value="<?= $enc->attr( $this->get( 'invoiceData/order.datedelivery/' . $idx ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'invoiceData/order.siteid/' . $idx ) ); ?> />
						</td>
						<td class="order-statusdelivery">
							<select class="form-control c-select order-statusdelivery" required="required" tabindex="<?= $this->get( "tabindex" ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.statusdelivery', '' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'invoiceData/order.siteid/' . $idx ) ); ?> >
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
						<td class="order-relatedid">
							<input class="form-control order-relatedid" type="text" tabindex="<?= $this->get( "tabindex" ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'invoice', 'order.relatedid', '' ) ) ); ?>"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Related order ID (optional)' ) ); ?>"
								value="<?= $enc->attr( $this->get( 'invoiceData/order.relatedid/' . $idx ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'invoiceData/order.siteid/' . $idx ) ); ?> />
						</td>
						<td class="actions"></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<?php if( $this->get( 'invoiceData/order.id', [] ) === [] ) : ?>
			<?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ); ?>
		<?php endif; ?>
	</div>
</div>
<?= $this->get( 'invoiceIBody' ); ?>
