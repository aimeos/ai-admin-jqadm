/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


const AimeosListComponent = Vue.component('aimeos-list', {

	template: `<table class="table">
		<tbody>
			<tr v-for="(entry, idx) in entries" :key="idx" class="config-item">
				<td class="actions">
					<div v-if="!readonly" class="btn act-delete fa"
						:tabindex="tabindex"
						@click="remove(idx)">
					</div>
				</td>
				<td class="config-row-value">
					<input class="form-control"
						:tabindex="tabindex"
						:readonly="readonly"
						v-model="entries[idx]">
				</td>
			</tr>
			<tr class="config-map-actions">
				<td class="config-map-action-add">
					<div class="btn act-add fa" :tabindex="tabindex" @click="add()"></div>
				</td>
				<td class="config-map-action-update" colspan="2">
					<div class="btn btn-primary act-check fa" :tabindex="tabindex" @click="update()"></div>
				</td>
			</tr>
		</tbody>
	</table>`,

	props: {
		'list': {type: Array, required: true},
		'readonly': {type: Boolean, default: true},
		'tabindex': {type: String, default: '1'}
	},

	data() {
		return {
			entries: []
		}
	},

	methods: {
		add() {
			this.entries.push('');
		},

		remove(idx) {
			this.entries.splice(idx, 1);
		},

		update() {
			this.$emit('update', this.entries)
		}
	},

	watch: {
		list: {
			immediate: true,
			handler() {
				this.entries = this.list
			}
		}
	}
});


const AimeosMapComponent = Vue.component('aimeos-map', {

	template: `<table class="table">
		<tbody>
			<tr v-for="(entry, idx) in entries" :key="idx" class="config-item">
				<td class="actions">
					<div v-if="!readonly" class="btn act-delete fa"
						:tabindex="tabindex"
						@click="remove(idx)">
					</div>
				</td>
				<td class="config-row-key">
					<input class="form-control" required
						:tabindex="tabindex"
						:readonly="readonly"
						v-model="entry.key">
				</td>
				<td class="config-row-value">
					<input class="form-control"
						:tabindex="tabindex"
						:readonly="readonly"
						v-model="entry.val">
				</td>
			</tr>
			<tr class="config-map-actions">
				<td class="config-map-action-add">
					<div class="btn act-add fa" :tabindex="tabindex" @click="add()"></div>
				</td>
				<td class="config-map-action-update" colspan="2">
					<div class="btn btn-primary act-check fa" :tabindex="tabindex" @click="update()"></div>
				</td>
			</tr>
		</tbody>
	</table>`,

	props: {
		'map': {type: Object, required: true},
		'readonly': {type: Boolean, default: true},
		'tabindex': {type: String, default: '1'}
	},

	data() {
		return {
			entries: []
		}
	},

	methods: {
		add() {
			this.entries.push({key: '', val: ''});
		},

		remove(idx) {
			this.entries.splice(idx, 1);
		},

		update() {
			const map = {}

			for(const idx in this.entries) {
				map[this.entries[idx]['key']] = this.entries[idx]['val']
			}

			this.$emit('update', map)
		}
	},

	watch: {
		map: {
			immediate: true,
			handler() {
				this.entries = []

				for(const key in this.map) {
					this.entries.push({key: key, val: this.map[key]})
				}
			}
		}
	}
});



