<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018
 */


$enc = $this->encoder();
$keys = [
	'Y', 'M', 'W', 'D',
	'product.lists.id', 'product.lists.siteid',
	'attribute.id', 'attribute.label', 'attribute.code'
];

?>
<div id="subscription" class="item-subscription tab-pane fade" role="tablist" aria-labelledby="subscription">

	<table class="subscription-list table table-default col-xl-12 content-block"
		data-items="<?= $enc->attr( json_encode( $this->get( 'subscriptionData', [] ) ) ); ?>"
		data-keys="<?= $enc->attr( json_encode( $keys ) ) ?>"
		data-siteid="<?= $this->site()->siteid() ?>" >

		<thead>
			<tr>
				<th>
					<span class="help"><?= $enc->html( $this->translate( 'admin', 'Active' ) ); ?></span>
					<div class="form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'Available intervals in the front-end' ) ); ?>
					</div>
				</th>
				<th>
					<span class="help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ); ?></span>
					<div class="form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'Label describing the interval, will be used if no name is available' ) ); ?>
					</div>
				</th>
				<th>
					<?= $enc->html( $this->translate( 'admin', 'Years' ) ); ?>
				</th>
				<th>
					<?= $enc->html( $this->translate( 'admin', 'Months' ) ); ?>
				</th>
				<th>
					<?= $enc->html( $this->translate( 'admin', 'Weeks' ) ); ?>
				</th>
				<th>
					<?= $enc->html( $this->translate( 'admin', 'Days' ) ); ?>
				</th>
				<th class="actions">
					<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
						v-on:click="addItem()">
					</div>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="(id, idx) in items['attribute.id']" v-bind:key="idx"
				v-bind:class="items['product.lists.siteid'][idx] != '<?= $this->site()->siteid() ?>' ? 'readonly' : ''">

				<td class="interval-check">
					<input class="form-control item-id" type="checkbox" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'attribute.id', 'idx' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:checked="items['product.lists.id'][idx] != '' || items['attribute.id'][idx] == '' ? 'checked' : ''"
						v-bind:value="items['attribute.id'][idx]" />
				</td>
				<td class="interval-label mandatory">
					<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'attribute.label', 'idx' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:disabled="items['attribute.id'][idx] != ''"
						v-model="items['attribute.label'][idx]" />
				</td>
				<td class="interval-field mandatory">
					<input class="form-control field-year" type="number" step="1" min="0" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:disabled="items['attribute.id'][idx] != ''"
						v-model="items['Y'][idx]" />
				</td>
				<td class="interval-field mandatory">
					<input class="form-control field-month" type="number" step="1" min="0" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:disabled="items['attribute.id'][idx] != ''"
						v-model="items['M'][idx]" />
				</td>
				<td class="interval-field mandatory">
					<input class="form-control field-week" type="number" step="1" min="0" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:disabled="items['attribute.id'][idx] != ''"
						v-model="items['W'][idx]" />
				</td>
				<td class="interval-field mandatory">
					<input class="form-control field-day" type="number" step="1" min="0" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:disabled="items['attribute.id'][idx] != ''"
						v-model="items['D'][idx]" />
				</td>
				<td class="actions">
					<input class="item-code" type="hidden"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'attribute.code', 'idx' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:value="getAttributeValue(idx)" />
					<input class="item-listid" type="hidden"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'product.lists.id', 'idx' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:value="items['product.lists.id'][idx]" />
					<div v-if="items['attribute.id'][idx] == ''" v-on:click="removeItem(idx)"
						class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
					</div>
				</td>

			</tr>
		</tbody>
	</table>

	<?= $this->get( 'subscriptionBody' ); ?>

</div>
