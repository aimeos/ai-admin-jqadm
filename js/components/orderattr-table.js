/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */


Aimeos.components['orderattr-table'] = {
	components: {
		Multiselect
	},
	template: `
		<table class="item-orderattr table table-default">
			<thead>
				<tr>
					<th class="row-key">
						<span class="help">{{ i18n.code ||'Code' }}</span>
						<div class="form-text text-muted help-text">
							{{ i18n.help ||'Service attribute code' }}
						</div>
					</th>
					<th class="row-value">{{ i18n.value ||'Value' }}</th>
					<th class="actions">
						<div class="btn act-add icon"
							:title="i18n.insert ||'Insert new entry (Ctrl+I)'"
							:tabindex="tabindex"
							@click="add()" />
					</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(entry, idx) in items" v-bind:key="idx" class="service-attr-item" v-bind:class="{mismatch: !can('match', idx)}" v-bind:title="title(idx)">
					<td class="attr-code">
						<input type="hidden" class="service-attr-id"
							v-model="entry['order.service.attribute.id']"
							v-bind:name="fname('order.service.attribute.id', idx)">

						<input type="hidden" class="service-attr-attributeid"
							v-model="entry['order.service.attribute.attributeid']"
							v-bind:name="fname('order.service.attribute.attributeid', idx)">

						<input type="hidden" class="service-attr-type"
							v-model="entry['order.service.attribute.type']"
							v-bind:name="fname('order.service.attribute.type', idx)">

						<input type="hidden" class="service-attr-name"
							v-model="entry['order.service.attribute.name']"
							v-bind:name="fname('order.service.attribute.name', idx)">

						<input type="hidden" class="service-attr-quantity"
							v-model="entry['order.service.attribute.quantity']"
							v-bind:name="fname('order.service.attribute.quantity', idx)">

						<Multiselect class="service-attr-type form-control"
							:name="fname('order.service.attribute.code', idx)"
							:value="entry['order.service.attribute.code']"
							:disabled="!can('change', idx)"
							:append-new-option="true"
							:resolve-on-load="false"
							:filter-results="false"
							:native-support="true"
							:create-option="true"
							:allow-absent="true"
							:can-deselect="true"
							:searchable="true"
							:can-clear="true"
							:required="true"
							:options="suggest"
							:attrs="{tabindex: tabindex}"
						></Multiselect>
					</td>
					<td class="attr-value">
						<div v-if="entry['order.service.attribute.value'] !== null && Array.isArray(JSON.parse(JSON.stringify(entry['order.service.attribute.value'])))" >
							<input class="form-control" readonly
								:tabindex="tabindex"
								:name="fname('order.service.attribute.value', idx)"
								:value="JSON.stringify(entry['order.service.attribute.value'] || [])"
								@click="entry['_show'] = true">
							<config-list v-if="entry._show"
								:readonly="!can('change', idx)"
								:list="entry['order.service.attribute.value'] || []"
								@update="entry['order.service.attribute.value'] = $event; entry['_show'] = false"
							></config-list>
						</div>
						<div v-else-if="entry['order.service.attribute.value'] !== null && typeof JSON.parse(JSON.stringify(entry['order.service.attribute.value'])) === 'object'">
							<input class="form-control" readonly
								:tabindex="tabindex"
								:name="fname('order.service.attribute.value', idx)"
								:value="JSON.stringify(entry['order.service.attribute.value'] || {})"
								@click="entry['_show'] = true">
							<config-map v-if="entry._show"
								:tabindex="tabindex"
								:readonly="!can('change', idx)"
								:map="entry['order.service.attribute.value'] || {}"
								@update="entry['order.service.attribute.value'] = $event; entry['_show'] = false"
							></config-map>
						</div>
						<input v-else
							class="service-attr-value form-control" v-bind:tabindex="tabindex"
							v-model="entry['order.service.attribute.value']"
							v-bind:name="fname('order.service.attribute.value', idx)"
							v-bind:readonly="!can('change', idx)">
					</td>
					<td class="actions">
						<div v-if="can('delete', idx)" class="btn act-delete icon" v-bind:tabindex="tabindex"
							v-bind:title="i18n.delete ||'Delete this entry'" v-on:click.stop="remove(idx)">
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	`,

	emits: ['update:attributes'],

	props: {
		'i18n': {type: Object, default: () => ({})},
		'items': {type: Array, required: true},
		'name': {type: String, required: true},
		'siteid': {type: String, required: true},
		'suggest': {type: Array, default: []},
		'tabindex': {type: Number, default: 1},
	},

	methods: {
		add(data = {}) {
			let entry = {};

			entry['order.service.attribute.id'] = null;
			entry['order.service.attribute.siteid'] = this.siteid;
			entry['order.service.attribute.attrid'] = null;
			entry['order.service.attribute.quantity'] = 1;
			entry['order.service.attribute.name'] = null;
			entry['order.service.attribute.code'] = null;
			entry['order.service.attribute.value'] = null;

			let list = this.items;
			list.push(Object.assign(entry, data));
			this.$emit('update:attributes', list);
		},

		can(action, idx) {
			return Aimeos.can(action, this.items[idx]['order.service.attribute.siteid'] || null, this.siteid)
		},

		fname(key, idx) {
			return this.name.replace('_idx_', idx).replace('_key_', key);
		},

		remove(idx) {
			let list = this.items;
			list.splice(idx, 1);
			this.$emit('update:attributes', list);
		},

		title(idx) {
			if(this.items[idx]['order.service.attribute.ctime']) {
				return 'Site ID: ' + this.items[idx]['order.service.attribute.siteid'] + "\n"
					+ 'Editor: ' + this.items[idx]['order.service.attribute.editor'] + "\n"
					+ 'Created: ' + this.items[idx]['order.service.attribute.ctime'] + "\n"
					+ 'Modified: ' + this.items[idx]['order.service.attribute.mtime'];
			}
			return ''
		}
	}
};
