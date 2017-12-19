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



Vue.component('combo-box', {
	template: '\
		<select v-bind:name="name" v-bind:readonly="readonly" v-bind:tabindex="tabindex">\
			<option v-bind:value="value">{{ label || value }}</option>\
		</select>\
	',
	props: ['name', 'value', 'label', 'readonly', 'tabindex', 'getfcn'],

	mounted: function() {
		var vm = this;

		var box = $(this.$el).combobox({
			getfcn: this.getfcn(),
			select: function(event, ui) {
				$(vm.$el).val(ui.item[0].value);
			}
		});
	},

	beforeDestroy: function() {
		$(this.$el).off().combobox('destroy');
	}
});



Vue.component('html-editor', {
	template: '<textarea rows="10" class="form-control htmleditor" v-bind:name="name" v-bind:value="value" v-bind:placeholder="placeholder" v-bind:readonly="readonly" v-bind:tabindex="tabindex"></textarea>',
	props: ['name', 'value', 'placeholder', 'readonly', 'tabindex'],

	mounted: function() {
		$(this.$el).ckeditor({toolbar: Aimeos.editorcfg});
	}
});
