/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



Vue.component('auto-complete', {
	template: '<input type="text" class="form-control" v-bind:name="name" v-bind:value="value" v-bind:readonly="readonly" v-bind:required="required" v-bind:tabindex="tabindex" />',
	props: ['keys', 'name', 'value', 'readonly', 'required', 'tabindex'],

	methods: {
		create: function() {
			var instance = $(this.$el).autocomplete({
				source: this.keys || [],
				change: this.select,
				minLength: 0,
				delay: 0
			});

			this.instance = instance;
			this.instance.on('focus', function() {
				instance.autocomplete("search", "");
			});
		},

		destroy: function() {
			if(this.instance) {
				this.instance.off().autocomplete('destroy');
				this.instance = null;
			}
		},

		select: function(ev, ui) {
			this.$emit('input', $(ev.currentTarget).val(), ui.item);
		}
	},

	mounted: function() {
		if(!this.readonly) {
			this.create();
		}
	},

	beforeDestroy: function() {
		this.destroy();
	}
});



Vue.component('combo-box', {
	template: '\
		<select required class="template" v-bind:name="name" v-bind:readonly="readonly" v-bind:tabindex="tabindex">\
			<option v-bind:value="value">{{ label || value }}</option>\
		</select>\
	',
	props: ['index', 'name', 'value', 'label', 'readonly', 'tabindex', 'getfcn'],

	beforeDestroy: function() {
		this.destroy();
	},

	methods: {
		create: function() {
			this.instance = $(this.$el).combobox({
				getfcn: this.getfcn(this.index),
				select: this.select
			});
			return this.instance;
		},

		destroy: function() {
			if(this.instance) {
				this.instance.off().combobox('destroy');
				this.instance = null;
			}
			return this;
		},

		select: function(ev, ui) {
			this.$emit('select',  {index: this.index, value: ui.item[0].value, label: ui.item[0].label});
		}
	},

	mounted: function() {
		if(!this.readonly) {
			this.create();
		}
	},

	updated : function() {
		if(this.readonly && this.instance) {
			return this.destroy();
		}

		if(!this.readonly && !this.instance) {
			this.create().combobox('instance').input[0].value = this.label;
		}
	}
});



Vue.component('config-table', {
	props: {
		'index': {required: false, default: ''},
		'items': {type: Array, required: true},
		'readonly': {type: Boolean, default: true}
	},

	methods: {
		add : function() {
			let list = this.items;
			list.push({key: '', val: ''});
			this.$emit('update:config', list);
		},

		remove: function(idx) {
			let list = this.items;
			list.splice(idx, 1);
			this.$emit('update:config', list);
		}
	}
});



Vue.component('html-editor', {
	template: '\
		<textarea rows="6" class="form-control htmleditor" v-bind:id="id" v-bind:name="name" v-bind:value="value"\
			v-bind:placeholder="placeholder" v-bind:readonly="readonly" v-bind:tabindex="tabindex">\
		</textarea>',
	props: ['id', 'name', 'value', 'placeholder', 'readonly', 'tabindex'],

	beforeDestroy: function() {
		if(this.instance) {
			this.instance.destroy();
			this.instance = null;
		}
	},

	data: function() {
		return {
			instance: null,
			text: null
		};
	},

	methods: {
		change: function() {
			this.text = this.instance.getData();
			this.$emit('input', this.text);
		},
	},

	mounted: function() {
		this.instance = CKEDITOR.replace(this.id, {
			extraAllowedContent: Aimeos.editortags,
			toolbar: Aimeos.editorcfg,
			extraPlugins: 'divarea',
			initialData: this.value,
			readOnly: this.readonly,
			protectedSource: [/\n/g],
			autoParagraph: false,
			entities: false
		});
		this.instance.on('change', this.change);
	},

	watch: {
		value: function(val, oldval) {
			if(val !== oldval && val !== this.text ) {
				this.instance.setData(val);
			}
		}
	}
});



Vue.component('property-table', {
	props: {
		'domain': {type: String, required: true},
		'index': {type: Number, default: 0},
		'items': {type: Array, required: true},
		'siteid': {type: String, required: true},
		'languages': {type: Object, required: true},
		'types': {type: Object, required: true}
	},

	methods: {
		add: function(data) {
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

		readonly: function(idx) {
			return this.items[idx][this.domain + '.property.siteid'] != this.siteid;
		},

		remove: function(idx) {
			let list = this.items;
			list.splice(idx, 1);
			this.$emit('update:property', list);
		}
	}
});



Vue.component('taxrates', {
	template: '\
		<div> \
			<table> \
				<tr v-for="(val, type) in taxrates" v-bind:key="type"> \
					<td class="input-group"> \
						<input class="form-control item-taxrate" required="required" step="0.01" type="number" v-bind:placeholder="placeholder" \
							v-bind:readonly="readonly" v-bind:tabindex="tabindex" v-bind:name="name + \'[\' + type + \']\'" \
							v-bind:value="val" v-on:input="update(type, $event.target.value)" /> \
						<div v-if="type != 0" class="input-group-append"><span class="input-group-text">{{ type.toUpperCase() }}</span></div> \
					</td> \
					<td class="actions"> \
						<div v-if="!readonly && type == 0 && types.length" class="dropdown"> \
							<button class="btn act-add fa" v-bind:tabindex="tabindex" \
								type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> \
							</button> \
							<div class="dropdown-menu dropdown-menu-right"> \
								<a v-for="(rate, code) in types" class="dropdown-item" href="#" v-on:click="add(code, rate)">{{ code.toUpperCase() }}</a> \
							</div> \
						</div> \
						<div v-if="!readonly && type != 0" class="btn act-delete fa" v-on:click="remove(type)"></div> \
					</td> \
				</tr> \
			</table> \
		</div> \
	',
	props: ['name', 'placeholder', 'readonly', 'tabindex', 'taxrates', 'types'],

	created: function() {
		delete this.types[''];
	},

	methods: {
		add: function(type, val) {
			this.$set(this.taxrates, type, val);
		},

		remove: function(type) {
			this.$delete(this.taxrates, type);
		},

		update: function(type, val) {
			this.$set(this.taxrates, type, val);
		}
	}
});



Vue.component('select-component', {
	template: '\
		<select v-on:change="$emit(\'input\', $event.target.value)"> \
			<option v-if="text" value="">{{ text }}</option> \
			<option v-if="value && !items[value]" v-bind:value="value">{{ value }}</option> \
			<option v-if="all" v-bind:value="null" v-bind:selected="value === null">{{ all }}</option> \
			<option v-for="(label, key) in items" v-bind:key="key" v-bind:value="key" v-bind:selected="key === value"> \
				{{ label || key }} \
			</option> \
		</select> \
	',
	props: {
		'all': {type: String, required: false},
		'items': {type: Object, required: true},
		'text': {type: String, required: false, default: ''},
		'value': {required: true}
	}
});
