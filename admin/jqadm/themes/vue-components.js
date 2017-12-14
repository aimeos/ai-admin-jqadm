/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */


Vue.component('auto-complete', {
	template: '<input type="text" class="form-control" v-bind:name="name" v-bind:value="value" v-bind:readonly="readonly" v-bind:tabindex="tabindex" />',
	props: ['keys', 'name', 'value', 'readonly', 'tabindex'],

	mounted: function() {
		var vm = this;

		$(this.$el).autocomplete({
			change: function(event) {
				vm.$emit('input', $(event.currentTarget).val());
			},
			source: vm.keys || [],
			minLength: 0,
			delay: 0
		});

		$(this.$el).on('focus', function(event) {
			$(this).autocomplete("search", "");
		});
	},

	beforeDestroy: function() {
		$(this.$el).off().autocomplete('destroy');
	}
});


Vue.component('html-editor', {
	template: '<textarea rows="10" class="form-control htmleditor" v-bind:name="name" v-bind:value="value" v-bind:placeholder="placeholder" v-bind:readonly="readonly" v-bind:tabindex="tabindex"></textarea>',
	props: ['name', 'value', 'placeholder', 'readonly', 'tabindex'],

	mounted: function() {
		$(this.$el).ckeditor({toolbar: Aimeos.editorcfg});
	}
});
