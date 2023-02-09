/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


Vue.component('html-editor', {
	template: `<input type="hidden" v-bind:id="id" v-bind:name="name" v-bind:value="value" />`,
	props: ['config', 'editor', 'id', 'name', 'value', 'placeholder', 'readonly', 'tabindex'],

	beforeDestroy() {
		if(this.instance) {
			this.instance.destroy();
			this.instance = null;
		}
	},

	data() {
		return {
			instance: null,
			content: null
		};
	},

	methods: {
		debounce(func, delay) {
			return function() {
				const context = this;
				const args = arguments;

				clearTimeout(this.timer);
				this.timer = setTimeout(() => func.apply(context, args), delay);
			};
		}
	},

	mounted() {
		const config = Object.assign({}, this.config);

		if(this.value) {
			config.initialData = this.value;
		}

		this.editor.create(this.$el, config).then(editor => {
			this.instance = editor;

			if(this.readonly) {
				editor.enableReadOnlyMode('html-editor');
			} else {
				editor.disableReadOnlyMode('html-editor');
			}

			const event = this.debounce(ev => {
				this.content = editor.getData();
        			const tagMatches = this.content.match(/<p>/g);
				if(tagMatches && tagMatches.length === 1 && this.content.startsWith('<p>') && this.content.endsWith('</p>')) {
					this.content = this.content.replace(/^<p>/, '').replace(/<\/p>$/, '');
				}
				this.$emit('input', this.content, ev, editor);
			}, 300);

			editor.model.document.on('change:data', event);
		} ).catch(err => {
			console.error(err);
		} );
	},

	watch: {
		value(val, oldval) {
			if(val !== oldval && val !== this.content) {
				this.instance.setData(val);
			}
		},

		readonly(val) {
			if(val) {
				this.instance.enableReadOnlyMode('html-editor');
			} else {
				this.instance.disableReadOnlyMode('html-editor');
			}
		}
	}
});