Vue.component('config-table', {

	components: {
		AimeosListComponent,
		AimeosMapComponent
	},

	template: `<table class="item-config table">
		<thead>
			<tr>
				<th class="config-row-key">
					<span class="help">{{ i18n.option ||'Option\' }}</span>
					<div class="form-text text-muted help-text">
						{{ i18n.help ||'Configuration options, will be available as key/value pairs in the templates\' }}
					</div>
				</th>
				<th class="config-row-value">{{ i18n.value ||'Value\' }}</th>
				<th class="actions">
					<div v-if="!readonly" class="btn act-add fa"
						:title="i18n.insert ||'Insert new entry (Ctrl+I)\'"
						:tabindex="tabindex"
						@click="add()" />
				</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="(entry, pos) in items" :key="pos" class="config-item">
				<td class="config-row-key">
					<input class="form-control" required
						:readonly="readonly"
						:tabindex="tabindex"
						:name="fname(\'key\', pos)"
						v-model="entry.key" />
					<div class="form-text text-muted help-text">{{ entry.label }}</div>
				</td>
				<td class="config-row-value">
					<textarea v-if="!entry.type || entry.type === 'string'" class="form-control config-type config-type-string" rows="1"
						:tabindex="tabindex"
						:readonly="readonly"
						:name="fname(\'val\', pos)"
						v-model="entry.val" />

					<div v-if="entry.type === 'list'">
						<input class="form-control" readonly
							:tabindex="tabindex"
							:name="fname(\'val\', pos)"
							:value="JSON.stringify(entry.val || [])"
							@click="$set(entry, 'show', true)">
						<AimeosListComponent v-if="entry.show"
							:tabindex="tabindex"
							:readonly="readonly"
							:list="entry.val || []"
							@update="$set(entry, 'val', $event); $set(entry, 'show', false)"
						></AimeosMapComponent>
					</div>

					<div v-if="entry.type === 'map'">
						<input class="form-control" readonly
							:tabindex="tabindex"
							:name="fname(\'val\', pos)"
							:value="JSON.stringify(entry.val || {})"
							@click="$set(entry, 'show', true)">
						<AimeosMapComponent v-if="entry.show"
							:tabindex="tabindex"
							:readonly="readonly"
							:map="entry.val || {}"
							@update="$set(entry, 'val', $event); $set(entry, 'show', false)"
						></AimeosMapComponent>
					</div>

					<select v-if="entry.type === 'boolean'" class="form-select config-type config-type-boolean"
						:tabindex="tabindex"
						:readonly="readonly"
						:name="fname(\'val\', pos)"
						v-model="entry.val">
						<option value=""></option>
						<option value="0">{{ i18n.no || 'no' }}</option>
						<option value="1">{{ i18n.yes || 'yes' }}</option>
					</select>

					<input v-if="entry.type === 'integer'" type="number" class="config-value form-control config-type config-type-integer"
						:tabindex="tabindex"
						:readonly="readonly"
						:name="fname(\'val\', pos)"
						v-model="entry.val">

					<input v-if="entry.type === 'number'" type="number" class="config-value form-control config-type config-type-number" step="0.01"
						:tabindex="tabindex"
						:readonly="readonly"
						:name="fname(\'val\', pos)"
						v-model="entry.val">

					<input v-if="entry.type === 'date'" type="date" class="config-value form-control config-type config-type-date"
						:tabindex="tabindex"
						:readonly="readonly"
						:name="fname(\'val\', pos)"
						v-model="entry.val">

					<input v-if="entry.type === 'datetime'" type="datetime-local" class="config-value form-control config-type config-type-date"
						:tabindex="tabindex"
						:readonly="readonly"
						:name="fname(\'val\', pos)"
						v-model="entry.val">

					<input v-if="entry.type === 'time'" type="time" class="config-value form-control config-type config-type-date"
						:tabindex="tabindex"
						:readonly="readonly"
						:name="fname(\'val\', pos)"
						v-model="entry.val">
				</td>
				<td class="actions">
					<div v-if="!readonly" class="btn act-delete fa"
						:title="i18n.delete ||'Delete this entry\'"
						:tabindex="tabindex"
						@click="remove(pos)" />
				</td>
			</tr>
		</tbody>
	</table>`,

	props: {
		'keys': {default: []},
		'i18n': {type: Object, default: () => ({})},
		'items': {type: Array, required: true},
		'name': {type: String, required: true},
		'readonly': {type: Boolean, default: true},
		'tabindex': {type: String, default: '1'}
	},

	watch: {
		keys: {
			immediate: true,
			handler() {
				if(Array.isArray(this.keys)) {
					this.update(this.keys)
				} else {
					this.keys.then(list => {
						this.update(list)
					})
				}
			}
		}
	},

	methods: {
		add() {
			let list = this.items;
			list.push({key: '', val: ''});
			this.$emit('update:items', list);
		},

		fname(key, idx) {
			return this.name.replace('_pos_', idx).replace('_key_', key);
		},

		remove(idx) {
			let list = [...this.items];
			list.splice(idx, 1);
			this.$emit('update:items', list);
		},

		update(list) {
			list.forEach(entry => {
				if(typeof entry === 'string' || entry instanceof String) {
					entry = {key: entry, type: 'string', label: '', val: ''}
				}

				const item = this.items.find(item => {
					return item.key === entry.key
				})

				if(item) {
					this.$set(item, 'type', entry.type)
					this.$set(item, 'label', entry.label)
				} else {
					this.items.push(entry)
				}
			})
		}
	}
});
