<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */


$enc = $this->encoder();


/** admin/jqadm/coupon/code/fields
 * List of coupon code columns that should be displayed in the coupon code view
 *
 * Changes the list of coupon code columns shown by default in the coupon code view.
 * The columns can be changed by the editor as required within the administraiton
 * interface.
 *
 * The names of the colums are in fact the search keys defined by the managers,
 * e.g. "coupon.code.code" for the ID value.
 *
 * @param array List of field names, i.e. search keys
 * @since 2017.10
 */
$default = $this->config( 'admin/jqadm/coupon/code/fields', ['coupon.code.code', 'coupon.code.count'] );
$fields = $this->session( 'aimeos/admin/jqadm/couponcode/fields', $default );

$columnList = [
	'coupon.code.id' => $this->translate( 'admin', 'ID' ),
	'coupon.code.code' => $this->translate( 'admin', 'Code' ),
	'coupon.code.count' => $this->translate( 'admin', 'Count' ),
	'coupon.code.datestart' => $this->translate( 'admin', 'Start date' ),
	'coupon.code.dateend' => $this->translate( 'admin', 'End date' ),
	'coupon.code.ref' => $this->translate( 'admin', 'Reference' ),
	'coupon.code.ctime' => $this->translate( 'admin', 'Created' ),
	'coupon.code.mtime' => $this->translate( 'admin', 'Modified' ),
	'coupon.code.editor' => $this->translate( 'admin', 'Editor' ),
];


