/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */



$(function() {
	Aimeos.ProductList.init();
});



Aimeos.ProductList = {

	init() {
		const node = document.querySelector('.item-product .productlist');

		if(node) {
			Aimeos.apps['productlist'] = Aimeos.app({
				'mixins': [Aimeos.ProductList.mixins]
			}, {...node.dataset || {}}).mount(node);
		}

		Aimeos.lazy('.item-product .productlist', function() {
			Aimeos.apps['productlist'] && Aimeos.apps['productlist'].reset();
		});
	},


	mixins: {
		props: {
			types: {type: String, required: true},
			fields: {type: String, required: true},
			domain: {type: String, required: true},
			siteid: {type: String, required: true},
			resource: {type: String, required: true},
			refid: {type: String, required: true},
		},

		data() {
			return {
				items: [],
				fieldlist: [],
				filter: {},
				offset: 0,
				limit: 25,
				total: 0,
				order: '',
				typelist: {},
				options: [],
				colselect: false,
				checked: false,
				loading: true
			}
		},


		beforeMount() {
			this.Aimeos = Aimeos;

			this.typelist = JSON.parse(this.types);
			this.order = this.prefix + 'position';

			const fieldkey = 'aimeos/jqadm/' + this.domain + this.resource.replace('/', '') + '/fields';
			this.fieldlist = this.columns(this.fields || [], fieldkey);
		},


		computed: {
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
				obj['edit'] = true;

				this.items.unshift(obj);
				return this;
			},


			can(action, idx) {
				return Aimeos.can(action, this.items[idx][this.prefix + 'siteid'] || null, this.siteid)
			},


			columns(json, key) {
				let list = [];
				try {
					if(window.sessionStorage) {
						list = JSON.parse(window.sessionStorage.getItem(key)) || [];
					}
					if(!list.length) {
						list = JSON.parse(json);
					}
				} catch(e) {
					console.log('[Aimeos] Failed to get list of columns: ' + e);
				}
				return list;
			},


			css(key) {
				return this.resource.replace('/', '-') + '-' + key;
			},


			delete(resource, id, callback) {

				const self = this;
				self.waiting(true);

				Aimeos.options.then(function(response) {

					if(response.meta && response.meta.resources && response.meta.resources[resource] ) {

						let url = response.meta.resources[resource] + (response.meta.resources[resource].includes('?') ? '&' : '?');
						const tname = response.meta.csrf.name;
						const tvalue = response.meta.csrf.value;

						if(response.meta.prefix && response.meta.prefix) {
							url += response.meta.prefix + '[id]=' + id + '&' + response.meta.prefix + '[' + tname + ']=' + tvalue;
						} else {
							url += 'id=' + id + '&' + tname + '=' + tvalue;
						}

						fetch(url, {
							'method': 'DELETE',
						}).then(function(response) {
							if(!response.ok) {
								throw Error(response.statusText);
							}
							return response.json();
						}).then(function(response) {
							callback ? callback(response.data) : null;
						}).then(function() {
							self.waiting(false);
						});
					}
				});

				return this;
			},


			edit(idx) {
				if(this.siteid === this.items[idx][this.prefix + 'siteid']) {
					this.items[idx]['edit'] = true;
				}
				return this;
			},


			find(ev, key, op) {
				const value = ev.target ? ev.target.value : ev;
				if(value) {
					const expr = {};
					expr[op || '=='] = {};
					expr[op || '=='][this.prefix + key] = value;
					this.filter[this.prefix + key] = expr;
				} else {
					delete this.filter[this.prefix + key];
				}
				return this.fetch();
			},


			fetch() {
				const self = this;
				const args = {
					'filter': {'&&': []},
					'fields': {},
					'page': {'offset': self.offset, 'limit': self.limit},
					'sort': self.order
				};

				for(let key in self.filter) {
					args['filter']['&&'].push(self.filter[key]);
				}

				if(this.fieldlist.includes(this.prefix + 'parentid')) {
					args.fields['product'] = ['product.id', 'product.code', 'product.label', 'product.status'];
				}
				args.fields[this.resource] = [self.prefix + 'id', self.prefix + 'siteid', self.prefix + 'editor', self.prefix + 'ctime', self.prefix + 'mtime', ...self.fieldlist];

				this.get(self.resource, args, function(data) {
					self.total = data.total || 0;
					self.items = data.items || [];
				});

				return this;
			},


			get(resource, args, callback) {

				const self = this;
				self.waiting(true);

				Aimeos.options.then(function(response) {

					if(response.meta && response.meta.resources && response.meta.resources[resource] ) {

						if(args.fields) {
							const include = [];
							for(let key in args.fields) {
								args.fields[key] = args.fields[key].join(',');
								if(key !== resource) {
									include.push(key);
								}
							}
							args['include'] = include.join(',');
						}

						let params = {};
						let url = response.meta.resources[resource] + (response.meta.resources[resource].includes('?') ? '&' : '?');

						if(response.meta.prefix && response.meta.prefix) {
							params[response.meta.prefix] = args;
						} else {
							params = args;
						}

						fetch(url + serialize(params)).then(function(response) {
							if(!response.ok) {
								throw new Error(response.statusText);
							}
							return response.json();
						}).then(function(response) {
							const list = [];
							const included = {};

							(response.included || []).forEach(function(entry) {
								if(!included[entry.type]) {
									included[entry.type] = {};
								}
								included[entry.type][entry.id] = entry;
							});

							(response.data || []).forEach(function(entry) {
								for(let type in (entry.relationships || {})) {
									const relitem = entry.relationships[type]['data'] && entry.relationships[type]['data'][0] || null;
									if(relitem && relitem['id'] && included[type][relitem['id']]) {
										Object.assign(entry['attributes'], included[type][relitem['id']]['attributes'] || {});
									}
								}
								list.push(entry.attributes || {});
							});

							callback({
								total: response.meta ? response.meta.total || 0 : 0,
								items: list
							});

						}).then(function() {
							self.waiting(false);
						});
					}
				});

				return this;
			},


			label(idx) {
				let str = '';

				if(this.items[idx]) {
					if(this.items[idx][this.prefix + 'parentid']) {
						str += this.items[idx][this.prefix + 'parentid'];
					}

					if(this.items[idx]['product.label']) {
						str += ' - ' + this.items[idx]['product.label'];
					}

					if(this.items[idx]['product.code']) {
						str += ' (' + this.items[idx]['product.code'] + ')';
					}
				}

				return str;
			},


			remove(idx) {
				const self = this;
				this.checked = false;

				if(idx !== undefined) {
					this.delete(this.resource, this.items[idx][this.prefix + 'id'], () => self.waiting(false));
					return this.items.splice(idx, 1);
				}

				this.items = this.items.filter(function(item) {
					if(item.checked) {
						self.delete(self.resource, item[self.prefix + 'id']);
					}
					return !item.checked;
				});

				return this.waiting(false);
			},


			reset() {
				const domain = {};
				const refid = {};

				domain[this.prefix + 'domain'] = this.domain;
				refid[this.prefix + 'refid'] = this.refid;

				Object.assign(this.$data, {filter: {'base': {'&&': [{'==': refid}, {'==': domain}]}}});
				return this.fetch();
			},


			sort(key) {
				this.order = this.order === this.prefix + key ? '-' + this.prefix + key : this.prefix + key;
				return this.fetch();
			},


			sortclass(key) {
				return this.order === this.prefix + key ? 'sort-desc' : (this.order === '-' + this.prefix + key ? 'sort-asc' : '');
			},


			status(map, val) {
				return map[val] || val;
			},

			stringify(value) {
				return typeof value === 'object' || typeof value === 'array' ? JSON.stringify(value) : value;
			},


			suggest(input) {
				const filter = {
					'&&': [
						{'>': {'product.status': 0}},
						{'||': [
							{'=~': {'product.label': input}},
							{'=~': {'product.code': input}},
							{'==': {'product.id': input}}
						]}
					]
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
						return {'product.lists.parentid': item.id, 'product.label': item.label + ' (' + item.code + ')'}
					})
				})
			},


			title(idx) {
				if(this.items[idx][this.prefix + 'siteid']) {
					return 'Site ID: ' + this.items[idx][this.prefix + 'siteid'] + "\n"
						+ 'Editor: ' + this.items[idx][this.prefix + 'editor'] + "\n"
						+ 'Created: ' + this.items[idx][this.prefix + 'ctime'] + "\n"
						+ 'Modified: ' + this.items[idx][this.prefix + 'mtime'];
				}
				return ''
			},


			toggle(fields) {
				this.fieldlist = fields;

				if(window.sessionStorage) {
					window.sessionStorage.setItem(
						'aimeos/jqadm/' + this.domain + this.resource.replace('/', '') + '/fields',
						JSON.stringify(this.fieldlist)
					);
				}

				return this.fetch();
			},


			use(idx, ev) {
				this.items[idx][this.prefix + 'refid'] = ev['product.lists.parentid'];
				this.items[idx]['product.lists.parentid'] = ev['product.lists.parentid'];
				this.items[idx]['product.label'] = ev['product.label'];
			},


			value(key) {
				const op = Object.keys(this.filter[this.prefix + key] || {}).pop();
				return this.filter[this.prefix + key] && this.filter[this.prefix + key][op][this.prefix + key] || '';
			},


			waiting(val) {
				this.loading = val;
				return this;
			}
		},


		watch: {
			checked() {
				for(let item of this.items) {
					item['checked'] = this.checked;
				}
			},


			limit() {
				this.fetch();
			},


			offset() {
				this.fetch();
			}
		}
	}
};
