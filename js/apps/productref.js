/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */


Aimeos.ProductRef = {

	init() {
		const node = document.querySelector('.item-product .productref-list');

		if(node) {
			Aimeos.apps['productref'] = Aimeos.app({
				'mixins': [Aimeos.ProductRef.mixins]
			}, {...node.dataset || {}}).mount(node);
		}
	},


	mixins: {
		props: {
			data: {type: String, required: true},
			types: {type: String, required: true},
			status: {type: String, required: true},
			siteid: {type: String, required: true},
			fields: {type: String, required: true},
			parentid: {type: String, required: true},
			resource: {type: String, required: true},
		},


		data() {
			return {
				items: [],
				filter: {},
				order: '',
				fieldlist: [],
				statuslist: {},
				typelist: {},
				colselect: false,
				checked: false,
				loading: true
			}
		},


		beforeMount() {
			this.Aimeos = Aimeos;
			this.items = JSON.parse(this.data) || [];
			this.typelist = JSON.parse(this.types) || {};
			this.statuslist = JSON.parse(this.status) || {};
			this.fieldlist = this.columns(null, JSON.parse(this.fields) || []);
		},


		computed: {
			domain() {
				return this.resource.slice(0, this.resource.indexOf('/'));
			},

			prefix() {
				return this.resource.replace('/', '.') + '.';
			}
		},


		methods: {
			add() {
				const obj = {};

				obj[this.prefix + 'id'] = null;
				obj[this.prefix + 'siteid'] = this.siteid;
				obj[this.prefix + 'position'] = 0;
				obj[this.prefix + 'status'] = 1;
				obj[this.prefix + 'type'] = 'default';
				obj[this.prefix + 'config'] = {};
				obj[this.prefix + 'datestart'] = null;
				obj[this.prefix + 'dateend'] = null;
				obj[this.prefix + 'refid'] = null;
				obj['_edit'] = true;

				this.items.unshift(obj);
				return this;
			},


			can(action, item) {
				return Aimeos.can(action, item[this.prefix + 'siteid'] || null, this.siteid)
			},


			columns(list, deflist = []) {
				if(window.sessionStorage) {
					if(list) {
						window.sessionStorage.setItem('aimeos/jqadm/' + this.domain + '/product/fields', JSON.stringify(list));
					} else {
						list = JSON.parse(window.sessionStorage.getItem('aimeos/jqadm/' + this.domain + '/product/fields')) || deflist;
					}
				}
				return list;
			},


			css(key) {
				return this.resource.replace('/', '-') + '-' + key;
			},


			edit(item) {
				if(this.siteid === item[this.prefix + 'siteid']) {
					item['_edit'] = true;
				}
			},


			label(item) {
				let str = '';

				if(item['product.label']) {
					str += item['product.label'];
				}

				if(item['product.code']) {
					str += ' (' + item['product.code'] + ')';
				}

				if(!str.length && item[this.prefix + 'refid']) {
					str += item[this.prefix + 'refid'];
				}

				return str;
			},


			remove(item) {
				if(item) {
					item['_delete'] = true
					return
				}

				for(const item of this.items) {
					if(item['_checked']) {
						item['_delete'] = true
					}
				}
			},


			reset() {
				for(const item of this.items) {
					item._hidden = false
				}
				this.filter = {}
			},


			search(ev, key) {
				const value = ev.target ? ev.target.value : ev;
				this.filter[this.prefix + key] = value

				for(const item of this.items) {
					item._hidden = false

					for(const name in this.filter) {
						if(item._hidden || this.filter[name] === '') {
							continue
						} else if(typeof item[name] === 'string') {
							item._hidden = !item[name].match(this.filter[name])
						} else if(typeof item[name] === 'object') {
							item._hidden = true
						} else {
							item._hidden = item[name] != this.filter[name]
						}
					}
				}
			},


			sort(key) {
				this.order = this.order === this.prefix + key ? '-' + this.prefix + key : this.prefix + key;

				if(this.order[0] === '-') {
					this.items.sort((a, b) => {
						if (a[this.prefix + key] < b[this.prefix + key]) {
							return 1;
						}
						if (a[this.prefix + key] > b[this.prefix + key]) {
							return -1;
						}
						return 0;
					})
				} else {
					this.items.sort((a, b) => {
						if (a[this.prefix + key] < b[this.prefix + key]) {
							return -1;
						}
						if (a[this.prefix + key] > b[this.prefix + key]) {
							return 1;
						}
						return 0;
					})
				}
			},


			sortclass(key) {
				return this.order === this.prefix + key ? 'sort-desc' : (this.order === '-' + this.prefix + key ? 'sort-asc' : '');
			},


			suggest(input) {
				const filter = {
					'&&': [
						{'>': {'product.status': 0}}
					]
				}

				if(input) {
					filter['&&'].push({
						'||': [
							{'=~': {'product.label': input}},
							{'=~': {'product.code': input}},
							{'==': {'product.id': input}}
						]
					});
				}

				return Aimeos.query(`query {
					searchProducts(filter: ` + JSON.stringify(JSON.stringify(filter)) + `, sort: ["product.label"]) {
						items {
							id
							code
							label
						}
					}
				  }
				`).then(result => {
					return (result?.searchProducts?.items || []).map(item => {
						const entry = {'product.label': item.label + ' (' + item.code + ')'}
						entry[this.prefix + 'refid'] = item.id;
						return entry
					})
				})
			},


			title(item) {
				if(item[this.prefix + 'siteid']) {
					return 'Site ID: ' + item[this.prefix + 'siteid'] + "\n"
						+ 'Editor: ' + item[this.prefix + 'editor'] + "\n"
						+ 'Created: ' + item[this.prefix + 'ctime'] + "\n"
						+ 'Modified: ' + item[this.prefix + 'mtime'];
				}
				return ''
			},


			use(item, ev) {
				item[this.prefix + 'refid'] = ev[this.prefix + 'refid'];
				item['product.label'] = ev['product.label'];
			},


			value(key) {
				return this.filter[this.prefix + key] || ''
			}
		},


		watch: {
			checked() {
				for(let item of this.items) {
					item['_checked'] = this.checked;
				}
			}
		}
	}
};


document.addEventListener("DOMContentLoaded", function() {
	Aimeos.ProductRef.init();
});