?>
<div id="code" class="item-code tab-pane fade box" role="tabpanel" aria-labelledby="code">

	<?= $this->partial( $this->config( 'admin/jqadm/partial/columns', 'columns' ) ) ?>

	<div class="coupon-code-list"
		data-parentid="<?= $enc->attr( $this->param( 'id' ) ) ?>"
		data-siteid="<?= $enc->attr( $this->site()->siteid() ) ?>"
		data-fields="<?= $enc->attr( $fields ) ?>">

		<column-select tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
			name="<?= $enc->attr( $this->formparam( ['fields', ''] ) ) ?>"
			v-bind:titles="<?= $enc->attr( $columnList ) ?>"
			v-bind:fields="<?= $enc->attr( $fields ) ?>"
			v-bind:show="colselect"
			v-bind:submit="false"
			v-on:submit="toggle($event)"
			v-on:close="colselect = false">
		</column-select>

		<div class="table-responsive">
			<table class="list-items table table-striped">
				<thead class="list-header">
					<tr>
						<th class="select">
							<button class="btn icon-menu" type="button" data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}'
								aria-expanded="false" title="<?= $enc->attr( $this->translate( 'admin', 'Menu' ) ) ?>">
							</button>
							<ul class="dropdown-menu">
								<li>
									<label for="import" class="btn btn-text fileupload"><?= $enc->html( $this->translate( 'admin', 'Upload CSV' ) ) ?></label>
									<input id="import" class="btn-text fileupload" type="file" tabindex="<?= $this->get( 'tabindex' ) ?>"
										name="<?= $enc->attr( $this->formparam( ['code', 'file'] ) ) ?>">
								</li>
								<li>
									<a v-on:click.prevent.stop="remove()" class="btn"
										tabindex="<?= $this->get( 'tabindex' ) ?>" href="#"
										title="<?= $enc->attr( $this->translate( 'admin', 'Delete selected entries' ) ) ?>">
										<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ) ?>
									</a>
								</li>
							</ul>
						</th>
						<th v-if="fieldlist.includes('coupon.code.id')" v-bind:class="css('id')">
							<a v-bind:class="sortclass('id')" v-on:click.prevent="sort('id')"
								tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'ID' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('coupon.code.code')" v-bind:class="css('code')">
							<a v-bind:class="sortclass('code')" v-on:click.prevent="sort('code')"
								tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Code' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('coupon.code.count')" v-bind:class="css('count')">
							<a v-bind:class="sortclass('count')" v-on:click.prevent="sort('count')"
								tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Count' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('coupon.code.datestart')" v-bind:class="css('datestart')">
							<a v-bind:class="sortclass('datestart')" v-on:click.prevent="sort('datestart')"
								tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Start date' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('coupon.code.dateend')" v-bind:class="css('dateend')">
							<a v-bind:class="sortclass('dateend')" v-on:click.prevent="sort('dateend')"
								tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('coupon.code.ref')" v-bind:class="css('ref')">
							<a v-bind:class="sortclass('ref')" v-on:click.prevent="sort('ref')"
								tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Reference' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('coupon.code.ctime')" v-bind:class="css('ctime')">
							<a v-bind:class="sortclass('ctime')" v-on:click.prevent="sort('ctime')"
								tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Created' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('coupon.code.mtime')" v-bind:class="css('mtime')">
							<a v-bind:class="sortclass('mtime')" v-on:click.prevent="sort('mtime')"
								tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Modified' ) ) ?>
							</a>
						</th>
						<th v-if="fieldlist.includes('coupon.code.editor')" v-bind:class="css('editor')">
							<a v-bind:class="sortclass('editor')" v-on:click.prevent="sort('editor')"
								tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
								<?= $enc->html( $this->translate( 'admin', 'Editor' ) ) ?>
							</a>
						</th>

						<th class="actions">
							<a class="btn icon act-add" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-on:click="add()" href="#"
								title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ) ?>">
							</a>
							<a class="btn icon act-columns" href="#" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Columns' ) ) ?>"
								v-on:click.prevent.stop="colselect = true">
							</a>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr class="list-search">
						<td class="select">
							<input v-model="checked" class="form-check-input" type="checkbox" tabindex="<?= $this->get( 'tabindex' ) ?>">
						</td>
						<td v-if="fieldlist.includes('coupon.code.id')" v-bind:class="css('id')">
							<input class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-on:change="find($event, 'id')"
								v-bind:value="value('id')">
						</td>
						<td v-if="fieldlist.includes('coupon.code.code')" v-bind:class="css('code')">
							<input class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-on:change="find($event, 'code', '=~')"
								v-bind:value="value('code')">
						</td>
						<td v-if="fieldlist.includes('coupon.code.count')" v-bind:class="css('count')">
							<input type="number" step="1" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-on:change="find($event, 'count', '<=')"
								v-bind:value="value('count')">
						</td>
						<td v-if="fieldlist.includes('coupon.code.datestart')" v-bind:class="css('datestart')">
							<input is="vue:flat-pickr" class="form-control novalidate custom-datetime" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:value="value('datestart')"
								v-on:input="find($event, 'datestart')"
								v-bind:config="Aimeos.flatpickr.datetimerange">
						</td>
						<td v-if="fieldlist.includes('coupon.code.dateend')" v-bind:class="css('dateend')">
							<input is="vue:flat-pickr" class="form-control novalidate custom-datetime" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:value="value('dateend')"
								v-on:input="find($event, 'dateend')"
								v-bind:config="Aimeos.flatpickr.datetimerange">
						</td>
						<td v-if="fieldlist.includes('coupon.code.ref')" v-bind:class="css('ref')">
							<input class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-on:change="find($event, 'ref')"
								v-bind:value="value('ref')">
						</td>
						<td v-if="fieldlist.includes('coupon.code.ctime')" v-bind:class="css('ctime')">
							<input is="vue:flat-pickr" class="form-control novalidate custom-datetime" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:value="value('ctime')"
								v-on:input="find($event, 'ctime')"
								v-bind:config="Aimeos.flatpickr.datetimerange">
						</td>
						<td v-if="fieldlist.includes('coupon.code.mtime')" v-bind:class="css('mtime')">
							<input is="vue:flat-pickr" class="form-control novalidate custom-datetime" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:value="value('mtime')"
								v-on:input="find($event, 'mtime')"
								v-bind:config="Aimeos.flatpickr.datetimerange">
						</td>
						<td v-if="fieldlist.includes('coupon.code.editor')" v-bind:class="css('editor')">
							<input type="number" step="1" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-on:change="find($event, 'editor', '=~')"
								v-bind:value="value('editor')">
						</td>

						<td class="actions">
							<a v-on:click.prevent="fetch()" class="btn act-search icon" href="#" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Search' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Search' ) ) ?>">
							</a>
							<a v-on:click.prevent="reset()" class="btn act-reset icon" href="#" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Reset' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Reset' ) ) ?>"></a>
						</td>
					</tr>

					<tr v-for="(item, idx) in items" v-bind:key="idx" class="list-item" v-bind:class="{mismatch: !can('match', idx)}">
						<td class="select">
							<input class="form-check-input" type="checkbox" tabindex="<?= $this->get( 'tabindex' ) ?>" v-model="item['checked']">
						</td>
						<td v-if="fieldlist.includes('coupon.code.id')" v-bind:class="css('id')">
							<div class="items-field">{{ item['id'] }}</div>
						</td>
						<td v-if="fieldlist.includes('coupon.code.code')" v-bind:class="css('code')">
							<input class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" maxlength="64"
								v-if="item.edit"
								v-bind:name="`<?= $enc->js( $this->formparam( ['code', 'coupon.code.code', ''] ) ) ?>`"
								v-model="item['code']">
							<div v-else v-on:click="edit(idx)" class="items-field">
								{{ item['code'] }}
							</div>
						</td>
						<td v-if="fieldlist.includes('coupon.code.count')" v-bind:class="css('count')">
							<input class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
								type="number" step="1" min="-2147483647" max="2147483647"
								v-if="item.edit"
								v-bind:name="`<?= $enc->js( $this->formparam( ['code', 'coupon.code.count', ''] ) ) ?>`"
								v-model="item['count']">
							</select>
							<div v-else v-on:click="edit(idx)" class="items-field">
								{{ item['count'] }}
							</div>
						</td>
						<td v-if="fieldlist.includes('coupon.code.datestart')" v-bind:class="css('datestart')">
							<input is="vue:flat-pickr" v-if="item.edit" class="form-control novalidate custom-datetime" type="datetime-local"
								v-bind:name="`<?= $enc->js( $this->formparam( ['code', 'coupon.code.datestart', ''] ) ) ?>`"
								tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:value="value('datestart')"
								v-bind:config="Aimeos.flatpickr.datetime">
							<div v-else v-on:click="edit(idx)" class="items-field">
								{{ item['datestart'] || '-' }}
							</div>
						</td>
						<td v-if="fieldlist.includes('coupon.code.dateend')" v-bind:class="css('dateend')">
							<input is="vue:flat-pickr" v-if="item.edit" class="form-control novalidate custom-datetime" type="datetime-local"
								v-bind:name="`<?= $enc->js( $this->formparam( ['code', 'coupon.code.dateend', ''] ) ) ?>`"
								tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:value="value('dateend')"
								v-bind:config="Aimeos.flatpickr.datetime">
							<div v-else v-on:click="edit(idx)" class="items-field">
								{{ item['dateend'] || '-' }}
							</div>
						</td>
						<td v-if="fieldlist.includes('coupon.code.ref')" v-bind:class="css('ref')">
							<input class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>" maxlength="64"
								v-if="item.edit"
								v-bind:name="`<?= $enc->js( $this->formparam( ['code', 'coupon.code.ref', ''] ) ) ?>`"
								v-model="item['ref']">
							</select>
							<div v-else v-on:click="edit(idx)" class="items-field">
								{{ item['ref'] }}
							</div>
						</td>
						<td v-if="fieldlist.includes('coupon.code.ctime')" v-bind:class="css('ctime')">
							<div class="items-field">{{ item['ctime'] }}</div>
						</td>
						<td v-if="fieldlist.includes('coupon.code.mtime')" v-bind:class="css('mtime')">
							<div class="items-field">{{ item['mtime'] }}</div>
						</td>
						<td v-if="fieldlist.includes('coupon.code.editor')" v-bind:class="css('editor')">
							<div class="items-field">{{ item['editor'] }}</div>
						</td>
						<td class="actions">
							<input type="hidden" v-if="item.edit" v-bind:value="item['id']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['code', 'coupon.code.id', ''] ) ) ?>`" >
							<a v-if="!item.edit && can('change', idx)" class="btn act-edit icon" href="#" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Edit this entry' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Edit' ) ) ?>"
								v-if="item['siteid'] === siteid"
								v-on:click.prevent.stop="edit(idx)" >
							</a>
							<a v-if="can('delete', idx)" class="btn act-delete icon" href="#" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ) ?>"
								v-on:click.prevent.stop="remove(idx)" >
							</a>
						</td>
					</tr>

				</tbody>
			</table>
		</div>

		<div v-if="loading" class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'Loading entries ...' ) ) ) ?></div>
		<div v-if="!loading && !items.length" class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No entries found' ) ) ) ?></div>

		<nav class="list-page">
			<page-offset v-model="offset" v-bind:limit="limit" v-bind:total="total" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"></page-offset>
			<page-limit v-model="limit" tabindex="<?= $enc->attr( $this->get( 'tabindex' ) ) ?>"></page-limit>
		</nav>

	</div>

	<?= $this->get( 'codeBody' ) ?>
</div>
