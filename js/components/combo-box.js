/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


Vue.component('combo-box', {
	template: '\
		<select required class="template" v-bind:name="name" v-bind:readonly="readonly" v-bind:tabindex="tabindex">\
			<option v-bind:value="value">{{ label || value }}</option>\
		</select>\
	',
	props: ['index', 'name', 'value', 'label', 'readonly', 'tabindex', 'getfcn'],

	beforeDestroy() {
		this.destroy();
	},

	methods: {
		create() {
			this.instance = $(this.$el).combobox({
				getfcn: this.getfcn(this.index),
				select: this.select
			});
			return this.instance;
		},

		destroy() {
			if(this.instance) {
				this.instance.off().combobox('destroy');
				this.instance = null;
			}
			return this;
		},

		select(ev, ui) {
			this.$emit('select',  {index: this.index, value: ui.item[0].value, label: ui.item[0].label});
		}
	},

	mounted() {
		if(!this.readonly) {
			this.create();
		}
	},

	updated() {
		if(this.readonly && this.instance) {
			return this.destroy();
		}

		if(!this.readonly && !this.instance) {
			this.create().combobox('instance').input[0].value = this.label;
		}
	}
});
