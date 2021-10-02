/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



Aimeos.Coupon = {

	init : function() {

		this.setupConfig();
		this.setupDecorator();
		this.setupProvider();

		Aimeos.Coupon.Code.init();
	},


	setupConfig : function() {

		const delegate = $(".aimeos .item-coupon .item-basic");
		const input = $(".block:not(.readonly) input.item-provider", delegate);

		if(input.length > 0 ) {
			Aimeos.Config.setup('coupon/config', input.val(), delegate);

			delegate.on("change", "input.item-provider", function(ev) {
				Aimeos.Config.setup('coupon/config', $(this).val(), ev.delegateTarget);
			});
		}
	},


	setupDecorator : function() {

		$(".aimeos .item-coupon").on("click", ".block:not(.readonly) .provider .dropdown .decorator-name", function() {

			var name = $(this).data("name");
			var input = $(this).closest(".provider").find('input.item-provider');

			if(input.val().indexOf(name) === -1) {
				input.val(input.val() + ',' + name);
				input.trigger('change');
			}
		});
	},


	setupProvider : function() {

		$(".aimeos .item-coupon").on("focus click", ".block:not(.readonly) input.item-provider", function() {
			const self = $(this);

			self.autocomplete({
				source: self.data("names").split(","),
				select: function(ev, ui) {
					self.val(ui.item.value);
					self.trigger('change');
				},
				minLength: 0,
				delay: 0
			});

			self.autocomplete("search", "");
		});
	}
};






Aimeos.Coupon.Code = {

	init: function() {

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
		'data': function() {
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


		beforeMount: function() {
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
			add: function() {
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


			columns: function(json, key) {
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


			css: function(key) {
				return 'coupon-code-' + key;
			},


			delete: function(resource, id, callback) {

				const self = this;
				self.waiting(true);

				Aimeos.options.done(function(response) {

					if(response.meta && response.meta.resources && response.meta.resources[resource] ) {

						const config = {};

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


			edit: function(idx) {
				if(this.siteid === this.items[idx]['coupon.code.siteid']) {
					this.$set(this.items[idx], 'edit', true);
				}
				return this;
			},


			find: function(ev, key, op) {
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


			fetch: function() {
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


			get: function(resource, args, callback) {

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
							'paramsSerializer': function(params) {
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


			remove: function(idx) {
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


			reset: function() {
				Object.assign(this.$data, {filter: {'base': {'==': {'coupon.code.parentid': this.parentid}}}});
				return this.fetch();
			},


			sort: function(key) {
				this.order = this.order === 'coupon.code.' + key ? '-' + 'coupon.code.' + key : 'coupon.code.' + key;
				return this.fetch();
			},


			sortclass: function(key) {
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


			value: function(key) {
				const op = Object.keys(this.filter['coupon.code.' + key] || {}).pop();
				return this.filter['coupon.code.' + key] && this.filter['coupon.code.' + key][op]['coupon.code.' + key] || '';
			},


			waiting: function(val) {
				this.loading = val;
				return this;
			}
		},


		watch: {
			checked: function() {
				for(let item of this.items) {
					this.$set(item, 'checked', this.checked);
				}
			},


			limit: function() {
				this.fetch();
			},


			offset: function() {
				this.fetch();
			}
		}
	}
};



$(function() {

	Aimeos.Coupon.init();
});
