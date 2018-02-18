<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018
 */


$enc = $this->encoder();


?>
<div class="item-subscription col-xl-12 content-block"
	data-items="<?= $enc->attr( json_encode( $this->get( 'subscriptionData', [] ) ) ); ?>">

	<table class="table table-default" >

		<thead>
			<tr>
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
						v-on:click="addInterval()">
					</div>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="(id, idx) in items['subscription']" v-bind:key="idx"
				v-bind:class="items['subscription']['product.lists.siteid'][idx] != '<?= $this->site()->siteid() ?>' ? 'readonly' : ''">

				<td class="interval-label">
					<input class="item-label" type="text"
						name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'attribute.label', 'idx' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:value="items['subscription']['attribute.label'][idx]" />
				</td>
				<td class="interval-field">
					<input class="field-year" type="number" step="1" min="0" disabled v-bind:value="year[idx]" />
				</td>
				<td class="interval-field">
					<input class="field-month" type="number" step="1" min="0" disabled v-bind:value="month[idx]" />
				</td>
				<td class="interval-field">
					<input class="field-week" type="number" step="1" min="0" disabled v-bind:value="week[idx]" />
				</td>
				<td class="interval-field">
					<input class="field-day" type="number" step="1" min="0" disabled v-bind:value="day[idx]" />
				</td>
				<td class="actions">
					<input class="item-id" type="checkbox"
						name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'attribute.id', 'idx' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:value="items['subscription']['attribute.id'][idx]" />
					<input class="item-code" type="hidden"
						name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'attribute.code', 'idx' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:value="items['subscription']['attribute.code'][idx]" />
					<input class="item-listid" type="hidden"
						name="'<?= $enc->attr( $this->formparam( array( 'subscription', 'product.lists.id', 'idx' ) ) ); ?>'.replace( 'idx', idx )"
						v-bind:value="items['subscription']['product.lists.id'][idx]" />
				</td>

			</tr>
		</tbody>
	</table>

	<?= $this->get( 'subscriptionBody' ); ?>

</div>
