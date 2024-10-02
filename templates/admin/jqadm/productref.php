<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2024
 */


$enc = $this->encoder();
$prefix = str_replace( '/', '.', $this->get( 'resource' ) ) . '.';

$status = [
	1 => $this->translate( 'mshop/code', 'status:1' ),
	0 => $this->translate( 'mshop/code', 'status:0' ),
	-1 => $this->translate( 'mshop/code', 'status:-1' ),
	-2 => $this->translate( 'mshop/code', 'status:-2' ),
];

$columnList = [
	$prefix . 'id' => $this->translate( 'admin', 'ID' ),
	$prefix . 'position' => $this->translate( 'admin', 'Position' ),
	$prefix . 'status' => $this->translate( 'admin', 'Status' ),
	$prefix . 'type' => $this->translate( 'admin', 'Type' ),
	$prefix . 'config' => $this->translate( 'admin', 'Config' ),
	$prefix . 'datestart' => $this->translate( 'admin', 'Start date' ),
	$prefix . 'dateend' => $this->translate( 'admin', 'End date' ),
];

$url = $this->link( 'admin/jqadm/url/get', ['resource' => 'product', 'id' => '_id_'] );


?>
<?= $this->partial( $this->config( 'admin/jqadm/partial/columns', 'columns' ) ) ?>

