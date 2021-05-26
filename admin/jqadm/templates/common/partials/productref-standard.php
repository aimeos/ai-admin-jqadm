<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2021
 */


$enc = $this->encoder();

$status = [
	1 => $this->translate( 'mshop/code', 'status:1' ),
	0 => $this->translate( 'mshop/code', 'status:0' ),
	-1 => $this->translate( 'mshop/code', 'status:-1' ),
	-2 => $this->translate( 'mshop/code', 'status:-2' ),
];

$target = $this->config( 'admin/jqadm/url/get/target' );
$cntl = $this->config( 'admin/jqadm/url/get/controller', 'Jqadm' );
$action = $this->config( 'admin/jqadm/url/get/action', 'get' );
$config = $this->config( 'admin/jqadm/url/get/config', [] );
$url = $this->url( $target, $cntl, $action, ['resource' => 'product', 'id' => '_id_'], [], $config );


?>
<div class="productref-list"
	data-resource="<?= $enc->attr( $this->get( 'resource' ) ) ?>"
	data-parentid="<?= $enc->attr( $this->get( 'parentid' ) ) ?>"
	data-fields="<?= $enc->attr( $this->get( 'fields', [] ) ) ?>"
	data-siteid="<?= $enc->attr( $this->get( 'siteid' ) ) ?>"
	data-types="<?= $enc->attr( $this->get( 'types' ) ) ?>">

	<div class="table-responsive">
		<table class="list-items table table-striped">
			<thead class="list-header">
				<tr>
					<th class="select">
						<a v-on:click.prevent.stop="remove()" class="btn act-delete fa"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete selected entries' ) ) ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ) ?>">
						</a>
					</th>
					<th v-if="fields.includes(prefix + 'id')" v-bind:class="css('id')">
						<a v-bind:class="sortclass('id')" v-on:click.prevent="sort('id')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'ID' ) ) ?>
						</a>
					</th>
					<th v-if="fields.includes(prefix + 'position')" v-bind:class="css('position')">
						<a v-bind:class="sortclass('position')" v-on:click.prevent="sort('position')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'Postition' ) ) ?>
						</a>
					</th>
					<th v-if="fields.includes(prefix + 'status')" v-bind:class="css('status')">
						<a v-bind:class="sortclass('status')" v-on:click.prevent="sort('status')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?>
						</a>
					</th>
					<th v-if="fields.includes(prefix + 'type')" v-bind:class="css('type')">
						<a v-bind:class="sortclass('type')" v-on:click.prevent="sort('type')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?>
						</a>
					</th>
					<th v-if="fields.includes(prefix + 'config')" v-bind:class="css('config')">
						<a v-bind:class="sortclass('config')" v-on:click.prevent="sort('config')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'Config' ) ) ?>
						</a>
					</th>
					<th v-if="fields.includes(prefix + 'datestart')" v-bind:class="css('datestart')">
						<a v-bind:class="sortclass('datestart')" v-on:click.prevent="sort('datestart')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'Start date' ) ) ?>
						</a>
					</th>
					<th v-if="fields.includes(prefix + 'dateend')" v-bind:class="css('dateend')">
						<a v-bind:class="sortclass('dateend')" v-on:click.prevent="sort('dateend')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?>
						</a>
					</th>
					<th v-if="fields.includes(prefix + 'refid')" v-bind:class="css('refid')">
						<a v-bind:class="sortclass('refid')" v-on:click.prevent="sort('refid')"
							tabindex="<?= $this->get( 'tabindex' ) ?>" href="#">
							<?= $enc->html( $this->translate( 'admin', 'Product' ) ) ?>
						</a>
					</th>

					<th class="actions">
						<a class="btn fa act-add" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-on:click.prevent.stop="add()" href="#"
							title="<?= $enc->attr( $this->translate( 'admin', 'Insert new entry (Ctrl+I)' ) ) ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Add' ) ) ?>">
						</a>
						<div class="dropdown filter-columns">
							<button class="btn act-columns fa" type="button" id="dropdownMenuButton-<?= $this->get( 'group' ) ?>"
								data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" tabindex="<?= $this->get( 'tabindex' ) ?>"
								aria-label="<?= $enc->attr( $this->translate( 'admin', 'Columns' ) ) ?>"
								title="<?= $enc->attr( $this->translate( 'admin', 'Columns' ) ) ?>">
							</button>
							<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton-<?= $this->get( 'group' ) ?>">
								<li class="dropdown-item">
									<a v-on:click.prevent.stop="toggle('id')" href="#"><label>
										<input class="form-check-input"
											v-on:click.capture.stop="toggle('id')"
											v-bind:checked="fields.includes(prefix + 'id')"
											type="checkbox" tabindex="<?= $this->get( 'tabindex' ) ?>" />
										<?= $enc->html( $this->translate( 'admin', 'ID' ) ) ?>
									</label></a>
								</li>
								<li class="dropdown-item">
									<a v-on:click.prevent.stop="toggle('position')" href="#"><label>
										<input class="form-check-input"
											v-on:click.capture.stop="toggle('position')"
											v-bind:checked="fields.includes(prefix + 'position')"
											type="checkbox" tabindex="<?= $this->get( 'tabindex' ) ?>" />
										<?= $enc->html( $this->translate( 'admin', 'Position' ) ) ?>
									</label></a>
								</li>
								<li class="dropdown-item">
									<a v-on:click.prevent.stop="toggle('status')" href="#"><label>
										<input class="form-check-input"
											v-on:click.capture.stop="toggle('status')"
											v-bind:checked="fields.includes(prefix + 'status')"
											type="checkbox" tabindex="<?= $this->get( 'tabindex' ) ?>" />
										<?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?>
									</label></a>
								</li>
								<li class="dropdown-item">
									<a v-on:click.prevent.stop="toggle('type')" href="#"><label>
										<input class="form-check-input"
											v-on:click.capture.stop="toggle('type')"
											v-bind:checked="fields.includes(prefix + 'type')"
											type="checkbox" tabindex="<?= $this->get( 'tabindex' ) ?>" />
										<?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?>
									</label></a>
								</li>
								<li class="dropdown-item">
									<a v-on:click.prevent.stop="toggle('config')" href="#"><label>
										<input class="form-check-input"
											v-on:click.capture.stop="toggle('config')"
											v-bind:checked="fields.includes(prefix + 'config')"
											type="checkbox" tabindex="<?= $this->get( 'tabindex' ) ?>" />
										<?= $enc->html( $this->translate( 'admin', 'Config' ) ) ?>
									</label></a>
								</li>
								<li class="dropdown-item">
									<a v-on:click.prevent.stop="toggle('datestart')" href="#"><label>
										<input class="form-check-input"
											v-on:click.capture.stop="toggle('datestart')"
											v-bind:checked="fields.includes(prefix + 'datestart')"
											type="checkbox" tabindex="<?= $this->get( 'tabindex' ) ?>" />
										<?= $enc->html( $this->translate( 'admin', 'Start date' ) ) ?>
									</label></a>
								</li>
								<li class="dropdown-item">
									<a v-on:click.prevent.stop="toggle('dateend')" href="#"><label>
										<input class="form-check-input"
											v-on:click.capture.stop="toggle('dateend')"
											v-bind:checked="fields.includes(prefix + 'dateend')"
											type="checkbox" tabindex="<?= $this->get( 'tabindex' ) ?>" />
										<?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?>
									</label></a>
								</li>
								<li class="dropdown-item">
									<a v-on:click.prevent.stop="toggle('refid')" href="#"><label>
										<input class="form-check-input"
											v-on:click.capture.stop="toggle('refid')"
											v-bind:checked="fields.includes(prefix + 'refid')"
											type="checkbox" tabindex="<?= $this->get( 'tabindex' ) ?>" />
										<?= $enc->html( $this->translate( 'admin', 'Product' ) ) ?>
									</label></a>
								</li>
							</ul>
						</div>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr class="list-search">
					<td class="select">
						<input v-model="checked" class="form-check-input" type="checkbox" tabindex="<?= $this->get( 'tabindex' ) ?>" />
					</td>
					<td v-if="fields.includes(prefix + 'id')" v-bind:class="css('id')">
						<input class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-on:change="find($event, 'id')"
							v-bind:value="value('id')" />
					</td>
					<td v-if="fields.includes(prefix + 'position')" v-bind:class="css('position')">
						<input type="number" step="1" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-on:change="find($event, 'position', '=~')"
							v-bind:value="value('position')" />
					</td>
					<td v-if="fields.includes(prefix + 'status')" v-bind:class="css('status')">
						<select class="form-select novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							is="select-component"
							v-bind:all="`<?= $enc->js( $this->translate( 'admin', 'All' ) ) ?>`"
							v-bind:items="<?= $enc->attr( $status ) ?>"
							v-bind:value="value('status')"
							v-on:input="find($event, 'status')">
						</select>
					</td>
					<td v-if="fields.includes(prefix + 'type')" v-bind:class="css('type')">
						<select class="form-select novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							is="select-component"
							v-bind:all="`<?= $enc->js( $this->translate( 'admin', 'All' ) ) ?>`"
							v-bind:items="types"
							v-bind:value="value('type')"
							v-on:input="find($event, 'type')">
						</select>
					</td>
					<td v-if="fields.includes(prefix + 'config')" v-bind:class="css('config')">
						<input class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-on:change="find($event, 'config', '~=')"
							v-bind:value="value('config')" />
					</td>
					<td v-if="fields.includes(prefix + 'datestart')" v-bind:class="css('datestart')">
						<input is="flat-pickr" class="form-control novalidate custom-datetime" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-bind:value="value('datestart')"
							v-on:input="find($event, 'datestart')"
							v-bind:config="Aimeos.flatpickr.datetimerange" />
					</td>
					<td v-if="fields.includes(prefix + 'dateend')" v-bind:class="css('dateend')">
						<input is="flat-pickr" class="form-control novalidate custom-datetime" type="text" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-bind:value="value('dateend')"
							v-on:input="find($event, 'dateend')"
							v-bind:config="Aimeos.flatpickr.datetimerange" />
					</td>
					<td v-if="fields.includes(prefix + 'refid')" v-bind:class="css('refid')">
						<input class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-on:change="find($event, 'refid')"
							v-bind:value="value('refid')" />
					</td>

					<td class="actions">
						<a v-on:click.prevent="fetch()" class="btn act-search fa" href="#" tabindex="<?= $this->get( 'tabindex' ) ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Search' ) ) ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Search' ) ) ?>">
						</a>
						<a v-on:click.prevent="reset()" class="btn act-reset fa" href="#" tabindex="<?= $this->get( 'tabindex' ) ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Reset' ) ) ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Reset' ) ) ?>"></a>
					</td>
				</tr>

				<tr v-for="(item, idx) in items" v-bind:key="idx" class="list-item">
					<td class="select">
						<input class="form-check-input" type="checkbox" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-model="item['checked']" />
					</td>
					<td v-if="fields.includes(prefix + 'id')" v-bind:class="css('id')">
						<div class="items-field">{{ item[prefix + 'id'] }}</div>
					</td>
					<td v-if="fields.includes(prefix + 'position')" v-bind:class="css('position')">
						<input type="number" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-position', ''] ) ) ?>`.replace('-prefix-', prefix)"
							v-if="item.edit" v-model="item[prefix + 'position']" />
						<div v-else v-on:click="edit(idx)" class="items-field">
							{{ item[prefix + 'position'] }}
						</div>
					</td>
					<td v-if="fields.includes(prefix + 'status')" v-bind:class="css('status')">
						<select class="form-select novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							is="select-component" v-if="item.edit"
							v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-status', ''] ) ) ?>`.replace('-prefix-', prefix)"
							v-bind:items="<?= $enc->attr( $status ) ?>"
							v-model="item[prefix + 'status']">
						</select>
						<div v-else v-on:click="edit(idx)" class="items-field">
							{{ <?= $enc->html( $status ) ?>[item[prefix + 'status']] }}
						</div>
					</td>
					<td v-if="fields.includes(prefix + 'type')" v-bind:class="css('type')">
						<select class="form-select novalidate" tabindex="<?= $this->get( 'tabindex' ) ?>"
							is="select-component" v-if="item.edit"
							v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-type', ''] ) ) ?>`.replace('-prefix-', prefix)"
							v-bind:items="types"
							v-model="item[prefix + 'type']">
						</select>
						<div v-else v-on:click="edit(idx)" class="items-field">
							{{ types[item[prefix + 'type']] || item[prefix + 'type'] }}
						</div>
					</td>
					<td v-if="fields.includes(prefix + 'config')" v-on:click="edit(idx)" v-bind:class="css('config')">
						<input-map
							v-bind:editable="siteid === item[prefix + 'siteid'] && item.edit"
							v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-config', ''] ) ) ?>`.replace('-prefix-', prefix)"
							v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"
							v-model="item[prefix + 'config']">
						</input-map>
					</td>
					<td v-if="fields.includes(prefix + 'datestart')" v-bind:class="css('datestart')">
						<input is="flat-pickr" v-if="item.edit" class="form-control novalidate custom-datetime" type="datetime-local"
							v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-datestart', ''] ) ) ?>`.replace('-prefix-', prefix)"
							tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-bind:value="value('datestart')"
							v-bind:config="Aimeos.flatpickr.datetime" />
						<div v-else v-on:click="edit(idx)" class="items-field">
							{{ item[prefix + 'datestart'] || '-' }}
						</div>
					</td>
					<td v-if="fields.includes(prefix + 'dateend')" v-bind:class="css('dateend')">
						<input is="flat-pickr" v-if="item.edit" class="form-control novalidate custom-datetime" type="datetime-local"
							v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-dateend', ''] ) ) ?>`.replace('-prefix-', prefix)"
							tabindex="<?= $this->get( 'tabindex' ) ?>"
							v-bind:value="value('dateend')"
							v-bind:config="Aimeos.flatpickr.datetime" />
						<div v-else v-on:click="edit(idx)" class="items-field">
							{{ item[prefix + 'dateend'] || '-' }}
						</div>
					</td>
					<td v-if="fields.includes(prefix + 'refid')" v-bind:class="css('refid')">
						<div v-if="item.edit && item[prefix + 'refid'] == ''">
							<v-select class="custom-combobox" tabindex="<?= $this->get( 'tabindex' ) ?>"
								v-bind:options="options" v-bind:reduce="entry => entry.id" v-bind:filterable="false"
								v-model="item[prefix + 'refid']" v-on:search="suggest" v-on:search:focus="suggest"
								v-bind:clearSearchOnSelect="false"
								placeholder="<?= $enc->attr( $this->translate( 'admin', 'Product ID, SKU or label' ) ) ?>" >
								<template v-slot:no-options="{ search, searching, loading }">
									<template v-if="searching">
										<?= $enc->html( $this->translate( 'admin', 'No product found' ) ) ?>
									</template>
									<em v-else>
										<?= $enc->html( $this->translate( 'admin', 'Start typing ...' ) ) ?>
									</em>
								</template>
							</v-select>
							<input type="hidden" v-model="item[prefix + 'refid']"
								v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-refid', ''] ) ) ?>`.replace('-prefix-', prefix)" />
						</div>
						<a v-else class="items-field act-view" v-bind:class="'status-' + item['product.status']"
							tabindex="<?= $this->get( 'tabindex' ) ?>" target="_blank"
							v-bind:href="`<?= $url ?>`.replace('_id_', item[prefix + 'refid'] || '')">
							{{ label(idx) }}
						</a>
					</td>
					<td class="actions">
						<input type="hidden" v-if="item.edit" v-bind:value="item[prefix + 'id']"
							v-bind:name="`<?= $enc->js( $this->formparam( ['product', '-prefix-id', ''] ) ) ?>`.replace('-prefix-', prefix)" >
						<a v-if="!item.edit" class="btn act-edit fa" href="#" tabindex="<?= $this->get( 'tabindex' ) ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Edit this entry' ) ) ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Edit' ) ) ?>"
							v-if="item[prefix + 'siteid'] === siteid"
							v-on:click.prevent.stop="edit(idx)" >
						</a>
						<a class="btn act-delete fa" href="#" tabindex="<?= $this->get( 'tabindex' ) ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
							aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ) ?>"
							v-if="item[prefix + 'siteid'] === siteid"
							v-on:click.prevent.stop="remove(idx)" >
						</a>
					</td>
				</tr>

			</tbody>
		</table>
	</div>

	<div v-if="loading" class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'Loading items ...' ) ) ) ?></div>
	<div v-if="!loading && !items.length" class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No items found' ) ) ) ?></div>

	<nav class="list-page">
		<page-offset v-model="offset" v-bind:limit="limit" v-bind:total="total" v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"></page-offset>
		<page-limit v-model="limit" v-bind:tabindex="`<?= $enc->js( $this->get( 'tabindex' ) ) ?>`"></page-limit>
	</nav>

</div>
