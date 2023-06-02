<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2023
 */


$enc = $this->encoder();
$keys = [
	'Y', 'M', 'W', 'D', 'H',
	'product.lists.id', 'product.lists.siteid',
	'attribute.id', 'attribute.label', 'attribute.code'
];

?>
<div id="subscription" class="item-subscription tab-pane fade" role="tablist" aria-labelledby="subscription">

	<div class="box">
		<div class="table-responsive">
			<table class="subscription-list table table-default col-xl-12"
				data-items="<?= $enc->attr( $this->get( 'subscriptionData', [] ) ) ?>"
				data-keys="<?= $enc->attr( $keys ) ?>"
				data-siteid="<?= $this->site()->siteid() ?>" >

				<thead>
					<tr>
						<th>
							<span class="help"><?= $enc->html( $this->translate( 'admin', 'Active' ) ) ?></span>
							<div class="form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Available intervals in the front-end' ) ) ?>
							</div>
						</th>
						<th>
							<span class="help"><?= $enc->html( $this->translate( 'admin', 'Label' ) ) ?></span>
							<div class="form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Label describing the interval, will be used if no name is available' ) ) ?>
							</div>
						</th>
						<th>
							<?= $enc->html( $this->translate( 'admin', 'Years' ) ) ?>
						</th>
						<th>
							<?= $enc->html( $this->translate( 'admin', 'Months' ) ) ?>
						</th>
						<th>
							<?= $enc->html( $this->translate( 'admin', 'Weeks' ) ) ?>
						</th>
						<th>
							<?= $enc->html( $this->translate( 'admin', 'Days' ) ) ?>
						</th>
						<th>
							<?= $enc->html( $this->translate( 'admin', 'Hours' ) ) ?>
						</th>
						<th class="actions">
							<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
								v-on:click="add()">
							</div>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(entry, idx) in items" v-bind:key="idx"
						v-bind:class="{readonly: !can('change', idx)}">

						<td class="interval-check">
							<input class="form-check-input item-id" type="checkbox" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'subscription', '_idx_', 'attribute.id' ) ) ) ?>`.replace( '_idx_', idx )"
								v-bind:checked="entry['product.lists.id'] || !entry['attribute.id'] ? 'checked' : ''"
								v-bind:value="entry['attribute.id']">
						</td>
						<td class="interval-label mandatory">
							<input class="form-control item-label" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'subscription', '_idx_', 'attribute.label' ) ) ) ?>`.replace( '_idx_', idx )"
								v-bind:readonly="!can('create', idx)"
								v-model="items[idx]['attribute.label']">
						</td>
						<td class="interval-field mandatory">
							<input class="form-control field-year" type="number" step="1" min="0" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'subscription', '_idx_', 'Y' ) ) ) ?>`.replace( '_idx_', idx )"
								v-bind:readonly="!can('create', idx)"
								v-model="items[idx]['Y']">
						</td>
						<td class="interval-field mandatory">
							<input class="form-control field-month" type="number" step="1" min="0" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'subscription', '_idx_', 'M' ) ) ) ?>`.replace( '_idx_', idx )"
								v-bind:readonly="!can('create', idx)"
								v-model="items[idx]['M']">
						</td>
						<td class="interval-field mandatory">
							<input class="form-control field-week" type="number" step="1" min="0" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'subscription', '_idx_', 'W' ) ) ) ?>`.replace( '_idx_', idx )"
								v-bind:readonly="!can('create', idx)"
								v-model="items[idx]['W']">
						</td>
						<td class="interval-field mandatory">
							<input class="form-control field-day" type="number" step="1" min="0" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'subscription', '_idx_', 'D' ) ) ) ?>`.replace( '_idx_', idx )"
								v-bind:readonly="!can('create', idx)"
								v-model="items[idx]['D']">
						</td>
						<td class="interval-field mandatory">
							<input class="form-control field-hour" type="number" step="1" min="0" required="required" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'subscription', '_idx_', 'H' ) ) ) ?>`.replace( '_idx_', idx )"
								v-bind:readonly="!can('create', idx)"
								v-model="items[idx]['H']">
						</td>
						<td class="actions">
							<input class="item-code" type="hidden"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'subscription', '_idx_', 'attribute.code' ) ) ) ?>`.replace( '_idx_', idx )"
								v-bind:value="value(idx)">

							<input class="item-listid" type="hidden"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'subscription', '_idx_', 'product.lists.id' ) ) ) ?>`.replace( '_idx_', idx )"
								v-bind:value="entry['product.lists.id']">

							<input class="item-siteid" type="hidden"
								v-bind:name="`<?= $enc->js( $this->formparam( array( 'subscription', '_idx_', 'product.lists.siteid' ) ) ) ?>`.replace( '_idx_', idx )"
								v-bind:value="entry['product.lists.siteid']">

							<div v-if="entry['attribute.id'] == ''" v-on:click="remove(idx)"
								class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>">
							</div>
						</td>

					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<?= $this->get( 'subscriptionBody' ) ?>

</div>
