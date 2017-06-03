/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */



Aimeos.Customer = {

	init : function() {

		Aimeos.Customer.Item.init();
		Aimeos.Customer.Item.Product.init();
	}
};



Aimeos.Customer.Item = {

	init : function() {

		this.addBlock();
		this.copyBlock();
		this.removeBlock();
		this.updateHeader();
	},


	addBlock : function() {

		$(".item-address").on("click", ".card-tools-more .act-add", function(ev) {
			ev.stopPropagation();

			var number = Math.floor((Math.random() * 1000));
			var node = $(".group-item.prototype", ev.delegateTarget);
			var clone = node.clone().removeClass("prototype");

			$(".card-block", clone).attr("id", "item-address-group-data-" + number);
			$(".card-header", clone).attr("id", "item-address-group-item-" + number);
			$(".card-header", clone).attr("data-target", "#item-address-group-data-" + number);
			$(".card-header", clone).attr("aria-controls", "item-address-group-data-" + number);

			$("[disabled='disabled']", clone).prop("disabled", false);
			clone.insertBefore(node);
		});
	},


	copyBlock : function() {

		$(".item-address").on("click", ".header .act-copy", function(ev) {
			ev.stopPropagation();

			var number = Math.floor((Math.random() * 1000));
			var block = $(this).parents(".group-item");
			var clone = block.clone();

			$(".card-block", clone).attr("id", "item-address-group-data-" + number);
			$(".card-header", clone).attr("id", "item-address-group-item-" + number);
			$(".card-header", clone).attr("data-target", "#item-address-group-data-" + number);
			$(".card-header", clone).attr("aria-controls", "item-address-group-data-" + number);

			clone.insertAfter(block);

			$("input.item-id", clone).val('');
			$(".card-header .header-label", clone).empty();
		});
	},


	removeBlock : function() {

		$(".item-address").on("click", ".header .act-delete", function() {
			Aimeos.focusBefore($(this).parents(".group-item")).remove();
		});
	},


	updateHeader : function() {

		$(".item-address").on("blur", "input.item-firstname,input.item-lastname,input.item-postal,input.item-city", function() {
			var item = $(this).parents(".group-item");
			var value = $("input.item-firstname", item).val() + ' ' + $("input.item-lastname", item).val()
				+ ' - ' + $("input.item-postal", item).val() + ' ' + $("input.item-city", item).val();

			$(".header .item-label", item).html(value);
		});
	}

};



Aimeos.Customer.Item.Product = {

	element: null,


	init : function() {

		this.askDelete();
		this.confirmDelete();
	},


	askDelete : function() {
		var self = this;

		$(".item-customer .item-product .list-items").on("click", ".act-delete", function(e) {
			$("#confirm-delete").modal("show", $(this));
			self.element = $(this);
			return false;
		});
	},


	confirmDelete : function() {
		var self = this;

		$("#confirm-delete").on("click", ".btn-danger", function(e) {

			if(self.element) {

				$.when( Aimeos.options ).then(function(data) {

					var params = {};

					if( data.meta && data.meta.csrf ) {
						params[data.meta.csrf.name] = data.meta.csrf.value;
					}

					$.ajax({
						dataType: "json",
						method: 'DELETE',
						url: self.element.attr("href") || null,
						data: params
					}).then(function() {
						self.element.closest("tr").remove();
						self.element = null;
					});
				});
			}
		});
	}
}


$(function() {

	Aimeos.Customer.init();
});
