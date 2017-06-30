/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */



Aimeos.Product = {

	init : function() {

		Aimeos.Product.List.init();
		Aimeos.Product.Item.init();
	}
};



Aimeos.Product.List = {

	element : null,


	init : function() {

		this.askDelete();
		this.confirmDelete();
	},


	askDelete : function() {
		var self = this;

		$(".list-product .list-items").on("click", ".act-delete", function(e) {
			$("#confirm-delete").modal("show", $(this));
			self.element = $(this);
			return false;
		});
	},


	confirmDelete : function() {
		var self = this;

		$("#confirm-delete").on("click", ".btn-danger", function(e) {
			if(self.element) {
				window.location = self.element.attr("href");
			}
		});
	}
};



Aimeos.Product.Item = {

	init : function() {

		Aimeos.Product.Item.Characteristic.init();
		Aimeos.Product.Item.Bundle.init();
		Aimeos.Product.Item.Category.init();
		Aimeos.Product.Item.Option.init();
		Aimeos.Product.Item.Related.init();
		Aimeos.Product.Item.Selection.init();
		Aimeos.Product.Item.Stock.init();
		Aimeos.Product.Item.Download.init();
	}
};



Aimeos.Product.Item.Bundle = {

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
				Aimeos.Product.Item.Bundle.select);
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
			select: Aimeos.Product.Item.Bundle.select
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



Aimeos.Product.Item.Characteristic = {

	init : function() {

		Aimeos.Product.Item.Characteristic.Attribute.init();
		Aimeos.Product.Item.Characteristic.Property.init();
	}
};


Aimeos.Product.Item.Characteristic.Attribute = {

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
				Aimeos.Product.Item.Characteristic.Attribute.select);
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
			select: Aimeos.Product.Item.Characteristic.Attribute.select
		});
	}
};


Aimeos.Product.Item.Characteristic.Property = {

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



Aimeos.Product.Item.Category = {

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
				Aimeos.Product.Item.Category.select);
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
			select: Aimeos.Product.Item.Category.select
		});
	}
};



Aimeos.Product.Item.Download = {

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



Aimeos.Product.Item.Option = {

	init : function() {

		Aimeos.Product.Item.Option.Config.init();
		Aimeos.Product.Item.Option.Custom.init();
	}
};


Aimeos.Product.Item.Option.Config = {

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
				Aimeos.Product.Item.Option.Config.select);
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
			select: Aimeos.Product.Item.Option.Config.select
		});
	}
};


Aimeos.Product.Item.Option.Custom = {

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
				Aimeos.Product.Item.Option.Custom.select);
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
			select: Aimeos.Product.Item.Option.Custom.select
		});
	}
};



Aimeos.Product.Item.Related = {

	init : function() {

		Aimeos.Product.Item.Related.Bought.init();
		Aimeos.Product.Item.Related.Suggest.init();
	}
};



Aimeos.Product.Item.Related.Bought = {

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
				Aimeos.Product.Item.Bundle.select);
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
			select: Aimeos.Product.Item.Bundle.select
		});
	}
};



Aimeos.Product.Item.Related.Suggest = {

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
				Aimeos.Product.Item.Bundle.select);
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
			select: Aimeos.Product.Item.Bundle.select
		});
	}
};



Aimeos.Product.Item.Selection = {

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
			var clone = Aimeos.addClone(line, Aimeos.getOptionsAttributes, Aimeos.Product.Item.Selection.select);

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
				select: Aimeos.Product.Item.Selection.select
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
			select: Aimeos.Product.Item.Selection.select
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



Aimeos.Product.Item.Stock = {

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
