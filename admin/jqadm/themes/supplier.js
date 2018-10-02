/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



Aimeos.Supplier = {

	init : function() {

		Aimeos.Supplier.Product.init();
	}
};



Aimeos.Supplier.Product = {

	init : function() {

		this.addItem();
		this.closeItem();
		this.removeItem();
	},


	addItem : function() {

		$(".item-supplier .item-product").on("click", ".list-header .act-add", function(ev) {
			Aimeos.addClone(
				$(".list-item-new.prototype", ev.delegateTarget),
				Aimeos.getOptionsProducts,
				Aimeos.Supplier.Product.select);
		});
	},


	closeItem : function() {

		$(".item-supplier .item-product").on("click", ".act-close", function(ev) {
			$(this).closest("tr").remove();
		});
	},


	removeItem : function() {

		$(".item-supplier .item-product .list-item").on("click", ".act-delete", function(ev) {

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

	Aimeos.Supplier.init();
});
