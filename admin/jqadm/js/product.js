/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



Aimeos.Product = {

	init : function() {

		Aimeos.Product.Bundle.init();
		Aimeos.Product.Download.init();
		Aimeos.Product.Selection.init();
		Aimeos.Product.Order.init();

		this.components();
		this.dataset();
	},


	components : function() {

		Aimeos.components['characteristic/attribute'] =  new Vue({
			'el': '.item-characteristic-attribute .attribute-list',
			'data': {
				'items': $(".item-characteristic-attribute .attribute-list").data("items"),
				'keys': $(".item-characteristic-attribute .attribute-list").data("keys"),
				'prefix': $(".item-characteristic-attribute .attribute-list").data("prefix"),
				'siteid': $(".item-characteristic-attribute .attribute-list").data("siteid")
			},
			'mixins': [Aimeos.Product.Attribute.mixins.bind(this)()]
		});

		Aimeos.components['characteristic/hidden'] = new Vue({
			'el': '.item-characteristic-hidden .attribute-list',
			'data': {
				'items': $(".item-characteristic-hidden .attribute-list").data("items"),
				'keys': $(".item-characteristic-hidden .attribute-list").data("keys"),
				'prefix': $(".item-characteristic-hidden .attribute-list").data("prefix"),
				'siteid': $(".item-characteristic-hidden .attribute-list").data("siteid")
			},
			'mixins': [Aimeos.Product.Attribute.mixins.bind(this)()]
		});

		Aimeos.components['characteristic/variant'] = new Vue({
			'el': '.item-characteristic-variant .attribute-list',
			'data': {
				'items': $(".item-characteristic-variant .attribute-list").data("items"),
				'keys': $(".item-characteristic-variant .attribute-list").data("keys"),
				'prefix': $(".item-characteristic-variant .attribute-list").data("prefix"),
				'siteid': $(".item-characteristic-variant .attribute-list").data("siteid")
			},
			'mixins': [Aimeos.Product.Attribute.mixins.bind(this)()]
		});


		Aimeos.components['option/config'] = new Vue({
			'el': '.item-option-config .attribute-list',
			'data': {
				'items': $(".item-option-config .attribute-list").data("items"),
				'keys': $(".item-option-config .attribute-list").data("keys"),
				'prefix': $(".item-option-config .attribute-list").data("prefix"),
				'siteid': $(".item-option-config .attribute-list").data("siteid")
			},
			'mixins': [Aimeos.Product.Attribute.mixins.bind(this)()]
		});

		Aimeos.components['option/custom'] = new Vue({
			'el': '.item-option-custom .attribute-list',
			'data': {
				'items': $(".item-option-custom .attribute-list").data("items"),
				'keys': $(".item-option-custom .attribute-list").data("keys"),
				'prefix': $(".item-option-custom .attribute-list").data("prefix"),
				'siteid': $(".item-option-custom .attribute-list").data("siteid")
			},
			'mixins': [Aimeos.Product.Attribute.mixins.bind(this)()]
		});

		Aimeos.components['catalog/default'] = new Vue({
			'el': '.item-category .catalog-default .category-list',
			'data': {
				'items': $(".item-category .catalog-default .category-list").data("items"),
				'keys': $(".item-category .catalog-default .category-list").data("keys"),
				'listtype': $(".item-category .catalog-default .category-list").data("listtype"),
				'siteid': $(".item-category .catalog-default .category-list").data("siteid")
			},
			'mixins': [Aimeos.Product.Catalog.mixins.bind(this)()]
		});

		Aimeos.components['catalog/promotion'] = new Vue({
			'el': '.item-category .catalog-promotion .category-list',
			'data': {
				'items': $(".item-category .catalog-promotion .category-list").data("items"),
				'keys': $(".item-category .catalog-promotion .category-list").data("keys"),
				'listtype': $(".item-category .catalog-promotion .category-list").data("listtype"),
				'siteid': $(".item-category .catalog-promotion .category-list").data("siteid")
			},
			'mixins': [Aimeos.Product.Catalog.mixins.bind(this)()]
		});

		Aimeos.components['supplier/default'] = new Vue({
			'el': '.item-supplier .supplier-default .supplier-list',
			'data': {
				'items': $(".item-supplier .supplier-default .supplier-list").data("items"),
				'keys': $(".item-supplier .supplier-default .supplier-list").data("keys"),
				'listtype': $(".item-supplier .supplier-default .supplier-list").data("listtype"),
				'siteid': $(".item-supplier .supplier-default .supplier-list").data("siteid")
			},
			'mixins': [Aimeos.Product.Supplier.mixins.bind(this)()]
		});

		Aimeos.components['supplier/promotion'] = new Vue({
			'el': '.item-supplier .supplier-promotion .supplier-list',
			'data': {
				'items': $(".item-supplier .supplier-promotion .supplier-list").data("items"),
				'keys': $(".item-supplier .supplier-promotion .supplier-list").data("keys"),
				'listtype': $(".item-supplier .supplier-promotion .supplier-list").data("listtype"),
				'siteid': $(".item-supplier .supplier-promotion .supplier-list").data("siteid")
			},
			'mixins': [Aimeos.Product.Supplier.mixins.bind(this)()]
		});

		Aimeos.components['related/bought'] = new Vue({
			'el': '.item-related-bought .product-list',
			'data': {
				'items': $(".item-related-bought .product-list").data("items"),
				'keys': $(".item-related-bought .product-list").data("keys"),
				'prefix': $(".item-related-bought .product-list").data("prefix"),
				'siteid': $(".item-related-bought .product-list").data("siteid")
			},
			'mixins': [Aimeos.Product.Product.mixins.bind(this)()]
		});

		Aimeos.components['related/suggest'] = new Vue({
			'el': '.item-related-suggest .product-list',
			'data': {
				'items': $(".item-related-suggest .product-list").data("items"),
				'keys': $(".item-related-suggest .product-list").data("keys"),
				'prefix': $(".item-related-suggest .product-list").data("prefix"),
				'siteid': $(".item-related-suggest .product-list").data("siteid")
			},
			'mixins': [Aimeos.Product.Product.mixins.bind(this)()]
		});

		Aimeos.components['bundle'] = new Vue({
			'el': '.item-bundle .product-list',
			'data': {
				'items': $(".item-bundle .product-list").data("items"),
				'keys': $(".item-bundle .product-list").data("keys"),
				'prefix': $(".item-bundle .product-list").data("prefix"),
				'siteid': $(".item-bundle .product-list").data("siteid")
			},
			'mixins': [Aimeos.Product.Product.mixins.bind(this)()]
		});

		Aimeos.components['selection'] = new Vue({
			'el': '#item-selection-group',
			'data': {
				'items': $("#item-selection-group").data("items"),
				'keys': $("#item-selection-group").data("keys"),
				'siteid': $("#item-selection-group").data("siteid")
			},
			'mixins': [Aimeos.Product.Selection.mixins.bind(this)()]
		});

		Aimeos.components['stock'] = new Vue({
			'el': '.item-stock .stock-list',
			'data': {
				'items': $(".item-stock .stock-list").data("items"),
				'keys': $(".item-stock .stock-list").data("keys"),
				'siteid': $(".item-stock .stock-list").data("siteid"),
				'numtypes': $(".item-stock .stock-list").data("numtypes")
			},
			'mixins': [Aimeos.Product.Stock.mixins.bind(this)()]
		});

		Aimeos.components['subscription'] = new Vue({
			'el': '.item-subscription .subscription-list',
			'data': {
				'items': $(".item-subscription .subscription-list").data("items"),
				'keys': $(".item-subscription .subscription-list").data("keys"),
				'siteid': $(".item-subscription .subscription-list").data("siteid")

			},
			'mixins': [Aimeos.Product.Subscription.mixins.bind(this)()]
		});
	},


	dataset : function() {

		$(".item-basic .item-set").on("change", function() {
			var config = $("option:selected", this).data("config");

			for(var name in config) {
				if(Aimeos.components[name]) {
					for(var key in config[name]) {
						if(Aimeos.components[name]) {
							Aimeos.components[name].add(config[name][key]);
						}
					}
				}
			}
		});
	}
};



