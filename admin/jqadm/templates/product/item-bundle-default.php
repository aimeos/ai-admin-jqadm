<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();


?>
<div id="bundle" class="row item-bundle tab-pane fade" role="tabpanel" aria-labelledby="bundle">
	<div class="col-xl-6 content-block">
		<table class="bundle-list table table-default">
			<thead>
				<tr>
					<th>
						<span class="help"><?= $enc->html( $this->translate( 'admin', 'Products' ) ); ?></span>
						<div class="form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'List of articles that should be sold as one product, often at a reduced price' ) ); ?>
						</div>
					</th>
					<th class="actions">
						<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+A)') ); ?>">
						</div>
					</th>
				</tr>
			</thead>
			<tbody>

				<?php foreach( $this->get( 'bundleData/product.lists.id', [] ) as $idx => $id ) : ?>
					<tr class="<?= $this->site()->readonly( $this->get( 'bundleData/product.lists.siteid/' . $idx ) ); ?>">
						<td>
							<input class="item-listid" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'bundle', 'product.lists.id', '' ) ) ); ?>"
								value="<?= $enc->attr( $id ); ?>" />
							<input class="item-label" type="hidden"
								name="<?= $enc->attr( $this->formparam( array( 'bundle', 'product.label', '' ) ) ); ?>"
								value="<?= $enc->attr( $this->get( 'bundleData/product.label/' . $idx ) ); ?>" />
							<select class="combobox item-refid" tabindex="<?= $this->get( 'tabindex' ); ?>"
								name="<?= $enc->attr( $this->formparam( array( 'bundle', 'product.lists.refid', '' ) ) ); ?>"
								<?= $this->site()->readonly( $this->get( 'bundleData/product.lists.siteid/' . $idx ) ); ?> >
								<option value="<?= $enc->attr( $this->get( 'bundleData/product.lists.refid/' . $idx ) ); ?>" >
									<?= $enc->html( $this->get( 'bundleData/product.label/' . $idx ) ); ?>
								</option>
							</select>
						</td>
						<td class="actions">
							<?php if( !$this->site()->readonly( $this->get( 'bundleData/product.lists.siteid/' . $idx ) ) ) : ?>
								<div class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
								</div>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>

				<tr class="prototype">
					<td>
						<input class="item-listid" type="hidden" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'bundle', 'product.lists.id', '' ) ) ); ?>" />
						<input class="item-label" type="hidden" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'bundle', 'product.label', '' ) ) ); ?>" />
						<select class="combobox-prototype item-refid" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'bundle', 'product.lists.refid', '' ) ) ); ?>">
						</select>
					</td>
					<td class="actions">
						<div class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<?= $this->get( 'bundleBody' ); ?>
</div>
