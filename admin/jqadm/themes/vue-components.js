/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



Vue.component('auto-complete', {
	template: '<input type="text" class="form-control" v-bind:name="name" v-bind:value="value" v-bind:readonly="readonly" v-bind:required="required" v-bind:tabindex="tabindex" />',
	props: ['keys', 'name', 'value', 'readonly', 'required', 'tabindex'],

	methods: {
		create: function() {
			this.instance = $(this.$el).autocomplete({
				source: vm.keys || [],
				change: select,
				minLength: 0,
				delay: 0
			});

			this.instance.on('focus', function() {
				this.instance.autocomplete("search", "");
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



Vue.component('html-editor', {
	template: '\
		<textarea rows="6" class="form-control htmleditor" v-bind:id="id" v-bind:name="name" v-bind:value="value"\
			v-bind:placeholder="placeholder" v-bind:readonly="readonly" v-bind:tabindex="tabindex">\
		</textarea>',
	props: ['id', 'name', 'value', 'placeholder', 'readonly', 'tabindex'],

	beforeDestroy: function() {
		this.instance.destroy();
	},

	computed: {
		instance: function() {
			return CKEDITOR.instances[this.id];
		}
	},

	methods: {
		change: function() {
			this.$emit('input', this.instance.getData());
		},

		create: function() {
			CKEDITOR.replace(this.id, {toolbar: Aimeos.editorcfg, autoParagraph: false, entities: false});
			this.instance.setData(this.value);
			this.instance.on('change', this.change);
		},

		destroy: function() {
			try {
				if(this.instance) {
					this.instance.destroy();
				}
			} catch (e) {}
		},

		update: function(val) {
			if(this.instance && val != this.instance.getData()) {
				this.destroy(); this.create();
				this.instance.setData(val, { internal: false });
			}
		}
	},

	mounted: function() {
		this.create();
	},

	watch: {
		value: function(val) {
			this.update(val);
		}
	}
});



Vue.component('taxrates', {
	template: '\
		<div> \
			<input type="hidden" v-bind:name="name" v-bind:value="payload"> \
			<table> \
				<tr v-for="(val, type) in taxrates" v-bind:key="type"> \
					<td class="input-group"> \
						<input class="form-control item-taxrate" required="required" step="0.01" type="number" v-bind:placeholder="placeholder" \
							v-bind:readonly="readonly" v-bind:tabindex="tabindex" v-bind:value="val" v-on:input="update(type, $event.target.value)" /> \
						<div v-if="type" class="input-group-append"><span class="input-group-text">{{ type.toUpperCase() }}</span></div> \
					</td> \
					<td class="actions"> \
						<div v-if="!readonly && !type" class="dropdown"> \
							<button class="btn act-add fa dropdown-toggle" v-bind:tabindex="tabindex" \
								type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> \
							</button> \
							<div class="dropdown-menu dropdown-menu-right"> \
								<a v-for="(rate, code) in types" class="dropdown-item" href="#" v-on:click="add(code, rate)">{{ code.toUpperCase() }}</a> \
							</div> \
						</div> \
						<div v-else class="btn act-delete fa" v-on:click="remove(type)"></div> \
					</td> \
				</tr> \
			</table> \
		</div> \
	',
	props: ['name', 'placeholder', 'precision', 'readonly', 'tabindex', 'taxrates', 'types'],

	computed: {
		payload: function() {
			return JSON.stringify(this.taxrates);
		}
	},

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