Aimeos.Product.Attribute = {

	mixins: function() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {
				checkSite : function(key, idx) {
					return this.items[idx][key] && this.items[idx][key] != this.siteid;
				},


				add : function(data) {

					var idx = (this.items || []).length;
					this.$set(this.items, idx, {});

					for(var key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], this.prefix + 'siteid', this.siteid);
				},


				remove : function(idx) {
					this.items.splice(idx, 1);
				},


				getItems : function(idx) {

					var self = this;

					return function(request, response, element) {

						var type = self.items[idx] && self.items[idx]['attribute.type'] || null;
						var criteria = type ? {'==': {'attribute.type': type}} : {};

						Aimeos.getOptions(request, response, element, 'attribute', 'attribute.label', 'attribute.label', criteria);
					};
				},


				/**
				 * @deprecated 2020.01 Use item['attribute.label'] instead
				 */
				getLabel : function(idx) {

					var label = this.items[idx]['attribute.label'];

					if(this.items[idx]['attribute.type']) {
						label += ' (' + this.items[idx]['attribute.type'] + ')';
					}

					return label;
				},


				getTypeItems : function() {

					var criteria = {'>': {'attribute.type.status': 0}};

					return function(request, response, element) {
						Aimeos.getOptions(request, response, element, 'attribute/type', 'attribute.type.code', 'attribute.type.code', criteria);
					};
				},


				update : function(ev) {

					this.$set(this.items[ev.index], this.prefix + 'id', '');
					this.$set(this.items[ev.index], this.prefix + 'siteid', this.siteid);
					this.$set(this.items[ev.index], this.prefix + 'refid', ev.value);
					this.$set(this.items[ev.index], 'attribute.label', ev.label);

					var ids = [];

					for(idx in this.items) {
						this.items[idx]['css'] = '';

						if(ids.indexOf(this.items[idx]['product.lists.refid']) !== -1) {
							this.items[idx]['css'] = 'is-invalid';
						}

						ids.push(this.items[idx]['product.lists.refid']);
					}
				},


				updateType : function(ev) {

					this.$set(this.items[ev.index], this.prefix + 'id', '');
					this.$set(this.items[ev.index], this.prefix + 'refid', '');
					this.$set(this.items[ev.index], this.prefix + 'siteid', this.siteid);
					this.$set(this.items[ev.index], 'attribute.type', ev.label);
					this.$set(this.items[ev.index], 'attribute.label', '');
				}
			}
		}
	}
};


