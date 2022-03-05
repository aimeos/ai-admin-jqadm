/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



Aimeos.Service = {

	init : function() {

		this.setupConfig();
		this.setupDecorator();
		this.setupProvider();
	},


	setupConfig : function() {

		var delegate = $(".aimeos .item-service .item-basic");

		if(delegate.length > 0 ) {
			var type = $(".item-type option:selected", delegate).val();
			Aimeos.Config.setup('service/config', $("input.item-provider", delegate).val(), delegate, type);
		}

		delegate.on("change input blur", "input.item-provider", function(ev) {
			var type = $(".item-type option:selected", delegate).val();
			Aimeos.Config.setup('service/config', $(ev.currentTarget).val(), ev.delegateTarget, type);
		});
	},


	setupDecorator : function() {

		$(".aimeos .item-service .item-provider").parent().on("click", ".dropdown .decorator-name", function(ev) {

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

			var type = $(".item-type option:selected", ev.delegateTarget).val();

			if(type) {
				$(this).autocomplete({
					source: $(this).data(type).split(","),
					minLength: 0,
					delay: 0
				});

				$(this).autocomplete("search", "");
			}
		});
	}
};



$(function() {

	Aimeos.Service.init();
});
