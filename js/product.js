/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



Aimeos.Product = {

	init() {

		Aimeos.Product.Bundle.init();
		Aimeos.Product.Download.init();
		Aimeos.Product.Selection.init();

		this.components();
	},


	components() {

		const components = [
			{
				name: 'basic',
				el: '.item-product .item-basic .box',
				data: {
					duplicate: false,
					item: $(".item-basic [data-data]").data("data") || {},
					siteid: $(".item-basic [data-siteid]").data("siteid"),
					datasets: $(".item-basic [data-datasets]").data("datasets") || {}
				},
				mixins: [Aimeos.Product.Basic.mixins.bind(this)()]
			},
			{
				name: 'catalog/default',
				el: '.item-product .item-category .catalog-default .category-list',
				data: {
					items: $(".item-category .catalog-default .category-list").data("items"),
					keys: $(".item-category .catalog-default .category-list").data("keys"),
					listtype: $(".item-category .catalog-default .category-list").data("listtype"),
					siteid: $(".item-category .catalog-default .category-list").data("siteid")
				},
				mixins: [Aimeos.Product.Catalog.mixins.bind(this)()]
			},
			{
				name: 'catalog/promotion',
				el: '.item-product .item-category .catalog-promotion .category-list',
				data: {
					items: $(".item-category .catalog-promotion .category-list").data("items"),
					keys: $(".item-category .catalog-promotion .category-list").data("keys"),
					listtype: $(".item-category .catalog-promotion .category-list").data("listtype"),
					siteid: $(".item-category .catalog-promotion .category-list").data("siteid")
				},
				mixins: [Aimeos.Product.Catalog.mixins.bind(this)()]
			},
			{
				name: 'supplier/default',
				el: '.item-product .item-supplier .supplier-default .supplier-list',
				data: {
					items: $(".item-supplier .supplier-default .supplier-list").data("items"),
					keys: $(".item-supplier .supplier-default .supplier-list").data("keys"),
					listtype: $(".item-supplier .supplier-default .supplier-list").data("listtype"),
					siteid: $(".item-supplier .supplier-default .supplier-list").data("siteid")
				},
				mixins: [Aimeos.Product.Supplier.mixins.bind(this)()]
			},
			{
				name: 'supplier/promotion',
				el: '.item-product .item-supplier .supplier-promotion .supplier-list',
				data: {
					items: $(".item-supplier .supplier-promotion .supplier-list").data("items"),
					keys: $(".item-supplier .supplier-promotion .supplier-list").data("keys"),
					listtype: $(".item-supplier .supplier-promotion .supplier-list").data("listtype"),
					siteid: $(".item-supplier .supplier-promotion .supplier-list").data("siteid")
				},
				mixins: [Aimeos.Product.Supplier.mixins.bind(this)()]
			},
			{
				name: 'related/bought',
				el: '.item-product .item-related-bought .product-list',
				data: {
					items: $(".item-related-bought .product-list").data("items"),
					keys: $(".item-related-bought .product-list").data("keys"),
					prefix: $(".item-related-bought .product-list").data("prefix"),
					siteid: $(".item-related-bought .product-list").data("siteid")
				},
				mixins: [Aimeos.Product.Product.mixins.bind(this)()]
			},
			{
				name: 'related/suggest',
				el: '.item-product .item-related-suggest .product-list',
				data: {
					items: $(".item-related-suggest .product-list").data("items"),
					keys: $(".item-related-suggest .product-list").data("keys"),
					prefix: $(".item-related-suggest .product-list").data("prefix"),
					siteid: $(".item-related-suggest .product-list").data("siteid")
				},
				mixins: [Aimeos.Product.Product.mixins.bind(this)()]
			},
			{
				name: 'bundle',
				el: '.item-product .item-bundle .product-list',
				data: {
					items: $(".item-bundle .product-list").data("items"),
					keys: $(".item-bundle .product-list").data("keys"),
					prefix: $(".item-bundle .product-list").data("prefix"),
					siteid: $(".item-bundle .product-list").data("siteid")
				},
				mixins: [Aimeos.Product.Product.mixins.bind(this)()]
			},
			{
				name: 'selection',
				el: '.item-product #item-selection-group',
				data: {
					items: $("#item-selection-group").data("items"),
					keys: $("#item-selection-group").data("keys"),
					siteid: $("#item-selection-group").data("siteid")
				},
				mixins: [Aimeos.Product.Selection.mixins.bind(this)()]
			},
			{
				name: 'stock',
				el: '.item-product .item-stock .stock-list',
				data: {
					items: $(".item-stock .stock-list").data("items"),
					keys: $(".item-stock .stock-list").data("keys"),
					siteid: $(".item-stock .stock-list").data("siteid"),
					numtypes: $(".item-stock .stock-list").data("numtypes")
				},
				mixins: [Aimeos.Product.Stock.mixins.bind(this)()]
			},
			{
				name: 'subscription',
				el: '.item-product .item-subscription .subscription-list',
				data: {
					items: $(".item-subscription .subscription-list").data("items"),
					keys: $(".item-subscription .subscription-list").data("keys"),
					siteid: $(".item-subscription .subscription-list").data("siteid")

				},
				mixins: [Aimeos.Product.Subscription.mixins.bind(this)()]
			},
			{
				name: 'order',
				el: '.item-product .item-order .order-list',
				data: {
					'id': null,
					'items': [],
					'fields': [],
					'filter': {},
					'included': {},
					'offset': 0,
					'limit': 25,
					'total': 0,
					'sort': '-order.id',
				},
				mixins: [Aimeos.Product.Order.mixins.bind(this)()]
			}
		]

		for(entry of $('.item-product .item-characteristic-attribute .attribute-list')) {
			const name = $(entry).attr('id');
			components.push({
				name: name.replace(/-/, '/'),
				el: '#' + name,
				data: {
					items: $(entry).data("items"),
					keys: $(entry).data("keys"),
					prefix: $(entry).data("prefix"),
					siteid: $(entry).data("siteid"),
					listtype: $(entry).data("listtype")
				},
				mixins: [Aimeos.Product.Attribute.mixins.bind(this)()]
			})
		}

		for(const component of components) {
			Aimeos.components[component.name] = new Vue({
				'el': document.querySelector(component.el),
				'data': component.data,
				'mixins': component.mixins
			});
		}
	}
};



