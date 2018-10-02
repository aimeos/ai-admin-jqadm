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
					return this.items[idx]['attr'][attridx][key] != this.siteid;
				}

				return this.items[idx][key] != this.siteid;
			},


			addItem : function() {

				var idx = this.items.length;
				this.$set(this.items, idx, {});

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items[idx], key, '');
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
				return ( idx !== 0 && this.items[idx]['product.id'] ? 'collapsed' : 'show' );
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


			updateProductItem : function(idx, ev, item) {

				this.$set(this.items[idx], 'product.id', item.id);
				this.$set(this.items[idx], 'product.code', item.code);
				this.$set(this.items[idx], 'product.label', item.label);
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

		this.vselection = new Vue({
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
		$(".item-basic .item-typeid option[selected]").data("code") === 'select' ? tab.show() : tab.hide();

		$(".item-basic .item-typeid").on("change", function() {
			$("option:selected", this).data("code") === 'select' ? tab.show() : tab.hide();
		});
	},
};





Aimeos.Product.Attribute = {

	mixins: {
		methods: {
			checkSite : function(key, idx) {
				return this.items[key][idx] != this.siteid;
			},


			addItem : function() {

				var idx = (this.items[this.prefix + 'id'] || []).length;

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items, key, (this.items[key] || []).concat(['']));
				}

				this.$set(this.items[this.prefix + 'siteid'], idx, this.siteid);
			},


			removeItem : function(idx) {
				for(key in this.items) {
					this.items[key].splice(idx, 1);
				}
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

				var label = this.items['attribute.label'][idx];

				if(this.items['attribute.type'][idx]) {
					label += ' (' + this.items['attribute.type'][idx] + ')';
				}

				return label;
			},


			update : function(ev) {
				this.$set(this.items[this.prefix + 'id'], ev.index, '');
				this.$set(this.items[this.prefix + 'refid'], ev.index, ev.value);
				this.$set(this.items['attribute.label'], ev.index, ev.label);
				this.$set(this.items['attribute.type'], ev.index, '');
			}
		}
	},


	init : function() {

		this.vvariant = new Vue({
			'el': '.item-characteristic-variant .attribute-list',
			'data': {
				'items': $(".item-characteristic-variant .attribute-list").data("items"),
				'keys': $(".item-characteristic-variant .attribute-list").data("keys"),
				'prefix': $(".item-characteristic-variant .attribute-list").data("prefix"),
				'siteid': $(".item-characteristic-variant .attribute-list").data("siteid")
			},
			'mixins': [this.mixins]
		});

		this.vattribute = new Vue({
			'el': '.item-characteristic-attribute .attribute-list',
			'data': {
				'items': $(".item-characteristic-attribute .attribute-list").data("items"),
				'keys': $(".item-characteristic-attribute .attribute-list").data("keys"),
				'prefix': $(".item-characteristic-attribute .attribute-list").data("prefix"),
				'siteid': $(".item-characteristic-attribute .attribute-list").data("siteid")
			},
			'mixins': [this.mixins]
		});


		this.vhidden = new Vue({
			'el': '.item-characteristic-hidden .attribute-list',
			'data': {
				'items': $(".item-characteristic-hidden .attribute-list").data("items"),
				'keys': $(".item-characteristic-hidden .attribute-list").data("keys"),
				'prefix': $(".item-characteristic-hidden .attribute-list").data("prefix"),
				'siteid': $(".item-characteristic-hidden .attribute-list").data("siteid")
			},
			'mixins': [this.mixins]
		});


		this.vconfig = new Vue({
			'el': '.item-option-config .attribute-list',
			'data': {
				'items': $(".item-option-config .attribute-list").data("items"),
				'keys': $(".item-option-config .attribute-list").data("keys"),
				'prefix': $(".item-option-config .attribute-list").data("prefix"),
				'siteid': $(".item-option-config .attribute-list").data("siteid")
			},
			'mixins': [this.mixins]
		});


		this.vcustom = new Vue({
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
			checkSite : function(key, idx) {
				return this.items[key][idx] != this.siteid;
			},


			addItem : function() {
				var idx = (this.items[this.prefix + 'id'] || []).length;

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items, key, (this.items[key] || []).concat(['']));
				}

				this.$set(this.items[this.prefix + 'siteid'], idx, this.siteid);
				this.$set(this.items[this.prefix + 'typeid'], idx, this.listtypeid);
			},


			removeItem : function(idx) {
				for(key in this.items) {
					this.items[key].splice(idx, 1);
				}
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

				var label = this.items['catalog.label'][idx];

				if(this.items['catalog.code'][idx]) {
					 label += ' (' + this.items['catalog.code'][idx] + ')';
				}

				return label;
			},


			update : function(ev) {
				this.$set(this.items[this.prefix + 'id'], ev.index, '');
				this.$set(this.items[this.prefix + 'siteid'], ev.index, this.siteid);
				this.$set(this.items[this.prefix + 'typeid'], ev.index, this.listtypeid);
				this.$set(this.items[this.prefix + 'refid'], ev.index, ev.value);
				this.$set(this.items['catalog.label'], ev.index, ev.label);
				this.$set(this.items['catalog.id'], ev.index, ev.value);
				this.$set(this.items['catalog.code'], ev.index, '');
			}
		}
	},


	init : function() {

		this.vdefault = new Vue({
			'el': '.item-category .catalog-default .category-list',
			'data': {
				'items': $(".item-category .catalog-default .category-list").data("items"),
				'keys': $(".item-category .catalog-default .category-list").data("keys"),
				'listtypeid': $(".item-category .catalog-default .category-list").data("listtypeid"),
				'prefix': $(".item-category .catalog-default .category-list").data("prefix"),
				'siteid': $(".item-category .catalog-default .category-list").data("siteid")
			},
			'mixins': [this.mixins]
		});


		this.vpromotion = new Vue({
			'el': '.item-category .catalog-promotion .category-list',
			'data': {
				'items': $(".item-category .catalog-promotion .category-list").data("items"),
				'keys': $(".item-category .catalog-promotion .category-list").data("keys"),
				'listtypeid': $(".item-category .catalog-promotion .category-list").data("listtypeid"),
				'prefix': $(".item-category .catalog-promotion .category-list").data("prefix"),
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
				return this.items[key][idx] != this.siteid;
			},


			addItem : function() {

				var idx = (this.items[this.prefix + 'id'] || []).length;

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items, key, (this.items[key] || []).concat(['']));
				}

				this.$set(this.items[this.prefix + 'siteid'], idx, this.siteid);
			},


			removeItem : function(idx) {
				for(key in this.items) {
					this.items[key].splice(idx, 1);
				}
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

				var label = this.items['product.label'][idx];

				if(this.items['product.code'][idx]) {
					label += ' (' + this.items['product.code'][idx] + ')';
				}

				return label;
			},


			update : function(ev) {
				this.$set(this.items[this.prefix + 'id'], ev.index, '');
				this.$set(this.items[this.prefix + 'siteid'], ev.index, this.siteid);
				this.$set(this.items[this.prefix + 'refid'], ev.index, ev.value);
				this.$set(this.items['product.label'], ev.index, ev.label);
				this.$set(this.items['product.code'], ev.index, '');
			}
		}
	},


	init : function()  {

		this.vsuggest = new Vue({
			'el': '.item-related-suggest .product-list',
			'data': {
				'items': $(".item-related-suggest .product-list").data("items"),
				'keys': $(".item-related-suggest .product-list").data("keys"),
				'prefix': $(".item-related-suggest .product-list").data("prefix"),
				'siteid': $(".item-related-suggest .product-list").data("siteid")
			},
			'mixins': [this.mixins]
		});


		this.vbought = new Vue({
			'el': '.item-related-bought .product-list',
			'data': {
				'items': $(".item-related-bought .product-list").data("items"),
				'keys': $(".item-related-bought .product-list").data("keys"),
				'prefix': $(".item-related-bought .product-list").data("prefix"),
				'siteid': $(".item-related-bought .product-list").data("siteid")
			},
			'mixins': [this.mixins]
		});

		this.vbundle = new Vue({
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
		$(".item-basic .item-typeid option[selected]").data("code") === 'bundle' ? tab.show() : tab.hide();

		$(".item-basic .item-typeid").on("change", function() {
			$("option:selected", this).data("code") === 'bundle' ? tab.show() : tab.hide();
		});
	}
};



Aimeos.Product.Stock = {

	init : function()  {

		this.vstock = new Vue({
			'el': '.item-stock .stock-list',
			'data': {
				'items': $(".item-stock .stock-list").data("items"),
				'keys': $(".item-stock .stock-list").data("keys"),
				'prefix': $(".item-stock .stock-list").data("prefix"),
				'siteid': $(".item-stock .stock-list").data("siteid"),
				'numtypes': $(".item-stock .stock-list").data("numtypes")
			},
			methods: {
				checkSite : function(key, idx) {
					return this.items[key][idx] != this.siteid;
				},


				addItem : function() {

					var idx = (this.items[this.prefix + 'id'] || []).length;

					for(var key in this.keys) {
						key = this.keys[key]; this.$set(this.items, key, (this.items[key] || []).concat(['']));
					}

					this.$set(this.items[this.prefix + 'siteid'], idx, this.siteid);
					this.numtypes--;
				},


				removeItem : function(idx) {
					for(key in this.items) {
						this.items[key].splice(idx, 1);
					}
					this.numtypes++;
				}
			}
		});
	}
};



Aimeos.Product.Subscription = {

	init : function()  {

		this.vsubscription = new Vue({
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


				addItem : function() {
					var idx = this.items.length;
					this.$set(this.items, idx, {});

					for(var key in this.keys) {
						key = this.keys[key]; this.$set(this.items[idx], key, '');
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
