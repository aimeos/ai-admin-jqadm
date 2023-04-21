/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


Vue.component('property-table', {
	template: '<table class="item-property table table-default" > \
		<thead> \
			<tr> \
				<th colspan="3"> \
					<span class="help">{{ i18n.header || \'Properties\' }}</span> \
					<div class="form-text text-muted help-text">{{ i18n.help || \'Non-shared properties for the item\' }}</div> \
				</th> \
				<th class="actions"> \
					<div class="btn act-add fa" v-bind:tabindex="tabindex" v-on:click="add()" \
						v-bind:title="i18n.insert || \'Insert new entry (Ctrl+I)\'"> \
					</div> \
				</th> \
			</tr> \
		</thead> \
		<tbody> \
			<tr v-for="(propdata, propidx) in items" v-bind:key="propidx" v-bind:class="{readonly: readonly(propidx)}" v-bind:title="title(propidx)"> \
				<td class="property-type"> \
					<input type="hidden" v-model="propdata[domain + \'.property.id\']" v-bind:name="fname(\'id\', propidx)" /> \
					<select is="select-component" required class="form-select item-type" v-bind:tabindex="tabindex" \
						v-bind:name="fname(\'type\', propidx)" \
						v-bind:text="i18n.select || \'Please select\'" \
						v-bind:readonly="readonly(propidx)" \
						v-bind:items="types" \
						v-model="propdata[domain + \'.property.type\']" > \
					</select> \
				</td> \
				<td class="property-language"> \
					<select is="select-component" class="form-select item-languageid" v-bind:tabindex="tabindex" \
						v-bind:name="fname(\'languageid\', propidx)" \
						v-bind:all="i18n.all || \'All\'" \
						v-bind:readonly="readonly(propidx)" \
						v-bind:items="languages" \
						v-model="propdata[domain + \'.property.languageid\']" > \
					</select> \
				</td> \
				<td class="property-value"> \
					<input class="form-control item-value" type="text" required="required" v-bind:tabindex="tabindex" \
						v-bind:name="fname(\'value\', propidx)" \
						v-bind:placeholder="i18n.placeholder || \'Property value (required)\'" \
						v-bind:readonly="readonly(propidx)" \
						v-model="propdata[domain + \'.property.value\']" > \
				</td> \
				<td class="actions"> \
					<div v-if="can(\'delete\', propidx)" class="btn act-delete fa" v-bind:tabindex="tabindex" \
						v-bind:title="i18n.delete || \'Delete this entry\'" v-on:click.stop="remove(propidx)"> \
					</div> \
				</td> \
			</tr> \
		</tbody> \
	</table>',

	props: {
		'domain': {type: String, required: true},
		'i18n': {type: Object, default: () => ({})},
		'index': {type: Number, default: 0},
		'items': {type: Array, required: true},
		'name': {type: String, required: true},
		'siteid': {type: String, required: true},
		'languages': {type: Object, required: true},
		'tabindex': {type: String, default: '1'},
		'types': {type: Object, required: true}
	},

	methods: {
		add(data) {
			let entry = {};

			entry[this.domain + '.property.id'] = null;
			entry[this.domain + '.property.languageid'] = '';
			entry[this.domain + '.property.siteid'] = this.siteid;
			entry[this.domain + '.property.type'] = null;
			entry[this.domain + '.property.value'] = null;

			let list = this.items;
			list.push(Object.assign(entry, data));
			this.$emit('update:property', list);
		},

		can(action, idx) {
			return this.items[idx][this.domain + '.property.siteid'] && (new String(this.items[idx][this.domain + '.property.siteid'])).startsWith(this.siteid);
		},

		fname(key, idx) {
			return this.name.replace('_idx_', this.index).replace('_propidx_', idx).replace('_key_', this.domain + '.property.' + key);
		},

		readonly(idx) {
			return this.items[idx][this.domain + '.property.siteid'] != this.siteid;
		},

		remove(idx) {
			let list = this.items;
			list.splice(idx, 1);
			this.$emit('update:property', list);
		},

		title(idx) {
			return 'Site ID: ' + this.items[idx][this.domain + '.property.siteid'] + "\n"
				+ 'Editor: ' + this.items[idx][this.domain + '.property.editor'] + "\n"
				+ 'Created: ' + this.items[idx][this.domain + '.property.ctime'] + "\n"
				+ 'Modified: ' + this.items[idx][this.domain + '.property.mtime'];
		}
	}
});
