/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


Vue.component('column-select', {
	template: '#column-select',
	props: {
		'titles': {type: Object, required: true},
		'fields': {type: Array, required: true},
		'name': {type: String, required: true},
		'show': {type: Boolean, default: false},
		'submit': {type: Boolean, default: true},
		'tabindex': {type: String, default: '1'}
	},
	data() {
		return {
			active: {}
		}
	},
	beforeMount() {
		for(const key of this.fields) {
			this.active[key] = true;
		}
	},
	methods: {
		checked(key) {
			return this.active[key] ? true : false;
		},

		toggle(key) {
			if(this.active[key]) {
				delete this.active[key];
			} else {
				this.active[key] = true;
			}
		},

		update(ev) {
			this.$emit('submit', Object.keys(this.active));
			if(!this.submit) {
				ev.preventDefault();
			}
		}
	}
});