Aimeos.Product.Basic = {

	mixins() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {
				can(what) {
					return (new String(this.item['product.siteid'])).startsWith(this.siteid);
				},


				dataset(ev) {
					const config = this.datasets[ev.target.value] || [];

					for(const name in config) {
						if(Aimeos.components[name]) {
							for(const key in config[name]) {
								if(Aimeos.components[name]) {
									Aimeos.components[name].add(config[name][key]);
								}
							}
						}
					}
				},


				exists(ev) {
					const filter = {'==': {'product.code': ev.target.value}}

					Aimeos.query(`query {
						searchProducts(filter: "` + JSON.stringify(filter).replace(/"/g, '\\"') + `") {
							id
						}
					}`).then(result => {
						this.duplicate = result?.searchProducts?.length > 0
							&& result?.searchProducts[0]?.id !== this.item['product.id']
					})
				}
			}
		}
	}
}


Aimeos.Product.Attribute = {

	mixins() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {
				add(data) {

					const idx = (this.items || []).length;
					this.$set(this.items, idx, {});

					for(let key of this.keys) {
						this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], this.prefix + 'siteid', this.siteid);
					this.$set(this.items[idx], this.prefix + 'type', this.listtype);
					this.$set(this.items[idx], 'config', []);
				},


				attr(input, idx) {
					const filter = {
						'&&': [
							{'==': {'attribute.type': this.items[idx]['attribute.type']}},
							{'||': [
								{'=~': {'attribute.label': input}},
								{'=~': {'attribute.code': input}},
								{'==': {'attribute.id': input}}
							]}
						]
					}

					return Aimeos.query(`query {
						searchAttributes(filter: "` + JSON.stringify(filter).replace(/"/g, '\\"') + `") {
						  id
						  label
						}
					  }
					`).then(result => {
						return (result?.searchAttributes || []).map(item => {
							return {'attribute.id': item.id, 'attribute.label': item.label}
						})
					})
				},


				attrTypes(input) {
					const filter = {
						'||': [
							{'=~': {'attribute.type.label': input}},
							{'=~': {'attribute.type.code': input}},
							{'==': {'attribute.type.id': input}}
						]
					}

					return Aimeos.query(`query {
						searchAttributeTypes(filter: "` + JSON.stringify(filter).replace(/"/g, '\\"') + `") {
						  code
						}
					  }
					`).then(result => {
						return (result?.searchAttributeTypes || []).map(item => {
							return {'attribute.type': item.code}
						})
					})
				},


				can(action, idx) {
					if(this.items[idx]['product.lists.siteid']) {
						let allow = (new String(this.items[idx]['product.lists.siteid'])).startsWith(this.siteid);

						switch(action) {
							case 'delete': return allow;
							case 'change': return allow;
							case 'move': return allow;
						}
					}

					return false;
				},


				load(select) {
					select.refreshOptions()
				},


				remove(idx) {
					this.items.splice(idx, 1);
				},


				title(idx) {
					return 'Site ID: ' + this.items[idx]['product.lists.siteid'] + "\n"
						+ 'Editor: ' + this.items[idx]['product.lists.editor'] + "\n"
						+ 'Created: ' + this.items[idx]['product.lists.ctime'] + "\n"
						+ 'Modified: ' + this.items[idx]['product.lists.mtime'];
				},


				toggle(what, idx) {
					if(this.items[idx]) {
						this.$set(this.items[idx], what, (!this.items[idx][what] ? true : false));
					}
				},


				use(idx, ev) {
					this.$set(this.items[idx], this.prefix + 'refid', ev['attribute.id']);
					this.$set(this.items[idx], 'attribute.label', ev['attribute.label']);
					this.$set(this.items[idx], 'attribute.id', ev['attribute.id']);
				},


				useType(idx, ev) {
					this.$set(this.items[idx], 'attribute.type', ev['attribute.type']);
				}
			}
		}
	}
};


Aimeos.Product.Bundle = {

	init()  {

		let tab = $(".item-navbar .bundle");
		$(".item-basic .item-type").val() === 'bundle' ? tab.show() : tab.hide();

		$(".item-basic .item-type").on("change", function() {
			$("option:selected", this).val() === 'bundle' ? tab.show() : tab.hide();
		});
	}
};


Aimeos.Product.Catalog = {

	mixins() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {
				add(data) {

					let idx = (this.items || []).length;
					this.$set(this.items, idx, {});

					for(let key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], 'product.lists.siteid', this.siteid);
					this.$set(this.items[idx], 'product.lists.type', this.listtype);
				},


				can(action, idx) {
					if(this.items[idx]['product.lists.siteid']) {
						let allow = (new String(this.items[idx]['product.lists.siteid'])).startsWith(this.siteid);

						switch(action) {
							case 'delete': return allow;
							case 'change': return allow || this.items[idx]['product.lists.id'] == '';
							case 'move': return allow;
						}
					}

					return false;
				},


				fetch(input, idx) {
					const filter = {
						'||': [
							{'=~': {'catalog.label': input}},
							{'=~': {'catalog.code': input}},
							{'==': {'catalog.id': input}}
						]
					}

					return Aimeos.query(`query {
						searchCatalogs(filter: "` + JSON.stringify(filter).replace(/"/g, '\\"') + `") {
						  id
						  label
						}
					  }
					`).then(result => {
						return (result?.searchCatalogs || []).map(item => {
							return {'catalog.id': item.id, 'catalog.label': item.label}
						})
					})
				},


				load(select) {
					select.refreshOptions()
				},


				remove(idx) {
					this.items.splice(idx, 1);
				},


				title(idx) {
					return 'Site ID: ' + this.items[idx]['product.lists.siteid'] + "\n"
						+ 'Editor: ' + this.items[idx]['product.lists.editor'] + "\n"
						+ 'Created: ' + this.items[idx]['product.lists.ctime'] + "\n"
						+ 'Modified: ' + this.items[idx]['product.lists.mtime'];
				},


				use(idx, ev) {
					this.$set(this.items[idx], 'product.lists.refid', ev['catalog.id']);
					this.$set(this.items[idx], 'catalog.label', ev['catalog.label']);
					this.$set(this.items[idx], 'catalog.id', ev['catalog.id']);
				},
			}
		};
	}
};