Aimeos.Product.Bundle = {

	init : function()  {

		var tab = $(".item-navbar .bundle");
		$(".item-basic .item-type").val() === 'bundle' ? tab.show() : tab.hide();

		$(".item-basic .item-type").on("change", function() {
			$("option:selected", this).val() === 'bundle' ? tab.show() : tab.hide();
		});
	}
};


Aimeos.Product.Catalog = {

	mixins : function() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {
				checkSite : function(idx) {
					return this.items[idx]['catalog.lists.siteid'] && this.items[idx]['catalog.lists.siteid'] != this.siteid;
				},


				add : function(data) {

					var idx = (this.items || []).length;
					this.$set(this.items, idx, {});

					for(var key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], 'catalog.lists.siteid', this.siteid);
					this.$set(this.items[idx], 'catalog.lists.type', this.listtype);
				},


				remove : function(idx) {
					this.items.splice(idx, 1);
				},


				getItems : function() {

					return function(request, response, element) {

						var labelFcn = function(attr) {
							return attr['catalog.label'] + ' (' + attr['catalog.code'] + ')';
						}

						Aimeos.getOptions(request, response, element, 'catalog', 'catalog.label', 'catalog.label', null, labelFcn);
					}
				},


				getLabel : function(idx) {

					var label = this.items[idx]['catalog.label'];

					if(this.items[idx]['catalog.code']) {
						label += ' (' + this.items[idx]['catalog.code'] + ')';
					}

					return label;
				},


				update : function(ev) {

					this.$set(this.items[ev.index], 'catalog.lists.id', '');
					this.$set(this.items[ev.index], 'catalog.lists.type', this.listtype);
					this.$set(this.items[ev.index], 'catalog.lists.siteid', this.siteid);
					this.$set(this.items[ev.index], 'catalog.lists.refid', '');
					this.$set(this.items[ev.index], 'catalog.label', ev.label);
					this.$set(this.items[ev.index], 'catalog.id', ev.value);
					this.$set(this.items[ev.index], 'catalog.code', '');

					var ids = [];

					for(idx in this.items) {

						if(this.items[idx]['catalog.lists.type'] != this.listtype) {
							continue;
						}

						this.items[idx]['css'] = '';

						if(ids.indexOf(this.items[idx]['catalog.id']) !== -1) {
							this.items[idx]['css'] = 'is-invalid';
						}

						ids.push(this.items[idx]['catalog.id']);
					}
				}
			}
		};
	}
};



