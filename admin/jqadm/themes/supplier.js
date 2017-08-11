/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */



Aimeos.Supplier = {

	init : function() {

		Aimeos.Supplier.Item.init();
	}
};



Aimeos.Supplier.Item = {

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



$(function() {

	Aimeos.Supplier.init();
});
