/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


Vue.component('translations', {
	template: `
		<div>
			<input type="hidden" v-bind:name="name" v-bind:value="JSON.stringify(value)" />
			<table class="table translations" v-on:keydown="keyboard">
				<thead>
					<tr>
						<th colspan="2" class="head">{{ i18n.header || 'Translations' }}</th>
						<th class="action">
							<a v-if="!readonly" v-on:click="add()" class="btn act-add fa" href="#" v-bind:tabindex="tabindex" v-bind:title="i18n.insert || 'Insert new entry (Ctrl+I)'"></a>
						</th>
					</tr>
				</thead>
				<tr v-for="(entry, idx) in list" v-bind:key="idx" class="line">
					<td>
                        <select class="form-select" v-bind:readonly="readonly" v-bind:tabindex="tabindex" required
                            v-on:change="update(idx, \'key\', $event.target.value)">
							<option code="">{{ i18n.select || 'Please select' }}</option>
                            <option v-for="(name, code) in langs" v-bind:value="code" v-bind:key="code" v-bind:selected="entry.key == code">
                                {{ name }}
                            </option>
                        </select>
					</td>

					<td v-if="!readonly">
						<input class="form-control" v-on:input="update(idx, \'val\', $event.target.value)" v-bind:value="entry.val" v-bind:tabindex="tabindex" required />
					</td>
					<td v-else>{{ entry.val }}</td> \

					<td v-if="!readonly" class="action">
						<a v-on:click="remove(idx)" class="btn act-delete fa" href="#" v-bind:tabindex="tabindex" v-bind:title="i18n.delete || 'Delete this entry'"></a>
					</td>
				</tr>
			</table>
		</div>
	`,

	props: {
        'i18n': {type: Object, required: true},
        'langs': {type: Object, required: true},
		'name': {type: String, required: true},
		'value': {type: Object, required: true},
		'readonly': {type: Boolean, default: true},
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

		keyboard(ev) {
			if(ev.key === 'i' && ev.ctrlKey === true) {
				this.add()
			}
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

		update(idx, key, val) {
			this.$set(this.list[idx], key, val);
		}
	},

	watch: {
		list: {
			deep: true,
			handler: function() {
				this.value = this.toObject(this.list)
			}
		}
	}
});