Aimeos.Product.Download = {

	init : function() {

		$(".item-download").on("change", ".fileupload", function(ev) {
			$(this.files).each( function(idx, file) {
				$("input.item-label", ev.delegateTarget).val(file.name);
				$(".custom-file-label", ev.delegateTarget).text(file.name);
			});
		});
	}
};



Aimeos.Product.Order = {
	init : function() {

		if(!document.querySelector('.item-order .order-list')) {
			return;
		}

		this.instance = new Vue({
			'el': '.item-order .order-list',
			'data': {
				'id': null,
				'items': [],
				'fields': [],
				'filter': {},
				'included': {},
				'offset': 0,
				'limit': 25,
				'total': 0,
				'sort': '-order.base.id',
			},
			beforeMount: function() {
				this.Aimeos = Aimeos;

				if(this.$el.dataset && this.$el.dataset.id) {
					this.id = this.$el.dataset.id;
				}
				this.filter['order.base.product.productid'] = {'==':{'order.base.product.productid': this.id}};

				let list = [];
				try {
					if(this.$el.dataset && this.$el.dataset.fields) {
						if(window.sessionStorage) {
							list = JSON.parse(window.sessionStorage.getItem('aimeos/jqadm/productorder/fields')) || [];
						}
						if(!list.length) {
							list = JSON.parse(this.$el.dataset.fields);
						}
					}
				} catch(e) {}
				this.fields = list;
			},
			computed : {
				first : function() {
					return this.offset > 0 ? 0 : null;
				},
				prev : function() {
					return this.offset - this.limit >= 0 ? this.offset - this.limit : null;
				},
				next : function() {
					return this.offset + this.limit < this.total ? this.offset + this.limit : null;
				},
				last : function() {
					return Math.floor((this.total - 1) / this.limit) * this.limit > this.offset ? Math.floor((this.total - 1) / this.limit ) * this.limit : null;
				},
				current : function() {
					return Math.floor( this.offset / this.limit ) + 1;
				},
				pages : function() {
					return this.total != 0 ? Math.ceil(this.total / this.limit) : 1;
				}
			},
			methods : {
				value : function(key) {
					let op = Object.keys(this.filter[key] || {}).pop();
					return this.filter[key] && this.filter[key][op][key] || '';
				},
				submit : function() {
					this.fetch();
				},
				reset : function() {
					Object.assign(this.$data, {filter: {'order.base.product.productid': {'==':{'order.base.product.productid': this.id}}}});
				},
				find : function(ev, key, op) {
					if(ev.target.value !== '') {
						let expr = {};
						expr[op || '=='] = {};
						expr[op || '=='][key] = ev.target.value;
						this.$set(this.filter, key, expr);
					} else {
						this.$delete(this.filter, key);
					}
				},
				fetch : function() {
					let self = this;

					Aimeos.options.done(function(response) {

						if(response.meta && response.meta.resources && response.meta.resources['order/base'] ) {

							let args = {
								'filter': {'&&': []},
								'fields': {
									'order/base': self.fields.join(',') + ',order.base.customerid',
									'order/base/address': self.fields.join(',') + ',order.base.address.type',
								},
								'include': 'order/base/address',
								'page': {
									'offset': self.offset,
									'limit': self.limit
								},
								'sort': self.sort
							};

							for(let key in self.filter) {
								args['filter']['&&'].push(self.filter[key]);
							}

							let config = {
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

							axios.get(response.meta.resources['order/base'], config).then(response => {

								if(response.data) {
									self.total = response.data.meta && response.data.meta.total || 0;
									self.items = response.data.data || [];
								}

								(response.data.included || []).forEach(function(item) {
									if(!self.included[item.type]) {
										self.$set(self.included, item.type, {});
									}
									self.$set(self.included[item.type], item.id, item);
								});

							}).catch(function(error) {
								console.log('Error: ', error.message);
								if(error.response && error.response.data && error.response.data.errors) {
									error.response.data.errors.forEach(function(elem) {
										console.log(elem.title);
									});
								}
							});
						}
					});
				},
				related : function(item, type, key) {
					let id = null;
					let self = this;

					(item['relationships'] && item['relationships'][type] && item['relationships'][type]['data'] || []).forEach(function(addr) {
						if(addr.data && addr.data.id && self.included[type] && self.included[type][addr.data.id]
							&& self.included[type][addr.data.id]['attributes']['order.base.address.type'] === 'payment'
						) {
							id = addr.data.id;
						}
					});

					return this.included[type] && this.included[type][id] ? this.included[type][id]['attributes'][key] : '';
				},
				pagecnt : function(str) {
					return sprintf(str, this.current, this.pages);
				},
				orderby : function(key) {
					this.sort = this.sort === key ? '-' + key : key;
				},
				sortclass : function(key) {
					return this.sort === key ? 'sort-desc' : (this.sort === '-' + key ? 'sort-asc' : '');
				},
				toggleField : function(key) {
					let idx = this.fields.indexOf(key);
					idx !== -1 ? this.fields.splice(idx, 1) : this.fields.push(key);

					if(window.sessionStorage) {
						window.sessionStorage.setItem('aimeos/jqadm/productorder/fields', JSON.stringify(this.fields));
					}
				}
			},
			watch: {
				fields : function() {
					this.fetch();
				},
				filter : {
					handler: function() {
						this.fetch();
					},
					deep: true
				},
				limit : function() {
					this.fetch();
				},
				offset : function() {
					this.fetch();
				},
				sort : function() {
					this.fetch();
				}
			}
		});
	}
};



Aimeos.Product.Product = {

	mixins : function() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {
				checkSite : function(key, idx) {
					return this.items[idx][key] && this.items[idx][key] != this.siteid;
				},


				add : function(data) {

					var idx = (this.items || []).length;
					this.$set(this.items, idx, {});

					for(var key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], this.prefix + 'siteid', this.siteid);
				},


				remove : function(idx) {
					this.items.splice(idx, 1);
				},


				getItems : function() {

					return function(request, response, element) {

						var labelFcn = function(attr) {
							return attr['product.label'] + ' (' + attr['product.code'] + ')';
						}
						Aimeos.getOptions(request, response, element, 'product', 'product.label', 'product.label', null, labelFcn);
					}
				},


				getLabel : function(idx) {

					var label = this.items[idx]['product.label'];

					if(this.items[idx]['product.code']) {
						label += ' (' + this.items[idx]['product.code'] + ')';
					}

					return label;
				},


				update : function(ev) {

					this.$set(this.items[ev.index], this.prefix + 'id', '');
					this.$set(this.items[ev.index], this.prefix + 'siteid', this.siteid);
					this.$set(this.items[ev.index], this.prefix + 'refid', ev.value);
					this.$set(this.items[ev.index], 'product.label', ev.label);
					this.$set(this.items[ev.index], 'product.code', '');

					var ids = [];

					for(idx in this.items) {
						this.items[idx]['css'] = '';

						if(ids.indexOf(this.items[idx]['product.lists.refid']) !== -1) {
							this.items[idx]['css'] = 'is-invalid';
						}

						ids.push(this.items[idx]['product.lists.refid']);
					}
				}
			}
		};
	}
};



