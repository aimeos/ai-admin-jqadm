<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


$enc = $this->encoder();
$stockTypes = $this->get( 'stockTypes', map() );

$keys = ['stock.id', 'stock.siteid', 'stock.type', 'stock.stocklevel', 'stock.dateback', 'stock.timeframe'];


?>
<div id="stock" class="item-stock tab-pane fade" role="tabpanel" aria-labelledby="stock">

	<div class="box">
		<div class="table-responsive">
			<table class="stock-list table table-default"
				data-items="<?= $enc->attr( $this->get( 'stockData', [] ) ) ?>"
				data-keys="<?= $enc->attr( $keys ) ?>"
				data-siteid="<?= $this->site()->siteid() ?>"
				data-numtypes="<?= $stockTypes->count() ?>" >

				<thead>
					<tr>
						<?php if( $stockTypes->count() !== 1 ) : ?>
							<th class="stock-type">
								<span class="help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?></span>
								<div class="form-text text-muted help-text">
									<?= $enc->html( $this->translate( 'admin', 'Warehouse or local store if your articles are available at several locations' ) ) ?>
								</div>
							</th>
						<?php endif ?>
						<th class="stock-stocklevel">
							<span class="help"><?= $enc->html( $this->translate( 'admin', 'Stock level' ) ) ?></span>
							<div class="form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Number of articles currently in stock, leave empty for an unlimited quantity' ) ) ?>
							</div>
						</th>
						<th class="stock-dateback">
							<span class="help"><?= $enc->html( $this->translate( 'admin', 'Back in stock' ) ) ?></span>
							<div class="form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Shown if the article reached a stock level of zero' ) ) ?>
							</div>
						</th>
						<th class="stock-timeframe">
							<span class="help"><?= $enc->html( $this->translate( 'admin', 'Delivery within' ) ) ?></span>
							<div class="form-text text-muted help-text">
								<?= $enc->html( $this->translate( 'admin', 'Usual time frame for the delivery of the product' ) ) ?>
							</div>
						</th>
						<th class="actions">
							<div v-if="(items || []).length < numtypes" class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
								v-on:click="add()">
							</div>
						</th>
					</tr>
				</thead>
				<tbody>

					<tr v-for="(item, idx) in items" v-bind:key="idx" class="stock-row">
						<?php if( $stockTypes->count() !== 1 ) : ?>
							<td v-bind:class="'stock-type mandatory ' + (item['css'] || '')">
								<select is="select-component" required class="form-select item-type" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"
									v-bind:items="<?= $enc->attr( $stockTypes->col( 'stock.type.label', 'stock.type.code' )->toArray() ) ?>"
									v-bind:name="`<?= $enc->js( $this->formparam( ['stock', '_idx_', 'stock.type'] ) ) ?>`.replace( '_idx_', idx )"
									v-bind:text="`<?= $enc->js( $this->translate( 'admin', 'Please select' ) ) ?>`"
									v-bind:readonly="!can('change', idx)"
									v-model="item['stock.type']" >
								</select>
							</td>
						<?php endif ?>
						<td class="stock-stocklevel optional">
							<div class="item-stockflag">
								<input class="form-check-input" type="checkbox" value="1" tabindex="<?= $this->get( 'tabindex' ) ?>"
									v-bind:name="`<?= $enc->js( $this->formparam( ['stock', '_idx_', 'stock.stockflag'] ) ) ?>`.replace( '_idx_', idx )"
									v-bind:readonly="!can('change', idx)"
									v-bind:checked="checked(idx)"
									v-on:click="toggle(idx)">
							</div><!--
							--><div v-if="!checked(idx)" class="form-control item-stocklevel">
								&infin;
								<input type="hidden" value=""
									v-bind:name="`<?= $enc->js( $this->formparam( ['stock', '_idx_', 'stock.stocklevel'] ) ) ?>`.replace( '_idx_', idx )"
									v-bind:readonly="!can('change', idx)">
							</div><!--
							--><div v-else class="form-control item-stocklevel">
								<span class="item-stocklevel-value">{{ item['stock.stocklevel'] || 0 }} +</span>
								<input class="item-stocklevel-diff" type="number" step="1" tabindex="<?= $this->get( 'tabindex' ) ?>"
									v-bind:name="`<?= $enc->js( $this->formparam( ['stock', '_idx_', 'stock.stockdiff'] ) ) ?>`.replace( '_idx_', idx )"
									v-bind:readonly="!can('change', idx)"
									v-bind:value="item['stock.stockdiff'] || 0"
									v-on:input="item['stock.stockdiff'] = $event.target.value">
							</div>
						</td>
						<td class="stock-dateback optional">
							<input is="flat-pickr" class="form-control item-dateback" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:name="`<?= $enc->js( $this->formparam( ['stock', '_idx_', 'stock.dateback'] ) ) ?>`.replace( '_idx_', idx )"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ) ?>"
								v-bind:config="Aimeos.flatpickr.datetime"
								v-bind:disabled="!can('change', idx)"
								v-model="item['stock.dateback']">
						</td>
						<td class="stock-timeframe optional">
							<input class="form-control item-timeframe" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:name="`<?= $enc->js( $this->formparam( ['stock', '_idx_', 'stock.timeframe'] ) ) ?>`.replace( '_idx_', idx )"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Time frame (optional)' ) ) ?>"
								v-bind:readonly="!can('change', idx)"
								v-model="item['stock.timeframe']">
						</td>
						<td class="actions">
							<input class="item-id" type="hidden" v-model="item['stock.id']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['stock', '_idx_', 'stock.id'] ) ) ?>`.replace( '_idx_', idx )">

							<?php if( $stockTypes->count() === 1 ) : ?>
								<input class="item-type" type="hidden"
									v-bind:name="`<?= $enc->js( $this->formparam( ['stock', '_idx_', 'stock.type'] ) ) ?>`.replace( '_idx_', idx )"
									value="<?= $enc->attr( $stockTypes->getCode()->first() ) ?>">
							<?php endif ?>

							<div v-if="can('delete', idx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
								v-on:click.stop="remove(idx)">
							</div>
						</td>
					</tr>

				</tbody>
			</table>
		</div>
	</div>

	<?= $this->get( 'stockBody' ) ?>

</div>
