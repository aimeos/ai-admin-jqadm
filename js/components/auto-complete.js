/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */



Vue.component('auto-complete', {
	template: '<input type="text" class="form-control" v-bind:name="name" v-bind:value="value" v-bind:readonly="readonly" v-bind:required="required" v-bind:tabindex="tabindex" />',
	props: ['keys', 'name', 'value', 'readonly', 'required', 'tabindex'],

	methods: {
		create() {
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

		destroy() {
			if(this.instance) {
				this.instance.off().autocomplete('destroy');
				this.instance = null;
			}
		},

		select(ev, ui) {
			this.$emit('input', $(ev.currentTarget).val(), ui.item);
		}
	},

	mounted() {
		if(!this.readonly) {
			this.create();
		}
	},

	beforeDestroy() {
		this.destroy();
	}
});
