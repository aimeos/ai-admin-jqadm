<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2024
 */


$enc = $this->encoder();

$status = [
	1 => $this->translate( 'mshop/code', 'status:1' ),
	0 => $this->translate( 'mshop/code', 'status:0' ),
	-1 => $this->translate( 'mshop/code', 'status:-1' ),
	-2 => $this->translate( 'mshop/code', 'status:-2' ),
];

$columnList = [
	'product.lists.id' => $this->translate( 'admin', 'ID' ),
	'product.lists.position' => $this->translate( 'admin', 'Position' ),
	'product.lists.status' => $this->translate( 'admin', 'Status' ),
	'product.lists.type' => $this->translate( 'admin', 'Type' ),
	'product.lists.config' => $this->translate( 'admin', 'Config' ),
	'product.lists.datestart' => $this->translate( 'admin', 'Start date' ),
	'product.lists.dateend' => $this->translate( 'admin', 'End date' ),
];

$url = $this->link( 'admin/jqadm/url/get', ['resource' => 'product', 'id' => '_id_'] );


?>
<?= $this->partial( $this->config( 'admin/jqadm/partial/columns', 'columns' ) ) ?>

<div class="list productlist"
	data-fields="<?= $enc->attr( $this->get( 'fields', [] ) ) ?>"
	data-siteid="<?= $enc->attr( $this->get( 'siteid' ) ) ?>"
	data-domain="<?= $enc->attr( $this->get( 'domain' ) ) ?>"
	data-types="<?= $enc->attr( $this->get( 'types' ) ) ?>"
	data-refid="<?= $enc->attr( $this->get( 'refid' ) ) ?>"
	data-status="<?= $enc->attr( $status ) ?>">

	<column-select tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
		name="<?= $enc->attr( $this->formparam( ['fields', ''] ) ) ?>"
		v-bind:titles="<?= $enc->attr( $columnList ) ?>"
		v-bind:fields="fieldlist"
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
						<button class="btn icon-menu" type="button" data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}'
							aria-expanded="false" title="<?= $enc->attr( $this->translate( 'admin', 'Menu' ) ) ?>">
						</button>
						<ul class="dropdown-menu">
							<li>
								<a v-on:click.prevent="toggle()" class="btn"
									tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#"
									title="<?= $enc->attr( $this->translate( 'admin', 'Select all entries' ) ) ?>">
									<?= $enc->attr( $this->translate( 'admin', 'Toogle selection' ) ) ?>
								</a>
							</li>
							<li>
								<a v-on:click.prevent="edit()" class="btn"
									tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#"
									title="<?= $enc->attr( $this->translate( 'admin', 'Edit selected entries' ) ) ?>">
									<?= $enc->attr( $this->translate( 'admin', 'Edit' ) ) ?>
								</a>
							</li>
							<li>
								<a v-on:click.prevent="remove()" class="btn"
									tabindex="<?= $this->get( 'tabindex', 1 ) ?>" href="#"
									title="<?= $enc->attr( $this->translate( 'admin', 'Delete selected entries' ) ) ?>">
									<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ) ?>
								</a>
							</li>
						</ul>
					</th>
					<th v-if="fieldlist.includes('product.lists.id')" v-bind:class="css('id')">
						<?= $enc->html( $this->translate( 'admin', 'ID' ) ) ?>
					</th>
					<th v-if="fieldlist.includes('product.lists.position')" v-bind:class="css('position')">
						<?= $enc->html( $this->translate( 'admin', 'Postition' ) ) ?>
					</th>
					<th v-if="fieldlist.includes('product.lists.status')" v-bind:class="css('status')">
						<?= $enc->html( $this->translate( 'admin', 'Status' ) ) ?>
					</th>
					<th v-if="fieldlist.includes('product.lists.type')" v-bind:class="css('type')">
						<?= $enc->html( $this->translate( 'admin', 'Type' ) ) ?>
					</th>
					<th v-if="fieldlist.includes('product.lists.config')" v-bind:class="css('config')">
						<?= $enc->html( $this->translate( 'admin', 'Config' ) ) ?>
					</th>
					<th v-if="fieldlist.includes('product.lists.datestart')" v-bind:class="css('datestart')">
						<?= $enc->html( $this->translate( 'admin', 'Start date' ) ) ?>
					</th>
					<th v-if="fieldlist.includes('product.lists.dateend')" v-bind:class="css('dateend')">
						<?= $enc->html( $this->translate( 'admin', 'End date' ) ) ?>
					</th>
					<th v-if="fieldlist.includes('product.lists.parentid')" v-bind:class="css('parentid')">
						<?= $enc->html( $this->translate( 'admin', 'Product' ) ) ?>
					</th>

					<th class="actions">
						<a class="btn act-columns icon" href="#" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Columns' ) ) ?>"
							v-on:click.prevent.stop="colselect = true">
						</a>
					</th>
				</tr>
			</thead>

			<tbody>
				<template v-for="(item, idx) in items" v-bind:key="idx">
					<template v-for="(litem, index) in (item.lists[domain] || [])" v-bind:key="index">
						<tr v-if="litem.refid === refid" class="list-item" v-bind:class="{mismatch: !can('match', litem)}" v-bind:title="title(litem)">
							<td class="select">
								<input class="form-check-input" type="checkbox" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
									v-model="litem._checked">
							</td>
							<td v-if="fieldlist.includes('product.lists.id')" v-bind:class="css('id')">
								<div class="items-field">{{ litem['id'] }}</div>
							</td>
							<td v-if="fieldlist.includes('product.lists.position')" v-bind:class="css('position')">
								<input type="number" class="form-control novalidate" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
									name="<?= $enc->attr( $this->formparam( ['product', 'product.lists.position', ''] ) ) ?>"
									v-if="litem._edit" v-model="litem['position']">
								<div v-else v-on:click="edit(litem)" class="items-field">
									{{ litem['position'] }}
								</div>
							</td>
							<td v-if="fieldlist.includes('product.lists.status')" v-bind:class="css('status')">
								<select class="form-select novalidate" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
									is="vue:select-component" v-if="litem._edit"
									name="<?= $enc->attr( $this->formparam( ['product', 'product.lists.status', ''] ) ) ?>"
									v-bind:items="<?= $enc->attr( $status ) ?>"
									v-model="litem['status']">
								</select>
								<div v-else v-on:click="edit(litem)" class="items-field" :class="'status-' + litem['status']"
									:title="statuslist[litem['status']] || litem['status']"></div>
							</td>
							<td v-if="fieldlist.includes('product.lists.type')" v-bind:class="css('type')">
								<select class="form-select novalidate" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
									is="vue:select-component" v-if="litem._edit"
									name="<?= $enc->attr( $this->formparam( ['product', 'product.lists.type', ''] ) ) ?>"
									v-bind:items="<?= $enc->attr( $this->get( 'types' ) ) ?>"
									v-model="litem['type']">
								</select>
								<div v-else v-on:click="edit(litem)" class="items-field">
									{{ typelist[litem['type']] || litem['type'] }}
								</div>
							</td>
							<td v-if="fieldlist.includes('product.lists.config')" v-bind:class="css('config')">
								<input v-if="litem._edit" class="form-control" readonly
									:tabindex="`<?= $enc->js( $this->get( 'tabindex', 1 ) ) ?>`"
									:required="true"
									name="<?= $enc->attr( $this->formparam( ['product', 'product.lists.config', ''] ) ) ?>"
									:value="litem['config'] || '{}'"
									@click="litem._show = true">
								<div v-else v-on:click="edit(litem)" class="items-field">
									{{ litem['config'] }}
								</div>
								<config-map v-if="litem._show"
									:tabindex="`<?= $enc->js( $this->get( 'tabindex', 1 ) ) ?>`"
									:readonly="false"
									:map="JSON.parse(litem.config) || {}"
									@update="litem['config'] = JSON.stringify($event); litem._show = false"
								></config-map>
							</td>
							<td v-if="fieldlist.includes('product.lists.datestart')" v-bind:class="css('datestart')">
								<input is="vue:flat-pickr" v-if="litem._edit" class="form-control novalidate custom-datetime" type="datetime-local"
									name="<?= $enc->attr( $this->formparam( ['product', 'product.lists.datestart', ''] ) ) ?>"
									tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
									v-bind:value="litem['datestart']"
									v-bind:config="Aimeos.flatpickr.datetime">
								<div v-else v-on:click="edit(litem)" class="items-field">
									{{ litem['datestart'] || '-' }}
								</div>
							</td>
							<td v-if="fieldlist.includes('product.lists.dateend')" v-bind:class="css('dateend')">
								<input is="vue:flat-pickr" v-if="litem._edit" class="form-control novalidate custom-datetime" type="datetime-local"
									name="<?= $enc->attr( $this->formparam( ['product', 'product.lists.dateend', ''] ) ) ?>"
									tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
									v-bind:value="litem['dateend']"
									v-bind:config="Aimeos.flatpickr.datetime">
								<div v-else v-on:click="edit(litem)" class="items-field">
									{{ litem['dateend'] || '-' }}
								</div>
							</td>
							<td v-if="fieldlist.includes('product.lists.parentid')" v-bind:class="css('parentid')">
								<a class="items-field act-view" v-bind:class="'status-' + litem['product.status']"
									tabindex="<?= $this->get( 'tabindex', 1 ) ?>" target="_blank"
									v-bind:href="`<?= $url ?>`.replace('_id_', item['id'] || '')">
									{{ item['label'] }}<br>({{ item['code'] }})
								</a>
							</td>
							<td class="actions">
								<input type="hidden" v-if="litem._edit" v-model="litem['id']"
									name="<?= $enc->attr( $this->formparam( ['product', 'product.lists.id', ''] ) ) ?>" >
								<input type="hidden" v-if="litem._edit" v-model="item['id']"
									name="<?= $enc->attr( $this->formparam( ['product', 'product.lists.parentid', ''] ) ) ?>">

								<a v-if="!litem._edit && can('change', litem)" class="btn act-edit icon" href="#" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Edit this entry' ) ) ?>"
									aria-label="<?= $enc->attr( $this->translate( 'admin', 'Edit' ) ) ?>"
									v-on:click.prevent.stop="edit(litem)" >
								</a>
								<a v-if="can('delete', litem)" class="btn act-delete icon" href="#" tabindex="<?= $this->get( 'tabindex', 1 ) ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry' ) ) ?>"
									aria-label="<?= $enc->attr( $this->translate( 'admin', 'Delete' ) ) ?>"
									v-on:click.prevent.stop="remove(idx, index)" >
								</a>
							</td>
						</tr>
					</template>
				</template>
			</tbody>
		</table>
	</div>

	<div v-if="loading" class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'Loading entries ...' ) ) ) ?></div>
	<div v-if="!loading && !items.length" class="noitems"><?= $enc->html( sprintf( $this->translate( 'admin', 'No entries found' ) ) ) ?></div>

	<nav class="list-page">
		<page-offset v-model="offset" v-bind:limit="limit" v-bind:total="total" tabindex="<?= $enc->attr( $this->get( 'tabindex', 1 ) ) ?>"></page-offset>
		<page-limit v-model="limit" tabindex="<?= $enc->attr( $this->get( 'tabindex', 1 ) ) ?>"></page-limit>
	</nav>

</div>
