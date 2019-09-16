/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



$(function() {

	Aimeos.Product.init();
});



Aimeos.Product = {

	init : function() {

		Aimeos.Product.Attribute.init();
		Aimeos.Product.Category.init();
		Aimeos.Product.Download.init();
		Aimeos.Product.Product.init();
		Aimeos.Product.Selection.init();
		Aimeos.Product.Stock.init();
		Aimeos.Product.Subscription.init();
	}
};



Aimeos.Product.Download = {

	init : function() {

		this.updateName();
	},


	updateName : function() {

		$(".item-download").on("change", ".fileupload", function(ev) {
			$(this.files).each( function(idx, file) {
				$("input.item-label", ev.delegateTarget).val(file.name);
				$(".custom-file-label", ev.delegateTarget).html(file.name);
			});
		});
	}
};



Aimeos.Product.Selection = {

	mixins : {
		'methods': {

			checkSite : function(key, idx, attridx) {

				if(attridx) {
					return this.items[idx]['attr'][attridx][key] && this.items[idx]['attr'][attridx][key] != this.siteid;
				}

				return this.items[idx][key] && this.items[idx][key] != this.siteid;
			},


			addItem : function(data) {

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


			removeItem : function(idx) {
				this.items.splice(idx, 1);
			},


			getCss : function(idx) {
				return ( idx !== 0 && this.items[idx]['product.id'] && this.items[idx]['attr'].length ? 'collapsed' : 'show' );
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
							var list = result.data || [];

							response( list.map(function(obj) {
								return {
									id: obj.id || null,
									code: obj.attributes['product.code'] || null,
									label: obj.attributes['product.label'] || null
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
		},
		'mounted' : function() {
			var el = document.getElementById('item-selection-group');
			if(el) { Sortable.create(el, {handle: '.act-move'}); }
		}
	},


	init : function() {

		this.showSelection();

		this.selection = new Vue({
			'el': '#item-selection-group',
			'data': {
				'items': $("#item-selection-group").data("items"),
				'keys': $("#item-selection-group").data("keys"),
				'siteid': $("#item-selection-group").data("siteid")
			},
			'mixins': [this.mixins]
		});
	},


	getArticles : function(request, response) {

		Aimeos.options.done(function(data) {

			if(!data.meta.resources['product']) {
				return;
			}

			var params = {}, param = {};

			param['filter'] = {'&&': [{'=~': {'product.code': request.term}}, {'==': {'product.type.code': 'default'}}]};
			param['fields'] = {'product': 'product.id,product.code,product.label'};
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
					var list = result.data || [];

					response( list.map(function(obj) {
						return {
							id: obj.id || null,
							code: obj.attributes['product.code'] || null,
							label: obj.attributes['product.label'] || null
						};
					}));
				}
			});
		});
	},


	showSelection : function() {

		var tab = $(".item-navbar .selection");
		$(".item-basic .item-type option[selected]").val() === 'select' ? tab.show() : tab.hide();

		$(".item-basic .item-type").on("change", function() {
			$("option:selected", this).val() === 'select' ? tab.show() : tab.hide();
		});
	},
};





Aimeos.Product.Attribute = {

	mixins: {
		methods: {
			checkSite : function(key, idx) {
				return this.items[idx][key] && this.items[idx][key] != this.siteid;
			},


			addItem : function(data) {

				var idx = (this.items || []).length;
				this.$set(this.items, idx, {});

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
				}

				this.$set(this.items[idx], this.prefix + 'siteid', this.siteid);
			},


			removeItem : function(idx) {
				this.items.splice(idx, 1);
			},


			getItems : function() {

				return function(request, response, element) {

					var labelFcn = function(attr) {
						return attr['attribute.label'] + ' (' + attr['attribute.type'] + ')';
					}
					Aimeos.getOptions(request, response, element, 'attribute', 'attribute.label', 'attribute.label', null, labelFcn);
				}
			},


			getLabel : function(idx) {

				var label = this.items[idx]['attribute.label'];

				if(this.items[idx]['attribute.type']) {
					label += ' (' + this.items[idx]['attribute.type'] + ')';
				}

				return label;
			},


			update : function(ev) {

				this.$set(this.items[ev.index], this.prefix + 'id', '');
				this.$set(this.items[ev.index], this.prefix + 'siteid', this.siteid);
				this.$set(this.items[ev.index], this.prefix + 'refid', ev.value);
				this.$set(this.items[ev.index], 'attribute.label', ev.label);
				this.$set(this.items[ev.index], 'attribute.type', '');

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
	},


	init : function() {

		this.variant = new Vue({
			'el': '.item-characteristic-variant .attribute-list',
			'data': {
				'items': $(".item-characteristic-variant .attribute-list").data("items"),
				'keys': $(".item-characteristic-variant .attribute-list").data("keys"),
				'prefix': $(".item-characteristic-variant .attribute-list").data("prefix"),
				'siteid': $(".item-characteristic-variant .attribute-list").data("siteid")
			},
			'mixins': [this.mixins]
		});

		this.attribute = new Vue({
			'el': '.item-characteristic-attribute .attribute-list',
			'data': {
				'items': $(".item-characteristic-attribute .attribute-list").data("items"),
				'keys': $(".item-characteristic-attribute .attribute-list").data("keys"),
				'prefix': $(".item-characteristic-attribute .attribute-list").data("prefix"),
				'siteid': $(".item-characteristic-attribute .attribute-list").data("siteid")
			},
			'mixins': [this.mixins]
		});


		this.hidden = new Vue({
			'el': '.item-characteristic-hidden .attribute-list',
			'data': {
				'items': $(".item-characteristic-hidden .attribute-list").data("items"),
				'keys': $(".item-characteristic-hidden .attribute-list").data("keys"),
				'prefix': $(".item-characteristic-hidden .attribute-list").data("prefix"),
				'siteid': $(".item-characteristic-hidden .attribute-list").data("siteid")
			},
			'mixins': [this.mixins]
		});


		this.config = new Vue({
			'el': '.item-option-config .attribute-list',
			'data': {
				'items': $(".item-option-config .attribute-list").data("items"),
				'keys': $(".item-option-config .attribute-list").data("keys"),
				'prefix': $(".item-option-config .attribute-list").data("prefix"),
				'siteid': $(".item-option-config .attribute-list").data("siteid")
			},
			'mixins': [this.mixins]
		});


		this.custom = new Vue({
			'el': '.item-option-custom .attribute-list',
			'data': {
				'items': $(".item-option-custom .attribute-list").data("items"),
				'keys': $(".item-option-custom .attribute-list").data("keys"),
				'prefix': $(".item-option-custom .attribute-list").data("prefix"),
				'siteid': $(".item-option-custom .attribute-list").data("siteid")
			},
			'mixins': [this.mixins]
		});
	}
};



Aimeos.Product.Category = {

	mixins : {
		methods: {
			checkSite : function(idx) {
				return this.items[idx]['catalog.lists.siteid'] && this.items[idx]['catalog.lists.siteid'] != this.siteid;
			},


			addItem : function(data) {

				var idx = (this.items || []).length;
				this.$set(this.items, idx, {});

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
				}

				this.$set(this.items[idx], 'catalog.lists.siteid', this.siteid);
				this.$set(this.items[idx], 'catalog.lists.type', this.listtype);
			},


			removeItem : function(idx) {
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
	},


	init : function() {

		this.default = new Vue({
			'el': '.item-category .catalog-default .category-list',
			'data': {
				'items': $(".item-category .catalog-default .category-list").data("items"),
				'keys': $(".item-category .catalog-default .category-list").data("keys"),
				'listtype': $(".item-category .catalog-default .category-list").data("listtype"),
				'siteid': $(".item-category .catalog-default .category-list").data("siteid")
			},
			'mixins': [this.mixins]
		});


		this.promotion = new Vue({
			'el': '.item-category .catalog-promotion .category-list',
			'data': {
				'items': $(".item-category .catalog-promotion .category-list").data("items"),
				'keys': $(".item-category .catalog-promotion .category-list").data("keys"),
				'listtype': $(".item-category .catalog-promotion .category-list").data("listtype"),
				'siteid': $(".item-category .catalog-promotion .category-list").data("siteid")
			},
			'mixins': [this.mixins]
		});
	}
};



Aimeos.Product.Product = {

	mixins : {
		methods: {
			checkSite : function(key, idx) {
				return this.items[idx][key] && this.items[idx][key] != this.siteid;
			},


			addItem : function(data) {

				var idx = (this.items || []).length;
				this.$set(this.items, idx, {});

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
				}

				this.$set(this.items[idx], this.prefix + 'siteid', this.siteid);
			},


			removeItem : function(idx) {
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
	},


	init : function()  {

		this.suggest = new Vue({
			'el': '.item-related-suggest .product-list',
			'data': {
				'items': $(".item-related-suggest .product-list").data("items"),
				'keys': $(".item-related-suggest .product-list").data("keys"),
				'prefix': $(".item-related-suggest .product-list").data("prefix"),
				'siteid': $(".item-related-suggest .product-list").data("siteid")
			},
			'mixins': [this.mixins]
		});


		this.bought = new Vue({
			'el': '.item-related-bought .product-list',
			'data': {
				'items': $(".item-related-bought .product-list").data("items"),
				'keys': $(".item-related-bought .product-list").data("keys"),
				'prefix': $(".item-related-bought .product-list").data("prefix"),
				'siteid': $(".item-related-bought .product-list").data("siteid")
			},
			'mixins': [this.mixins]
		});

		this.bundle = new Vue({
			'el': '.item-bundle .product-list',
			'data': {
				'items': $(".item-bundle .product-list").data("items"),
				'keys': $(".item-bundle .product-list").data("keys"),
				'prefix': $(".item-bundle .product-list").data("prefix"),
				'siteid': $(".item-bundle .product-list").data("siteid")
			},
			'mixins': [this.mixins]
		});

		this.showBundles();
	},


	showBundles : function() {

		var tab = $(".item-navbar .bundle");
		$(".item-basic .item-type option[selected]").val() === 'bundle' ? tab.show() : tab.hide();

		$(".item-basic .item-type").on("change", function() {
			$("option:selected", this).val() === 'bundle' ? tab.show() : tab.hide();
		});
	}
};



Aimeos.Product.Stock = {

	init : function()  {

		this.stock = new Vue({
			'el': '.item-stock .stock-list',
			'data': {
				'items': $(".item-stock .stock-list").data("items"),
				'keys': $(".item-stock .stock-list").data("keys"),
				'siteid': $(".item-stock .stock-list").data("siteid"),
				'numtypes': $(".item-stock .stock-list").data("numtypes")
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


				addItem : function(data) {

					var idx = (this.items || []).length;
					this.$set(this.items, idx, {});

					for(var key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], 'stock.siteid', this.siteid);
				},


				removeItem : function(idx) {
					this.items.splice(idx, 1);
				}
			}
		});
	}
};



Aimeos.Product.Subscription = {

	init : function()  {

		this.subscription = new Vue({
			'el': '.item-subscription .subscription-list',
			'data': {
				'items': $(".item-subscription .subscription-list").data("items"),
				'keys': $(".item-subscription .subscription-list").data("keys"),
				'siteid': $(".item-subscription .subscription-list").data("siteid")

			},
			methods: {
				getAttributeValue: function(idx) {
					return 'P' + (this.items[idx]['Y'] || 0) + 'Y' + (this.items[idx]['M'] || 0) + 'M'
						+ (this.items[idx]['W'] || 0) + 'W' + (this.items[idx]['D'] || 0) + 'D';
				},


				getReadOnly: function(idx) {
					return this.items[idx]['attribute.id'] != '' && this.items[idx]['attribute.id'] != null;
				},


				addItem : function(data) {
					var idx = this.items.length;
					this.$set(this.items, idx, {});

					for(var key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
					}

					this.$set(this.items[idx], 'product.lists.siteid', this.siteid);
				},


				removeItem : function(idx) {
					this.items.splice(idx, 1);
				}
			}
		});
	}
};
