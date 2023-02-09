/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


Vue.component('input-map', {
	template: '\
		<div> \
			<input type="hidden" v-bind:name="name" v-bind:value="JSON.stringify(value)" /> \
			<table class="table config-table" > \
				<tr v-for="(entry, idx) in list" v-bind:key="idx" class="config-item"> \
					<td v-if="editable" class="config-item-key"> \
						<input v-on:input="update(idx, \'key\', $event.target.value)" v-bind:value="entry.key" v-bind:tabindex="tabindex" /> \
					</td> \
					<td v-else class="config-item-key">{{ entry.key }}</td> \
					<td v-if="editable" class="config-item-value"> \
						<input v-on:input="update(idx, \'val\', $event.target.value)" v-bind:value="toString(entry.val)" v-bind:tabindex="tabindex" /> \
					</td> \
					<td v-else class="config-item-value">{{ entry.val }}</td> \
					<td v-if="editable" class="action"> \
						<a v-on:click="remove(idx)" class="btn act-delete fa" href="#" v-bind:tabindex="tabindex"></a> \
					</td> \
				</tr> \
				<tr v-if="editable" class="config-item"> \
					<td></td> \
					<td></td> \
					<td class="action"> \
						<a v-on:click="add()" class="btn act-add fa" href="#" v-bind:tabindex="tabindex"></a> \
					</td> \
				</tr> \
			</table> \
		</div> \
	',

	props: {
		'name': {type: String, required: true},
		'value': {type: Object, required: true},
		'editable': {type: Boolean, default: false},
		'tabindex': {type: String, default: '1'}
	},

	data: function () {
		return {
			list: []
		}
	},

	created() {
		this.list = this.toList(this.value);
	},

	methods: {
		add() {
			this.list.push({key: '', val: ''});
		},

		remove(idx) {
			this.list.splice(idx, 1);
		},

		toList(obj) {
			let list = [];
			for(let key in obj) {
				list.push({"key": key, "val": obj[key]});
			}
			return list;
		},

		toObject(list) {
			let obj = {};
			for(let entry of list) {
				obj[entry.key] = entry.val;
			}
			return obj;
		},

		toString(value) {
			return typeof value === 'object' || typeof value === 'array' ? JSON.stringify(value) : value;
		},

		update(idx, key, val) {
			this.$set(this.list[idx], key, val);
			this.$emit('input', this.toObject(this.list));
		}
	},

	watch: {
		value() {
			this.list = this.toList(this.value);
		}
	}
});
