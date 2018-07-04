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
			<tr v-for="(entry, idx) in items" v-bind:key="idx"
				v-bind:class="entry['product.lists.siteid'] != '<?= $this->site()->siteid() ?>' ? 'readonly' : ''">

				<td class="interval-check">
					<input class="form-control item-id" type="checkbox" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'idx', 'attribute.id' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:checked="entry['product.lists.id'] || !entry['attribute.id'] ? 'checked' : ''"
						v-bind:value="entry['attribute.id']" />
				</td>
				<td class="interval-label mandatory">
					<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'idx', 'attribute.label' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:readonly="getReadOnly(idx)"
						v-model="items[idx]['attribute.label']" />
				</td>
				<td class="interval-field mandatory">
					<input class="form-control field-year" type="number" step="1" min="0" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'idx', 'Y' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:readonly="getReadOnly(idx)"
						v-model="items[idx]['Y']" />
				</td>
				<td class="interval-field mandatory">
					<input class="form-control field-month" type="number" step="1" min="0" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'idx', 'M' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:readonly="getReadOnly(idx)"
						v-model="items[idx]['M']" />
				</td>
				<td class="interval-field mandatory">
					<input class="form-control field-week" type="number" step="1" min="0" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'idx', 'W' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:readonly="getReadOnly(idx)"
						v-model="items[idx]['W']" />
				</td>
				<td class="interval-field mandatory">
					<input class="form-control field-day" type="number" step="1" min="0" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'idx', 'D' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:readonly="getReadOnly(idx)"
						v-model="items[idx]['D']" />
				</td>
				<td class="actions">
					<input class="item-code" type="hidden"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'idx', 'attribute.code' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:value="getAttributeValue(idx)" />

					<input class="item-listid" type="hidden"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'idx', 'product.lists.id' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:value="entry['product.lists.id']" />

					<input class="item-siteid" type="hidden"
						v-bind:name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'idx', 'product.lists.siteid' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:value="entry['product.lists.siteid']" />

					<div v-if="entry['attribute.id'] == ''" v-on:click="removeItem(idx)"
						class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
					</div>
				</td>

			</tr>
		</tbody>
	</table>

	<?= $this->get( 'subscriptionBody' ); ?>

</div>
