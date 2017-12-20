/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */



Aimeos.Product = {

	init : function() {

		Aimeos.Product.Characteristic.init();
		Aimeos.Product.Bundle.init();
		Aimeos.Product.Category.init();
		Aimeos.Product.Option.init();
		Aimeos.Product.Related.init();
		Aimeos.Product.Selection.init();
		Aimeos.Product.Stock.init();
		Aimeos.Product.Download.init();
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



Aimeos.Product.Characteristic = {

	init : function() {

		Aimeos.Product.Characteristic.Attribute.init();
		Aimeos.Product.Characteristic.Hidden.init();
		Aimeos.Product.Characteristic.Property.init();
	}
};


Aimeos.Product.Characteristic.Attribute = {

	init : function() {

		this.addLine();
		this.removeLine();
		this.setupComponents();
	},


	addLine : function() {

		$(".item-characteristic-attribute").on("click", ".act-add", function(ev) {
			Aimeos.addClone(
				$(".prototype", ev.delegateTarget),
				Aimeos.getOptionsAttributes,
				Aimeos.Product.Characteristic.Attribute.select);
		});
	},


	removeLine : function() {

		$(".item-characteristic-attribute").on("click", ".act-delete", function() {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("tr").find("input.item-label").val(node.val());
	},


	setupComponents : function() {

		$(".item-characteristic-attribute .combobox").combobox({
			getfcn: Aimeos.getOptionsAttributes,
			select: Aimeos.Product.Characteristic.Attribute.select
		});
	}
};


Aimeos.Product.Characteristic.Hidden = {

	init : function() {

		this.addLine();
		this.removeLine();
		this.setupComponents();
	},


	addLine : function() {

		$(".item-characteristic-hidden").on("click", ".act-add", function(ev) {
			Aimeos.addClone(
				$(".prototype", ev.delegateTarget),
				Aimeos.getOptionsAttributes,
				Aimeos.Product.Characteristic.Hidden.select);
		});
	},


	removeLine : function() {

		$(".item-characteristic-hidden").on("click", ".act-delete", function() {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("tr").find("input.item-label").val(node.val());
	},


	setupComponents : function() {

		$(".item-characteristic-hidden .combobox").combobox({
			getfcn: Aimeos.getOptionsAttributes,
			select: Aimeos.Product.Characteristic.Hidden.select
		});
	}
};


Aimeos.Product.Characteristic.Property = {

	init : function() {

		this.addLine();
		this.removeLine();
		this.setupComponents();
	},


	addLine : function() {

		$(".item-characteristic-property").on("click", ".act-add", function(ev) {
			Aimeos.addClone($(".prototype", ev.delegateTarget), Aimeos.getOptionsLanguages);
		});
	},


	removeLine : function() {

		$(".item-characteristic-property").on("click", ".act-delete", function() {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	setupComponents : function() {
		$(".item-characteristic-property .combobox").combobox({getfcn: Aimeos.getOptionsLanguages});
	}
};



Aimeos.Product.Category = {

	init : function() {

		this.addLine();
		this.removeLine();
		this.setupComponents();
	},


	addLine : function() {

		$(".item-category .category-list").on("click", ".act-add", function(ev) {
			Aimeos.addClone(
				$(".prototype", ev.delegateTarget),
				Aimeos.getOptionsCategories,
				Aimeos.Product.Category.select);
		});
	},


	removeLine : function() {

		$(".item-category .category-list").on("click", ".act-delete", function() {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("tr").find("input.item-label").val(node.val());
	},


	setupComponents : function() {

		$(".item-category .combobox").combobox({
			getfcn: Aimeos.getOptionsCategories,
			select: Aimeos.Product.Category.select
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



Aimeos.Product.Option = {

	init : function() {

		Aimeos.Product.Option.Config.init();
		Aimeos.Product.Option.Custom.init();
	}
};


Aimeos.Product.Option.Config = {

	init : function() {

		this.addLine();
		this.removeLine();
		this.setupComponents();
	},


	addLine : function() {

		$(".item-option-config").on("click", ".act-add", function(ev) {
			Aimeos.addClone(
				$(".prototype", ev.delegateTarget),
				Aimeos.getOptionsAttributes,
				Aimeos.Product.Option.Config.select);
		});
	},


	removeLine : function() {

		$(".item-option-config").on("click", ".act-delete", function() {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("tr").find("input.item-label").val(node.val());
	},


	setupComponents : function() {

		$(".item-option-config .combobox").combobox({
			getfcn: Aimeos.getOptionsAttributes,
			select: Aimeos.Product.Option.Config.select
		});
	}
};


Aimeos.Product.Option.Custom = {

	init : function() {

		this.addLine();
		this.removeLine();
		this.setupComponents();
	},


	addLine : function() {

		$(".item-option-custom").on("click", ".act-add", function(ev) {
			Aimeos.addClone(
				$(".prototype", ev.delegateTarget),
				Aimeos.getOptionsAttributes,
				Aimeos.Product.Option.Custom.select);
		});
	},


	removeLine : function() {

		$(".item-option-custom").on("click", ".act-delete", function() {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("tr").find("input.item-label").val(node.val());
	},


	setupComponents : function() {

		$(".item-option-custom .combobox").combobox({
			getfcn: Aimeos.getOptionsAttributes,
			select: Aimeos.Product.Option.Custom.select
		});
	}
};



Aimeos.Product.Related = {

	init : function() {

		Aimeos.Product.Related.Bought.init();
		Aimeos.Product.Related.Suggest.init();
	}
};



Aimeos.Product.Related.Bought = {

	init : function() {

		this.addLine();
		this.removeLine();
		this.setupComponents();
	},


	addLine : function() {

		$(".item-product .item-related-bought").on("click", ".act-add", function(ev) {
			Aimeos.addClone(
				$(".prototype", ev.delegateTarget),
				Aimeos.getOptionsProducts,
				Aimeos.Product.Bundle.select);
		});
	},


	removeLine : function() {

		$(".item-product .item-related-bought").on("click", ".act-delete", function() {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("tr").find("input.item-label").val(node.val());
	},


	setupComponents : function() {

		$(".item-product .item-related-bought .combobox").combobox({
			getfcn: Aimeos.getOptionsProducts,
			select: Aimeos.Product.Bundle.select
		});
	}
};



Aimeos.Product.Related.Suggest = {

	init : function() {

		this.addLine();
		this.removeLine();
		this.setupComponents();
	},


	addLine : function() {

		$(".item-product .item-related-suggest").on("click", ".act-add", function(ev) {
			Aimeos.addClone(
				$(".prototype", ev.delegateTarget),
				Aimeos.getOptionsProducts,
				Aimeos.Product.Bundle.select);
		});
	},


	removeLine : function() {

		$(".item-product .item-related-suggest").on("click", ".act-delete", function() {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("tr").find("input.item-label").val(node.val());
	},


	setupComponents : function() {

		$(".item-product .item-related-suggest .combobox").combobox({
			getfcn: Aimeos.getOptionsProducts,
			select: Aimeos.Product.Bundle.select
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



Aimeos.Product.Stock = {

	init : function() {

		this.addLine();
		this.removeLine();
		this.setup();
	},


	addLine : function() {

		$(".item-stock").on("click", ".act-add", function(ev) {

			var clone = Aimeos.addClone($(".prototype", ev.delegateTarget));

			$(".date-prototype", clone).each(function(idx, elem) {
				$(elem).addClass("date").removeClass("date-prototype");
				$(elem).datepicker({
					dateFormat: $(elem).data("format"),
					constrainInput: false
				});
			});

			if($(".item-stock input.item-typeid").length > 0 && $(".item-stock .stock-list tbody > :not(tr.prototype)").length > 0) {
				$(".item-stock .act-add").hide();
			}
		});
	},


	removeLine : function() {

		$(".item-stock").on("click", ".act-delete", function(ev) {

			var line = $(this).closest("tr");

			Aimeos.focusBefore(line).remove();

			if($(".item-stock input.item-typeid").length == 0 || $(".item-stock .stock-list tbody > tr:not(.prototype)").length == 0) {
				$(".item-stock .act-add").show();
			}
		});
	},


	setup : function() {

		if($(".item-stock input.item-typeid").length > 0 && $(".item-stock .stock-list tbody > :not(tr.prototype)").length > 0) {
			$(".item-stock .act-add").hide();
		}
	}
};




$(function() {

	Aimeos.Product.init();
});
