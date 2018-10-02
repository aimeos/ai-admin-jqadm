<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018
 */


$enc = $this->encoder();

$keys = [
	'catalog.lists.id', 'catalog.lists.siteid', 'catalog.lists.typeid', 'catalog.lists.refid',
	'catalog.label', 'catalog.code', 'catalog.id'
];


?>
<div id="category" class="row item-category tab-pane fade" role="tabpanel" aria-labelledby="category">

	<div class="col-xl-6 content-block catalog-default">

		<table class="category-list table table-default"
			data-items="<?= $enc->attr( json_encode( $this->get( 'categoryData', [] ) ) ); ?>"
			data-listtypeid="<?= $this->get( 'categoryListTypes/default' ) ?>"
			data-keys="<?= $enc->attr( json_encode( $keys ) ) ?>"
			data-prefix="catalog.lists."
			data-siteid="<?= $this->site()->siteid() ?>" >

			<thead>
				<tr>
					<th>
						<span class="help"><?= $enc->html( $this->translate( 'admin', 'Default' ) ); ?></span>
						<div class="form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Categories the product can be found in by the user on the web site' ) ); ?>
						</div>
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

				<tr v-for="(id, idx) in items['catalog.lists.id']" v-if="items['catalog.lists.typeid'][idx] == listtypeid" v-bind:key="idx"
					v-bind:class="items['catalog.lists.siteid'][idx] != '<?= $this->site()->siteid() ?>' ? 'readonly' : ''">

					<td>
						<input class="item-listtypeid" type="hidden" v-model="items['catalog.lists.typeid'][idx]"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.lists.typeid', '' ) ) ); ?>" />

						<input class="item-listid" type="hidden" v-model="items['catalog.lists.id'][idx]"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.lists.id', '' ) ) ); ?>" />

						<input class="item-label" type="hidden" v-model="items['catalog.code'][idx]"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.code', '' ) ) ); ?>" />

						<input class="item-label" type="hidden" v-model="items['catalog.label'][idx]"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.label', '' ) ) ); ?>" />

						<select is="combo-box" class="form-control custom-select item-id"
							v-bind:name="'<?= $enc->attr( $this->formparam( array( 'category', 'catalog.id', '' ) ) ); ?>'"
							v-bind:readonly="checkSite('catalog.lists.siteid', idx) || id != ''"
							v-bind:tabindex="'<?= $this->get( 'tabindex' ); ?>'"
							v-bind:label="getLabel(idx)"
							v-bind:required="'required'"
							v-bind:getfcn="getItems"
							v-bind:index="idx"
							v-on:select="update"
							v-model="items['catalog.id'][idx]" >
						</select>
					</td>
					<td class="actions">
						<div v-if="!checkSite('catalog.lists.siteid', idx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
							v-on:click.stop="removeItem(idx)">
					</td>
				</tr>

			</tbody>

		</table>

	</div>
	<div class="col-xl-6 content-block catalog-promotion">

		<table class="category-list table table-default"
			data-items="<?= $enc->attr( json_encode( $this->get( 'categoryData', [] ) ) ); ?>"
			data-listtypeid="<?= $this->get( 'categoryListTypes/promotion' ) ?>"
			data-keys="<?= $enc->attr( json_encode( $keys ) ) ?>"
			data-prefix="catalog.lists."
			data-siteid="<?= $this->site()->siteid() ?>" >

			<thead>
				<tr>
					<th>
						<span class="help"><?= $enc->html( $this->translate( 'admin', 'Promotion' ) ); ?></span>
						<div class="form-text text-muted help-text">
							<?= $enc->html( $this->translate( 'admin', 'Categories the product will be shown for in the promotional section' ) ); ?>
						</div>
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

				<tr v-for="(id, idx) in items['catalog.lists.id']" v-if="items['catalog.lists.typeid'][idx] == listtypeid" v-bind:key="idx"
					v-bind:class="items['catalog.lists.siteid'][idx] != '<?= $this->site()->siteid() ?>' ? 'readonly' : ''">

					<td>
						<input class="item-listtypeid" type="hidden" v-model="items['catalog.lists.typeid'][idx]"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.lists.typeid', '' ) ) ); ?>" />

						<input class="item-listid" type="hidden" v-model="items['catalog.lists.id'][idx]"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.lists.id', '' ) ) ); ?>" />

						<input class="item-label" type="hidden" v-model="items['catalog.label'][idx]"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.label', '' ) ) ); ?>" />

						<select is="combo-box" class="form-control custom-select item-id"
							v-bind:name="'<?= $enc->attr( $this->formparam( array( 'category', 'catalog.id', '' ) ) ); ?>'"
							v-bind:readonly="checkSite('catalog.lists.siteid', idx) || id != ''"
							v-bind:tabindex="'<?= $this->get( 'tabindex' ); ?>'"
							v-bind:label="getLabel(idx)"
							v-bind:required="'required'"
							v-bind:getfcn="getItems"
							v-bind:index="idx"
							v-on:select="update"
							v-model="items['catalog.id'][idx]" >
						</select>
					</td>
					<td class="actions">
						<div v-if="!checkSite('catalog.lists.siteid', idx)" class="btn act-delete fa" tabindex="<?= $this->get( 'tabindex' ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>"
							v-on:click.stop="removeItem(idx)">
					</td>
				</tr>

			</tbody>

		</table>
	</div>

	<?= $this->get( 'categoryBody' ); ?>

</div>
