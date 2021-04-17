/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



Aimeos.Coupon = {

	init : function() {

		this.setupConfig();
		this.setupDecorator();
		this.setupProvider();

		Aimeos.Coupon.Code.init();
	},


	setupConfig : function() {

		var delegate = $(".aimeos .item-coupon .item-basic");

		if(delegate.length > 0 ) {
			Aimeos.Config.setup('coupon/config', $("input.item-provider", delegate).val(), delegate);
		}

		delegate.on("change input blur", "input.item-provider", function(ev) {
			Aimeos.Config.setup('coupon/config', $(ev.currentTarget).val(), ev.delegateTarget);
		});
	},


	setupDecorator : function() {

		$(".aimeos .item-coupon .item-provider").parent().on("click", ".dropdown .decorator-name", function(ev) {

			var name = $(this).data("name");
			var input = $("input.item-provider", ev.delegateTarget);

			if(input.val().indexOf(name) === -1) {
				input.val(input.val() + ',' + name);
				input.trigger("change");
			}
		});
	},


	setupProvider : function() {

		var input = $(".aimeos .item-coupon").on("focus", ".item-provider", function(ev) {

			$(this).autocomplete({
				source: $(this).data("names").split(","),
				minLength: 0,
				delay: 0
			});

			$(this).autocomplete("search", "");
		});
	}
};



Aimeos.Coupon.Code = {

	init : function() {

		this.addItem();
		this.closeItem();
		this.askDelete();
		this.confirmDelete();
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


	askDelete : function() {
		var self = this;

		$(".item-coupon .item-code").on("click", ".act-delete", function(e) {
			$("#confirm-delete").modal("show", $(this));
			self.element = $(this);
			return false;
		});
	},


	confirmDelete : function() {
		var self = this;

		$("#confirm-delete").on("click", ".btn-danger", function(e) {

			if(!self.element) {
				return;
			}

			var row = self.element.closest("tr");

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
					url: self.element.attr("href"),
					data: params,
				}).done(function() {
					Aimeos.focusBefore(row).remove();
				});
			});

			return false;
		});
	}
};



$(function() {

	Aimeos.Coupon.init();
});
