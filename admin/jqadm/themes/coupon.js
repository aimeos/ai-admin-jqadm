/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */



Aimeos.Coupon = {

	init : function() {

		Aimeos.Coupon.Item.init();
	}
};



Aimeos.Coupon.Item = {

	init : function() {

		this.addItem();
		this.closeItem();
		this.removeItem();
	},


	addItem : function() {

		$(".item-coupon .item-code").on("click", ".act-add", function(ev) {
			$(".prototype input, .prototype select", ev.delegateTarget).removeAttr("disabled");
			$(".prototype", ev.delegateTarget).removeClass("prototype");
		});
	},


	closeItem : function() {

		$(".item-coupon .item-code").on("click", ".act-close", function(ev) {
			var row = $(this).closest("tr");

			row.addClass("prototype");
			$("input,select", row).attr("disabled", "disabled");
		});
	},


	removeItem : function() {

		$(".item-coupon .item-code").on("click", ".act-delete", function(ev) {

			var elem = $(this);
			var row = elem.closest("tr");

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
	}
}



$(function() {

	Aimeos.Coupon.init();
});