Aimeos.Product.Selection = {

	init : function() {

		var tab = $(".item-navbar .selection");
		['group', 'select'].includes($(".item-basic .item-type").val()) ? tab.show() : tab.hide();

		$(".item-product").on("change", ".item-basic .item-type", function() {
			['group', 'select'].includes($("option:selected", this).val()) ? tab.show() : tab.hide();
		});
	},


	mixins : function() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {

				checkSite : function(key, idx, attridx) {

					if(attridx) {
						return this.items[idx]['attr'][attridx][key] && this.items[idx]['attr'][attridx][key] != this.siteid;
					}

					return this.items[idx][key] && this.items[idx][key] != this.siteid;
				},


				add : function(data) {

					var idx = this.items.length;
					this.$set(this.items, idx, {});

					for(var key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], 'product.lists.siteid', this.siteid);
					this.$set(this.items[idx], 'product.siteid', this.siteid);
					this.$set(this.items[idx], 'product.status', 1);
					this.$set(this.items[idx], 'product.id', '');
					this.$set(this.items[idx], 'attr', []);
				},


				copyItem : function(idx) {

					var len = this.items.length;
					this.$set(this.items, len, {});

					for(var key in this.items[idx]) {
						this.$set(this.items[len], key, this.items[idx][key]);
					}

					this.$set(this.items[len], 'attr', []);
					this.$set(this.items[len], 'product.id', '');
					this.$set(this.items[len], 'product.code', this.items[idx]['product.code'] + '_copy');
					this.$set(this.items[len], 'product.label', this.items[idx]['product.label'] + '_copy');
					this.$set(this.items[len], 'product.lists.siteid', this.siteid);
					this.$set(this.items[len], 'product.lists.id', '');

					for(var attridx in this.items[idx]['attr']) {
						this.$set(this.items[len]['attr'], attridx, {});

						for(var key in this.items[idx]['attr'][attridx]) {
							this.$set(this.items[len]['attr'][attridx], key, this.items[idx]['attr'][attridx][key]);
						}

						this.$set(this.items[len]['attr'][attridx], 'product.lists.siteid', this.siteid);
						this.$set(this.items[len]['attr'][attridx], 'product.lists.id', '');
					}
				},


				remove : function(idx) {
					this.items.splice(idx, 1);
				},


				getCss : function(idx) {
					return ( idx !== 0 && this.items[idx]['product.id'] &&
						this.items[idx]['attr'] && this.items[idx]['attr'].length ? 'collapsed' : 'show' );
				},


				getLabel : function(idx) {

					var label = this.items[idx]['product.label'];

					if(this.items[idx]['product.status'] < 1) {
						label = '<s>' + label + '</s>';
					}

					return label;
				},


				getArticles : function(request, response) {

					Aimeos.options.done(function(data) {

						if(!data.meta.resources['product']) {
							return;
						}

						var params = {}, param = {};

						param['filter'] = {'&&': [{'=~': {'product.code': request.term}}, {'==': {'product.type': 'default'}}]};
						param['fields'] = {'product': 'product.id,product.code,product.label'};
						param['include'] = 'attribute';
						param['sort'] = 'product.code';

						if( data.meta && data.meta.prefix ) {
							params[data.meta.prefix] = param;
						} else {
							params = param;
						}

						$.ajax({
							dataType: "json",
							url: data.meta.resources['product'],
							data: params,
							success: function(result) {
								var map = {};

								(result.included || []).forEach(function(item) {
									map[item.id] = item.attributes;
								});

								response( (result.data || []).map(function(obj) {
									var list = [];

									(obj.relationships.attribute && obj.relationships.attribute.data || []).forEach(function(item) {
										if(item.attributes && item.attributes['product.lists.type'] === 'variant') {
											list.push(Object.assign({}, item.attributes, map[item.id] || {}));
										}
									});

									return {
										id: obj.id || null,
										code: obj.attributes['product.code'] || null,
										label: obj.attributes['product.label'] || null,
										stock: false,
										attr: list
									};
								}));
							}
						});
					});
				},


				updateProductItem : function(idx, ev, item) {

					if(item) {
						this.$set(this.items[idx], 'product.id', item.id);
						this.$set(this.items[idx], 'product.code', item.code);
						this.$set(this.items[idx], 'product.label', item.label);
						this.$set(this.items[idx], 'stock', item.stock);
						this.$set(this.items[idx], 'attr', item.attr);
					}
				},


				addAttributeItem : function(idx) {

					if(!this.items[idx]['attr']) {
						this.$set(this.items[idx], 'attr', []);
					}

					var len = this.items[idx]['attr'].length;

					if(!this.items[idx]['attr'][len]) {
						this.$set(this.items[idx]['attr'], len, {});
					}

					var keys = ['product.lists.id', 'product.lists.refid', 'attribute.label'];

					for(key in keys) {
						key = keys[key]; this.$set(this.items[idx]['attr'][len], key, '');
					}

					this.$set(this.items[idx]['attr'][len], 'product.lists.siteid', this.siteid);
				},


				getAttributeData : function(idx) {

					if(this.items[idx] && this.items[idx]['attr']) {
						return this.items[idx]['attr'];
					}

					return [];
				},


				getAttributeItems : function() {

					return function(request, response, element) {

						var labelFcn = function(attr) {
							return attr['attribute.label'] + ' (' + attr['attribute.type'] + ')';
						}
						Aimeos.getOptions(request, response, element, 'attribute', 'attribute.label', 'attribute.label', null, labelFcn);
					}
				},


				getAttributeLabel : function(idx, attridx) {

					var label = this.items[idx]['attr'][attridx]['attribute.label'];

					if(this.items[idx]['attr'][attridx]['attribute.type']) {
						label += ' (' + this.items[idx]['attr'][attridx]['attribute.type'] + ')';
					}

					return label;
				},


				removeAttributeItem : function(idx, attridx) {
					this.items[idx]['attr'].splice(attridx, 1);
				},


				updateAttributeItem : function(ev, idx, listidx) {

					this.$set(this.items[idx]['attr'][listidx], 'product.lists.id', '');
					this.$set(this.items[idx]['attr'][listidx], 'product.lists.siteid', this.siteid);
					this.$set(this.items[idx]['attr'][listidx], 'product.lists.refid', ev.value);
					this.$set(this.items[idx]['attr'][listidx], 'attribute.label', ev.label);
					this.$set(this.items[idx]['attr'][listidx], 'attribute.type', '');
				}
			}
		}
	}
};