<div class="list productref-list"
	data-data="<?= $enc->attr( array_values( $this->get( 'data', [] ) ) ) ?>"
	data-resource="<?= $enc->attr( $this->get( 'resource' ) ) ?>"
	data-parentid="<?= $enc->attr( $this->get( 'parentid' ) ) ?>"
	data-fields="<?= $enc->attr( $this->get( 'fields', [] ) ) ?>"
	data-siteid="<?= $enc->attr( $this->get( 'siteid' ) ) ?>"
	data-types="<?= $enc->attr( $this->get( 'types' ) ) ?>"
	data-status="<?= $enc->attr( $status ) ?>">

	<column-select tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
		name="<?= $enc->attr( $this->formparam( ['fields', ''] ) ) ?>"
		v-bind:fields="<?= $enc->attr( $this->get( 'fields', [] ) ) ?>"
		v-bind:titles="<?= $enc->attr( $columnList ) ?>"
		v-bind:show="colselect"
		v-bind:submit="false"
		v-on:submit="fieldlist = columns($event)"
		v-on:close="colselect = false">
	</column-select>

	<div class="table-responsive">
		<table class="list-items table table-striped">
			<thead class="list-header">
				<tr>
					<th class="select">
						<a v-on:click.prevent.stop="remove()" class="btn act-delete icon"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete selected entries' ) ) ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ) ?>">
						</a>
					</th>
					<th v-if="fieldlist.includes(prefix + 'id')" v-bind:class="css('id')">
						<a v-bind:class="sortclass('id')" v-on:click.prevent="sort('id')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'ID' ) ) ?>
						</a>
					</th>
					<th v-if="fieldlist.includes(prefix + 'position')" v-bind:class="css('position')">
						<a v-bind:class="sortclass('position')" v-on:click.prevent="sort('position')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'Postition' ) ) ?>
						</a>
					</th>
					<th v-if="fieldlist.includes(prefix + 'status')" v-bind:class="css('status')">
						<a v-bind:class="sortclass('status')" v-on:click.prevent="sort('status')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?>
						</a>
					</th>
					<th v-if="fieldlist.includes(prefix + 'type')" v-bind:class="css('type')">
						<a v-bind:class="sortclass('type')" v-on:click.prevent="sort('type')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?>
						</a>
					</th>
					<th v-if="fieldlist.includes(prefix + 'config')" v-bind:class="css('config')">
						<a v-bind:class="sortclass('config')" v-on:click.prevent="sort('config')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'Config' ) ) ?>
						</a>
					</th>
					<th v-if="fieldlist.includes(prefix + 'datestart')" v-bind:class="css('datestart')">
						<a v-bind:class="sortclass('datestart')" v-on:click.prevent="sort('datestart')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'Start date' ) ) ?>
						</a>
					</th>
					<th v-if="fieldlist.includes(prefix + 'dateend')" v-bind:class="css('dateend')">
						<a v-bind:class="sortclass('dateend')" v-on:click.prevent="sort('dateend')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?>
						</a>
					</th>
					<th v-if="fieldlist.includes(prefix + 'refid')" v-bind:class="css('refid')">
						<a v-bind:class="sortclass('refid')" v-on:click.prevent="sort('refid')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'Product' ) ) ?>
						</a>
					</th>

					<th class="actions">
						<a class="btn icon act-add" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-on:click.prevent.stop="add()" href="#"
							title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ) ?>">
						</a>
						<a class="btn act-columns icon" href="#" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
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
					<td v-if="fieldlist.includes(prefix + 'id')" v-bind:class="css('id')">
						<input class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-on:change="search($event, 'id')"
							v-bind:value="value('id')">
					</td>
					<td v-if="fieldlist.includes(prefix + 'position')" v-bind:class="css('position')">
						<input type="number" step="1" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-on:change="search($event, 'position')"
							v-bind:value="value('position')">
					</td>
					<td v-if="fieldlist.includes(prefix + 'status')" v-bind:class="css('status')">
						<select class="form-select novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							is="vue:select-component"
							v-bind:all="`<?= $enc->js( $this->translate( 'admin', 'All' ) ) ?>`"
							v-bind:items="statuslist"
							v-bind:value="value('status')"
							v-on:input="search($event, 'status')">
						</select>
					</td>
					<td v-if="fieldlist.includes(prefix + 'type')" v-bind:class="css('type')">
						<select class="form-select novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							is="vue:select-component"
							v-bind:all="`<?= $enc->js( $this->translate( 'admin', 'All' ) ) ?>`"
							v-bind:items="typelist"
							v-bind:value="value('type')"
							v-on:input="search($event, 'type')">
						</select>
					</td>
					<td v-if="fieldlist.includes(prefix + 'config')" v-bind:class="css('config')">
						<input class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-on:change="search($event, 'config')"
							v-bind:value="value('config')">
					</td>
					<td v-if="fieldlist.includes(prefix + 'datestart')" v-bind:class="css('datestart')">
						<input is="vue:flat-pickr" class="form-control novalidate custom-datetime" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-bind:value="value('datestart')"
							v-on:input="search($event, 'datestart')"
							v-bind:config="Aimeos.flatpickr.datetimerange">
					</td>
					<td v-if="fieldlist.includes(prefix + 'dateend')" v-bind:class="css('dateend')">
						<input is="vue:flat-pickr" class="form-control novalidate custom-datetime" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-bind:value="value('dateend')"
							v-on:input="search($event, 'dateend')"
							v-bind:config="Aimeos.flatpickr.datetimerange">
					</td>
					<td v-if="fieldlist.includes(prefix + 'refid')" v-bind:class="css('refid')">
						<input class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-on:change="search($event, 'refid')"
							v-bind:value="value('refid')">
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

				<template v-for="(item, idx) in items" v-bind:key="idx">
					<tr v-show="!item._hidden && !item._delete" class="list-item" v-bind:class="{mismatch: !can('match', item)}" v-bind:title="title(item)">
						<td class="select">
							<input class="form-check-input" type="checkbox" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-model="item['_checked']">
						</td>
						<td v-if="fieldlist.includes(prefix + 'id')" v-bind:class="css('id')">
							<div class="items-field">{{ item[prefix + 'id'] }}</div>
						</td>
						<td v-if="fieldlist.includes(prefix + 'position')" v-bind:class="css('position')">
							<input type="number" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-position', ''] ) ) ?>`.replace('-prefix-', prefix)"
								v-if="item._edit" v-model="item[prefix + 'position']">
							<div v-else v-on:click="edit(item)" class="items-field">
								{{ item[prefix + 'position'] }}
							</div>
						</td>
						<td v-if="fieldlist.includes(prefix + 'status')" v-bind:class="css('status')">
							<select class="form-select novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
								is="vue:select-component" v-if="item._edit"
								v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-status', ''] ) ) ?>`.replace('-prefix-', prefix)"
								v-bind:items="<?= $enc->attr( $status ) ?>"
								v-model="item[prefix + 'status']">
							</select>
							<div v-else v-on:click="edit(item)" class="items-field" :class="'status-' + item[prefix + 'status']"
								:title="statuslist[item[prefix + 'status']] || item[prefix + 'status']"></div>
						</td>
						<td v-if="fieldlist.includes(prefix + 'type')" v-bind:class="css('type')">
							<select class="form-select novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
								is="vue:select-component" v-if="item._edit"
								v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-type', ''] ) ) ?>`.replace('-prefix-', prefix)"
								v-bind:items="JSON.parse(types)"
								v-model="item[prefix + 'type']">
							</select>
							<div v-else v-on:click="edit(item)" class="items-field">
								{{ JSON.parse(types)[item[prefix + 'type']] || item[prefix + 'type'] }}
							</div>
						</td>
						<td v-if="fieldlist.includes(prefix + 'config')" v-on:click="edit(item)" v-bind:class="css('config')">
							<input v-if="item._edit" class="form-control" readonly
								:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-config', ''] ) ) ?>`.replace('-prefix-', prefix)"
								:tabindex="`<?= $enc->js( $this->get( 'tabindex', 1 ) ) ?>`"
								:value="JSON.stringify(item['customer.lists.config'] || {})"
								@click="item._show = true">
							<div v-else v-on:click="edit(item)" class="items-field">
								{{ item['customer.lists.config'] }}
							</div>
							<config-map v-if="item._show"
								:tabindex="`<?= $enc->js( $this->get( 'tabindex', 1 ) ) ?>`"
								:map="item['customer.lists.config'] || {}"
								@update="item['customer.lists.config'] = $event; item._show = false"
							></config-map>
						</td>
						<td v-if="fieldlist.includes(prefix + 'datestart')" v-bind:class="css('datestart')">
							<input is="vue:flat-pickr" v-if="item._edit" class="form-control novalidate custom-datetime" type="datetime-local"
								v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-datestart', ''] ) ) ?>`.replace('-prefix-', prefix)"
								tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:value="value('datestart')"
								v-bind:config="Aimeos.flatpickr.datetime">
							<div v-else v-on:click="edit(item)" class="items-field">
								{{ item[prefix + 'datestart'] || '-' }}
							</div>
						</td>
						<td v-if="fieldlist.includes(prefix + 'dateend')" v-bind:class="css('dateend')">
							<input is="vue:flat-pickr" v-if="item._edit" class="form-control novalidate custom-datetime" type="datetime-local"
								v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-dateend', ''] ) ) ?>`.replace('-prefix-', prefix)"
								tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:value="value('dateend')"
								v-bind:config="Aimeos.flatpickr.datetime">
							<div v-else v-on:click="edit(item)" class="items-field">
								{{ item[prefix + 'dateend'] || '-' }}
							</div>
						</td>
						<td v-if="fieldlist.includes(prefix + 'refid')" v-bind:class="css('refid')">
							<div v-if="item._edit">
								<Multiselect class="item-id form-control"
									placeholder="Enter product ID, code or label"
									:value-prop="prefix + 'refid'"
									:track-by="prefix + 'refid'"
									label="product.label"
									@open="function(select) {return select.refreshOptions()}"
									@input="use(item, $event)"
									:value="item"
									:title="title(item)"
									:disabled="!can('change', item)"
									:options="async function(query) {return await suggest(query)}"
									:resolve-on-load="false"
									:filter-results="false"
									:can-deselect="false"
									:allow-absent="true"
									:searchable="true"
									:can-clear="false"
									:required="true"
									:min-chars="1"
									:object="true"
									:delay="300"
								></Multiselect>
							</div>
							<a v-else class="items-field link act-view icon" v-bind:class="'status-' + item['product.status']"
								tabindex="<?= $this->get( 'tabindex' ) ?>" target="_blank"
								v-bind:href="`<?= $url ?>`.replace('_id_', item[prefix + 'refid'] || '')">
								<span>{{ label(item) }}</span>
							</a>
						</td>
						<td class="actions">
							<input type="hidden" v-if="item._delete" v-bind:value="item[prefix + 'id']"
								name="<?= $enc->attr( $this->formparam( ['product', 'delete', ''] ) ) ?>" >
							<input type="hidden" v-if="item._edit" v-bind:value="item[prefix + 'id']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-id', ''] ) ) ?>`.replace('-prefix-', prefix)" >
							<input type="hidden" v-if="item._edit" v-bind:value="item[prefix + 'refid']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-refid', ''] ) ) ?>`.replace('-prefix-', prefix)" >

							<a v-if="!item._edit && can('change', item)" class="btn act-edit icon" href="#" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Edit this entry' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Edit' ) ) ?>"
								v-on:click.prevent.stop="edit(item)" >
							</a>
							<a v-if="can('delete', item)" class="btn act-delete icon" href="#" tabindex="<?= $this->get( 'tabindex' ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ) ?>"
								v-on:click.prevent.stop="remove(item)" >
							</a>
						</td>
					</tr>
				</template>

			</tbody>
		</table>
	</div>

	<div v-if="!items.length" class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No entries found' ) ) ) ?></div>

</div>
