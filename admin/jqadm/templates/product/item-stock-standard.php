<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
 */


$enc = $this->encoder();
$stockTypes = $this->get( 'stockTypes', [] );

$keys = ['stock.id', 'stock.siteid', 'stock.typeid', 'stock.stocklevel', 'stock.dateback'];


?>
<div id="stock" class="item-stock content-block tab-pane fade" role="tabpanel" aria-labelledby="stock">

	<table class="stock-list table table-default"
		data-items="<?= $enc->attr( json_encode( $this->get( 'stockData', [] ) ) ); ?>"
		data-keys="<?= $enc->attr( json_encode( $keys ) ) ?>"
		data-prefix="stock."
		data-siteid="<?= $this->site()->siteid() ?>"
		data-numtypes="<?= count( $stockTypes ) ?>" >

		<thead>
			<tr>
				<?php if( count( $stockTypes ) > 1 ) : ?>
					<th class="stock-type">
						<span class="help"><?= $enc->html( $this->translate( 'admin', 'Type' ) ); ?></span>
						<div class="form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Warehouse or local store if your articles are available at several locations' ) ); ?>
						</div>
					</th>
				<?php endif; ?>
				<th class="stock-stocklevel">
					<span class="help"><?= $enc->html( $this->translate( 'admin', 'Stock level' ) ); ?></span>
					<div class="form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'Number of articles currently in stock, leave empty for an unlimited quantity' ) ); ?>
					</div>
				</th>
				<th class="stock-databack">
					<span class="help"><?= $enc->html( $this->translate( 'admin', 'Back in stock' ) ); ?></span>
					<div class="form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'Shown if the article reached a stock level of zero' ) ); ?>
					</div>
				</th>
				<th class="actions">
					<div v-if="(items['stock.id'] || []).length < numtypes" class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>"
						v-on:click="addItem()">
					</div>
				</th>
			</tr>
		</thead>
		<tbody>

			<tr v-for="(id, idx) in items['stock.id']" v-bind:key="idx" class="stock-row">
				<?php if( count( $stockTypes ) > 1 ) : ?>
					<td class="stock-type mandatory">
						<select class="form-control custom-select item-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
							name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.typeid', '' ) ) ); ?>"
							v-bind:readonly="checkSite('stock.siteid', idx)"
							v-model="items['stock.typeid'][idx]" >

							<option value="" disable>
								<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
							</option>

							<?php foreach( $stockTypes as $typeId => $typeItem ) : ?>
								<option value="<?= $enc->attr( $typeId ); ?>" v-bind:selected="items['stock.typeid'][idx] == '<?= $enc->attr( $typeId ) ?>'">
									<?= $enc->html( $typeItem->getLabel() ) ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				<?php else : ?>
					<input class="item-typeid" type="hidden"
						name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.typeid', '' ) ) ); ?>"
						value="<?= $enc->attr( key( $stockTypes ) ); ?>" />
				<?php endif; ?>
				<td class="stock-stocklevel optional">
					<input class="form-control item-stocklevel" type="number" step="1" min="0" tabindex="<?= $this->get( 'tabindex' ); ?>"
						name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.stocklevel', '' ) ) ); ?>"
						v-bind:readonly="checkSite('stock.siteid', idx)"
						v-model="items['stock.stocklevel'][idx]" />
				</td>
				<td class="stock-databack optional">
					<input class="form-control item-dateback" type="datetime-local" tabindex="<?= $this->get( 'tabindex' ); ?>"
						name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.dateback', '' ) ) ); ?>"
						placeholder="<?= $enc->attr( $this->translate( 'admin', 'YYYY-MM-DD hh:mm:ss (optional)' ) ); ?>"
						v-bind:readonly="checkSite('stock.siteid', idx)"
						v-model="items['stock.dateback'][idx]" />
				</td>
				<td class="actions">
					<input class="item-id" type="hidden" v-model="items['stock.id'][idx]"
						name="<?= $enc->attr( $this->formparam( array( 'stock', 'stock.id', '' ) ) ); ?>" />

					<div v-if="!checkSite('stock.siteid', idx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
						v-on:click.stop="removeItem(idx)">
					</div>
				</td>
			</tr>

		</tbody>
	</table>

	<?= $this->get( 'stockBody' ); ?>

</div>
