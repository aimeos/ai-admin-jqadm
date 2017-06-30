<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$enc = $this->encoder();

?>
<div class="col-xl-6 content-block item-related-suggest">
	<table class="suggest-list table table-default">
		<thead>
			<tr>
				<th>
					<span class="help"><?= $enc->html( $this->translate( 'admin', 'Suggested products' ) ); ?></span>
					<div class="form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'List of suggested products' ) ); ?>
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

			<?php foreach( $this->get( 'suggestData/product.lists.id', [] ) as $idx => $id ) : ?>
				<tr class="<?= $this->site()->readonly( $this->get( 'suggestData/product.lists.siteid/' . $idx ) ); ?>">
					<td>
						<input class="item-listid" type="hidden"
							name="<?= $enc->attr( $this->formparam( array( 'related', 'suggest', 'product.lists.id', '' ) ) ); ?>"
							value="<?= $enc->attr( $id ); ?>" />
						<input class="item-label" type="hidden"
							name="<?= $enc->attr( $this->formparam( array( 'related', 'suggest', 'product.label', '' ) ) ); ?>"
							value="<?= $enc->attr( $this->get( 'suggestData/product.label/' . $idx ) ); ?>" />
						<select class="combobox item-refid" tabindex="<?= $this->get( 'tabindex' ); ?>"
							name="<?= $enc->attr( $this->formparam( array( 'related', 'suggest', 'product.lists.refid', '' ) ) ); ?>"
							<?= $this->site()->readonly( $this->get( 'suggestData/product.lists.siteid/' . $idx ) ); ?> >
							<option value="<?= $enc->attr( $this->get( 'suggestData/product.lists.refid/' . $idx ) ); ?>" >
								<?= $enc->html( $this->get( 'suggestData/product.label/' . $idx ) ); ?>
							</option>
						</select>
					</td>
					<td class="actions">
						<?php if( !$this->site()->readonly( $this->get( 'suggestData/product.lists.siteid/' . $idx ) ) ) : ?>
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
						name="<?= $enc->attr( $this->formparam( array( 'related', 'suggest', 'product.lists.id', '' ) ) ); ?>" />
					<input class="item-label" type="hidden" disabled="disabled"
						name="<?= $enc->attr( $this->formparam( array( 'related', 'suggest', 'product.label', '' ) ) ); ?>" />
					<select class="combobox-prototype item-refid" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
						name="<?= $enc->attr( $this->formparam( array( 'related', 'suggest', 'product.lists.refid', '' ) ) ); ?>">
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

	<?= $this->get( 'suggestBody' ); ?>
</div>
