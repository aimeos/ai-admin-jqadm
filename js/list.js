/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2024
 */



Aimeos.List = {

	instance : null,


	init() {
		const node = document.querySelector(".list-view");

		if(node) {
			this.instance = Aimeos.app({
				mixins: [this.mixins]
			}, {...node.dataset || {}}).mount(node);
		}
	},


	mixins : {
		props: {
			items: {type: String, default: '{}'},
			filter: {type: String, default: '{}'},
			domain: {type: String, default: ''},
			siteid: {type: String, default: ''},
		},
		data() {
			return {
				all: false,
				batch: false,
				columns: false,
				dialog: false,
				entries: {},
				filters: {},
				search: false,
				states: {}
			}
		},
		beforeMount() {
			this.Aimeos = Aimeos;
			this.entries = JSON.parse(this.items);
			this.filters = JSON.parse(this.filter);
			this.filters['val'] = this.filters['val'] || {}
		},
		computed: {
			prefix() {
				return this.domain ? this.domain.replace(/\//g, '.') + '.' : ''
			},

			selected() {
				let count = 0;

				for(const key in this.entries) {
					if(this.entries[key].checked) {
						count++;
					}
				}

				return count;
			},

			unconfirmed() {
				let list = {};
				for(const key in this.entries) {
					if(this.entries[key].checked) {
						list[key] = this.entries[key][this.prefix + 'label'] || this.entries[key][this.prefix + 'code'] || this.entries[key][this.prefix + 'id'];
					}
				}

				return list;
			}
		},
		methods: {
			askDelete(id, ev) {
				if(id) {
					this.clear(false);
					this.entries[id]['checked'] = true;
				}

				this.deleteUrl = ev.target.href;
				this.dialog = true;
			},

			checked(id) {
				return this.entries[id] && this.entries[id].checked;
			},

			confirmDelete(val) {
				if(val) {
					if(this.$refs.form && this.deleteUrl) {
						this.$refs.form.action = this.deleteUrl;
						this.$refs.form.submit();
					} else {
						console.log('[Aimeos] Missing form reference or deleteUrl');
					}
				}

				if(Object.keys(this.unconfirmed).length === 1) {
					this.clear(false);
				}

				this.dialog = false;
			},

			clear(val) {
				this.all = val;
				for(const key in this.entries) {
					if([this.siteid, ''].includes(this.entries[key][this.prefix + 'siteid'])) {
						this.entries[key]['checked'] = val;
					}
				};
			},

			readonly(id) {
				return !(this.entries[id] && this.entries[id][this.prefix + 'siteid'] == this.siteid);
			},

			reset() {
				this.filters['val'] = {}
			},

			setState(key) {
				this.states[key] = !this.states[key];
			},

			state(key) {
				return !(this.states[key] || false);
			},

			toggle(id) {
				this.entries[id]['checked'] = !this.entries[id].checked;
			},

			toggleAll() {
				this.clear(this.all = !this.all);
			},

			update(idx, val) {
				this.filters['val'][idx] = val;
			},

			upload() {
				this.$refs.import.click()
			},

			value(idx) {
				return this.filters['val'] && this.filters['val'][idx] ? this.filters['val'][idx] : null;
			}
		}
	}
}



document.addEventListener("DOMContentLoaded", function() {
    Aimeos.List.init();
});