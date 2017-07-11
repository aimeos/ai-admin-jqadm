/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */



Aimeos.Coupon = {

	init : function() {

		Aimeos.Coupon.Item.init();
		Aimeos.Coupon.Item.Code.init();
	}
};



Aimeos.Coupon.Item = {

	init : function() {

		this.setupConfig();
		this.setupDecorator();
		this.setupProvider();
	},


	setupConfig : function() {

		$(".aimeos .item-coupon .item-basic").on("change input blur", "input.item-provider", function(ev) {

			Aimeos.options.done(function(data) {

				var params = {};

				if(data.meta && data.meta.prefix) {
					params[data.meta.prefix] = {id: $(ev.currentTarget).val()};
				} else {
					params['id'] = $(ev.currentTarget).val();
				}

				$.ajax({
					url: data.meta.resources['coupon/config'] || null,
					dataType: "json",
					data: params
				}).done(function(result) {

					$(result.data).each(function(idx, entry) {
						var found = false;

						$("table.item-config .config-key", ev.delegateTarget).each(function() {
							if($(this).val() === entry.id) {
								found = true;
							}
						});

						if(found === false) {
							var clone = Aimeos.addClone($("table.item-config .prototype", ev.delegateTarget));

							$(".config-key", clone).val(entry.id);
							$(".config-value", clone).attr("placeholder", entry.attributes.label);
						}
					});
				});
			});
		});
	},


	setupDecorator : function() {

		$(".aimeos .item-coupon .item-provider").parent().on("click", ".input-group-addon .decorator-name", function(ev) {

			var input = $("input.item-provider", ev.delegateTarget);
			input.val(input.val() + ',' + $(this).data("name"));
			input.trigger("change");
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



Aimeos.Coupon.Item.Code = {

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
};



$(function() {

	Aimeos.Coupon.init();
});
