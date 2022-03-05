/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2022
 */


Vue.component('config-table', {
	template: '<table class="item-config table"> \
		<thead> \
			<tr> \
				<th class="config-row-key"> \
					<span class="help">{{ i18n.option || \'Option\' }}</span> \
					<div class="form-text text-muted help-text"> \
						{{ i18n.help || \'Category specific configuration options, will be available as key/value pairs in the templates\' }} \
					</div> \
				</th> \
				<th class="config-row-value">{{ i18n.value || \'Value\' }}</th> \
				<th class="actions"> \
					<div v-if="!readonly" class="btn act-add fa" v-bind:tabindex="tabindex" v-on:click="add()" \
						title="i18n.insert || \'Insert new entry (Ctrl+I)\'"></div> \
				</th> \
			</tr> \
		</thead> \
		<tbody> \
			<tr v-for="(entry, pos) in items" v-bind:key="pos" class="config-item"> \
				<td class="config-row-key"> \
					<input is="auto-complete" required class="form-control" v-bind:readonly="readonly" \
						v-bind:tabindex="tabindex" v-bind:keys="keys" \
						v-bind:name="fname(\'key\', pos)" \
						v-model="entry.key" /> \
				</td> \
				<td class="config-row-value"> \
					<input class="form-control" v-bind:tabindex="tabindex" v-bind:readonly="readonly" \
						v-bind:name="fname(\'val\', pos)" \
						v-model="entry.val" /> \
				</td> \
				<td class="actions"> \
					<div v-if="!readonly" class="btn act-delete fa" v-bind:tabindex="tabindex" v-on:click="remove(pos)" \
						title="i18n.delete || \'Delete this entry\'"></div> \
				</td> \
			</tr> \
		</tbody> \
	</table>',
	props: {
		'i18n': {type: Object, default: {}},
		'items': {type: Array, required: true},
		'keys': {type: Array, default: []},
		'name': {type: String, required: true},
		'readonly': {type: Boolean, default: true},
		'tabindex': {type: String, default: '1'}
	},

	methods: {
		add: function() {
			let list = this.items;
			list.push({key: '', val: ''});
			this.$emit('update:config', list);
		},

		fname: function(key, idx) {
			return this.name.replace('_pos_', idx).replace('_key_', key);
		},

		remove: function(idx) {
			let list = [...this.items];
			list.splice(idx, 1);
			this.$emit('update:config', list);
		}
	}
});