Aimeos.Product.Stock = {

	mixins : function() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {
				checkSite : function(idx) {
					return this.items[idx]['stock.siteid'] && this.items[idx]['stock.siteid'] != this.siteid;
				},


				checkType : function() {
					var types = [];

					for(idx in this.items) {
						this.items[idx]['css'] = '';

						if(types.indexOf(this.items[idx]['stock.type']) !== -1) {
							this.items[idx]['css'] = 'is-invalid';
						}

						types.push(this.items[idx]['stock.type']);
					}
				},


				checked : function(idx) {
					return this.items[idx].checked || this.items[idx].checked === undefined && this.items[idx]['stock.stocklevel'] !== null;
				},


				add : function(data) {

					var idx = (this.items || []).length;
					this.$set(this.items, idx, {});

					for(var key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], 'stock.siteid', this.siteid);
				},


				remove : function(idx) {
					this.items.splice(idx, 1);
				},


				toggle : function(idx) {
					this.$set(this.items[idx], 'checked', !this.checked(idx));
				}
			}
		}
	}
};



Aimeos.Product.Subscription = {

	mixins : function() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {
				add : function(data) {

					var idx = this.items.length;
					this.$set(this.items, idx, {});

					for(var key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], 'product.lists.siteid', this.siteid);
				},


				readonly: function(idx) {
					return this.items[idx]['attribute.id'] != '' && this.items[idx]['attribute.id'] != null;
				},


				remove : function(idx) {
					this.items.splice(idx, 1);
				},


				value: function(idx) {
					const map = this.items[idx];
					return 'P' + (map['Y'] > 0 ? map['Y'] + 'Y' : '')
						+ (map['M'] > 0 ? map['M'] + 'M' : '')
						+ (map['W'] > 0 ? map['W'] + 'W' : '')
						+ (map['D'] > 0 ? map['D'] + 'D' : '')
						+ (map['H'] > 0 ? map['H'] + 'H' : '');
				}
			}
		}
	}
};


