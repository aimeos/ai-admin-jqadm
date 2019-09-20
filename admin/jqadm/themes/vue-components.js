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
