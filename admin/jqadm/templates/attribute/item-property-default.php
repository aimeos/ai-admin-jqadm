<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

$selected = function( $key, $code ) {
	return ( $key == $code ? 'selected="selected"' : '' );
};

$enc = $this->encoder();

?>
<div id="property" class="item-property tab-pane fade" role="tabpanel" aria-labelledby="property">
	<table class="property-list table table-default">
		<thead>
			<tr>
				<th colspan="3">
					<span class="help"><?= $enc->html( $this->translate( 'admin', 'Properties' ) ); ?></span>
					<div class="form-text text-muted help-text">
						<?= $enc->html( $this->translate( 'admin', 'Attribute properties that are not shared with other attributes' ) ); ?>
					</div>
				</th>
				<th class="actions">
					<div class="btn act-add fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)') ); ?>">
					</div>
				</th>
			</tr>
		</thead>
		<tbody>

			<?php foreach( $this->get( 'propertyData/attribute.property.id', [] ) as $idx => $id ) : ?>
				<tr class="<?= $this->site()->readonly( $this->get( 'propertyData/attribute.property.siteid/' . $idx ) ); ?>">
					<td class="property-type">
						<input class="item-id" type="hidden" value="<?= $enc->attr( $id ); ?>"
							name="<?= $enc->attr( $this->formparam( array( 'property', 'attribute.property.id', '' ) ) ); ?>" />
						<select class="form-control custom-select item-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
							name="<?= $enc->attr( $this->formparam( array( 'property', 'attribute.property.typeid', '' ) ) ); ?>"
							<?= $this->site()->readonly( $this->get( 'propertyData/attribute.property.siteid/' . $idx ) ); ?> >

							<?php foreach( $this->get( 'propertyTypes', [] ) as $typeid => $item ) : ?>
								<option value="<?= $enc->attr( $typeid ); ?>" <?= $selected( $this->get( 'propertyData/attribute.property.typeid/' . $idx ), $typeid ); ?> >
									<?= $enc->html( $item->getLabel() ); ?>
								</option>
							<?php endforeach; ?>

						</select>
					</td>
					<td class="property-language">
						<select class="form-control custom-select item-languageid" tabindex="<?= $this->get( 'tabindex' ); ?>"
							name="<?= $enc->attr( $this->formparam( array( 'property', 'attribute.property.languageid', '' ) ) ); ?>"
							<?= $this->site()->readonly( $this->get( 'propertyData/attribute.property.siteid/' . $idx ) ); ?> >
							<?php $lang = $this->get( 'propertyData/attribute.property.languageid/' . $idx ); ?>

							<option value="" <?= ( $lang == '' ? 'selected="selected"' : '' ) ?> >
								<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>
							</option>

							<?php foreach( $this->get( 'pageLangItems', [] ) as $langId => $langItem ) : ?>
								<option value="<?= $enc->attr( $langId ); ?>" <?= ( $lang == $langId ? 'selected="selected"' : '' ) ?> >
									<?= $enc->html( $langItem->getLabel() ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
					<td class="property-value">
						<input class="form-control item-value" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>"
							name="<?= $enc->attr( $this->formparam( array( 'property', 'attribute.property.value', '' ) ) ); ?>"
							placeholder="<?= $enc->attr( $this->translate( 'admin', 'Property value (required)' ) ); ?>"
							value="<?= $enc->attr( $this->get( 'propertyData/attribute.property.value/' . $idx ) ); ?>"
							<?= $this->site()->readonly( $this->get( 'propertyData/attribute.property.siteid/' . $idx ) ); ?> />
					</td>
					<td class="actions">
						<?php if( !$this->site()->readonly( $this->get( 'propertyData/attribute.property.siteid/' . $idx ) ) ) : ?>
							<div class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
							</div>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>

			<tr class="prototype">
				<td class="property-type">
					<input class="item-id" type="hidden" disabled="disabled"
						name="<?= $enc->attr( $this->formparam( array( 'property', 'attribute.property.id', '' ) ) ); ?>" value="" />
					<select class="form-control custom-select item-typeid" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
						name="<?= $enc->attr( $this->formparam( array( 'property', 'attribute.property.typeid', '' ) ) ); ?>">
						<option value="">
							<?= $enc->html( $this->translate( 'admin', 'Please select' ) ); ?>
						</option>

						<?php foreach( $this->get( 'propertyTypes', [] ) as $typeid => $item ) : ?>
							<option value="<?= $enc->attr( $typeid ); ?>" ><?= $enc->html( $item->getLabel() ); ?></option>
						<?php endforeach; ?>

					</select>
				</td>
				<td class="property-language">
					<select class="form-control custom-select item-languageid" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
						name="<?= $enc->attr( $this->formparam( array( 'property', 'attribute.property.languageid', '' ) ) ); ?>">
						<option value="">
							<?= $enc->html( $this->translate( 'admin', 'All' ) ); ?>
						</option>

						<?php foreach( $this->get( 'pageLangItems', [] ) as $langId => $langItem ) : ?>
							<option value="<?= $enc->attr( $langId ); ?>">
								<?= $enc->html( $langItem->getLabel() ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
				<td class="property-value">
					<input class="form-control item-value" type="text" required="required" tabindex="<?= $this->get( 'tabindex' ); ?>" disabled="disabled"
						name="<?= $enc->attr( $this->formparam( array( 'property', 'attribute.property.value', '' ) ) ); ?>"
						placeholder="<?= $enc->attr( $this->translate( 'admin', 'Property value (required)' ) ); ?>" />
				</td>
				<td class="actions">
					<div class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
						title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
					</div>
				</td>
			</tr>
		</tbody>
	</table>

	<?= $this->get( 'propertyBody' ); ?>
</div>