Aimeos.Product.Supplier = {

	mixins : function() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {
				checkSite : function(idx) {
					return this.items[idx]['supplier.lists.siteid'] && this.items[idx]['supplier.lists.siteid'] != this.siteid;
				},


				add : function(data) {

					var idx = (this.items || []).length;
					this.$set(this.items, idx, {});

					for(var key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], 'supplier.lists.siteid', this.siteid);
					this.$set(this.items[idx], 'supplier.lists.type', this.listtype);
				},


				remove : function(idx) {
					this.items.splice(idx, 1);
				},


				getItems : function() {

					return function(request, response, element) {

						var labelFcn = function(attr) {
							return attr['supplier.label'] + ' (' + attr['supplier.code'] + ')';
						}

						Aimeos.getOptions(request, response, element, 'supplier', 'supplier.label', 'supplier.label', null, labelFcn);
					}
				},


				getLabel : function(idx) {

					var label = this.items[idx]['supplier.label'];

					if(this.items[idx]['supplier.code']) {
						label += ' (' + this.items[idx]['supplier.code'] + ')';
					}

					return label;
				},


				update : function(ev) {

					this.$set(this.items[ev.index], 'supplier.lists.id', '');
					this.$set(this.items[ev.index], 'supplier.lists.type', this.listtype);
					this.$set(this.items[ev.index], 'supplier.lists.siteid', this.siteid);
					this.$set(this.items[ev.index], 'supplier.lists.refid', '');
					this.$set(this.items[ev.index], 'supplier.label', ev.label);
					this.$set(this.items[ev.index], 'supplier.id', ev.value);
					this.$set(this.items[ev.index], 'supplier.code', '');

					var ids = [];

					for(idx in this.items) {

						if(this.items[idx]['supplier.lists.type'] != this.listtype) {
							continue;
						}

						this.items[idx]['css'] = '';

						if(ids.indexOf(this.items[idx]['supplier.id']) !== -1) {
							this.items[idx]['css'] = 'is-invalid';
						}

						ids.push(this.items[idx]['supplier.id']);
					}
				}
			}
		};
	}
};



$(function() {

	Aimeos.Product.init();
});
