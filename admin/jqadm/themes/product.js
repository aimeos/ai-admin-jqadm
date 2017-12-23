/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */



$(function() {

	Aimeos.Product.init();
});



Aimeos.Product = {

	init : function() {

		Aimeos.Product.Attribute.init();
		Aimeos.Product.Bundle.init();
		Aimeos.Product.Category.init();
		Aimeos.Product.Download.init();
		Aimeos.Product.Product.init();
		Aimeos.Product.Selection.init();
		Aimeos.Product.Stock.init();
	}
};



Aimeos.Product.Bundle = {

	init : function() {

		this.addLine();
		this.removeLine();
		this.setupComponents();
		this.showBundles();
	},


	addLine : function() {

		$(".item-product .item-bundle").on("click", ".act-add", function(ev) {
			Aimeos.addClone(
				$(".prototype", ev.delegateTarget),
				Aimeos.getOptionsProducts,
				Aimeos.Product.Bundle.select);
		});
	},


	removeLine : function() {

		$(".item-product .item-bundle").on("click", ".act-delete", function() {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("tr").find("input.item-label").val(node.val());
	},


	setupComponents : function() {

		$(".item-product .item-bundle .combobox").combobox({
			getfcn: Aimeos.getOptionsProducts,
			select: Aimeos.Product.Bundle.select
		});
	},


	showBundles : function() {

		var tab = $(".item-navbar .bundle");
		$(".item-basic .item-typeid option[selected]").data("code") === 'bundle' ? tab.show() : tab.hide();

		$(".item-basic .item-typeid").on("change", function() {
			$("option:selected", this).data("code") === 'bundle' ? tab.show() : tab.hide();
		});
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
			});
		});
	}
};



Aimeos.Product.Selection = {

	init : function() {

		this.addBlock();
		this.copyBlock();
		this.removeBlock();
		this.addAttribute();
		this.removeAttribute();
		this.setupComponents();
		this.showSelection();
		this.showVariant();
		this.updateCode();
	},


	addAttribute : function() {

		$(".item-selection").on("click", ".selection-item-attributes .act-add", function(ev) {

			var code = $(this).closest(".group-item").find("input.item-code").val();
			var line = $(this).closest(".selection-item-attributes").find(".prototype");
			var clone = Aimeos.addClone(line, Aimeos.getOptionsAttributes, Aimeos.Product.Selection.selectAttributes);

			$("input.item-attr-ref", clone).val(code);
		});
	},


	removeAttribute : function() {

		$(".item-selection").on("click", ".selection-item-attributes .act-delete", function() {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	addBlock : function() {

		$(".item-selection").on("click", ".card-tools-more .act-add", function(ev) {
			ev.stopPropagation();

			var number = Math.floor((Math.random() * 1000));
			var node = $(".group-item.prototype", ev.delegateTarget);
			var clone = node.clone().removeClass("prototype");

			$(".card-block", clone).attr("id", "item-selection-group-data-" + number);
			$(".card-header", clone).attr("id", "item-selection-group-item-" + number);
			$(".card-header", clone).attr("data-target", "#item-selection-group-data-" + number);
			$(".card-header", clone).attr("aria-controls", "item-selection-group-data-" + number);

			$("[disabled='disabled']", clone).prop("disabled", false);
			clone.insertBefore(node);
		});
	},


	copyBlock : function() {

		$(".item-selection").on("click", ".header .act-copy", function(ev) {
			ev.stopPropagation();

			var number = Math.floor((Math.random() * 1000));
			var block = $(this).closest(".group-item");
			var clone = block.clone();

			$(".card-block", clone).attr("id", "item-selection-group-data-" + number);
			$(".card-header", clone).attr("id", "item-selection-group-item-" + number);
			$(".card-header", clone).attr("data-target", "#item-selection-group-data-" + number);
			$(".card-header", clone).attr("aria-controls", "item-selection-group-data-" + number);

			clone.insertAfter(block);
			$(".ai-combobox", clone).remove();
			$(".combobox", clone).combobox({
				getfcn: Aimeos.getOptionsAttributes,
				select: Aimeos.Product.Selection.selectAttributes
			});

			var codeNode = $("input.item-code", clone);
			codeNode.val(codeNode.val() + '_copy');
			codeNode.trigger("blur");

			$("input.item-listid", clone).val('');
			$("input.item-id", clone).val('');
			$(".card-header .header-label", clone).empty();
		});
	},


	removeBlock : function() {

		$(".item-selection").on("click", ".header .act-delete", function() {
			Aimeos.focusBefore($(this).closest(".group-item")).remove();
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


	selectAttributes: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("tr").find("input.item-attr-label").val(node.val());
	},


	setupComponents : function() {

		$(".item-selection .combobox").combobox({
			getfcn: Aimeos.getOptionsAttributes,
			select: Aimeos.Product.Selection.selectAttributes
		});

		$(".aimeos .item-product .item-selection").on("focus", ".item-code", function(ev) {

			if( $(".item-id", $(ev.target).closest(".group-item")).val() == '' ) {

				$(this).autocomplete({
					source: Aimeos.Product.Selection.getArticles,
					minLength: 3,
					delay: 200,
					select: function(event, ui) {
						var el = $(ev.target).closest(".group-item");

						$(".item-id", el).val(ui.item.id);
						$(".item-code", el).val(ui.item.code);
						$(".item-label", el).val(ui.item.label);

						return false;
					}
				}).autocomplete( "instance" )._renderItem = function(ul, item) {
					return $("<li>").append("<div>" + item.code + "</div>").appendTo(ul);
				};
			}
		});
	},


	showSelection : function() {

		var tab = $(".item-navbar .selection");
		$(".item-basic .item-typeid option[selected]").data("code") === 'select' ? tab.show() : tab.hide();

		$(".item-basic .item-typeid").on("change", function() {
			$("option:selected", this).data("code") === 'select' ? tab.show() : tab.hide();
		});
	},


	showVariant : function() {

		$(".item-selection").on("click", ".act-view", function(ev) {
			ev.stopPropagation();
			return true;
		});
	},


	updateCode : function() {

		$(".item-selection").on("blur", "input.item-code", function() {
			var item = $(this).closest(".group-item");
			var value = $(this).val();

			$(".header .item-code", item).html(value);
			$(".selection-item-attributes .item-attr-ref", item).val(value);
		});
	}
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
				'siteid': $(".item-stock .stock-list").data("siteid")
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
				},


				removeItem : function(idx) {
					for(key in this.items) {
						this.items[key].splice(idx, 1);
					}
				}
			}
		});
	}
};
