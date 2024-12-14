/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



Aimeos.Product = {

	init() {

		Aimeos.Product.Price.init();
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
				mixins: [Aimeos.Product.Basic.mixins.bind(this)()]
			},
			{
				name: 'bundle',
				el: '.item-product .item-bundle .product-list',
				mixins: [Aimeos.Product.Product.mixins.bind(this)()]
			},
			{
				name: 'selection',
				el: '.item-product #item-selection-group',
				mixins: [Aimeos.Product.Selection.mixins.bind(this)()]
			},
			{
				name: 'stock',
				el: '.item-product .item-stock .stock-list',
				mixins: [Aimeos.Product.Stock.mixins.bind(this)()]
			},
			{
				name: 'subscription',
				el: '.item-product .item-subscription .subscription-list',
				mixins: [Aimeos.Product.Subscription.mixins.bind(this)()]
			},
			{
				name: 'order',
				el: '.item-product .item-order .order-list',
				mixins: [Aimeos.Product.Order.mixins.bind(this)()]
			}
		]

		for(entry of document.querySelectorAll('.item-product .item-characteristic-attribute .attribute-list')) {
			components.push({
				name: entry.id.replace(/-/, '/'),
				el: '#' + entry.id,
				mixins: [Aimeos.Product.Attribute.mixins.bind(this)()]
			})
		}

		for(entry of document.querySelectorAll('.item-product .item-category .catalog')) {
			components.push({
				name: entry.id.replace(/-/, '/'),
				el: '#' + entry.id,
				mixins: [Aimeos.Product.Catalog.mixins.bind(this)()]
			})
		}

		for(entry of document.querySelectorAll('.item-product .item-related .product')) {
			components.push({
				name: entry.id.replace(/-/, '/'),
				el: '#' + entry.id,
				mixins: [Aimeos.Product.Product.mixins.bind(this)()]
			})
		}

		for(entry of document.querySelectorAll('.item-product .item-supplier .supplier')) {
			components.push({
				name: entry.id.replace(/-/, '/'),
				el: '#' + entry.id,
				mixins: [Aimeos.Product.Supplier.mixins.bind(this)()]
			})
		}

		for(const component of components) {
			const node = document.querySelector(component.el);

			if(node) {
				Aimeos.apps[component.name] = Aimeos.app({
					data: component.data,
					mixins: component.mixins
				}, {...node.dataset || {}}).mount(node);
			}
		}
	}
};



Aimeos.Product.Basic = {

	mixins() {
		return {
			props: {
				data: {type: String, required: true},
				siteid: {type: String, required: true},
				datasets: {type: String, required: true}
			},
			data() {
				return {
					item: {},
					dsets: [],
					duplicate: false,
				}
			},
			beforeMount() {
				this.Aimeos = Aimeos;
				this.item = JSON.parse(this.data);
				this.dsets = JSON.parse(this.datasets);
			},
			methods: {
				can(action) {
					return Aimeos.can(action, this.item['product.siteid'] || null, this.siteid)
				},


				dataset(ev) {
					const config = this.dsets[ev.target.value] || [];

					for(const name in config) {
						if(Aimeos.apps[name]) {
							for(const key in config[name]) {
								Aimeos.apps[name].add(config[name][key]);
							}
						}
					}
				},


				exists(ev) {
					const filter = {'==': {'product.code': ev.target.value}}

					Aimeos.query(`query {
						searchProducts(filter: ` + JSON.stringify(JSON.stringify(filter)) + `) {
							items {
								id
							}
						}
					}`).then(result => {
						this.duplicate = result?.searchProducts?.items?.length > 0
							&& result?.searchProducts?.items[0]?.id !== this.item['product.id']
					})
				}
			}
		}
	}
}


