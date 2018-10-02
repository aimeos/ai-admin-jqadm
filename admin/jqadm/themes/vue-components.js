/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



Vue.component('auto-complete', {
	template: '<input type="text" class="form-control" v-bind:name="name" v-bind:value="value" v-bind:readonly="readonly" v-bind:required="required" v-bind:tabindex="tabindex" />',
	props: ['keys', 'name', 'value', 'readonly', 'required', 'tabindex'],

	mounted: function() {
		var vm = this;

		if(!this.readonly) {
			$(this.$el).autocomplete({
				change: function(event, ui) {
					vm.$emit('input', $(event.currentTarget).val(), ui.item);
				},
				source: vm.keys || [],
				minLength: 0,
				delay: 0
			});

			$(this.$el).on('focus', function(event) {
				$(this).autocomplete("search", "");
			});
		}
	},

	beforeDestroy: function() {
		if(!this.readonly) {
			$(this.$el).off().autocomplete('destroy');
		}
	}
});



Vue.component('combo-box', {
	template: '\
		<select required class="template" v-bind:name="name" v-bind:readonly="readonly" v-bind:tabindex="tabindex">\
			<option v-bind:value="value">{{ label || value }}</option>\
		</select>\
	',
	props: ['index', 'name', 'value', 'label', 'readonly', 'tabindex', 'getfcn'],

	mounted: function() {
		var vm = this;

		if(!this.readonly) {
			var box = $(this.$el).combobox({
				getfcn: this.getfcn(),
				select: function(event, ui) {
					vm.$emit('select',  {index: vm.index, value: ui.item[0].value, label: ui.item[0].label});
				}
			});
		}
	},

	beforeDestroy: function() {
		if(!this.readonly) {
			$(this.$el).off().combobox('destroy');
		}
	},

	updated : function() {
		if(!this.readonly) {
			$(this.$el).combobox('instance').input[0].value = this.label;
		}
	}
});



Vue.component('html-editor', {
	template: '<textarea rows="6" class="form-control htmleditor" v-bind:name="name" v-bind:value="value" v-bind:placeholder="placeholder" v-bind:readonly="readonly" v-bind:tabindex="tabindex"></textarea>',
	props: ['name', 'value', 'placeholder', 'readonly', 'tabindex'],

	mounted: function() {
		var vm = this;
		var el = $(this.$el);

		el.ckeditor({toolbar: Aimeos.editorcfg, autoParagraph: false, entities: false});

		el.ckeditor().editor.on('change', function() {
			vm.$emit('input', el.ckeditor().editor.getData());
		});
	}
});
