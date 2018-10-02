/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



Aimeos.Customer = {

	init : function() {

		this.setupComponents();

		Aimeos.Customer.Product.init();
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("card-block").find("input.item-countryid").val(node.val());
	},


	setupComponents : function() {

		$(".item-customer .item-countryid.combobox").combobox({
			getfcn: Aimeos.getCountries,
			select: Aimeos.Customer.select
		});
	}
};



Aimeos.Customer.Product = {

	init : function() {

		this.addItem();
		this.closeItem();
		this.removeItem();
	},


	addItem : function() {

		$(".item-customer .item-product").on("click", ".list-header .act-add", function(ev) {
			Aimeos.addClone(
				$(".list-item-new.prototype", ev.delegateTarget),
				Aimeos.getOptionsProducts,
				Aimeos.Customer.Product.select);
		});
	},


	closeItem : function() {

		$(".item-customer .item-product").on("click", ".act-close", function(ev) {
			$(this).closest("tr").remove();
		});
	},


	removeItem : function() {

		$(".item-customer .item-product .list-item").on("click", ".act-delete", function(ev) {

			var elem = $(this);
			var row = $(ev.delegateTarget);

			Aimeos.options.done(function(data) {

				var params = {}, param = {};

				if(data.meta && data.meta.csrf) {
					param[data.meta.csrf.name] = data.meta.csrf.value;
				}

				if(data.meta && data.meta.prefix) {
					params[data.meta.prefix] = param;
				} else {
					params = param;
				}

				$.ajax({
					dataType: "json",
					method: "DELETE",
					url: elem.attr("href"),
					data: params,
				}).done(function() {
					Aimeos.focusBefore(row).remove();
				});
			});

			return false;
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("tr").find("input.item-label").val(node.val());
	}
};



$(function() {

	Aimeos.Customer.init();
});
