/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */



Aimeos.Service = {

	init : function() {

		Aimeos.Service.init();
	}
};



Aimeos.Service = {

	init : function() {

		this.setupConfig();
		this.setupDecorator();
		this.setupProvider();
	},


	setupConfig : function() {

		$(".aimeos .item-service .item-basic").on("change input blur", "input.item-provider", function(ev) {

			Aimeos.options.done(function(data) {

				var params = {};

				if(data.meta && data.meta.prefix) {
					params[data.meta.prefix] = {id: $(ev.currentTarget).val()};
				} else {
					params['id'] = $(ev.currentTarget).val();
				}

				$.ajax({
					url: data.meta.resources['service/config'] || null,
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

		$(".aimeos .item-service .item-provider").parent().on("click", ".input-group-addon .decorator-name", function(ev) {

			var name = $(this).data("name");
			var input = $("input.item-provider", ev.delegateTarget);

			if(input.val().indexOf(name) === -1) {
				input.val(input.val() + ',' + name);
				input.trigger("change");
			}
		});
	},


	setupProvider : function() {

		var input = $(".aimeos .item-service").on("focus", ".item-provider", function(ev) {

			var type = $(".item-typeid option:selected", ev.delegateTarget).data("code");

			$(this).autocomplete({
				source: $(this).data(type).split(","),
				minLength: 0,
				delay: 0
			});

			$(this).autocomplete("search", "");
		});
	}
};



$(function() {

	Aimeos.Service.init();
});
