/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */



Aimeos.Coupon = {

	init() {
		Aimeos.components['coupon'] = new Vue({
			el: document.querySelector('.item-coupon #basic'),
			data: {
				item: null,
				cache: {},
				decorators: [],
				providers: [],
				siteid: null,
			},
			beforeMount() {
				this.Aimeos = Aimeos;
				this.decorators = JSON.parse(this.$el.dataset.decorators || '[]');
				this.providers = JSON.parse(this.$el.dataset.providers || '[]');
				this.item = JSON.parse(this.$el.dataset.item || '{}');
				this.siteid = this.$el.dataset.siteid;
			},
			mixins: [this.mixins]
		});

		Aimeos.Coupon.Code.init();
	},


	mixins: {
		methods: {
			can(action) {
				if(this.item['coupon.siteid']) {
					let allow = (new String(this.item['coupon.siteid'])).startsWith(this.siteid);

					switch(action) {
						case 'change': return allow;
					}
				}

				return false;
			},


			config(provider) {
				if(!provider) return []
				if(this.cache[provider]) return this.cache[provider]

				return this.cache[provider] = Aimeos.query(`query {
					getCouponConfig(provider: "` + String(provider).replace(/"/g, '\\"') + `") {
						code
						label
						type
					}
				}`).then(result => {
					return (result?.getCouponConfig || []).map(entry => {
						entry.key = entry.code
						return entry
					})
				})
			},


			decorate(name) {
				if(!(new String(this.item['coupon.provider'])).includes(name)) {
					this.item['coupon.provider'] = this.item['coupon.provider'] + ',' + name
				}
			},
		}
	}
};



Aimeos.Coupon.Code = {

	init() {
		const node = document.querySelector('.item-coupon .coupon-code-list');

		if(node) {
			Aimeos.components['coupon.code'] = new Vue({
				'el': node,
				'mixins': [Aimeos.Coupon.Code.mixins]
			});
		}

		Aimeos.lazy('.item-coupon .coupon-code-list', function() {
			Aimeos.components['coupon.code'] && Aimeos.components['coupon.code'].reset();
		});
	},


	mixins: {
		'data'() {
			return {
				'parentid': null,
				'siteid': '',
				'items': [],
				'fields': [],
				'filter': {},
				'offset': 0,
				'limit': 25,
				'total': 0,
				'order': '',
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
				if(!this.$el.dataset.siteid) {
					throw 'Missing "data-siteid" attribute';
				}
				if(!this.$el.dataset.parentid) {
					throw 'Missing "data-parentid" attribute';
				}

				this.siteid = this.$el.dataset.siteid;
				this.parentid = this.$el.dataset.parentid;
				this.order = 'coupon.code.id';

				const fieldkey = 'aimeos/jqadm/couponcode/fields';
				this.fields = this.columns(this.$el.dataset.fields || [], fieldkey);
			} catch(e) {
				console.log( '[Aimeos] Init coupon code list failed: ' + e);
			}
		},


		methods: {
			add() {
				const obj = {};

				obj['coupon.code.id'] = null;
				obj['coupon.code.siteid'] = this.siteid;
				obj['coupon.code.code'] = '';
				obj['coupon.code.count'] = 1;
				obj['coupon.code.datestart'] = null;
				obj['coupon.code.dateend'] = null;
				obj['coupon.code.ref'] = null;
				obj['edit'] = true;

				this.items.unshift(obj);
				return this;
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
				return 'coupon-code-' + key;
			},


			delete(resource, id, callback) {

				const self = this;
				self.waiting(true);

				Aimeos.options.done(function(response) {

					if(response.meta && response.meta.resources && response.meta.resources[resource] ) {

						let params = {};

						if(response.meta.prefix && response.meta.prefix) {
							params[response.meta.prefix] = {'id': id};
						} else {
							params = {'id': id};
						}

						if(response.meta.csrf) {
							params[response.meta.csrf.name] = response.meta.csrf.value;
						}

						let url = new URL(response.meta.resources[resource]);
						url.search = url.search ? url.search + '&' + window.param(params) : '?' + window.param(params);

						fetch(url, {
							method: "DELETE"
						}).then(function(response) {
							return response.json();
						}).then(function() {
							callback ? callback(response.data) : null;
						}).finally(function() {
							self.waiting(false);
						});
					}
				});

				return this;
			},


			edit(idx) {
				if(this.siteid === this.items[idx]['coupon.code.siteid']) {
					this.$set(this.items[idx], 'edit', true);
				}
				return this;
			},


			find(ev, key, op) {
				const value = ev.target ? ev.target.value : ev;
				if(value) {
					const expr = {};
					expr[op || '=='] = {};
					expr[op || '==']['coupon.code.' + key] = value;
					this.$set(this.filter, 'coupon.code.' + key, expr);
				} else {
					this.$delete(this.filter, 'coupon.code.' + key);
				}

				return this;
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

				this.get('coupon/code', args, function(data) {
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


			remove(idx) {
				const self = this;
				this.checked = false;

				if(idx !== undefined) {
					this.delete('coupon/code', this.items[idx]['coupon.code.id'], () => self.waiting(false));
					return this.items.splice(idx, 1);
				}

				this.items = this.items.filter(function(item) {
					if(item.checked) {
						self.delete('coupon/code', item['coupon.code.id']);
					}
					return !item.checked;
				});

				this.waiting(false);
				return this;
			},


			reset() {
				Object.assign(this.$data, {filter: {'base': {'==': {'coupon.code.parentid': this.parentid}}}});
				return this.fetch();
			},


			sort(key) {
				this.order = this.order === 'coupon.code.' + key ? '-' + 'coupon.code.' + key : 'coupon.code.' + key;
				return this.fetch();
			},


			sortclass(key) {
				return this.order === 'coupon.code.' + key ? 'sort-desc' : (this.order === '-' + 'coupon.code.' + key ? 'sort-asc' : '');
			},


			toggle(fields) {
				this.fields = fields;

				if(window.sessionStorage) {
					window.sessionStorage.setItem(
						'aimeos/jqadm/couponcode/fields',
						JSON.stringify(this.fields)
					);
				}

				return this.fetch();
			},


			value(key) {
				const op = Object.keys(this.filter['coupon.code.' + key] || {}).pop();
				return this.filter['coupon.code.' + key] && this.filter['coupon.code.' + key][op]['coupon.code.' + key] || '';
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



$(function() {
	Aimeos.Coupon.init();
});