Aimeos.Product.Download = {

	init() {

		$(".item-download").on("change", ".fileupload", function(ev) {
			$(this.files).each(function(idx, file) {
				$("input.item-label", ev.delegateTarget).val(file.name);
				$(".custom-file-label", ev.delegateTarget).text(file.name);
			});
		});
	}
};



Aimeos.Product.Order = {

	mixins() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;

				if(this.$el.dataset && this.$el.dataset.id) {
					this.id = this.$el.dataset.id;
				}
				this.filter['order.product.productid'] = {'==':{'order.product.productid': this.id}};

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
				first() {
					return this.offset > 0 ? 0 : null;
				},
				prev() {
					return this.offset - this.limit >= 0 ? this.offset - this.limit : null;
				},
				next() {
					return this.offset + this.limit < this.total ? this.offset + this.limit : null;
				},
				last() {
					return Math.floor((this.total - 1) / this.limit) * this.limit > this.offset ? Math.floor((this.total - 1) / this.limit ) * this.limit : null;
				},
				current() {
					return Math.floor( this.offset / this.limit ) + 1;
				},
				pages() {
					return this.total != 0 ? Math.ceil(this.total / this.limit) : 1;
				}
			},
			methods : {
				value(key) {
					let op = Object.keys(this.filter[key] || {}).pop();
					return this.filter[key] && this.filter[key][op][key] || '';
				},
				submit() {
					this.fetch();
				},
				reset() {
					Object.assign(this.$data, {filter: {'order.product.productid': {'==':{'order.product.productid': this.id}}}});
				},
				find(ev, key, op) {
					if(ev.target.value !== '') {
						let expr = {};
						expr[op || '=='] = {};
						expr[op || '=='][key] = ev.target.value;
						this.$set(this.filter, key, expr);
					} else {
						this.$delete(this.filter, key);
					}
				},
				fetch() {
					let self = this;

					Aimeos.options.done(function(response) {

						if(response.meta && response.meta.resources && response.meta.resources['order'] ) {

							let args = {
								'filter': {'&&': []},
								'fields': {
									'order': self.fields.join(',') + ',order.customerid',
									'order/address': self.fields.join(',') + ',order.address.type',
								},
								'include': 'order/address',
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

							axios.get(response.meta.resources['order'], config).then(response => {

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
				related(item, type, key) {
					let id = null;
					let self = this;

					(item['relationships'] && item['relationships'][type] && item['relationships'][type]['data'] || []).forEach(function(addr) {
						if(addr.data && addr.data.id && self.included[type] && self.included[type][addr.data.id]
							&& self.included[type][addr.data.id]['attributes']['order.address.type'] === 'payment'
						) {
							id = addr.data.id;
						}
					});

					return this.included[type] && this.included[type][id] ? this.included[type][id]['attributes'][key] : '';
				},
				pagecnt(str) {
					return sprintf(str, this.current, this.pages);
				},
				orderby(key) {
					this.sort = this.sort === key ? '-' + key : key;
				},
				sortclass(key) {
					return this.sort === key ? 'sort-desc' : (this.sort === '-' + key ? 'sort-asc' : '');
				},
				toggleField(key) {
					let idx = this.fields.indexOf(key);
					idx !== -1 ? this.fields.splice(idx, 1) : this.fields.push(key);

					if(window.sessionStorage) {
						window.sessionStorage.setItem('aimeos/jqadm/productorder/fields', JSON.stringify(this.fields));
					}
				}
			},
			watch: {
				fields() {
					this.fetch();
				},
				filter : {
					handler() {
						this.fetch();
					},
					deep: true
				},
				limit() {
					this.fetch();
				},
				offset() {
					this.fetch();
				},
				sort() {
					this.fetch();
				}
			}
		};
	}
};



Aimeos.Product.Product = {

	mixins() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {
				add(data) {

					let idx = (this.items || []).length;
					this.$set(this.items, idx, {});

					for(let key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], this.prefix + 'siteid', this.siteid);
				},


				can(action, idx) {

					if(this.items[idx]['product.lists.siteid']) {
						let allow = (new String(this.items[idx]['product.lists.siteid'])).startsWith(this.siteid);

						switch(action) {
							case 'delete': return allow;
							case 'change': return allow || this.items[idx]['product.lists.id'] == '';
							case 'move': return allow;
						}
					}

					return false;
				},


				fetch(input, idx) {
					const filter = {
						'||': [
							{'=~': {'product.label': input}},
							{'=~': {'product.code': input}},
							{'==': {'product.id': input}}
						]
					}

					return Aimeos.query(`query {
						searchProducts(filter: "` + JSON.stringify(filter).replace(/"/g, '\\"') + `") {
						  id
						  label
						}
					  }
					`).then(result => {
						return (result?.searchProducts || []).map(item => {
							return {'product.id': item.id, 'product.label': item.label}
						})
					})
				},


				load(select) {
					select.refreshOptions()
				},


				remove(idx) {
					this.items.splice(idx, 1);
				},


				title(idx) {
					return 'Site ID: ' + this.items[idx]['product.lists.siteid'] + "\n"
						+ 'Editor: ' + this.items[idx]['product.lists.editor'] + "\n"
						+ 'Created: ' + this.items[idx]['product.lists.ctime'] + "\n"
						+ 'Modified: ' + this.items[idx]['product.lists.mtime'];
				},


				use(idx, ev) {
					this.$set(this.items[idx], 'product.lists.refid', ev['product.id']);
					this.$set(this.items[idx], 'product.label', ev['product.label']);
					this.$set(this.items[idx], 'product.id', ev['product.id']);
				},
			}
		};
	}
};



Aimeos.Product.Selection = {

	init() {

		const tab = $(".item-navbar .selection");
		['group', 'select'].includes($(".item-basic .item-type").val()) ? tab.show() : tab.hide();

		$(".item-product").on("change", ".item-basic .item-type", function() {
			['group', 'select'].includes($("option:selected", this).val()) ? tab.show() : tab.hide();
		});
	},


	mixins() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {

				add(data) {

					const idx = this.items.length;
					this.$set(this.items, idx, {});

					for(let key of this.keys) {
						this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], 'product.lists.siteid', this.siteid);
					this.$set(this.items[idx], 'product.siteid', this.siteid);
					this.$set(this.items[idx], 'product.status', 1);
					this.$set(this.items[idx], 'product.id', '');
					this.$set(this.items[idx], 'attr', []);
				},


				can(action, idx, attridx) {

					if(attridx && this.items[idx]['attr'][attridx]['product.lists.siteid']) {
						const allow = (new String(this.items[idx]['attr'][attridx]['product.lists.siteid'])).startsWith(this.siteid);

						switch(action) {
							case 'delete': return allow;
							case 'change': return allow || this.items[idx]['attr'][attridx]['product.lists.id'] == '';
							case 'move': return allow  && this.items[idx]['attr'][attridx]['product.lists.id'] != '';
						}
					}

					if(this.items[idx]['product.lists.siteid']) {
						return (new String(this.items[idx]['product.lists.siteid'])).startsWith(this.siteid);
					}

					return false;
				},


				copy(idx) {

					const len = this.items.length;
					this.$set(this.items, len, {});

					for(let key in this.items[idx]) {
						this.$set(this.items[len], key, this.items[idx][key]);
					}

					this.$set(this.items[len], 'attr', []);
					this.$set(this.items[len], 'product.id', '');
					this.$set(this.items[len], 'product.code', this.items[idx]['product.code'] + '_copy');
					this.$set(this.items[len], 'product.label', this.items[idx]['product.label'] + '_copy');
					this.$set(this.items[len], 'product.lists.siteid', this.siteid);
					this.$set(this.items[len], 'product.lists.id', '');

					for(let attridx in this.items[idx]['attr']) {
						this.$set(this.items[len]['attr'], attridx, {});

						for(let key in this.items[idx]['attr'][attridx]) {
							this.$set(this.items[len]['attr'][attridx], key, this.items[idx]['attr'][attridx][key]);
						}

						this.$set(this.items[len]['attr'][attridx], 'product.lists.siteid', this.siteid);
						this.$set(this.items[len]['attr'][attridx], 'product.lists.id', '');
					}
				},


				css(idx) {
					return ( idx !== 0 && this.items[idx]['product.lists.id'] &&
						this.items[idx]['attr'] && this.items[idx]['attr'].length ? 'collapsed' : 'show' );
				},


				editable(idx) {
					return this.items[idx]['product.siteid'] && (new String(this.items[idx]['product.siteid'])).startsWith(this.siteid);
				},


				get(request, response) {

					const filter = {'&&': [
						{'=~': {'product.code': request.term}},
						{'==': {'product.type': ['default', 'event', 'voucher']}}
					]};
					const fstr = JSON.stringify(filter).replace(/"/g, '\\"');
					const body = JSON.stringify({'query': `query {
						searchProducts(filter: "` + fstr + `", include: ["attribute"], sort: ["product.code"]) {
							id
							type
							code
							label
							lists {
								attribute(listtype: "variant") {
									id
									siteid
									refid
									editor
									ctime
									mtime
									item {
										type
										code
										label
									}
								}
							}
						}
					}`});

					fetch($('.aimeos').data('graphql'), {
						method: 'POST',
						credentials: 'same-origin',
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						body: body
					}).then(response => {
						return response.json();
					}).then(result => {
						response((result.data.searchProducts || []).map(function(item) {
							return {
								id: item.id,
								type: item.type,
								code: item.code,
								label: item.label,
								stock: false,
								attr: (item.lists.attribute || []).map((entry) => {
									return {
										'product.lists.id': entry.id,
										'product.lists.siteid': entry.siteid,
										'product.lists.refid': entry.refid,
										'product.lists.editor': entry.editor,
										'product.lists.ctime': entry.ctime,
										'product.lists.mtime': entry.mtime,
										'attribute.id': entry.item.id,
										'attribute.type': entry.item.type,
										'attribute.code': entry.item.code,
										'attribute.label': entry.item.label
									}
								})
							};
						}));
					});
				},


				remove(idx) {
					this.items.splice(idx, 1);
				},


				update(idx, ev, item) {

					if(item) {
						this.$set(this.items[idx], 'product.id', item.id);
						this.$set(this.items[idx], 'product.code', item.code);
						this.$set(this.items[idx], 'product.label', item.label);
						this.$set(this.items[idx], 'product.type', item.type);
						this.$set(this.items[idx], 'stock', item.stock);
						this.$set(this.items[idx], 'attr', item.attr);
					}
				},


				addAttribute(idx) {

					if(!this.items[idx]['attr']) {
						this.$set(this.items[idx], 'attr', []);
					}

					const len = this.items[idx]['attr'].length;

					if(!this.items[idx]['attr'][len]) {
						this.$set(this.items[idx]['attr'], len, {});
					}

					const keys = ['product.lists.id', 'product.lists.refid', 'attribute.label'];

					for(let key of keys) {
						this.$set(this.items[idx]['attr'][len], key, '');
					}

					this.$set(this.items[idx]['attr'][len], 'product.lists.siteid', this.siteid);
				},


				getAttributes() {

					return function(request, response, element) {

						const labelFcn = function(attr) {
							return attr['attribute.label'] + ' (' + attr['attribute.type'] + ')';
						}
						Aimeos.getOptions(request, response, element, 'attribute', 'attribute.label', 'attribute.label', null, labelFcn);
					}
				},


				label(idx, attridx) {

					let label = this.items[idx]['attr'][attridx]['attribute.label'];

					if(this.items[idx]['attr'][attridx]['attribute.type']) {
						label += ' (' + this.items[idx]['attr'][attridx]['attribute.type'] + ')';
					}

					return label;
				},


				removeAttribute(idx, attridx) {
					this.items[idx]['attr'].splice(attridx, 1);
				},


				title(idx, attridx) {
					return 'Site ID: ' + this.items[idx]['attr'][attridx]['product.lists.siteid'] + "\n"
						+ 'Editor: ' + this.items[idx]['attr'][attridx]['product.lists.editor'] + "\n"
						+ 'Created: ' + this.items[idx]['attr'][attridx]['product.lists.ctime'] + "\n"
						+ 'Modified: ' + this.items[idx]['attr'][attridx]['product.lists.mtime'];
				},


				updateAttribute(ev, idx, attridx) {

					this.$set(this.items[idx]['attr'][attridx], 'product.lists.id', '');
					this.$set(this.items[idx]['attr'][attridx], 'product.lists.siteid', this.siteid);
					this.$set(this.items[idx]['attr'][attridx], 'product.lists.refid', ev.value);
					this.$set(this.items[idx]['attr'][attridx], 'attribute.label', ev.label);
					this.$set(this.items[idx]['attr'][attridx], 'attribute.type', '');
				}
			}
		}
	}
};



Aimeos.Product.Stock = {

	mixins() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {
				add(data) {

					var idx = (this.items || []).length;
					this.$set(this.items, idx, {});

					for(var key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], 'stock.siteid', this.siteid);
				},


				can(action, idx) {
					if(this.items[idx]['stock.siteid']) {
						let allow = (new String(this.items[idx]['stock.siteid'])).startsWith(this.siteid);

						switch(action) {
							case 'delete': return allow;
							case 'change': return allow || this.items[idx]['stock.id'] == '';
						}
					}

					return false;
				},


				checked(idx) {
					return this.items[idx].checked || this.items[idx].checked === undefined && this.items[idx]['stock.stocklevel'] !== null;
				},


				checkType() {
					var types = [];

					for(idx in this.items) {
						this.items[idx]['css'] = '';

						if(types.indexOf(this.items[idx]['stock.type']) !== -1) {
							this.items[idx]['css'] = 'is-invalid';
						}

						types.push(this.items[idx]['stock.type']);
					}
				},


				remove(idx) {
					this.items.splice(idx, 1);
				},


				toggle(idx) {
					this.$set(this.items[idx], 'checked', !this.checked(idx));
				}
			}
		}
	}
};



Aimeos.Product.Subscription = {

	mixins() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {
				add(data) {

					const idx = this.items.length;
					this.$set(this.items, idx, {});

					for(let key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], 'product.lists.siteid', this.siteid);
				},


				can(action, idx) {
					if(this.items[idx]['product.lists.siteid']) {
						switch(action) {
							case 'create': return !this.items[idx]['attribute.id'];
							case 'change': return (new String(this.items[idx]['product.lists.siteid'])).startsWith(this.siteid);
						}
					}

					return false;
				},


				remove(idx) {
					this.items.splice(idx, 1);
				},


				value(idx) {
					const map = this.items[idx];
					return 'P' + (map['Y'] > 0 ? parseInt( map['Y'] ) + 'Y' : '')
						+ (map['M'] > 0 ? parseInt( map['M'] ) + 'M' : '')
						+ (map['W'] > 0 ? parseInt( map['W'] ) + 'W' : '')
						+ (map['D'] > 0 ? parseInt( map['D'] ) + 'D' : '')
						+ (map['H'] > 0 ? 'T' + parseInt( map['H'] ) + 'H' : '');
				}
			}
		}
	}
};


Aimeos.Product.Supplier = {

	mixins() {
		return {
			beforeMount() {
				this.Aimeos = Aimeos;
			},
			methods: {
				add(data) {

					let idx = (this.items || []).length;
					this.$set(this.items, idx, {});

					for(let key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], 'product.lists.siteid', this.siteid);
					this.$set(this.items[idx], 'product.lists.type', this.listtype);
				},


				can(action, idx) {
					if(this.items[idx]['product.lists.siteid']) {
						let allow = (new String(this.items[idx]['product.lists.siteid'])).startsWith(this.siteid);

						switch(action) {
							case 'delete': return allow;
							case 'change': return allow || this.items[idx]['product.lists.id'] == '';
							case 'move': return allow;
						}
					}

					return false;
				},


				fetch(input, idx) {
					const filter = {
						'||': [
							{'=~': {'supplier.label': input}},
							{'=~': {'supplier.code': input}},
							{'==': {'supplier.id': input}}
						]
					}

					return Aimeos.query(`query {
						searchSuppliers(filter: "` + JSON.stringify(filter).replace(/"/g, '\\"') + `") {
						  id
						  label
						}
					  }
					`).then(result => {
						return (result?.searchSuppliers || []).map(item => {
							return {'supplier.id': item.id, 'supplier.label': item.label}
						})
					})
				},


				load(select) {
					select.refreshOptions()
				},


				remove(idx) {
					this.items.splice(idx, 1);
				},


				title(idx) {
					return 'Site ID: ' + this.items[idx]['product.lists.siteid'] + "\n"
						+ 'Editor: ' + this.items[idx]['product.lists.editor'] + "\n"
						+ 'Created: ' + this.items[idx]['product.lists.ctime'] + "\n"
						+ 'Modified: ' + this.items[idx]['product.lists.mtime'];
				},


				use(idx, ev) {
					this.$set(this.items[idx], 'product.lists.refid', ev['supplier.id']);
					this.$set(this.items[idx], 'supplier.label', ev['supplier.label']);
					this.$set(this.items[idx], 'supplier.id', ev['supplier.id']);
				},
			}
		};
	}
};



$(function() {

	Aimeos.Product.init();
});
