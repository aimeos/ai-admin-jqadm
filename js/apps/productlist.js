/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */



$(function() {
	Aimeos.ProductList.init();
});



Aimeos.ProductList = {

	init() {

		const node = document.querySelector('.item-product .productlist');

		if(node) {
			Aimeos.components['productlist'] = new Vue({
				'el': node,
				'mixins': [Aimeos.ProductList.mixins]
			});
		}

		Aimeos.lazy('.item-product .productlist', function() {
			Aimeos.components['productlist'] && Aimeos.components['productlist'].reset();
		});
	},


	mixins: {
		'data'() {
			return {
				'refid': null,
				'siteid': '',
				'resource': '',
				'domain': '',
				'items': [],
				'fields': [],
				'filter': {},
				'offset': 0,
				'limit': 25,
				'total': 0,
				'order': '',
				'types': {},
				'options': [],
				'colselect': false,
				'checked': false,
				'loading': true
			}
		},


		beforeMount() {
			this.Aimeos = Aimeos;
			try {
				if(!this.$el.dataset) {
					throw 'Missing "data" attributes';
				}
				if(!this.$el.dataset.domain) {
					throw 'Missing "data-domain" attribute';
				}
				if(!this.$el.dataset.types) {
					throw 'Missing "data-types" attribute';
				}
				if(!this.$el.dataset.siteid) {
					throw 'Missing "data-siteid" attribute';
				}
				if(!this.$el.dataset.refid) {
					throw 'Missing "data-refid" attribute';
				}
				if(!this.$el.dataset.resource) {
					throw 'Missing "data-resource" attribute';
				}

				this.siteid = this.$el.dataset.siteid;
				this.refid = this.$el.dataset.refid;
				this.domain = this.$el.dataset.domain;
				this.resource = this.$el.dataset.resource;
				this.types = JSON.parse(this.$el.dataset.types);
				this.order = this.prefix + 'position';

				const fieldkey = 'aimeos/jqadm/' + this.domain + this.resource.replace('/', '') + '/fields';
				this.fields = this.columns(this.$el.dataset.fields || [], fieldkey);
			} catch(e) {
				console.log( '[Aimeos] Init referenced product list failed: ' + e);
			}
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
				if(!this.items[idx][this.prefix + 'siteid']) {
					return false;
				}

				return (new String(this.items[idx][this.prefix + 'siteid'])).startsWith(this.siteid);
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

				Aimeos.options.done(function(response) {

					if(response.meta && response.meta.resources && response.meta.resources[resource] ) {

						const config = {'params': {}};

						if(response.meta.prefix && response.meta.prefix) {
							config['params'][response.meta.prefix] = {'id': id};
						} else {
							config['params'] = {'id': id};
						}

						axios.delete(response.meta.resources[resource], config).then(function(response) {
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
					this.$set(this.items[idx], 'edit', true);
				}
				return this;
			},


			find(ev, key, op) {
				const value = ev.target ? ev.target.value : ev;
				if(value) {
					const expr = {};
					expr[op || '=='] = {};
					expr[op || '=='][this.prefix + key] = value;
					this.$set(this.filter, this.prefix + key, expr);
				} else {
					this.$delete(this.filter, this.prefix + key);
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

				if(this.fields.includes(this.prefix + 'parentid')) {
					args.fields['product'] = ['product.id', 'product.code', 'product.label', 'product.status'];
				}
				args.fields[this.resource] = [self.prefix + 'id', self.prefix + 'siteid', self.prefix + 'editor', self.prefix + 'ctime', self.prefix + 'mtime', ...self.fields];

				this.get(self.resource, args, function(data) {
					self.total = data.total || 0;
					self.items = data.items || [];
				});

				return this;
			},


			get(resource, args, callback) {

				const self = this;
				self.waiting(true);

				Aimeos.options.done(function(response) {

					if(response.meta && response.meta.resources && response.meta.resources[resource] ) {

						if(args.fields) {
							const include = [];
							for(let key in args.fields) {
								args.fields[key] = args.fields[key].join(',');
								include.push(key);
							}
							args['include'] = include.join(',');
						}

						const config = {
							'paramsSerializer': (params) => {
								return jQuery.param(params); // workaround, Axios and QS fail on [==]
							},
							'params': {}
						};

						if(response.meta.prefix && response.meta.prefix) {
							config['params'][response.meta.prefix] = args;
						} else {
							config['params'] = args;
						}

						axios.get(response.meta.resources[resource], config).then(function(response) {
							const list = [];
							const included = {};

							(response.data.included || []).forEach(function(entry) {
								if(!included[entry.type]) {
									included[entry.type] = {};
								}
								included[entry.type][entry.id] = entry;
							});

							(response.data.data || []).forEach(function(entry) {
								for(let type in (entry.relationships || {})) {
									const relitem = entry.relationships[type]['data'] && entry.relationships[type]['data'][0] || null;
									if(relitem && relitem['id'] && included[type][relitem['id']]) {
										Object.assign(entry['attributes'], included[type][relitem['id']]['attributes'] || {});
									}
								}
								list.push(entry.attributes || {});
							});

							callback({
								total: response.data.meta ? response.data.meta.total || 0 : 0,
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


			suggest(input, loadfcn) {
				const self = this;
				const args = {
					'filter': {'||': [
						{'==': {}},
						{'=~': {}},
						{'=~': {}}
					]},
					'fields': {},
					'page': {'offset': 0, 'limit': 25},
					'sort': self.domain + '.label'
				};
				args['filter']['||'][0]['=='][self.domain + '.id'] = input;
				args['filter']['||'][1]['=~'][self.domain + '.code'] = input;
				args['filter']['||'][2]['=~'][self.domain + '.label'] = input;
				args['fields'][self.domain] = [self.domain + '.id', self.domain + '.code', self.domain + '.label'];

				try {
					loadfcn ? loadfcn(true) : null;

					this.get(self.domain, args, function(data) {
						self.options = [];
						(data.items || []).forEach(function(entry) {
							self.options.push({
								'id': entry[self.domain + '.id'],
								'label': entry[self.domain + '.id'] + ' - ' + entry[self.domain + '.label'] + ' (' + entry[self.domain + '.code'] + ')'
							});
						});
					});
				} finally {
					loadfcn ? loadfcn(false) : null;
				}
			},


			title(idx) {
				return 'Site ID: ' + this.items[idx][this.prefix + 'siteid'] + "\n"
					+ 'Editor: ' + this.items[idx][this.prefix + 'editor'] + "\n"
					+ 'Created: ' + this.items[idx][this.prefix + 'ctime'] + "\n"
					+ 'Modified: ' + this.items[idx][this.prefix + 'mtime'];
			},


			toggle(fields) {
				this.fields = fields;

				if(window.sessionStorage) {
					window.sessionStorage.setItem(
						'aimeos/jqadm/' + this.domain + this.resource.replace('/', '') + '/fields',
						JSON.stringify(this.fields)
					);
				}

				return this.fetch();
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
					this.$set(item, 'checked', this.checked);
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
