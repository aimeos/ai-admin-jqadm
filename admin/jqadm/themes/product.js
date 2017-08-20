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
			Aimeos.focusBefore($(this).parents("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.parents("tr").find("input.item-label").val(node.val());
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
			Aimeos.focusBefore($(this).parents("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.parents("tr").find("input.item-label").val(node.val());
	},


	setupComponents : function() {

		$(".item-characteristic-attribute .combobox").combobox({
			getfcn: Aimeos.getOptionsAttributes,
			select: Aimeos.Product.Characteristic.Attribute.select
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
			Aimeos.focusBefore($(this).parents("tr")).remove();
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
			Aimeos.focusBefore($(this).parents("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.parents("tr").find("input.item-label").val(node.val());
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
			Aimeos.focusBefore($(this).parents("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.parents("tr").find("input.item-label").val(node.val());
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
			Aimeos.focusBefore($(this).parents("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.parents("tr").find("input.item-label").val(node.val());
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
			Aimeos.focusBefore($(this).parents("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.parents("tr").find("input.item-label").val(node.val());
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
			Aimeos.focusBefore($(this).parents("tr")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.parents("tr").find("input.item-label").val(node.val());
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

			var code = $(this).parents(".group-item").find("input.item-code").val();
			var line = $(this).parents(".selection-item-attributes").find(".prototype");
			var clone = Aimeos.addClone(line, Aimeos.getOptionsAttributes, Aimeos.Product.Selection.select);

			$("input.item-attr-ref", clone).val(code);
		});
	},


	removeAttribute : function() {

		$(".item-selection").on("click", ".selection-item-attributes .act-delete", function() {
			Aimeos.focusBefore($(this).parents("tr")).remove();
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
			var block = $(this).parents(".group-item");
			var clone = block.clone();

			$(".card-block", clone).attr("id", "item-selection-group-data-" + number);
			$(".card-header", clone).attr("id", "item-selection-group-item-" + number);
			$(".card-header", clone).attr("data-target", "#item-selection-group-data-" + number);
			$(".card-header", clone).attr("aria-controls", "item-selection-group-data-" + number);

			clone.insertAfter(block);
			$(".ai-combobox", clone).remove();
			$(".combobox", clone).combobox({
				getfcn: Aimeos.getOptionsAttributes,
				select: Aimeos.Product.Selection.select
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
			Aimeos.focusBefore($(this).parents(".group-item")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.parents("tr").find("input.item-attr-label").val(node.val());
	},


	setupComponents : function() {

		$(".item-selection .combobox").combobox({
			getfcn: Aimeos.getOptionsAttributes,
			select: Aimeos.Product.Selection.select
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
			var item = $(this).parents(".group-item");
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
		});
	},


	removeLine : function() {

		$(".item-stock").on("click", ".act-delete", function() {
			Aimeos.focusBefore($(this).parents("tr")).remove();
		});
	}
};




$(function() {

	Aimeos.Product.init();
});