Aimeos.Product.Attribute = {

	mixins() {
		return {
			props: {
				data: {type: String, required: true},
				keys: {type: String, required: true},
				prefix: {type: String, required: true},
				siteid: {type: String, required: true},
				listtype: {type: String, required: true}
			},
			data() {
				return {
					items: []
				}
			},
			beforeMount() {
				this.Aimeos = Aimeos;
				this.items = JSON.parse(this.data);
			},
			methods: {
				add(data) {
					const idx = (this.items || []).length;
					this.items[idx] = {};

					for(const key of (JSON.parse(this.keys) || [])) {
						this.items[idx][key] = (data && data[key] || '');
					}

					this.items[idx][this.prefix + 'siteid'] = this.siteid;
					this.items[idx][this.prefix + 'type'] = this.listtype;
					this.items[idx]['config'] = [];
				},


				attr(input, idx) {
					const filter = {
						'&&': [
							{'==': {'attribute.type': this.items[idx]['attribute.type']}},
							{'>': {'attribute.status': 0}}
						]
					}

					if(input) {
						filter['&&'].push({
							'||': [
								{'=~': {'attribute.label': input}},
								{'=~': {'attribute.code': input}},
								{'==': {'attribute.id': input}}
							]
						});
					}

					return Aimeos.query(`query {
						searchAttributes(filter: ` + JSON.stringify(JSON.stringify(filter)) + `, sort: ["attribute.label"]) {
							items {
								id
								label
							}
						}
					  }
					`).then(result => {
						return (result?.searchAttributes?.items || []).map(item => {
							return {'attribute.id': item.id, 'attribute.label': item.label}
						})
					})
				},


				attrTypes(input) {
					const filter = {
						'&&': [
							{'>': {'attribute.type.status': 0}}
						]
					}

					if(input) {
						filter['&&'].push({
							'||': [
								{'=~': {'attribute.type.label': input}},
								{'=~': {'attribute.type.code': input}},
								{'==': {'attribute.type.id': input}}
							]
						});
					}

					return Aimeos.query(`query {
						searchAttributeTypes(filter: ` + JSON.stringify(JSON.stringify(filter)) + `, sort: ["attribute.type.code"]) {
							items {
								code
							}
						}
					  }
					`).then(result => {
						return (result?.searchAttributeTypes?.items || []).map(item => {
							return {'attribute.type': item.code}
						})
					})
				},


				can(action, idx) {
					return Aimeos.can(action, this.items[idx]['product.lists.siteid'] || null, this.siteid)
				},


				remove(idx) {
					this.items.splice(idx, 1);
				},


				title(idx) {
					if(this.items[idx]['product.lists.ctime']) {
						return 'Site ID: ' + this.items[idx]['product.lists.siteid'] + "\n"
							+ 'Editor: ' + this.items[idx]['product.lists.editor'] + "\n"
							+ 'Created: ' + this.items[idx]['product.lists.ctime'] + "\n"
							+ 'Modified: ' + this.items[idx]['product.lists.mtime'];
					}
					return ''
				},


				toggle(what, idx) {
					if(this.items[idx]) {
						this.items[idx][what] = (!this.items[idx][what] ? true : false);
					}
				},


				use(idx, ev) {
					this.items[idx]['attribute.label'] = ev['attribute.label'];
					this.items[idx]['attribute.id'] =ev['attribute.id'];
				},


				useType(idx, ev) {
					this.items[idx]['attribute.type'] = ev['attribute.type'];
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
			props: {
				data: {type: String, required: true},
				keys: {type: String, required: true},
				siteid: {type: String, required: true},
				listtype: {type: String, required: true}
			},
			data() {
				return {
					items: [],
				}
			},
			beforeMount() {
				this.Aimeos = Aimeos;
				this.items = JSON.parse(this.data);
			},
			methods: {
				add(data) {

					let idx = (this.items || []).length;
					this.items[idx] = {};

					for(const key of (JSON.parse(this.keys) || [])) {
						this.items[idx][key] = (data && data[key] || '');
					}

					this.items[idx]['product.lists.siteid'] = this.siteid;
					this.items[idx]['product.lists.type'] = this.listtype;
				},


				can(action, idx) {
					return Aimeos.can(action, this.items[idx]['product.lists.siteid'] || null, this.siteid)
				},


				fetch(input, idx) {
					const filter = {
						'&&': [
							{'>': {'catalog.status': 0}}
						]
					}

					if(input) {
						filter['&&'].push({
							'||': [
								{'=~': {'catalog.label': input}},
								{'=~': {'catalog.code': input}},
								{'==': {'catalog.id': input}}
							]
						});
					}

					return Aimeos.query(`query {
						searchCatalogs(filter: ` + JSON.stringify(JSON.stringify(filter)) + `, sort: ["catalog.label"]) {
							items {
								id
								code
								label
							}
						}
					  }
					`).then(result => {
						return (result?.searchCatalogs?.items || []).map(item => {
							return {'catalog.id': item.id, 'catalog.label': item.label + ' (' + item.code + ')'}
						})
					})
				},


				remove(idx) {
					this.items.splice(idx, 1);
				},


				title(idx) {
					if(this.items[idx]['product.lists.ctime']) {
						return 'Site ID: ' + this.items[idx]['product.lists.siteid'] + "\n"
							+ 'Editor: ' + this.items[idx]['product.lists.editor'] + "\n"
							+ 'Created: ' + this.items[idx]['product.lists.ctime'] + "\n"
							+ 'Modified: ' + this.items[idx]['product.lists.mtime'];
					}
					return ''
				},


				use(idx, ev) {
					this.items[idx]['catalog.label'] = ev['catalog.label'];
					this.items[idx]['catalog.id'] = ev['catalog.id'];
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
			props: {
				id: {type: String, required: true},
				fields: {type: String, required: true},
			},
			data() {
				return {
					columns: false,
					items: [],
					fieldlist: [],
					filter: {},
					included: {},
					offset: 0,
					limit: 25,
					total: 0,
					sort: '-order.id',
				}
			},
			beforeMount() {
				this.Aimeos = Aimeos;

				let list = [];
				if(window.sessionStorage) {
					list = JSON.parse(window.sessionStorage.getItem('aimeos/jqadm/productorder/fields')) || [];
				}
				if(!list.length) {
					list = JSON.parse(this.fields);
				}
				this.fieldlist = list;

				this.filter['order.product.productid'] = {'==':{'order.product.productid': this.id}};
			},

			methods : {
				address(item, code) {
					return item.address && item.address[0] && item.address[0][code] ? item.address[0][code] : '';
				},

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
						this.filter[key] = expr;
					} else {
						delete this.filter[key];
					}
				},

				fetch() {
					const filter = {'&&': Object.values(this.filter)};
					this.loading = true;

					return Aimeos.query(`query {
						searchOrders(filter: ` + JSON.stringify(JSON.stringify(filter)) + `, include: ["order/address"], sort: ` + JSON.stringify([this.sort]) + `, offset: ` + this.offset + `, limit: ` + this.limit + `) {
							items {
								id
								sitecode
								languageid
								currencyid
								price
								costs
								rebate
								taxvalue
								customerref
								comment
								address {
									type
									company
									vatid
									salutation
									title
									firstname
									lastname
									address1
									address2
									address3
									postal
									city
									state
									countryid
									mobile
									telephone
									telefax
									email
									website
								}
							}
							total
						}
					  }
					`).then((result) => {
						this.total = result?.searchOrders?.total || 0;
						this.items = result?.searchOrders?.items || [];
						this.loading = false;
					});
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

				update(keys) {
					this.fieldlist = keys;
					if(window.sessionStorage) {
						window.sessionStorage.setItem('aimeos/jqadm/productorder/fields', JSON.stringify(this.fieldlist));
					}
				}
			},

			watch: {
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



Aimeos.Product.Price = {

	init()  {
		$(".item-price .item-pricecustom").on("change", function(ev) {
			const item = $(ev.target).data('attr')
			item['product.lists.refid'] = item['attribute.id']

			if(Aimeos.apps['characteristics/custom']) {
				const items = Aimeos.apps['characteristics/custom'].items || []

				for(const key in items) {
					if(items[key]['attribute.type'] === 'price' && items[key]['attribute.code'] === 'custom') {
						if(!ev.target.checked) {
							Aimeos.apps['characteristics/custom'].remove(key)
							return
						} else {
							return
						}
					}
				}

				Aimeos.apps['characteristics/custom'].add(item)
			}
		});
	}
};


Aimeos.Product.Product = {

	mixins() {
		return {
			props: {
				data: {type: String, required: true},
				keys: {type: String, required: true},
				siteid: {type: String, required: true},
				listtype: {type: String, required: true}
			},
			data() {
				return {
					items: [],
				}
			},
			beforeMount() {
				this.Aimeos = Aimeos;
				this.items = JSON.parse(this.data);
			},
			methods: {
				add(data) {

					const idx = (this.items || []).length;
					this.items[idx] = {};

					for(const key of this.keys) {
						this.items[idx][key] = (data && data[key] || '');
					}

					this.items[idx]['product.lists.siteid'] = this.siteid;
					this.items[idx]['product.lists.type'] = this.listtype;
				},


				can(action, idx) {
					return Aimeos.can(action, this.items[idx]['product.lists.siteid'] || null, this.siteid)
				},


				fetch(input, idx) {
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
							return {'product.id': item.id, 'product.label': item.label + ' (' + item.code + ')'}
						})
					})
				},


				remove(idx) {
					this.items.splice(idx, 1);
				},


				title(idx) {
					if(this.items[idx]['product.lists.ctime']) {
						return 'Site ID: ' + this.items[idx]['product.lists.siteid'] + "\n"
							+ 'Editor: ' + this.items[idx]['product.lists.editor'] + "\n"
							+ 'Created: ' + this.items[idx]['product.lists.ctime'] + "\n"
							+ 'Modified: ' + this.items[idx]['product.lists.mtime'];
					}
					return ''
				},


				use(idx, ev) {
					this.items[idx]['product.label'] = ev['product.label'];
					this.items[idx]['product.id'] = ev['product.id'];
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
			props: {
				data: {type: String, required: true},
				keys: {type: String, required: true},
				siteid: {type: String, required: true},
			},
			data() {
				return {
					items: [],
				}
			},
			beforeMount() {
				this.Aimeos = Aimeos;
				this.items = JSON.parse(this.data);

				if(this.items[0]) {
					this.items[0]['_show'] = true;
				}
			},
			methods: {

				add(data = {}) {
					const entry = {};

					entry['product.lists.siteid'] = this.siteid;
					entry['product.siteid'] = this.siteid;
					entry['product.type'] = 'default';
					entry['product.status'] = 1;
					entry['product.id'] = '';
					entry['_show'] = true;
					entry['attr'] = [];

					this.items[this.items.length] = entry;
				},


				can(action, idx, attridx) {

					if(this.items[idx] && this.items[idx]['attr'] && this.items[idx]['attr'][attridx]) {
						return Aimeos.can(action, this.items[idx]['attr'][attridx]['product.lists.siteid'] || null, this.siteid)
					}

					return Aimeos.can(action, this.items[idx]['product.lists.siteid'] || null, this.siteid)
				},


				copy(idx) {

					const len = this.items.length;
					this.items[len] = {};

					for(let key in this.items[idx]) {
						this.items[len][key] = this.items[idx][key];
					}

					this.items[len]['attr'] = [];
					this.items[len]['_show'] = true;
					this.items[len]['product.id'] = '';
					this.items[len]['product.siteid'] = this.siteid;
					this.items[len]['product.code'] = this.items[idx]['product.code'] + '_copy';
					this.items[len]['product.label'] = this.items[idx]['product.label'] + '_copy';
					this.items[len]['product.status'] = this.items[idx]['product.status'];
					this.items[len]['product.lists.siteid'] = this.siteid;
					this.items[len]['product.lists.id'] = '';

					for(let attridx in this.items[idx]['attr']) {
						this.items[len]['attr'][attridx] = {};

						for(let key in this.items[idx]['attr'][attridx]) {
							this.items[len]['attr'][attridx][key] = this.items[idx]['attr'][attridx][key];
						}

						this.items[len]['attr'][attridx]['product.lists.siteid'] = this.siteid;
						this.items[len]['attr'][attridx]['product.lists.id'] = '';
					}
				},


				create(idx, option) {
					this.items[idx]['attr'] = []
					this.items[idx]['_show'] = true;
					this.items[idx]['product.id'] = ''
					this.items[idx]['product.status'] = 1
					this.items[idx]['product.siteid'] = this.siteid
					this.items[idx]['product.code'] = option['product.code']
					this.items[idx]['product.lists.siteid'] = this.siteid
					this.items[idx]['product.lists.id'] = ''
					return true
				},


				css(idx) {
					return this.items[idx]['_show'] ? 'show' : 'collapsed';
				},


				fetch(input, type) {
					const siteid = this.siteid
					const used = this.items.map((item) => {
						return item['product.code'] || ''
					})
					const filter = {'&&': [
						{'=~': {'product.code': input || ''}},
						{'>': {'product.status': 0}}
					]};

					if(used) {
						filter['&&'].push({'!=': {'product.code': used}})
					}

					if(type) {
						filter['&&'].push({'==': {'product.type': type}})
					}

					return Aimeos.query(`query {
						searchProducts(filter: ` + JSON.stringify(JSON.stringify(filter)) + `, include: ["attribute"], sort: ["product.code"]) {
							items {
								id
								type
								code
								label
								siteid
								status
								lists {
									attribute(listtype: "variant") {
										id
										siteid
										refid
										editor
										ctime
										mtime
										item {
											id
											type
											code
											label
										}
									}
								}
							}
						}
					}`).then(result => {
						return (result.searchProducts?.items || []).map(function(item) {
							return {
								'product.id': item.id,
								'product.type': item.type,
								'product.code': item.code,
								'product.label': item.label,
								'product.siteid': item.siteid,
								'product.status': item.status,
								'product.lists.siteid': siteid,
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
										'attribute.label': entry.item.label + ' (' + entry.item.type + ')'
									}
								})
							};
						});
					});
				},


				remove(idx) {
					this.items.splice(idx, 1);
				},


				toggle(idx) {
					this.items[idx]['_show'] = this.items[idx]['_show'] ? false : true;
				},


				use(idx, ev) {
					if(ev) {
						this.items[idx] = Object.assign(this.items[idx], ev);
					}
				},


				addAttribute(idx) {

					if(!this.items[idx]['attr']) {
						this.items[idx]['attr'] = [];
					}

					const len = this.items[idx]['attr'].length;

					if(!this.items[idx]['attr'][len]) {
						this.items[idx]['attr'][len] = {};
					}

					for(let key of ['product.lists.id', 'attribute.id', 'attribute.label']) {
						this.items[idx]['attr'][len][key] = '';
					}

					this.items[idx]['attr'][len]['product.lists.siteid'] = this.siteid;
				},


				fetchAttribute(input) {
					const filter = {
						'&&': [
							{'>': {'attribute.status': 0}}
						]
					}

					if(input) {
						filter['&&'].push({
							'||': [
								{'=~': {'attribute.label': input}},
								{'=~': {'attribute.code': input}},
								{'==': {'attribute.id': input}}
							]
						});
					}

					return Aimeos.query(`query {
						searchAttributes(filter: ` + JSON.stringify(JSON.stringify(filter)) + `, sort: ["attribute.type", "attribute.code"]) {
							items {
								id
								type
								label
							}
						}
					  }
					`).then(result => {
						return (result?.searchAttributes?.items || []).map(item => {
							return {'attribute.id': item.id, 'attribute.label': item.label + ' (' + item.type + ')'}
						})
					})
				},


				removeAttribute(idx, attridx) {
					this.items[idx]['attr'].splice(attridx, 1);
				},


				title(idx, attridx) {
					if(this.items[idx]['attr'][attridx]['product.lists.ctime']) {
						return 'Site ID: ' + this.items[idx]['attr'][attridx]['product.lists.siteid'] + "\n"
							+ 'Editor: ' + this.items[idx]['attr'][attridx]['product.lists.editor'] + "\n"
							+ 'Created: ' + this.items[idx]['attr'][attridx]['product.lists.ctime'] + "\n"
							+ 'Modified: ' + this.items[idx]['attr'][attridx]['product.lists.mtime'];
					}
					return ''
				},


				useAttribute(idx, attridx, ev) {
					this.items[idx]['attr'][attridx]['attribute.label'] = ev['attribute.label'];
					this.items[idx]['attr'][attridx]['attribute.id'] = ev['attribute.id'];
				}
			}
		}
	}
};



Aimeos.Product.Stock = {

	mixins() {
		return {
			props: {
				data: {type: String, required: true},
				keys: {type: String, required: true},
				siteid: {type: String, required: true},
				numtypes: {type: String, required: true}
			},
			data() {
				return {
					items: [],
				}
			},
			beforeMount() {
				this.Aimeos = Aimeos;
				this.items = JSON.parse(this.data);
			},
			methods: {
				add(data) {

					const idx = (this.items || []).length;
					this.items[idx] = {};

					for(const key of JSON.parse(this.keys)) {
						this.items[idx][key] = (data && data[key] || '');
					}

					this.items[idx]['stock.siteid'] = this.siteid;
				},


				can(action, idx) {
					return Aimeos.can(action, this.items[idx]['stock.siteid'] || null, this.siteid)
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


				title(idx) {
					if(this.items[idx]['stock.ctime']) {
						return 'Site ID: ' + this.items[idx]['stock.siteid'] + "\n"
							+ 'Editor: ' + this.items[idx]['stock.editor'] + "\n"
							+ 'Created: ' + this.items[idx]['stock.ctime'] + "\n"
							+ 'Modified: ' + this.items[idx]['stock.mtime'];
					}
				},


				toggle(idx) {
					this.items[idx]['checked'] = !this.checked(idx);
				}
			}
		}
	}
};



Aimeos.Product.Subscription = {

	mixins() {
		return {
			props: {
				data: {type: String, required: true},
				keys: {type: String, required: true},
				siteid: {type: String, required: true},
			},
			data() {
				return {
					items: [],
				}
			},
			beforeMount() {
				this.Aimeos = Aimeos;
				this.items = JSON.parse(this.data);
			},
			methods: {
				add(data) {
					const idx = this.items.length;
					this.items[idx] = {};

					for(const key of JSON.parse(this.keys)) {
						this.items[idx][key] = (data && data[key] || '');
					}

					this.items[idx]['product.lists.siteid'] = this.siteid;
				},


				can(action, idx) {
					if((action === 'create' || action === 'delete') && this.items[idx]['product.lists.siteid']) {
						return !this.items[idx]['attribute.id'];
					}

					return Aimeos.can(action, this.items[idx]['product.lists.siteid'] || null, this.siteid)
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
			props: {
				data: {type: String, required: true},
				keys: {type: String, required: true},
				siteid: {type: String, required: true},
				listtype: {type: String, required: true}
			},
			data() {
				return {
					items: [],
				}
			},
			beforeMount() {
				this.Aimeos = Aimeos;
				this.items = JSON.parse(this.data);
			},
			methods: {
				add(data) {

					const idx = (this.items || []).length;
					this.items[idx] = {};

					for(const key of this.keys) {
						this.items[idx][key] = (data && data[key] || '');
					}

					this.items[idx]['product.lists.siteid'] = this.siteid;
					this.items[idx]['product.lists.type'] = this.listtype;
				},


				can(action, idx) {
					return Aimeos.can(action, this.items[idx]['product.lists.siteid'] || null, this.siteid)
				},


				fetch(input, idx) {
					const filter = {
						'&&': [
							{'>': {'supplier.status': 0}}
						]
					}

					if(input) {
						filter['&&'].push({
							'||': [
								{'=~': {'supplier.label': input}},
								{'=~': {'supplier.code': input}},
								{'==': {'supplier.id': input}}
							]
						});
					}

					return Aimeos.query(`query {
						searchSuppliers(filter: ` + JSON.stringify(JSON.stringify(filter)) + `, sort: ["supplier.label"]) {
							items {
								id
								code
								label
							}
						}
					  }
					`).then(result => {
						return (result?.searchSuppliers?.items || []).map(item => {
							return {'supplier.id': item.id, 'supplier.label': item.label + ' (' + item.code + ')'}
						})
					})
				},


				remove(idx) {
					this.items.splice(idx, 1);
				},


				title(idx) {
					if(this.items[idx]['product.lists.ctime']) {
						return 'Site ID: ' + this.items[idx]['product.lists.siteid'] + "\n"
							+ 'Editor: ' + this.items[idx]['product.lists.editor'] + "\n"
							+ 'Created: ' + this.items[idx]['product.lists.ctime'] + "\n"
							+ 'Modified: ' + this.items[idx]['product.lists.mtime'];
					}
					return ''
				},


				use(idx, ev) {
					this.items[idx]['supplier.label'] = ev['supplier.label'];
					this.items[idx]['supplier.id'] = ev['supplier.id'];
				},
			}
		};
	}
};



document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Product.init();
});
